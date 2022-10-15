<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Repositories\Cart\CartRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $cartRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function index()
    {
        //
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
        try {
            $cart = $this->cartRepo->create([
                'product_size_id' => $request->product_size_id,
                'cart_quantity' => $request->size_quantity,
                'customer_id' => $request->user_id,
            ]);
            $cartData = CartResource::make($cart);
        } catch (\Throwable $th) {
            $this->setStatusCode(JsonResponse::HTTP_FORBIDDEN);
            $this->setStatus('failed');
            $this->setMessage($th->getMessage());
            return $this->respond();
        }

        $this->setStatusCode(JsonResponse::HTTP_CREATED);
        $this->setStatus('success');
        $this->setMessage('Add cart success');
        $this->setData($cartData);
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
