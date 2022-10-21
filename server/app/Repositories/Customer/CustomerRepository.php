<?php

namespace App\Repositories\Customer;

use App\Repositories\BaseRepository;
use App\Models\Customer;
use App\Models\UserDetail;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Customer::class;
    }

    public function checkCustomer($email)
    {
        $customer = Customer::where('customer_email', '=', $email)->first();
        return $customer;
    }

    public function addCustomerDetail($info)
    {
        $customer_detail = UserDetail::create([
            'user_first_name' => $info->first_name,
            'user_last_name' => $info->last_name,
            'user_phone' => $info->phone,
            'user_avatar' => '@/assets/avatar_default/default-avatar.png'
        ]);
        return $customer_detail->id;
    }
    public function deleteCustomer($arrId)
    {
        foreach ($arrId as $id) {
            $customer = Customer::find($id);
            $user_detail = UserDetail::find($customer->user_detail_id);
            $customer->delete();
            $user_detail->delete();
        }
        return true;
    }
}
