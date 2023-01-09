<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\EmployeeFormRequest;
use App\Models\Employee;
use App\Models\Password;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendMail;
use Exception;
use Illuminate\Support\Facades\Mail;

class EmployeeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('containers.dashboard.employee');
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
        try {
            $validator = Validator::make($request->all(), [
                // 'employee_id' => 'required',
                'organization_id' => 'required',
                'supervisor_id' => 'required',
                'regional_id' => 'required',
                'role_id' => 'required',
                'join_date' => 'required',
                'quit_date' => 'required',
                'employee_birth' => 'required',
                'employee_ktp' => 'required|min:16|unique:employee_tbl',
                'employee_name' => 'required',
                'employee_email' => 'required|email|unique:employee_tbl',
            ]);
    
            if ($validator->fails()) {
                $errors =  $validator->errors()->all();
                return $this->sendError('Error validation', ['error' => $errors]);
            }
    
            //generate employee ID 
    
            $employeeData = Employee::select('employee_id')->latest('created_at')->first();
            $employeeCount = (int)$employeeData->employee_id;
            $employeeCount++;
    
            $employeeId = sprintf("%09s", $employeeCount);
    
            // generate Password
    
            $password = EmployeeController::randomPassword(8);
    
            // send mail
    
            $testMailData = [
                'title' => 'Eticket Mobile Password',
                'body' => 'This is your password for mobile eticket aplication. Please Change Your Password And Dont Show this mail for another people. thanks',
                'password' => $password,
                'nik' => $employeeId,
            ];
    
                
            
            
    
            $input = $request->all();
    
            $input['employee_id'] = $employeeId;
            $input['password_id'] = 0;
            $input['device_id'] = null; 
            $input['api_token'] = null;
            $user = Employee::create($input);
            
            // $token = $user->createToken('MyAuthApp')->plainTextToken;
            $inputPassword['employee_id'] = $input['employee_id'];
            $inputPassword['password'] = bcrypt($password);
            $inputPassword['non_active_date'] = Carbon::now()->addDays(90);
    
            if ($user) {
                $password = Password::create($inputPassword);
    
                if ($password) {
                    // Mail::to($request->employee_email)->send(new SendMail($testMailData));
                    $token = $user->createToken('MyAuthApp')->plainTextToken;
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

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }

        
    }


    public function selectEmployee(Request $request){
        $search = $request->search;

        if($search == ''){
            $employees = Employee::orderby('employee_name','asc')->select('employee_id','employee_name')->limit(5)->get();
        }else{
            $employees = Employee::orderby('employee_name','asc')->where('employee_name', 'ILIKE', "%".$search."%")->limit(5)->get();
        }

        if ($employees) {
            $response = array();
            foreach($employees as $employee){
                $response[] = array(
                    "id"=>$employee->employee_id,
                    "text"=>$employee->employee_name
                );
            }
            return $this->sendResponse($response, 'success'); 
        }

        
    }


    public function getEmployee(){
        try {
            
            $datas = Employee::orderBy('created_at', 'desc')->with('Role', 'Organization', 'Regional')->get();
            $isNow = Carbon::now();

            $dataEmployee = [];
            foreach($datas as $d){
                
                $data = $d;
                if ($d->quit_date > $isNow) {
                    $data['status'] = 'Active';
                }else{
                    $data['status'] = 'Non Active';
                }

                $dataEmployee[] = $data;
            }
            
            

            if ($datas) {
                return $this->sendResponse($dataEmployee, "successs");
            }else{
                return $this->sendError('Error validation', ['error' => $isNow]);
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $employee = Employee::with('Role', 'Organization', 'Regional')->where('employee_id', $id)->first();
            $supervisor = Employee::select('employee_name')->where('employee_id', $employee->supervisor_id)->first();


            if ($employee && $supervisor) {

                $employee['supervisor'] = $supervisor;

                return $this->sendResponse($employee, 'success get data');
            }else {
                return $this->sendError('Error validation', ['error' => $supervisor]);
            }
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
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
        try {
            $validator = Validator::make($request->all(), [
                'organization_id' => 'required',
                'supervisor_id' => 'required',
                'regional_id' => 'required',
                'role_id' => 'required',
                'join_date' => 'required',
                'quit_date' => 'required',
                'employee_birth' => 'required',
                'employee_ktp' => 'required|min:16',
                'employee_name' => 'required',
                'employee_email' => 'required|email',
            ]);

            if ($validator->fails()) {
                $errors =  $validator->errors()->all();
                return $this->sendError('Error validation', ['error' => $errors]);
            }

            $emailValidation = Employee::where(
                'employee_id', '!=', $id
                )->where('employee_email', $request->employee_email)
            ->get();

            foreach($emailValidation as $email){
                if ($email['employee_email'] != null) {
                    return $this->sendError('Error validation', ['error' =>  'email already exist']);
                }
            }

            $ktpValidation = Employee::where(
                'employee_id', '!=', $id
                )->where('employee_ktp', $request->employee_ktp)
            ->get();

            foreach($ktpValidation as $ktp){
                if ($ktp['employee_ktp'] != null) {
                    return $this->sendError('Error validation', ['error' => 'ktp already exist']);
                }
            }


            $updateEmployee = Employee::where('employee_id', $id)
                ->update([
                    'organization_id' => $request->organization_id,
                    'supervisor_id' => $request->supervisor_id,
                    'regional_id' => $request->regional_id,
                    'role_id' => $request->role_id,
                    'join_date' => $request->join_date,
                    'quit_date' => $request->quit_date,
                    'employee_birth' => $request->employee_birth,
                    'employee_ktp' => $request->employee_ktp,
                    'employee_name' => $request->employee_name,
                    'employee_email' => $request->employee_email,
                ]);

            if ($updateEmployee) {
                return $this->sendResponse($updateEmployee, 'success update data');
            }else{
                return $this->sendError('Error validation', ['error' => $updateEmployee]);
            }

                    
                
            


            
             
            
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = Employee::where('employee_id', $id)->delete();
            $deletePassword = Password::where('employee_id', $id)->delete();

            if ($delete) {
                return $this->sendResponse($delete, 'success delete data');
            }else{
                return $this->sendError('Error validation', ['error' => $delete]);
            }
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }



    public function randomPassword($len) {

        //enforce min length 8
        if($len < 8)
            $len = 8;
    
        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '123456789';
        $sets[]  = '!@#$%&*,.?';
    
        $password = NULL;
        
        //append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
    
        //use all characters to fill up to $len
        while(strlen($password) < $len) {
            //get a random set
            $randomSet = $sets[array_rand($sets)];
            
            //add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        
        //shuffle the password string before returning!
        return str_shuffle($password);
    }




    public function resetPassword($id){
        try {
            $password = EmployeeController::randomPassword(8);


            $input['employee_id'] = $id;
            $input['password'] = bcrypt($password);
            $input['non_active_date'] = Carbon::now()->addDays(90);
            $inputPassword = Password::create($input);

            $testMailData = [
                'title' => 'Eticket Mobile Password',
                'body' => 'This is your password for mobile eticket aplication. Please Change Your Password And Dont Show this mail for another people. thanks',
                'password' => $password,
                'nik' => $id,
            ];

            $isMail = Employee::select('employee_email')->where('employee_id', $id)->first(); 

            if ($inputPassword) {
                Employee::where('employee_id', $id)
                    ->update(
                        [
                            'password_id' => $inputPassword->password_id,
                        ]
                    );
                
                $sendMail = Mail::to($isMail->employee_email)->send(new SendMail($testMailData));

                if ($sendMail) {
                    $lastThreePasswordIds = Password::where('employee_id', $id)
                    ->orderBy('updated_at', 'desc')
                    ->take(3)
                    ->pluck('password_id');


                    if($lastThreePasswordIds){
                        Password::where('employee_id',$id)
                        ->whereNotIn('password_id', $lastThreePasswordIds)
                        ->delete();
                    }

                    return $this->sendResponse('success', 'success reset password');
                }else{
                    return $this->sendError('Error validation', ['error' => 'gagal kirim email']);
                }
                
            }else{
                return $this->sendError('Error validation', ['error' => $inputPassword]);
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }


    public function destroyAll(Request $request){

        try {   
            $validator = Validator::make($request->all(),[
                'ids' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{

                $ids = $request->ids;

                $deleteAll = Employee::whereIn('employee_id',explode(",",$ids))->delete();
                $deletePassword = Password::whereIn('employee_id', explode(",",$ids))->delete();

                if ($deleteAll) {
                    return $this->sendResponse($deleteAll, 'success delete data');
                }else{
                    return $this->sendError('Error validation', ['error' => $deleteAll]);
                }
            }
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }


    public function setStatusEmployee(Request $request, $id){
        try {
            $valditaor = Validator::make($request->all(),[
                'status' => 'required',
                'quit_date' => 'required'
            ]);

            if ($request->status == 'Active') {
                $quitDate = Carbon::now();

                $updateStatus = Employee::where('employee_id', $id)->update(['quit_date' => $quitDate]);
                if ($updateStatus) {
                    return $this->sendResponse($request->all(), 'user deactivation was successful');
                }
                
            }else{

                $updateStatus = Employee::where('employee_id', $id)
                ->update([
                    'quit_date' => $request->quit_date
                ]);

                return $this->sendResponse($updateStatus, 'successfully activated the user');
            }


        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }
}
