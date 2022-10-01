<?php
namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Models\Category;
use App\Models\ProductImages;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Products::class;
    }

    public function getProduct()
    {
        return $this->model->select('product_name')->take(5)->get();
    }

    public function getCategoryAll(){
        return Category::all();
    }

    public function createImage($product_id,$images){
        foreach($images as $img){
            $image = ProductImages::create([
                'product_id' => $product_id,
                'product_image_name' => $img->product_image_name,
                'product_image_link'=> $img->product_image_link
            ]);
        }
        return true;
    }

    public function updateImage($product_image_id,$product_image_link){
        $array_image_id = array();
        foreach($product_image_id as $image_id){
            array_push($array_image_id,$image_id);
        }
        for($i = 0;$i<$array_image_id;$i++){
            $image = ProductImages::where('product_image_id',$array_image_id[$i])->update([
                'product_image_link' => $product_image_link[$i]
            ]);
        }
        return true;
    }
}