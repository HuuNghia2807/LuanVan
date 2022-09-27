<?php
namespace App\Repositories\Product;

use App\Repositories\BaseRepository;
use App\Models\Category;
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
}