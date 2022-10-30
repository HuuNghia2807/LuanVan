<?php

namespace App\Http\Controllers;

use App\Repositories\Order\OrderRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
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
        // dd($request->all());
        $validateOrder = Validator::make(
            $request->all(),
            [
                'total_price' => 'required|numeric',
                'payment_id' => 'required|numeric',
                'customer_id' => 'required|numeric',
                'note' => 'nullable|string',
                'address_order' => 'required',
                'customer_name' => 'required|string'
            ]
        );
        if ($validateOrder->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        DB::beginTransaction();
        try {
            $address_id = $this->orderRepo->checkIdAddress($request->address_order, $request->customer_id);
            $time = Carbon::now('Asia/Ho_Chi_Minh');
            $order = $this->orderRepo->create([
                'order_total_price' => $request->total_price,
                'payment_id' => $request->payment_id,
                'order_note' => $request->note,
                'address_id' => $address_id,
                'order_time' => $time->toDateTimeString(),
                'order_status_id' => 1,
                'customer_id' => $request->customer_id
            ]);
            $this->orderRepo->createOrderDetail($request->product_order, $order->id);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setStatusCode(JsonResponse::HTTP_FORBIDDEN);
            $this->setStatus('failed');
            $this->setMessage($th->getMessage());
            return $this->respond();
            // response()->json('error', 500);
        }

        $this->setStatusCode(JsonResponse::HTTP_CREATED);
        $this->setStatus('success');
        $this->setMessage('Order success');
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
