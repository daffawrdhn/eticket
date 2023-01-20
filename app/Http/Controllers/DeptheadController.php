<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Depthead;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DeptheadController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('containers.dashboard.depthead');
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
                'employee_id' => 'required|unique:depthead_tbl,employee_id',
            ]);

            
            if ($validator->fails()) {
                $errors =  $validator->errors();
                $errors->toArray();
                return $this->sendError('Error validation', ['error' => $errors]);
            }else{

                    
                $input['employee_id'] = $request->employee_id;
                $postApproval = Depthead::create($input);

                if ($postApproval) {
                    return $this->sendResponse('success', 'success input new helpdesk');
                }else{
                    return $this->sendError('Error validation', ['error' => $postApproval]);
                }
                
            }
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Depthead  $depthead
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $isData = Depthead::find($id);
            // $getEmployee = Employee::find($isData->employee_id);
            $getEmployee = Employee::find($isData->employee_id);
           
            $data = [
                'depthead' => $isData,
                'employee' => $getEmployee,
            ];

            return $this->sendResponse($data, 'success');
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Depthead  $depthead
     * @return \Illuminate\Http\Response
     */
    public function edit(Depthead $depthead)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Depthead  $depthead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(),[
                'employee_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{

                $checkData = Depthead::where('employee_id', $request->employee_id)
                                        ->where('id', '!=' , $id)
                                        ->first();

                if ($checkData != null){
                    return $this->sendError('Multiple Data', ['error' => 'data already has been taken']);
                }else{

                    
                    $updatePic = Depthead::where('id', $id)
                        ->update([
                            'employee_id' => $request->employee_id
                        ]);

                    if ($updatePic) {
                        return $this->sendResponse($checkData, 'success update data');
                    }else{
                        return $this->sendError('Error validation', ['error' => $updatePic]);
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
     * @param  \App\Models\Depthead  $depthead
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = Depthead::where('id', $id)->delete();

            if ($delete) {
                return $this->sendResponse($delete, 'success delete data');
            }else{
                return $this->sendError('Error validation', ['error' => $delete]);
            }
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    public function getDepthead(Request $request){
        try {
            
            $datas = Depthead::all();

            $approvalData = [];
            $no =1;
            foreach($datas as $d){
                $data = $d;
                $getEmployee = Employee::find($d->employee_id);




                $data['no'] = $no;
                $data['employee_id'] = $getEmployee == null ? 0 : $getEmployee->employee_id;
                $data['employee_name'] = $getEmployee == null ? 'null' : $getEmployee->employee_name;

                $approvalData[] = $data;
                $no++;
            }
            // return $this->sendResponse($datas, 'success update data');
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
}
