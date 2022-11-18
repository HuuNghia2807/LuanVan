<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order_today = Order::whereDate('order_time', '=', Carbon::today()->toDateString())->get();
        $price = 0;
        foreach ($order_today as $order) {
            $price += $order['order_total_price'];
        };
        $data = [
            'customer' => [
                'new' => Customer::where('user_status_id', '=', 1)->count(),
                'total' => Customer::all()->count()
            ],
            'order' => [
                'new' => $order_today->where('order_status_id', '=', 1)->count(),
                'total' => $order_today->count()
            ],
            'price_today' => $price
        ];
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('Success');
        $this->setMessage('Get success');
        $this->setData($data);
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
        //
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

    public function getByMonth(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if (!$validated) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('dont get');
            return $this->respond();
        };

        $orders = Order::whereBetween('order_time', [$request->start_date, $request->end_date])->orderBy('order_time', 'ASC')->get();
        $result = array();
        foreach ($orders as $key => $val) {
            $result[] = array(
                'date' => Carbon::create($val->order_time)->toDateString(),
                'total_price' => $val->order_total_price
            );
        }
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('Success');
        $this->setMessage('Get success');
        $this->setData($result ? $result : []);
        return $this->respond();
    }
}
