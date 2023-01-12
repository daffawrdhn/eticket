<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Approval;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApprovalController extends BaseController
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

                $input['regional_id'] = $request->regional_id;
                $input['employee_id'] = $request->employee_id;

                $postApproval = Approval::create($input);

                if ($postApproval) {
                    return $this->sendResponse('success', 'success input new approval');
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
     * @param  \App\Models\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function show(Approval $approval)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function edit(Approval $approval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Approval $approval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Approval  $approval
     * @return \Illuminate\Http\Response
     */
    public function destroy(Approval $approval)
    {
        //
    }


    public function getApproval(Request $request){
        try {
            
            $datas = Approval::with('regional', 'employee')->get();

            return $this->sendResponse($datas, 'success input new approval');
            // $approvalData = [];
            // $no =1;
            // foreach($datas as $d){
            //     $data = $d;

            //     $data['no'] = $no;
            //     $data['regional_name'] = $d->regional['regional_name'];
            //     $data['employee_id'] = $d->employee['employee_id'];
            //     $data['employee_name'] = $d->employee['employee_name'];

            //     $approvalData[] = $data;
            //     $no++;
            // }
            

            // if ($request->ajax()) {
            //     $customers = $approvalData;
            //     return datatables()->of($customers)
            //         ->addColumn('action', function ($row) {
            //             $action = '
            //                 <button id="edit-approval" value="'. $row->employee_id .'"  class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop><i class="bi bi-pencil-fill"></i></button>
            //                 <button id="delete-approval" value="'. $row->employee_id .'" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
            //             ';
            //             return $action;
            //         })->toJson();
            // }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }
}
