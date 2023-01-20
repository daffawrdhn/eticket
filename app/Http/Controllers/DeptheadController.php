<?php

namespace App\Http\Controllers;

use App\Models\Depthead;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Employee;
use App\Models\Feature;
use App\Models\Helpdesk;
use App\Models\RegionalPIC;
use App\Models\Ticket;
use App\Models\TicketStatusHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

class DeptheadController extends BaseController
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Depthead  $depthead
     * @return \Illuminate\Http\Response
     */
    public function show(Depthead $depthead)
    {
        //
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
    public function update(Request $request, Depthead $depthead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Depthead  $depthead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Depthead $depthead)
    {
        //
    }

    public function getDepthead()
    {
        try
        {
            $responses = Depthead::get();
            $response = [
                'DEPTHEAD ' => $responses->map(function ($response) {
                    $employee = Employee::where('employee_id', $response->employee_id)->first();
                    return [
                        'employee_id' => $employee->employee_id,
                        'employee_name' => $employee->employee_name,
                        'supervisor_id' => $employee->supervisor_id,
                    ];
                }),
            ];

            return $this->sendResponse($response, 'Dept Head collected.');

        } catch (Exception $error) {
            return $this->sendError('Error get Dept Head', ['error' => $error->getMessage()]);
        }
    }
}
