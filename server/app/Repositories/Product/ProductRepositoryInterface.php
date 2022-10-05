<?php

namespace App\Repositories\Product;

use App\Repositories\RepositoryInterface;

interface ProductRepositoryInterface extends RepositoryInterface
{
    //ví dụ: lấy 5 sản phầm đầu tiên
    public function getProduct();
    public function getCategoryAll();
    public function createImage($product_id, $images);
    public function updateImage($product_image_id, $product_image_link);
    public function deleteProduct($arrId);
}
