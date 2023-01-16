<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Feature;
use App\Models\SubFeature;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;



class FeatureController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('containers.dashboard.feature');
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
                    'feature_name' => 'required|unique:feature_tbl,feature_name',
                ]);
        
                if ($validator->fails()) {
                    return $this->sendError('Error validation', ['error' => $validator->errors()]);
                }else{

                    $checkFeature = Feature::where('feature_name', 'ILIKE', '%'. $request->feature_name .'%')->first();

                    if ($checkFeature != null) {
                        return $this->sendError('Error validation', ['error' => 'Feature Name has Already been taken']);
                    }else{

                        $feature['feature_name'] = $request->feature_name;
                        $storeFeature = Feature::create($feature);

                        if ($storeFeature) {
                            return $this->sendResponse($feature, 'success input new feature');
                        }else{
                            return $this->sendError('Error validation', ['error' => $storeFeature]);
                        }
                    }
                }

            } catch (Exception $error) {
                return $this->sendError('Error validation', ['error' => $error]);
            }
        

    }


    public function getFeature(){
        try {
            
            $getFeature = Feature::all();

            if ($getFeature) {
                return $this->sendResponse($getFeature, 'success get data');
            }else{
                return $this->sendError('Error validation', ['error' => $getFeature]);
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = Feature::where('feature_id',$id)->first();


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
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function edit(Feature $feature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(),[
                'feature_name' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{

                $checkFeature = Feature::where('feature_name', $request->feature_name)->first();

                if ($checkFeature != null) {
                    return $this->sendError('Error validation', ['error' => 'Feature Name has Already been taken']);
                }else{

                    $updateFeature = Feature::where('feature_id', $id)
                        ->update([
                            'feature_name' => $request->feature_name
                        ]);
    
                    $data = Feature::find($id);
    
                    if ($updateFeature) {
                        return $this->sendResponse($data, 'success update data');
                    }else{
                        return $this->sendError('Error validation', ['error' => $updateFeature]);
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
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $isFeature = SubFeature::where('feature_id', $id)->first();
            
            if ($isFeature != null) {
                return $this->sendError('Error validation', ['error' => ['this data is already exists in another table']]);

            }else{
                $delete = Feature::where('feature_id',$id)->delete();
    
                if ($delete) {
                    return $this->sendResponse($delete, 'success delete data');
                }else{
                    return $this->sendError('Error validation', ['error' => $delete]);
                }
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

                $deleteAll = Feature::whereIn('feature_id',explode(",",$ids))->delete();

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


    public function selectFeature(Request $request){
        $search = $request->search;

        if($search == ''){
            $features = Feature::orderby('feature_name','asc')->select('feature_id','feature_name')->limit(5)->get();
        }else{
            $features = Feature::orderby('feature_name','asc')->where('feature_name', 'ILIKE', "%".$search."%")->limit(5)->get();
        }

        if ($features) {
            $response = array();
            foreach($features as $feature){
                $response[] = array(
                    "feature_id"=>$feature->feature_id,
                    "feature_name"=>$feature->feature_name
                );
            }
            return $this->sendResponse($response, 'success'); 
        }

    }

    public function featureDataTable(Request $request){
        try {
            
            $datas = Feature::all();
            
            $features = [];
            $no =1;
            foreach($datas as $d){
                $data = $d;
                
                $data['no'] = $no;
                $data['feature_name'] = $d->feature_name;

                $features[] = $data;
                $no++;
            }
            
            if ($request->ajax()) {
                $customers = $features;
                return DataTables::of($customers)
                    ->addColumn('action', function ($row) {
                        $action = '
                            <button id="edit-feature" value="'. $row->feature_id .'"  class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-pencil-fill"></i></button>
                            <button id="delete-feature" value="'. $row->feature_id .'" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                        ';
                        return $action;
                    })->toJson();
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }
}
