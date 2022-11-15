<?php

namespace App\Repositories\Order;

use App\Repositories\RepositoryInterface;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function checkIdAddress($address, $customer_id);
    public function createOrderDetail($product_order, $order_id);
    public function updateQuantity($product_order);
}
