<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Address;
use App\Models\Customer;
use App\Models\UserDetail;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $customerRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
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
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Get customer success');
        $this->setData(CustomerResource::make(Customer::find($id)));
        return $this->respond();
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
    public function update(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required',
            'info' => 'required',
        ]);
        if (!$validated) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        $customer_id = $request->customer_id;
        $data = $request->info;
        DB::beginTransaction();
        try {
            $cus = Customer::find($customer_id);
            UserDetail::find($cus->user_detail_id)->update([
                'user_first_name' => $data['first_name'],
                'user_last_name' => $data['last_name'],
                'user_phone' => $data['phone'],
                'user_birth' => $data['birth'],
                'user_gender' => $data['gender'],
                'user_avatar' => $data['avatar'],
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('Failed');
            $this->setMessage($th->getMessage());
            return $this->respond();
        }

        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('Success');
        $this->setMessage('Update info success');
        $this->setData(CustomerResource::make($cus));
        return $this->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->customerRepo->deleteCustomer($request->customer_ids);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setStatusCode(JsonResponse::HTTP_FORBIDDEN);
            $this->setStatus('failed');
            $this->setMessage($th->getMessage());
            return $this->respond();
        }

        $this->setStatusCode(JsonResponse::HTTP_CREATED);
        $this->setStatus('success');
        $this->setMessage('Delete customer success');
        return $this->respond();
    }

    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validateCustomer = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|max:255'
            ]
        );
        if ($validateCustomer->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        DB::beginTransaction();
        try {
            $customer = $this->customerRepo->checkCustomer($request->email);
            if ($customer) {
                $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
                $this->setStatus('failed');
                $this->setMessage('Email đã tồn tại!');
                return $this->respond();
            }
            $customer_detail_id = $this->customerRepo->addCustomerDetail($request);
            Customer::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'user_status_id' => 1,
                'user_detail_id' => $customer_detail_id
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage($th->getMessage());
            return $this->respond();
        }

        $this->setStatusCode(JsonResponse::HTTP_CREATED);
        $this->setStatus('success');
        $this->setMessage('Create account success');
        return $this->respond();
    }

    /**
     * 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validateCustomer = Validator::make(
            $request->all(),
            [
                'email' => 'required|email:filter|max:255|ends_with:gmail.com',
                'password' => 'required|min:6|max:255'
            ]
        );
        if ($validateCustomer->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        if (!Auth::guard('api')->attempt($request->only(['email', 'password']))) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Tài khoản hoặc mật khẩu không chính xác!');
            return $this->respond();
        }
        $customer = Customer::where('email', '=', $request->email)->first();
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Create account success');
        $this->setData(CustomerResource::make($customer));
        return $this->respond();
    }

    public function changePass(Request $request)
    {
        $validateCustomer = Validator::make(
            $request->all(),
            [
                'customer_id' => 'required|numeric',
                'old_pass' => 'required|min:6|max:255',
                'new_pass' => 'required|min:6|max:255|confirmed',
            ]
        );
        if ($validateCustomer->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        $customer = Customer::find($request->customer_id);
        if (Hash::check($request->old_pass, $customer->password)) {
            $customer->update([
                'password' => Hash::make($request->new_pass)
            ]);
        } else {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Mật khẩu củ không chính xác');
            return $this->respond();
        }

        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Update success');
        return $this->respond();
    }


    public function updateAddress(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [

                'address_id' => 'required|numeric',
                'address' => 'required',
                'ward_id' => 'required|numeric',
            ]
        );
        if ($validate->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        $address = Address::find($request->address_id);
        if ($address) {
            $address->update([
                'address' => $request->address,
                'ward_id' => $request->ward_id
            ]);
        }
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Update success');
        return $this->respond();
    }
}
