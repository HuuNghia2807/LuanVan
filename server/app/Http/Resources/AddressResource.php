<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'address' => $this->address,
            'ward' => $this->getPovince($this->ward_id)['ward'],
            'district' => $this->getPovince($this->ward_id)['district'],
            'city' => $this->getPovince($this->ward_id)['city'],
        ];
        return $data;
    }

    public function getPovince($id)
    {
        $ward = Ward::find($id);
        $district = District::find($ward->district_id);
        $city = City::find($district->city_id);
        return [
            'ward' => $ward,
            'district' => $district,
            'city' => $city
        ];
    }
}
