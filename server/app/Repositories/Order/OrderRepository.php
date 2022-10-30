<?php

namespace App\Repositories\Order;

use App\Models\Address;
use App\Repositories\BaseRepository;
use App\Models\Order;
use App\Models\OrderDetail;

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
        foreach ($product_order as $product) {
            OrderDetail::create([
                'product_quantity' => $product['quantity'],
                'product_size_id' => $product['productSizeId'],
                'order_id' => $order_id,
            ]);
        }
        return true;
    }
}
