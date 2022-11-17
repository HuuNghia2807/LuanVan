<?php

namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    //ví dụ: lấy 5 sản phầm đầu tiên
    public function getProduct();
    public function getCategoryAll();
    public function createImage($product_id, $images);
    public function checkIdCategory($cate);
    public function createProductSize($product_id, $sizes);
    public function updateImage($product_id, $data);
    public function updateSize($product_id, $data);
    public function deleteProduct($arrId);
    public function getHome();
    public function search($q);
}
