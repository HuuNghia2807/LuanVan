<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'cart_id' => $this->id,
            'cart_quantity' => $this->cart_quantity,
            'product_id' => $this->getProductId($this->product_size_id),
            'product_size_id' => $this->product_size_id,
            'product_size' => $this->getSize($this->product_size_id),
            'customer_id' => $this->customer_id,
        ];
        return $data;
    }

    public function getProductSize($id)
    {
        $sizes = ProductSize::find($id);
        return $sizes;
    }

    public function getProductId($id)
    {
        $productSize = ProductSize::find($id);
        $product = Product::find($productSize->product_id);
        return $product->id;
    }

    public function getSize($id)
    {
        $productSize = ProductSize::find($id);
        $size = Size::find($productSize->size_id);
        return $size->size;
    }
}
