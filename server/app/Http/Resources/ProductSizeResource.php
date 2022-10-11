<?php

namespace App\Http\Resources;

use App\Models\Size;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductSizeResource extends JsonResource
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
            'product_size_id' => $this->id,
            'product_size_quantity' => $this->product_size_quantity,
            'size_id' => $this->size_id,
            'size' => $this->getSize($this->size_id),

        ];
        return $data;
    }

    public function getSize($id)
    {
        $size = Size::find($id);
        return $size->size;
    }
}
