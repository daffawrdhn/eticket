<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Ticket;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
                'feature_id' => 'required',
                'sub_feature_id' => 'required',
                'ticket_title' => 'required',
                'ticket_description' => 'required',
                'ticket_status' => 'required',
                'photo' => 'required',
            ]);

    
            if ($validator->fails()) {
                return $this->sendError('Error validation', ['error' => $validator->errors()]);
            }else{
                
                $employeeId = Auth::user()->employee_id;

                $image = $request->file('photo');

                // Jika ada file yang diupload
                if ($image) {
                    // Buat nama file unik
                    $filename =  time().'.'.$request->photo->extension();
        
                    // Pindahkan file ke folder public/images
                    $image->move(public_path('asset/images'), $filename);
        
                    // Ubah ukuran file
                    $thumbnail = $request->photo->make(public_path('images/' . $filename))->resize(100, 100);
        
                    // Simpan file thumbnail
                    $thumbnail->save(public_path('asset/images/' . $filename));
        
                    // Update field photo pada tabel tickets
                }

                $ticket = [
                    'employee_id' => $employeeId,
                    'feature_id' => $request->feature_id,
                    'sub_feature_id' => $request->sub_feature_id,
                    'ticket_title' => $request->ticket_title,
                    'ticket_description' => $request->ticket_description,
                    'ticket_status' => $request->ticket_status,
                    'photo' => $filename

                ];

                $storeTicket = Ticket::create($ticket);

                if ($storeTicket) {
                    return $this->sendResponse($thumbnail, 'success input new ticket');
                }else{
                    return $this->sendError('Error validation', ['error' => $storeTicket]);
                }
            }

        } catch (Exception $error) {
            return $this->sendError('Error validation', ['error' => $error]);
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
