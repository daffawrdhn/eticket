<?php

namespace App\Http\Controllers\forgotpassword;

use App\Http\Controllers\API\BaseController;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckEmployeeController extends BaseController
{
    public function index(){
        return view('containers.login.check_employee');
    }

    public function checkData(Request $request){

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
            return $this->sendResponse($success, 'Data Correct!');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Data incorrect!']);
        }
    }
}
