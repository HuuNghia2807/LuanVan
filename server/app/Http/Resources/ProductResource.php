<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Discount;
use App\Models\ProductImage;
use App\Models\ProductSize;
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
            'product_code' => $this->product_code,
            'product_price' => $this->product_price,
            'product_rating' => $this->handleRating($this->id, $this->product_rating),
            'category_id' => $this->category_id,
            'category' => $this->getCategory($this->category_id),
            'discount' => $this->getDiscount($this->discount_id),
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

    public function getDiscount($id)
    {
        $discount = Discount::find($id);
        return DiscountResource::make($discount);
    }

    public function handleRating($id, $rate_default)
    {
        $list_rate = Comment::where('product_id', '=', $id)->get();
        if (!count($list_rate)) {
            return $rate_default;
        }
        $total_sum = $list_rate->sum('comment_rating');
        $count = $list_rate->count() != 0 ? $list_rate->count() : 1;
        return $total_sum / $count;
    }
}
