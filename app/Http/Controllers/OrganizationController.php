<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Organization;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrganizationController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('containers.dashboard.organization');
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
                'organization_name' => 'required|unique:organization_tbl,organization_name'
            ]);
    
            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()->all()]);
            }else{

                $organization['organization_name'] = $request->organization_name;
                $storeOrganization = Organization::create($organization);

                if ($storeOrganization) {
                    return $this->sendResponse($organization, 'success input new organization');
                }else{
                    return $this->sendError('Error validation', ['error' => $storeOrganization]);
                }
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    public function getOrganization(){
        try {
            
            $getOrganization = Organization::all();

            if ($getOrganization) {
                return $this->sendResponse($getOrganization, 'success get data');
            }else{
                return $this->sendError('Error validation', ['error' => $getOrganization]);
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
            $data = Organization::where('organization_id',$id)->first();


            if ($data) {
                return $this->sendResponse($data, 'success get data');
            }else{
                return $this->sendError('Error validation', ['error' => $data]);
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
            $validator = Validator::make($request->all(),[
                'organization_name' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{

                $update = Organization::where('organization_id', $id)
                    ->update([
                        'organization_name' => $request->organization_name
                    ]);

                $data = Organization::find($id);

                if ($update) {
                    return $this->sendResponse($data, 'success update data');
                }else{
                    return $this->sendError('Error validation', ['error' => $update]);
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
            $delete = Organization::where('organization_id', $id)->delete();

            if ($delete) {
                return $this->sendResponse($delete, 'success delete data');
            }else{
                return $this->sendError('Error validation', ['error' => $delete]);
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

                $deleteAll = Organization::whereIn('organization_id',explode(",",$ids))->delete();

                if ($deleteAll) {
                    return $this->sendResponse($deleteAll, 'success delete data');
                }else{
                    return $this->sendError('Error validation', ['error' => $deleteAll]);
                }
            }
        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $validator->errors()]);
        }
    }

    public function selectOrganization(Request $request){
        $search = $request->search;

        if($search == ''){
            $organizations = Organization::orderby('organization_name','asc')->select('organization_id','organization_name')->limit(5)->get();
        }else{
            $organizations = Organization::orderby('organization_name','asc')->where('organization_name', 'ILIKE', "%".$search."%")->limit(5)->get();
        }

        if ($organizations) {
            $response = array();
            foreach($organizations as $organization){
                $response[] = array(
                    "id"=>$organization->organization_id,
                    "text"=>$organization->organization_name
                );
            }
            return $this->sendResponse($response, 'success'); 
        }

        
    }

    public function dataTableOrganization(Request $request){
        try {
            
            $datas = Organization::all();
            
            $organizations = [];
            $no =1;
            foreach($datas as $d){
                $data = $d;
                
                $data['no'] = $no;
                $data['organization_name'] = $d->organization_name;

                $organizations[] = $data;
                $no++;
            }
            if ($request->ajax()) {
                $customers = $organizations;
                return DataTables::of($customers)
                    ->addColumn('action', function ($row) {
                        $action = '
                            <div class="d-flex">
                                <div class="tooltipButtonEdit">
                                    <button id="edit-organization" value="'. $row->organization_id .'"  class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-pencil-fill"></i></button>
                                    <p class="tooltiptext">Edit Organization</p>
                                </div>
                                <div class="tooltipButtonDelete">
                                    <button id="delete-organization" value="'. $row->organization_id .'" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                                    <p class="tooltiptext">Delete Organization</p>
                                </div>
                            </div>
                        ';
                        return $action;
                    })->toJson();
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }
}
