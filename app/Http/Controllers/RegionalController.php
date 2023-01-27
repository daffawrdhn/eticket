<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Employee;
use App\Models\Regional;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RegionalController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('containers.dashboard.regional');
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
                    'regional_name' => 'required|unique:regional_tbl,regional_name'
                ]);
        
                if ($validator->fails()) {
                    return $this->sendError('Error validation', ['error' => $validator->errors()->all()]);
                }else{

                    $regional['regional_name'] = $request->regional_name;
                    $storeRegional = Regional::create($regional);

                    if ($storeRegional) {
                        return $this->sendResponse($regional, 'success input new regional');
                    }else{
                        return $this->sendError('Error validation', ['error' => $storeRegional]);
                    }
                }

            } catch (Exception $error) {
                return $this->sendError('Error validation', ['error' => $error]);
            }
        

    }


    public function getRegional(){
        try {
            
            $getRegional = Regional::all();

            if ($getRegional) {
                return $this->sendResponse($getRegional, 'success get data');
            }else{
                return $this->sendError('Error validation', ['error' => $getRegional]);
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
            $data = Regional::where('regional_id',$id)->first();


            if ($data) {
                return $this->sendResponse($data, 'success get data');
            }else{
                return $this->sendError('Error validation', ['error' => $data]);
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }

    public function edit(Regional $regional)
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
                'regional_name' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{

                $update = Regional::where('regional_id', $id)
                    ->update([
                        'regional_name' => $request->regional_name
                    ]);

                $data = Regional::find($id);

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
     * @param  \App\Models\Feature  $feature
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $delete = Regional::where('regional_id', $id)->delete();

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

                $deleteAll = Regional::whereIn('regional_id',explode(",",$ids))->delete();

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


    public function selectRegional(Request $request){
        $search = $request->search;

        if($search == ''){
            $regionals = Regional::orderby('regional_name','asc')->select('regional_id','regional_name')->limit(5)->get();
        }else{
            $regionals = Regional::orderby('regional_name','asc')->where('regional_name', 'ILIKE', "%".$search."%")->limit(5)->get();
        }

        if ($regionals) {
            $response = array();
            foreach($regionals as $regional){
                $response[] = array(
                    "regional_id"=>$regional->regional_id,
                    "regional_name"=>$regional->regional_name
                );
            }
            return $this->sendResponse($response, 'success'); 
        }

        
    }


    public function selectRegionalByEmployeeId(Request $request, $id){
        $search = $request->search;
        $isRegionalId = Employee::find($id);
        
        if($search == ''){
            $regionals = Regional::orderby('regional_name','asc')
                            ->select('regional_id','regional_name')
                            ->where('regional_id', $isRegionalId->regional_id)
                            ->limit(5)
                            ->get();

                            return $this->sendResponse($regionals, 'success'); 
        }else{
            $regionals = Regional::orderby('regional_name','asc')
                        ->where('regional_name', 'ILIKE', "%".$search."%")
                        ->where('regional_id', $id)
                        ->limit(5)
                        ->get();
        }

        if ($regionals) {
            $response = array();
            foreach($regionals as $regional){
                $response[] = array(
                    "regional_id"=>$regional->regional_id,
                    "regional_name"=>$regional->regional_name
                );
            }
            return $this->sendResponse($response, 'success'); 
        }

        
        
    }

    public function dataTableRegional(Request $request){
        try {
            
            $datas = Regional::all();
            
            $regionals = [];
            $no =1;
            foreach($datas as $d){
                $data = $d;
                
                $data['no'] = $no;
                $data['regional_name'] = $d->regional_name;

                $regionals[] = $data;
                $no++;
            }
            if ($request->ajax()) {
                $customers = $regionals;
                return DataTables::of($customers)
                    ->addColumn('action', function ($row) {
                        $action = '
                            <button id="edit-regional" value="'. $row->regional_id .'"  class="btn btn-sm btn-success me-1" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="bi bi-pencil-fill"></i></button>
                            <button id="delete-regional" value="'. $row->regional_id .'" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                        ';
                        return $action;
                    })->toJson();
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
        }
    }
}
