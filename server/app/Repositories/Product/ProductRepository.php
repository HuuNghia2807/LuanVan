<?php

namespace App\Repositories\Product;

use App\Http\Resources\ProductResource;
use App\Repositories\BaseRepository;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\Size;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Product::class;
    }

    public function getProduct()
    {
        return $this->model->select('product_name')->take(5)->get();
    }

    public function getCategoryAll()
    {
        return Category::all();
    }

    public function createImage($product_id, $images)
    {
        foreach ($images as $img) {
            // $dir = 'img';
            // $absolutePath = $img->product_image_link->getClientOriginalName();
            // $img->move($dir, $absolutePath);
            // $image_src = $dir . '/' . $absolutePath;
            // $img['product_image_link'] = $image_src;
            ProductImage::create([
                'product_id' => $product_id,
                'product_image_name' => 'king shoes',
                'product_image_link' => $img
            ]);
        }
        return true;
    }
    public function checkIdCategory($cate)
    {
        $category = Category::where('category_name', '=', $cate)->first();
        if (!$category) {
            $category = Category::create([
                'category_name' => $cate,
            ]);
        };
        return $category->id;
    }
    public function createProductSize($product_id, $sizes)
    {
        foreach ($sizes as $sz) {
            $size = Size::where('size', '=', $sz['size'])->first();
            if (!$size) {
                $size = Size::create([
                    'size' => $sz['size'],
                ]);
            };
            ProductSize::create([
                'size_id' => $size->id,
                'product_size_quantity' => $sz['size_quantity'],
                'product_id' => $product_id,
            ]);
        }
        return true;
    }

    public function updateImage($product_id, $data)
    {

        $images = $data['images'];
        if (!empty($images)) {
            foreach ($images as $img) {
                ProductImage::create([
                    'product_id' => $product_id,
                    'product_image_name' => 'king shoes',
                    'product_image_link' => $img
                ]);
            }
        }
        $images_delete = $data['image_delete'];
        if (!empty($images_delete)) {
            foreach ($images_delete as $img_id) {
                $img_del = ProductImage::find($img_id);
                $img_del->delete();
            }
        }
        return true;
    }

    public function updateSize($product_id, $data)
    {
        $sizes = $data['sizes'];
        foreach ($sizes as $sz) {
            $size = Size::where('size', '=', $sz['size'])->first();
            if (!$size) {
                $size = Size::create([
                    'size' => $sz['size'],
                ]);
                ProductSize::create([
                    'size_id' => $size->id,
                    'product_size_quantity' => $sz['size_quantity'],
                    'product_id' => $product_id,
                ]);
            } else {
                $product_sz = ProductSize::where('size_id', '=', $size->id)->where('product_id', '=', $product_id)->first();
                if ($product_sz) {
                    $product_sz->update(['product_size_quantity' => $sz['size_quantity']]);
                } else {
                    ProductSize::create([
                        'size_id' => $size->id,
                        'product_size_quantity' => $sz['size_quantity'],
                        'product_id' => $product_id,
                    ]);
                }
            };
        }
        $size_delete = $data['size_delete'];
        if (!empty($size_delete)) {
            foreach ($size_delete as $sz) {
                $size = ProductSize::find($sz);
                $size->delete();
            }
        }
        return true;
    }

    public function deleteProduct($arrId)
    {
        foreach ($arrId as $id) {
            $product = Product::find($id);
            $product->delete();
        }
        return true;
    }


    public function getHome()
    {
        $new_product = Product::all()->sortByDesc('id')->take(10);

        $sale_product =  Product::where('discount_id', '!=', null)->get()->take(10);

        $data = [
            'new_product' => ProductResource::collection($new_product),
            'hot_product' => ProductResource::collection($this->getProductsHot()),
            'sale_product' => $sale_product ? ProductResource::collection($sale_product) : [],
        ];
        return $data;
    }

    public function getProductsHot()
    {
        $products = array();
        $result = array();
        $orders = Order::all();
        foreach ($orders as $order) {
            foreach ($order->order_details as $detail) {
                if (!in_array($detail->product_id(), array_keys($products))) {
                    $products[$detail->product_id()] = $detail->product_quantity;
                } else {
                    $products[$detail->product_id()] += $detail->product_quantity;
                }
            }
        }
        arsort($products);
        foreach ($products as $key => $product) {
            $product = $this->model->find($key);
            array_push($result, $product);
        }

        return $result;
    }

    public function search($q)
    {
        $products = Product::where('product_name', 'like', '%' . $q . '%')->orWhere('product_code', 'like', '%' . $q . '%')->get();

        return $products;
    }
}
