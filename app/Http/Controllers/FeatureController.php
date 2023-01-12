<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Feature;
use App\Models\SubFeature;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

                    $feature['feature_name'] = $request->feature_name;
                    $storeFeature = Feature::create($feature);

                    if ($storeFeature) {
                        return $this->sendResponse($feature, 'success input new feature');
                    }else{
                        return $this->sendError('Error validation', ['error' => $storeFeature]);
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

            $isFeature = SubFeature::where('feature_id', '$id')->get();
            
            if ($isFeature) {
                return $this->sendResponse('warning', 'this data is already exists in another table, are you sure to delete this data ??');
            }

            $delete = Feature::find($id)->delete();

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
}
