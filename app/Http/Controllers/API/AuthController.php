<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\Employee;
use App\Models\Password;
   
class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        foreach(Password::where('employee_id', '=' , $request->employee_id)->get() as $pass)
        {
            // dd($pass);
            if (Hash::check($request->password, $pass->password))
            {
                Auth::loginUsingId($request->employee_id);

                $authUser = Employee::find(Auth::user()->employee_id);
                $authPass = Password::find(Auth::user()->employee_id);

                $success['user_data'] = $authUser;
                $success['password_data'] = $authPass;
                $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken;
                
                $authUser->remember_token = $success['token'];
                $authUser->save();
                return $this->sendResponse($success, 'User signed in');
            }
        }
        return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);


        // if (Auth::attempt(
        //         ['employee_id' => $request->employee_id,
        //         'password_id' => $request->password_id]
        //         )
        //     ) {

        //     $authUser = Employee::find(Auth::user()->employee_id);
        //     $authPass = Password::find(Auth::user()->employee_id);

        //     $success['user_data'] = $authUser;
        //     $success['password_data'] = $authPass;
        //     $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken;
            
        //     $authUser->remember_token = $success['token'];
        //     $authUser->save();


        //     return $this->sendResponse($success, 'User signed in');
        // } else {
        //     return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        // }
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'organization_id' => 'required',
            'supervisor_id' => 'required',
            'regional_id' => 'required',
            'role_id' => 'required',
            'device_id' => 'required',
            'employee_name' => 'required',
            'employee_email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();

        $input['employee_id'] = $input['employee_id'];
        $input['password'] = bcrypt($input['password']);

        $password = Password::create($input
        );

        $input['password_id'] = $password->password_id;

        $user = Employee::create($input);

        $success['user_data'] =  $user;
        $success['password_data'] =  $password;
        
        return $this->sendResponse($success, 'User created successfully.');
    } 
}