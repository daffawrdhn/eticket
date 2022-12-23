<?php

namespace App\Http\Controllers\forgotpassword;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckDataEmployeeController extends BaseController
{
    public function index(){

        return view('containers.login.check_employee');
    }



    public function checkEmployee(Request $request){
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'employee_ktp' => 'required',
            'employee_birth' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', ['error' => $validator->errors()]);
        }
        

        $getEmployee = Employee::where('employee_id', '=', $request->employee_id)
        ->where('employee_ktp', '=', $request->employee_ktp)
        ->where('employee_birth', '=', $request->employee_birth)
        ->get()->first();

        if($getEmployee != null){
            $success['token'] = $getEmployee->api_token;
            $success['role'] = $getEmployee->role_id;
            return $this->sendResponse($success, 'Data Correct!');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Data incorrect!']);
        }
    }

    public function data(Request $request){
        $success = Auth::user();
       return $this->sendResponse($success, 'Data Found!');
    }
}

