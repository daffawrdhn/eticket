<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Employee;
use App\Models\Password;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Mail\SendMail;
use App\Models\Helpdesk;
use App\Models\RegionalPIC;
use App\Models\Ticket;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\Ticket as TicketMail;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
            ],
            [
                'organization_id.required' => 'organization field is required',
                'regional_id.required' => 'regional field is required',
                'role_id.required' => 'role field is required',
                'supervisor_id.required' => 'supervisor field is required',
                'quit_date.required' => 'quit field is required',
                'join_date.required' => 'join date field is required',
                'employee_birth.required' => 'date of birth field is required',
                'employee_email.required' => 'Email field is required',
                'employee_ktp.required' => 'No. KTP field is required',
                'employee_name.required' => 'Name field is required',
            ]);
    
            if ($validator->fails()) {
                $errors =  $validator->errors();
                $errors->toArray();
                return $this->sendError('Error validation', ['error' => $errors]);
            }
    
            //generate employee ID 
    
            $employeeData = Employee::orderBy('employee_id', 'desc')->first();   
            $employeeCount = (int)$employeeData->employee_id;
            $employeeCount++;
    
            $employeeId = sprintf("%09s", $employeeCount);
    
            // generate Password
    
            $employeePassword = $this->randomPassword(8);
    
            // send mail
    
            $input = $request->all();
    
            $input['employee_id'] = $employeeId;
            $input['password_id'] = 0;
            $input['device_id'] = null; 
            $input['api_token'] = null;
            $user = Employee::create($input);

            // $token = $user->createToken('MyAuthApp')->plainTextToken;
            $inputPassword['employee_id'] = $input['employee_id'];
            $inputPassword['password'] = bcrypt($employeePassword);
            $inputPassword['non_active_date'] = Carbon::now()->addDays(90);
    
            if ($user) {
                $password = Password::create($inputPassword);
    
                if ($password) {
                    
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

            $params = [
                'recipients' => [
                  [
                    'email' => $request->employee_email,
                    'subject' => 'Password Eticket Mobile',
                    'body' => 'This is your password for mobile eticket aplication. Please Change Your  Password And Dont Show this mail for another people. thanks. ',
                    'nik' =>  $user->employee_id,
                    'password' => $employeePassword
                  ]
                ],
              ];
              
            
            $this->sendNotifEmail($params);
    
            
            return $this->sendResponse($success, 'User created successfully.');

        } catch (Exception $error) {
            return $this->sendError('Error', ['error' => $error]);
        }

        
    }


    public function selectEmployee(Request $request){
        

        if($request->search == ''){
            $employees = Employee::orderby('employee_name','asc')
                            ->select('employee_id','employee_name')
                            ->limit(5)
                            ->get();
        }else{
            $number = "/^[0-9]*$/";
            if (preg_match($number, $request->search)) {
                $employees = Employee::orderby('employee_name','asc')->where('employee_id', 'ILIKE', "%".$request->search."%")->limit(5)->get();

            }else{
                $employees = Employee::orderby('employee_name','asc')->where('employee_name', 'ILIKE', "%".$request->search."%")->limit(5)->get();

            }
        }

        // return $this->sendResponse($employees, 'success'); 

        if ($employees) {
            $response = array();
            foreach($employees as $employee){
                $response[] = array(
                    "id"=>$employee->employee_id,
                    "text"=> "".$employee->employee_id." - ".$employee->employee_name.""
                );
            }
            return $this->sendResponse($response, 'success'); 
        }

        
    }

    public function selectEmployeeByRegional(Request $request, $id){
        if ($id == 0) {
            $response = [
                "id" => null,
                "text" => "pelase select regional"
            ];

            return $this->sendResponse($response, 'success'); 

        }else{

        if($request->search == ''){
            $employees = Employee::orderby('employee_name','asc')
                            ->select('employee_id','employee_name')
                            ->where('regional_id', $id)
                            ->limit(5)
                            ->get();
        }else{
            $number = "/^[0-9]*$/";
            if (preg_match($number, $request->search)) {
                
                $employees = Employee::orderby('employee_name','asc')
                        ->where('employee_id', 'ILIKE', "%".$request->search."%")
                        ->where('regional_id', $id)
                        ->limit(5)
                        ->get();
            }else{
                $employees = Employee::orderby('employee_name','asc')
                        ->where('employee_name', 'ILIKE', "%".$request->search."%")
                        ->where('regional_id', $id)
                        ->limit(5)
                        ->get();
            }
            
        }

        if ($employees) {
            $response = array();
            foreach($employees as $employee){
                $response[] = array(
                    "id"=>$employee->employee_id,
                    "text"=> "".$employee->employee_id." - ".$employee->employee_name.""
                );
            }
            return $this->sendResponse($response, 'success end'); 
        }

        }
        
    }


    public function getEmployee(Request $request){
        try {
            
            $datas = Employee::orderBy('created_at', 'asc')->with('Role', 'Organization', 'Regional')->get();
            $isNow = Carbon::now();

            $dataEmployee = [];
            foreach($datas as $d){
                $employee = Employee::where('employee_id', $d->supervisor_id)->first();
                $data = $d;

                $data['employee_id'] = $d->employee_id;
                $data['employee_ktp'] = $d->employee_ktp;
                $data['supervisor_id'] = $d->supervisor_id;
                $data['supervisor_name'] = $employee->employee_name;
                $data['role_name'] = $data->role['role_name'];
                $data['regional_name'] = $data->regional['regional_name'];
                $data['organization_name'] = $data->organization['organization_name'];
                if ($d->quit_date > $isNow) {
                    $data['status'] = 'Active';
                }else{
                    $data['status'] = 'Non Active';
                }

                $dataEmployee[] = $data;
            }
            

            if ($request->ajax()) {
                $customers = $dataEmployee;
                return DataTables::of($customers)
                    ->addColumn('action', function ($row) {
                        $action = '
                        <div class="tooltipButtonEdit">
                            <div class="btn-group">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill"></i>
                                </button>
                                <ul class="dropdown-menu">
                                <li><a class="dropdown-item" id="reset-pass" href="#" data-id="'. $row->employee_id .'">reset Pass</a></li>
                                <li><a class="dropdown-item" id="edit-user" href="#" data-id="'. $row->employee_id .'" data-bs-toggle="modal" data-bs-target="#modalAddUser">Edit</a></li>
                                <li><a class="dropdown-item" id="delete-user" data-id="'. $row->employee_id .'" href="#">Delete</a></li>
                                </ul>
                            </div>
                            <p class="tooltiptext">Setting</p>
                        </div>
                        ';
                        return $action;
                    })->addColumn('status', function ($row) {
                        if ($row->status == 'Active') {
                            $html = '<button class="btn btn-sm btn-success" id="status" value="'. $row->status .'" data-id="'. $row->employee_id .'">'. $row->status .'</button>
                            ';
                        }
                        else{
                            $html = '<button class="btn btn-sm btn-danger" id="status" value="'. $row->status .'" data-id="'. $row->employee_id .'">'. $row->status .'</button>';
                        }
                        
                        return $html;
                    })->rawColumns(['status', 'action'])->toJson();
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
                    return $this->sendError('Error validation', ['error' =>  ['email already exist']]);
                }
            }

            $ktpValidation = Employee::where(
                'employee_id', '!=', $id
                )->where('employee_ktp', $request->employee_ktp)
            ->get();

            foreach($ktpValidation as $ktp){
                if ($ktp['employee_ktp'] != null) {
                    return $this->sendError('Error validation', ['error' => ['ktp already exist']]);
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
            

            $employeeRegionalPic = RegionalPIC::where('employee_id', $id)->first();
            $employeeHelpdesk = Helpdesk::where('employee_id', $id)->first();
            $employeeTicket = Ticket::where('employee_id', $id)->first();

            if ($employeeRegionalPic == null || $employeeHelpdesk == null || $employeeTicket == null) {
                
                $delete = Employee::where('employee_id', $id)->delete();
                
    
    
                if ($delete) {
                    $deletePassword = Password::where('employee_id', $id)->delete();
                    return $this->sendResponse($delete, 'success delete data');
                }else{
                    return $this->sendError('Error validation', ['error' => $delete]);
                }
            }else{
                return $this->sendError('Error validation', ['error' => 'this data is already exists in another table']);
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
            $employeePassword = 'Admin123!';


            $input['employee_id'] = $id;
            $input['password'] = bcrypt($employeePassword);
            $input['non_active_date'] = Carbon::now()->addDays(90);
            $inputPassword = Password::create($input);
            
            $isMail = Employee::select('employee_email')->where('employee_id', $id)->first(); 

            $params = [
                'recipients' => [
                    [
                        'email' => $isMail->employee_email,
                        'subject' => 'Password Eticket Mobile',
                        'body' => 'This is your password for mobile eticket aplication. Please Change Your  Password And Dont Show this mail for another people. thanks. ',
                        'nik' =>  $id,
                        'password' => $employeePassword
                        ]
                    ],
                ];
                
                
              
              
            if ($inputPassword) {
                  Employee::where('employee_id', $id)
                  ->update(
                        [
                          'password_id' => $inputPassword->password_id,
                        ]
                    );
                    
                    
                    
                    // if ($sendMail) {
                    $lastThreePasswordIds = Password::where('employee_id', $id)
                    ->orderBy('updated_at', 'desc')
                    ->take(3)
                    ->pluck('password_id');
                    
                    
                    if($lastThreePasswordIds){
                        Password::where('employee_id',$id)
                        ->whereNotIn('password_id', $lastThreePasswordIds)
                        ->delete();
                        
                    }

                    // return $this->sendResponse($params, 'success reset password');
                    
                    $this->sendNotifEmail($params);

                    return $this->sendResponse('success', 'success reset password');
               
                
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
            $validator = Validator::make($request->all(),[
                'status' => 'required',
                'quit_date' => 'required'
            ]);

            if ($validator->fails()) {
                $errors =  $validator->errors()->all();
                return $this->sendError('Error validation', ['error' => $errors]);
            }

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

    function sendNotifEmail(array $params) {
        foreach ($params['recipients'] as $recipient) {
          $data = $recipient;
          Mail::to($recipient['email'])->send(new TicketMail($data));
        }
    }
}
