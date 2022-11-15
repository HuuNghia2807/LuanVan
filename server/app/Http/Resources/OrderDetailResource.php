<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'order_detail_id' => $this->id,
            'product_quantity' => $this->product_quantity,
            'product_size_id' => $this->product_size_id,
            'size' => $this->getSize($this->product_size_id),
            'product' => $this->getProduct($this->product_size_id),
            'sale' => $this->sale,
            'order_id' => $this->order_id,
        ];
        return $data;
    }

    public function getProduct($product_size_id)
    {
        $pds = ProductSize::find($product_size_id);
        $pro = Product::find($pds->product_id);
        return new ProductResource($pro);
    }

    public function getSize($product_size_id)
    {
        $size = ProductSize::find($product_size_id);
        $s = Size::find($size->size_id);
        return $s->size;
    }
}
