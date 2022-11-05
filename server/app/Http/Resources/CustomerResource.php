<?php

namespace App\Http\Resources;

use App\Models\Address;
use App\Models\UserDetail;
use App\Models\UserStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'id' => $this->id,
            'email' => $this->email,
            'user_status_id' => $this->user_status_id,
            'user_status' => $this->getUserStatus($this->user_status_id),
            'first_name' => $this->getUserDetail($this->user_detail_id)->user_first_name,
            'last_name' => $this->getUserDetail($this->user_detail_id)->user_last_name,
            'phone' => $this->getUserDetail($this->user_detail_id)->user_phone,
            'birth' => $this->getUserDetail($this->user_detail_id)->user_birth,
            'gender' => $this->getUserDetail($this->user_detail_id)->user_gender,
            'avatar' => $this->getUserDetail($this->user_detail_id)->user_avatar,
            'address' => $this->getAddress($this->id),
        ];
        return $data;
    }

    public function getUserStatus($id)
    {
        $status = UserStatus::find($id);
        return $status->status;
    }

    public function getUserDetail($id)
    {
        $detail = UserDetail::find($id);
        return $detail;
    }

    public function getAddress($id)
    {
        $add = Address::where('customer_id', '=', $id)->get();
        return AddressResource::collection($add);
    }
}
