<?php

namespace App\Repositories\Customer;

use App\Repositories\RepositoryInterface;

interface CustomerRepositoryInterface extends RepositoryInterface
{
    public function checkCustomer($email);
    public function addCustomerDetail($info);
    public function deleteCustomer($arrId);
}
