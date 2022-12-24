<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Employee;
use App\Models\Password;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends BaseController
{
    public function index($token)
    {
        return view('containers.login.forgot_password', ['token' => $token]);
    }

    public function forgotPassword(Request $request){
        $user = Auth::user();

        if($user != null) {

        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'new_password_confirm' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', ['error' => $validator->errors()]);
        }

            if ($request->new_password == $request->new_password_confirm) {
                
                $lastThreePasswords = Password::where('employee_id', Auth::user()->employee_id)->orderBy('updated_at', 'desc')->take(3)->get();
                
                foreach ($lastThreePasswords as $password) {

                    if (Hash::check($request->new_password, $password->password)) {
                        
                        return $this->sendError('Unauthorised.', ['error' => 'Password has already been used recently!']);
                  
                    }
                }

                $input['employee_id'] = Auth::user()->employee_id;
                        $input['password'] = bcrypt($request->new_password);
                        $input['non_active_date'] = Carbon::now()->addDays(90);
                        $password = Password::create($input);
                
                        if ($password) {

                            $success = Employee::where('employee_id', Auth::user()->employee_id)
                                ->update(['password_id' => $password->password_id]);

                            }
                
                        return $this->sendResponse($success, 'Password Changed!');
            
            } else {

                return $this->sendError('Unauthorised.', ['error' => 'Please input password confirmation correctly!']);
            
            }

       } else {
            
        return $this->sendError('Unauthorised.', ['error' => 'No Valid Token!']);
       
        }

    }

}
