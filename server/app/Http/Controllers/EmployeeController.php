<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Http\Resources\EmployeeResource;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Role;
use App\Models\UserDetail;
use App\Repositories\Employee\EmployeeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends AbstractApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $employeeRepo;

    public function __construct(EmployeeRepositoryInterface $employeeRepo)
    {
        $this->employeeRepo = $employeeRepo;
    }

    public function index()
    {
        $customer = Customer::all();
        $employee = Employee::all();
        $data = [
            'customer' => CustomerResource::collection($customer),
            'employee' => EmployeeResource::collection($employee),
        ];
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Get user success');
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
        // dd($request->all());
        $validate = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'phone' => 'required',
                'role_id' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|max:255'
            ]
        );
        if ($validate->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        DB::beginTransaction();
        try {
            $customer = $this->employeeRepo->checkEmployee($request->email);
            if ($customer) {
                $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
                $this->setStatus('failed');
                $this->setMessage('Nhân viên đã tồn tại!');
                return $this->respond();
            }
            $employee_detail_id = $this->employeeRepo->addEmployeeDetail($request);
            Employee::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'user_status_id' => 1,
                'user_detail_id' => $employee_detail_id
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
    public function update(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'info' => 'required',
        ]);
        if (!$validated) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        $employee_id = $request->employee_id;
        $data = $request->info;
        DB::beginTransaction();
        try {
            $emp = Employee::find($employee_id);
            UserDetail::find($emp->user_detail_id)->update([
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
        $this->setData(EmployeeResource::make($emp));
        return $this->respond();
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

    public function login(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'email' => 'required|email:filter|max:255|ends_with:gmail.com',
                'password' => 'required|min:6|max:255'
            ]
        );
        if ($validate->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };
        if (!Auth::guard('api-employee')->attempt($request->only(['email', 'password']))) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Tài khoản hoặc mật khẩu không chính xác!');
            return $this->respond();
        }
        $employee = Employee::where('email', '=', $request->email)->first();
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Login success');
        $this->setData(EmployeeResource::make($employee));
        return $this->respond();
    }

    public function getRole()
    {
        $roles = Role::all();
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('Login success');
        $this->setData($roles);
        return $this->respond();
    }

    public function delegateEmp(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required',
                'role_id' => 'required'
            ]
        );
        if ($validate->fails()) {
            $this->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
            $this->setStatus('failed');
            $this->setMessage('Validation error');
            return $this->respond();
        };

        Employee::find($request->employee_id)->update([
            'role_id' => $request->role_id
        ]);
        $this->setStatusCode(JsonResponse::HTTP_OK);
        $this->setStatus('success');
        $this->setMessage('delegate success');
        return $this->respond();
    }
}
