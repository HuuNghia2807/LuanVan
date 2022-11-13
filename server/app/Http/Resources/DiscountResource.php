<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
            'discount_id' => $this->id,
            'title' => $this->discount_title,
            'percent' => $this->discount,
            'time_create' => strtotime($this->created_at)
        ];
        return $data;
    }
}
