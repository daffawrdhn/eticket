<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Employee;
use App\Models\Password;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;

class AuthController extends BaseController
{
    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', ['error' => $validator->errors()]);
        }

        $idPassword = Employee::where('employee_id', '=', $request->employee_id)->get('password_id')->first();

        if ($idPassword == null) {
            return $this->sendError('Unauthorised.', ['error' => 'User Credential not Found!']);
        }else{
                $getPassword = Password::where(
                    [
                        'employee_id' => $request->employee_id,
                        'password_id' => $idPassword->password_id
                    ])->get()->first();
    
                $dateNow = Carbon::now();
                $nonActiveDate = $getPassword->non_active_date;
               
                if (Hash::check($request->password, $getPassword->password)) {

                    if ($nonActiveDate > $dateNow)
                    {
                        Auth::loginUsingId($request->employee_id);

                        $authUser = Employee::with('role', 'organization', 'regional')->find(Auth::user()->employee_id);

                        $tokens = $authUser->createToken('MyAuthApp')->plainTextToken;
                        $success = $authUser;
                        $authUser->api_token = $tokens;
                        $authUser->remember_token = $tokens;

                        // $authUser->save();
                        // return $this->sendResponse($success, 'User signed in', $tokens);

                        // dd($authUser->device_id);

                        if ($authUser->role_id == 0){ //check by role

                            $authUser->save();
                            return $this->sendResponse($success, 'User signed in', $tokens);

                        } else { // jika role selain 0 maka->

                            if($authUser->device_id == null){ //check auth apakah device id 0

                                $authUser->device_id = $request->device_id;
                                $authUser->save();

                                return $this->sendResponse($success, 'User signed in', $tokens);
    
                            } else { // jika tidak null/device id sudah ada

                                if($authUser->device_id == $request->device_id){ // cek devide id apakah sudah sama
    
                                    $authUser->save();
                                    return $this->sendResponse($success, 'User signed in', $tokens);
    
                                } else { // jika tidak error
                                    return $this->deviceError('Unauthorised.', ['error' => 'Device not recognized !']);
                                }
                            }
                        }

                    } else {
                        return $this->sendError('Unauthorised.', ['error' => 'Password Expired!']);
                    }
                }else{
                    return $this->sendError('Unauthorised.', ['error' => 'Password incorrect!']);
                }
        }
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
        

        $forgot = Employee::where('employee_id', '=', $request->employee_id)
        ->where('employee_ktp', '=', $request->employee_ktp)
        ->where('employee_birth', '=', $request->employee_birth)
        ->get()->first();

        if($forgot != null){
            $success['token'] = $forgot->api_token;
            return $this->sendResponse($success, 'Data Correct!');
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Data incorrect!']);
        }
    }

    public function forgotPassword(Request $request){
        $user = Auth::user();

        if($user != null){

        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
            'new_password_confirm' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', ['error' => $validator->errors()]);
        }

            if ($request->new_password == $request->new_password_confirm){
                
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

    public function data(Request $request){
        $success = Auth::user();
       return $this->sendResponse($success, 'Data Found!');
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'organization_id' => 'required',
            'supervisor_id' => 'required',
            'regional_id' => 'required',
            'role_id' => 'required',
            // 'device_id' => 'required',
            'employee_name' => 'required',
            'employee_email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $input = $request->all();

        $input['password_id'] = 0;
        $input['device_id'] = null; //device_id generator
        $input['api_token'] = 
        $user = Employee::create($input);

        $token = $user->createToken('MyAuthApp')->plainTextToken;
        $input['employee_id'] = $input['employee_id'];
        $input['password'] = bcrypt($input['password']);
        $input['non_active_date'] = Carbon::now()->addDays(90);

        if ($user) {
            $password = Password::create($input);

            if ($password) {
                Employee::where('employee_id', $user->employee_id)
                ->update(
                    [
                        'password_id' => $password->password_id,
                        'api_token' => $token, 
                        'remember_token' => $token
                    ]
                );
            }
        }

        $success['employee_id'] =  $user->employee_id;
        $success['employee_name'] =  $user->employee_name;
        $success['employee_email'] =  $user->employee_email;
        $success['token'] =  $token;
        
        return $this->sendResponse($success, 'User created successfully.');
    } 
}