<?php

namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;

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
                'product_image_name' => $img['product_image_name'],
                'product_image_link' => $img['product_image_link']
            ]);
        }
        return true;
    }

    public function updateImage($product_image_id, $product_image_link)
    {
        $array_image_id = array();
        foreach ($product_image_id as $image_id) {
            array_push($array_image_id, $image_id);
        }
        for ($i = 0; $i < $array_image_id; $i++) {
            $image = ProductImage::where('product_image_id', $array_image_id[$i])->update([
                'product_image_link' => $product_image_link[$i]
            ]);
        }
        return true;
    }

    public function deleteProduct($arrId)
    {
        foreach ($arrId as $id) {
            $product = Product::find($id);
            $product->delete();
            // $this->productRepo->delete($id);
        }
        return true;
    }
}
