<?php

namespace App\Http\Resources;

use App\Models\Customer;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'comment_id' => $this->id,
            'product_id' => $this->product_id,
            'comment' => $this->comment,
            'comment_rating' => $this->comment_rating,
            'customer_id' => $this->customer_id,
            'customer' => $this->getCustomer($this->customer_id),
        ];
        return $data;
    }

    public function getCustomer($id)
    {
        $cus = Customer::find($id);
        return CustomerResource::make($cus);
    }
}
