<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\SubFeature;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubFeatureController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('containers.dashboard.sub_feature');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
                'sub_feature_name' => 'required|unique:sub_feature_tbl,sub_feature_name',
                'feature_id' => 'required'
            ]);
    
            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{

                
                $subFeature['sub_feature_name'] = $request->sub_feature_name;
                $subFeature['feature_id'] = $request->feature_id;
                $storeFeature = SubFeature::create($subFeature);

                if ($storeFeature) {
                    return $this->sendResponse($subFeature, 'success input new sub feature');
                }else{
                    return $this->sendError('Error validation', ['error' => $storeFeature]);
                }
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    public function getSubFeature(){
        try {

            $getSubFeature = SubFeature::with('feature')->get();

            if ($getSubFeature) {
                return $this->sendResponse($getSubFeature, 'success get data');
            }else{
                return $this->sendError('Error validation', ['error' => $getSubFeature]);
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SubFeature  $subFeature
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = SubFeature::with('feature')->where('sub_feature_id',$id)->first();


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
     * @param  \App\Models\SubFeature  $subFeature
     * @return \Illuminate\Http\Response
     */
    public function edit(SubFeature $subFeature)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SubFeature  $subFeature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(),[
                'sub_feature_name' => 'required',
                'feature_id' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{

                $updateFeature = SubFeature::where('sub_feature_id', $id)
                    ->update([
                        'sub_feature_name' => $request->sub_feature_name,
                        'feature_id' => $request->feature_id
                    ]);

                $data = SubFeature::find($id);

                if ($updateFeature) {
                    return $this->sendResponse($data, 'success update data');
                }else{
                    return $this->sendError('Error validation', ['error' => $updateFeature]);
                }
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SubFeature  $subFeature
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = SubFeature::where('sub_feature_id', $id)->delete();

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

                $deleteAll = SubFeature::whereIn('sub_feature_id',explode(",",$ids))->delete();

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

    public function subFeatureDataTable(Request $request){
        try {
            
            $datas = SubFeature::with('feature')->get();

            $approvalData = [];
            $no =1;
            foreach($datas as $d){

                $data = $d;



                $data['no'] = $no;
                $data['feature_name'] = $d->feature['feature_name'];
                $data['sub_feature_name'] = $d->sub_feature_name;

                $approvalData[] = $data;
                $no++;
            }
            
            if ($request->ajax()) {
                $customers = $approvalData;
                return DataTables::of($customers)
                    ->addColumn('action', function ($row) {
                        $action = '
                            <div class="d-flex">
                                <div class="tooltipButtonEdit">
                                    <button id="edit-sub_feature" value="'. $row->sub_feature_id .'"  class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-pencil-fill"></i></button>
                                    <p class="tooltiptext">Edit Sub Feature</p>
                                </div>
                                <div class="tooltipButtonDelete">
                                    <button id="delete-sub_feature" value="'. $row->sub_feature_id .'" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                                    <p class="tooltiptext">Delete Sub Feature</p>
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
