<?php

namespace App\Http\Resources;

use App\Models\Address;
use App\Models\City;
use App\Models\Customer;
use App\Models\District;
use App\Models\Employee;
use App\Models\OrderDetail;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\Ward;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'order_id' => $this->id,
            'total_price' => $this->order_total_price,
            'order_note' => $this->order_note,
            'order_time' => strtotime($this->order_time),
            'receive_time' => strtotime($this->receive_time),
            'payment' => $this->getPayment($this->payment_id),
            'address' => $this->getAddress($this->address_id),
            'order_status' => $this->getStatus($this->order_status_id),
            'customer' => $this->getCustomer($this->customer_id),
            'employee' => $this->getEmployee($this->employee_id),
            'product_order' => $this->getProductOrder($this->id),
        ];
        return $data;
    }

    public function getPayment($id)
    {
        $payment = Payment::find($id);
        return $payment;
    }

    public function getAddress($id)
    {
        $address = Address::find($id);
        $ward = Ward::find($address->ward_id);
        $district = District::find($ward->district_id);
        $city = City::find($district->city_id);
        return [
            'address' => $address->address,
            'ward' => $ward->ward,
            'district' => $district->district,
            'city' => $city->city
        ];
    }

    public function getStatus($id)
    {
        $status = OrderStatus::find($id);
        return $status;
    }

    public function getCustomer($id)
    {
        $cus = Customer::find($id);
        return CustomerResource::make($cus);
    }

    public function getEmployee($id)
    {
        $emp = Employee::find($id);
        return $emp;
    }

    public function getProductOrder($order_id)
    {
        $pro = OrderDetailResource::collection(OrderDetail::where('order_id', '=', $order_id)->get());
        return $pro;
    }
}
