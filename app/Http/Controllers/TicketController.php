<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Feature;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;


class TicketController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    public function features()
    {
        $features = Feature::with('subfeatures')->get();
        
        $response = [
            'feature' => $features->map(function ($feature) {
                return [
                    'feature_id' => $feature->feature_id,
                    'feature_name' => $feature->feature_name,
                    'sub_feature' => $feature->subfeatures->map(function ($subfeature) {
                        return [
                            'sub_feature_id' => $subfeature->sub_feature_id,
                            'sub_feature_name' => $subfeature->sub_feature_name
                        ];
                    })
                ];
            })
            ];
            
            return $this->sendResponse($response, 'Features responses get.');
                        
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'feature_id' => 'required',
            'sub_feature_id' => 'required',
            'ticket_title' => 'required',
            'ticket_description' => 'required',
            'ticket_status_id' => 'required',
            'photo' => 'required|mimes:jpg,png',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', ['error' => $validator->errors()]);
        } else {
            $employeeId = Auth::user();

            $image = $request->file('photo');

            if ($image) {
                $filename =  time().'.'.$request->photo->extension();
            
                $image->move(public_path('asset/images'), $filename);
            
                $thumbnail = Image::make(public_path('asset/images/' . $filename))->resize(100, 100);
            
                $thumbnail->save(public_path('asset/images/' . $filename));
            }

            
            $ticket = [
                'employee_id' => $employeeId->employee_id,
                'feature_id' => $request->feature_id,
                'sub_feature_id' => $request->sub_feature_id, // Add this line
                'ticket_title' => $request->ticket_title,
                'ticket_description' => $request->ticket_description,
                'ticket_status_id' => $request->ticket_status_id,
                'photo' => $filename
            ];
            // dd($ticket);

            $storeTicket = Ticket::create($ticket);
            $storeTicket['supervisor_id'] = $employeeId->supervisor_id;

            if ($storeTicket instanceof Ticket) {
                return $this->sendResponse($storeTicket, 'success input new ticket');
            } else {
                return $this->sendError('Error creating ticket', ['error' => $storeTicket]);
            }
        }
    } catch (Exception $error) {
        return $this->sendError('Error creating ticket', ['error' => $error->getMessage()]);
    }
}


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
