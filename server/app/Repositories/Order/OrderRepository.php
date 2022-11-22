<?php

namespace App\Repositories\Order;

use App\Models\Address;
use App\Models\Category;
use App\Models\Customer;
use App\Repositories\BaseRepository;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductSize;
use Carbon\Carbon;
use App\Mail\SendMail;

use App\Models\Product;
use App\Models\Size;
use App\Models\UserDetail;
use Mail;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Order::class;
    }

    public function checkIdAddress($address, $customer_id)
    {
        $add = Address::where('address', '=', $address['address'])->where('ward_id', '=', $address['ward'])->where('customer_id', '=', $customer_id)->first();
        if (!$add) {
            $add = Address::create([
                'address' => $address['address'],
                'customer_id' => $customer_id,
                'ward_id' => $address['ward']
            ]);
        }
        return $add->id;
    }

    public function createOrderDetail($product_order, $order_id)
    {
        // dd($product_order);
        $cart_list = array();
        foreach ($product_order as $product) {
            $value = OrderDetail::create([
                'product_quantity' => $product['quantity'],
                'product_size_id' => $product['productSizeId'],
                'sale' => $product['sale'],
                'order_id' => $order_id,
            ]);
            array_push($cart_list, $value);
        }
        return $cart_list;
    }

    public function updateQuantity($product_order)
    {
        // dd($product_order);
        foreach ($product_order as $product) {
            $pds = ProductSize::find($product['productSizeId']);
            $pds->update([
                'product_size_quantity' => $pds->product_size_quantity - $product['quantity'],
            ]);
        }
        return true;
    }

    public function getDataBar($orders)
    {
        $result = array();
        foreach ($orders as $key => $val) {
            $result[] = array(
                'date' => Carbon::create($val->order_time)->toDateString(),
                'total_price' => $val->order_total_price
            );
        }

        return $result;
    }
    public function getDataPie($orders)
    {
        $categories = array();
        foreach ($orders as $order) {
            foreach ($order->order_details as $detail) {
                if (!in_array($detail->category_id(), array_keys($categories))) {
                    $categories[$detail->category_id()] = $detail->product_quantity;
                } else {
                    $categories[$detail->category_id()] += $detail->product_quantity;
                }
            }
        }
        $result = array();
        foreach ($categories as $key => $category_quantity) {
            $result[] = array(
                'category' => Category::find($key)->category_name,
                'total' => $category_quantity
            );
        }

        return $result;
    }
    public function sendMail($order_details, $order)
    {
        $user = Customer::find($order->customer_id);
        $user_detail = UserDetail::find($user->user_detail_id);
        $email = $user->email;
        $data = array();
        foreach ($order_details as $order_detail) {
            $product_size = ProductSize::find($order_detail->product_size_id);
            $product = Product::find($product_size->product_id);
            $size = Size::find($product_size->size_id);
            $value = [
                "product_name" => $product->product_name,
                "product_size" => $size->size,
                "product_quantity" => $order_detail->product_quantity,
                "product_price" => $product->product_price,
            ];
            array_push($data, $value);
        }
        $mailData = [
            'greeting' => 'Hi ' . $user_detail->user_last_name,
            'body' => $data,
            'order_id' => $order->id,
            'total_price' => $order->order_total_price,
            'actiontext' => 'Liên hệ cửa hàng',
            'actionurl' => 'http://localhost:8080',
            'lastline' => 'Cảm ơn bạn đã mua hàng. Nếu có thắc mắc, vui lòng gọi: 0859255277',
        ];
        Mail::to($email)->send(new SendMail($mailData));
        return $mailData;
    }
}
