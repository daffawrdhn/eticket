<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Employee;
use App\Models\Helpdesk;
use App\Models\Regional;
use App\Models\RegionalPIC;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RegionalPicController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('containers.dashboard.approval');
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
            $validator = Validator::make($request->all(),[
                'employee_id' => 'required',
                'regional_id' => 'required'
            ]);

            if ($validator->fails()) {
                $errors =  $validator->errors();
                $errors->toArray();
                return $this->sendError('Error validation', ['error' => $errors]);
            }else{
                $checkData = RegionalPIC::where('employee_id', $request->employee_id)
                                        ->where('regional_id', $request->regional_id)
                                        ->first();

                if ($checkData != null) {
                    return $this->sendError('Error validation', ['error' => 'data has already been taken']);
                }else{


                    $checkEmployee = Helpdesk::where('employee_id', $request->employee_id)->first();

                    if ($checkEmployee != null) {
                        return $this->sendError('Error validation', ['error' => 'Employee Has Already Exist in Approval Helpdesk ']);
                    }else{
                        $input['regional_id'] = $request->regional_id;
                        $input['employee_id'] = $request->employee_id;

                        $postApproval = RegionalPIC::create($input);

                        if ($postApproval) {
                            return $this->sendResponse('success', 'success input new regional pic');
                        }else{
                            return $this->sendError('Error validation', ['error' => $postApproval]);
                        }
                    }
                }
                
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
            $isData = RegionalPIC::find($id);
            // $getEmployee = Employee::find($isData->employee_id);
            $getEmployee = Employee::find($isData->employee_id);
            $getRegional = Regional::find($isData->regional_id);
           
            $data = [
                'regional_pic' => $isData,
                'employee' => $getEmployee,
                'regional' => $getRegional
            ];

            return $this->sendResponse($data, 'success');
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    public function getDataRegionalPic(Request $request){
        try {
            
            $datas = RegionalPIC::all();

            $approvalData = [];
            $no =1;
            foreach($datas as $d){
                $data = $d;
                $getRegional = Regional::find($d->regional_id);
                $getEmployee = Employee::find($d->employee_id);




                $data['no'] = $no;
                $data['regional_name'] = $getRegional->regional_name;
                $data['employee_id'] = $getEmployee->employee_id;
                $data['employee_name'] = $getEmployee->employee_name;

                $approvalData[] = $data;
                $no++;
            }

            if ($request->ajax()) {
                $customers = $approvalData;
                return DataTables::of($customers)
                    ->addColumn('action', function ($row) {
                        $action = '
                            <button id="edit-approval" value="'. $row->id .'"  class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-pencil-fill"></i></button>
                            <button id="delete-approval" value="'. $row->id .'" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                        ';
                        return $action;
                    })->toJson();
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    public function getPics($regionalId)
    {
        try
        {
            $responses = RegionalPIC::where('regional_id',$regionalId)->get();
            $response = [
                'PIC ' => $responses->map(function ($response) {
                    $employee = Employee::where('employee_id', $response->employee_id)->first();
                    return [
                        'employee_id' => $employee->employee_id,
                        // 'employee_id_int' => intval(sprintf("%08d",$employee->employee_id)),
                        'employee_name' => $employee->employee_name,
                        'supervisor_id' => $employee->supervisor_id,
                        // 'supervisor_id_int' => 0000000001,
                    ];
                }),
            ];

            return $this->sendResponse($response, 'PICs collected.');

        } catch (Exception $error) {
            return $this->sendError('Error get PICs', ['error' => $error->getMessage()]);
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
            $validator = Validator::make($request->all(),[
                'employee_id' => 'required',
                'regional_id' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{

                $checkData = RegionalPIC::where('employee_id', $request->employee_id)
                                        ->where('regional_id', $request->regional_id)
                                        ->where('id', '!=' , $id)
                                        ->first();

                if ($checkData != null) {
                    return $this->sendError('Error validation', ['error' => 'data already has been taken']);
                }else{

                    $checkEmployee = Helpdesk::where('employee_id', $request->employee_id)->first();

                    if ($checkEmployee == null) {
                        $updatePic = RegionalPIC::where('id', $id)
                            ->update([
                                'regional_id' => $request->regional_id,
                                'employee_id' => $request->employee_id
                            ]);
    
                        if ($updatePic) {
                            return $this->sendResponse($checkData, 'success update data');
                        }else{
                            return $this->sendError('Error validation', ['error' => $updatePic]);
                        }
                    }else{
                        return $this->sendError('Error validation', ['error' => 'Employee Has Already Exist in Approval Helpdesk ']);
                    }
                    
                }
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
            $delete = RegionalPIC::where('id', $id)->delete();

            if ($delete) {
                return $this->sendResponse($delete, 'success delete data');
            }else{
                return $this->sendError('Error validation', ['error' => $delete]);
            }
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    
}
