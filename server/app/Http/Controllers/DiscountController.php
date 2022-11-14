<?php

namespace App\Http\Controllers;

use App\Http\Resources\DiscountResource;
use App\Http\Resources\ProductResource;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discount = DiscountResource::collection(Discount::all()->sortByDesc('id'));
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Get discount success');
        $this->setData($discount);
        return $this->respond();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'discount_title' => 'required',
                'discount' => 'required',
            ]
        );
        if ($validate->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        $data = $request->all();
        Discount::create($data);
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Add Discount Success');
        return $this->respond();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function addToProduct(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'discount_id' => 'required|numeric',
                'product_ids' => 'required',
            ]
        );
        if ($validate->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };

        $ids = $request->product_ids;
        foreach ($ids as $id) {
            Product::find($id)->update([
                'discount_id' => $request->discount_id
            ]);
        }

        $products = ProductResource::collection(Product::all()->sortByDesc('id'));
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('add discount success');
        $this->setData($products);
        return $this->respond();
    }


    public function removeToProduct(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'discount_id' => 'required|numeric',
                'product_ids' => 'required',
            ]
        );
        if ($validate->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };

        $ids = $request->product_ids;
        foreach ($ids as $id) {
            Product::find($id)->update([
                'discount_id' => null
            ]);
        }

        $products = ProductResource::collection(Product::all()->sortByDesc('id'));
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('add discount success');
        $this->setData($products);
        return $this->respond();
    }


    public function deleteDiscount(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'ids' => 'required',
            ]
        );
        if ($validate->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };

        foreach ($request->ids as $id) {
            $products = Product::where('discount_id', '=', $id)->get();
            foreach ($products as $product) {
                $product->update([
                    'discount_id' => null
                ]);
            }
            Discount::find($id)->delete();
        }

        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('delete discount success');
        return $this->respond();
    }
}
