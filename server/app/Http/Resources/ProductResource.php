<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'product_id' => $this->id,
            'product_name' => $this->product_name,
            'product_price' => $this->product_price,
            'product_rating' => $this->product_rating,
            'category_id' => $this->category_id,
            'sizes' => $this->getProductSize($this->id),
            'product_image' => $this->getProductImage($this->id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        return $data;
    }

    public function getCategory($id)
    {
        $category = Category::find($id);
        return $category->category_name;
    }

    public function getProductSize($id)
    {
        $sizes = array();
        $sizes = ProductSize::where('product_id', '=', $id)->get();
        return ProductSizeResource::collection($sizes);
    }

    public function getProductImage($id)
    {
        $product_image = array();
        $product_image = ProductImage::where('product_id', '=', $id)->get();
        return ProductImageResource::collection($product_image);
    }
}
