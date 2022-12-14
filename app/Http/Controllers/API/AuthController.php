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

        $idPassword = Employee::where('employee_id', '=', $request->employee_id)->get('password_id')->first();

        if ($idPassword == null) {
            return $this->sendError('Unauthorised.', ['error' => 'your password or nik is false']);
        }else{

            $getPassword = Password::where(
                [
                    'employee_id' => $request->employee_id,
                    'password_id' => $idPassword->password_id
                ])->get()->first();
    
            if ($getPassword != null) {
                $dateNow = Carbon::now();
                $nonActiveDate = $getPassword->non_active_date;

                if ($nonActiveDate > $dateNow) {
                    if (Hash::check($request->password, $getPassword->password))
                    {
                        Auth::loginUsingId($request->employee_id);

                        $authUser = Employee::find(Auth::user()->employee_id);

                        // $dataEmployee = Employee::find(Auth::user()->employee_id)->with('role_tbl')->get();
        
                        $success['employee_id'] =  Auth::user()->employee_id;
                        $success['token'] =  $authUser->createToken('MyAuthApp')->plainTextToken;
                        $success['data'] =  Employee::with('role_tbl')->get();
                        
                        $authUser->remember_token = $success['token'];
                        $authUser->save();
                        return $this->sendResponse($success, 'User signed in');
                    }else{
                        return $this->sendError('Unauthorised.', ['error' => 'your password is fails']);
                    }
                }else{
                    return $this->sendError('Unauthorised.', ['error' => 'your password is not Active, please Update your password']);
                }
            }else{
                return $this->sendError('Unauthorised.', ['error' => 'your password is fails']);
            }
        }
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

        $input['employee_id'] = $input['employee_id'];
        $input['password'] = bcrypt($input['password']);
        $input['non_active_date'] = Carbon::now()->addDays(90);

        $password = Password::create($input
        );

        $input['password_id'] = $password->password_id;
        $input['device_id'] = Str::random(16); //device_id generator
        $user = Employee::create($input);

        // $success['user_data'] =  $user;
        // $success['password_data'] =  $password;

        $success['employee_id'] =  $user->employee_id;
        $success['employee_name'] =  $user->employee_name;
        $success['employee_email'] =  $user->employee_email;
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
        
        return $this->sendResponse($success, 'User created successfully.');
    } 
}