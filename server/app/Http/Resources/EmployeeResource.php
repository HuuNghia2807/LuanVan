<?php

namespace App\Http\Resources;

use App\Models\Address;
use App\Models\Role;
use App\Models\UserDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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
            'role' => $this->getRole($this->role_id),
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

    public function getRole($id)
    {
        $role = Role::find($id);
        return $role->role;
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
