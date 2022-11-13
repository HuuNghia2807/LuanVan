<?php

namespace App\Http\Controllers;

use App\Http\Resources\DiscountResource;
use App\Models\Discount;
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
}
