<?php

namespace App\Repositories\Employee;

use App\Models\Employee;
use App\Repositories\BaseRepository;
use App\Models\UserDetail;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return Employee::class;
    }

    public function checkEmployee($email)
    {
        $employee = Employee::where('email', '=', $email)->first();
        return $employee;
    }

    public function addEmployeeDetail($info)
    {
        $customer_detail = UserDetail::create([
            'user_first_name' => $info->first_name,
            'user_last_name' => $info->last_name,
            'user_phone' => $info->phone,
        ]);
        return $customer_detail->id;
    }
    // public function deleteCustomer($arrId)
    // {
    //     foreach ($arrId as $id) {
    //         $customer = Customer::find($id);
    //         $user_detail = UserDetail::find($customer->user_detail_id);
    //         $customer->delete();
    //         $user_detail->delete();
    //     }
    //     return true;
    // }
}
