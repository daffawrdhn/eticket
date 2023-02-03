<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Employee;
use App\Models\Feature;
use App\Models\Helpdesk;
use App\Models\RegionalPIC;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketStatusHistory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Mail;
use App\Mail\Ticket as TicketMail;


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

    public function getSummary()
    {
        try
        {
            $auth = Auth::user();
            $open = Ticket::where('employee_id', $auth->employee_id)->where('ticket_status_id', 1)->count(); // Open
            $ap1 = Ticket::where('employee_id', $auth->employee_id)->where('ticket_status_id', 2)->count(); // Approve 1
            $ap2 = Ticket::where('employee_id', $auth->employee_id)->where('ticket_status_id', 3)->count(); // Approve 2
            $ap3 = Ticket::where('employee_id', $auth->employee_id)->where('ticket_status_id', 4)->count(); // Approve 3
            $completed = Ticket::where('employee_id', $auth->employee_id)->where('ticket_status_id', 5)->count(); // Completed
            $rejected = Ticket::where('employee_id', $auth->employee_id)->where('ticket_status_id', 6)->count(); // Rejected
            $total = Ticket::where('employee_id', $auth->employee_id)->count(); // Rejected
            $summary = [
                'open' => $open,
                'ap1' => $ap1,
                'ap2' => $ap2,
                'ap3' => $ap3,
                'completed' => $completed,
                'rejected' => $rejected,
                'total' => $total
                ];
                return $this->sendResponse($summary, 'Summary collected.');

        } catch (Exception $error) {
            return $this->sendError('Summary error', ['error' => $error->getMessage()]);
        }
    }

    public function getPhoto($ticketId)
{
    try
    {
        if (!Auth::check()) {
            return $this->sendError('Error get ticket photo', ['error' => 'Not Logged into system']);
        }
        
        // $auth = Auth::user();
        $tickets = Ticket::where('ticket_id', $ticketId)->first();

        $basePath = public_path();
        $filePath = '/storage/'.$tickets->photo;
        $path = $basePath.$filePath;

        if (!file_exists($path)) {
            return $this->sendError('Error get ticket photo', ['error' => 'File not found']);
        }

        ini_set('default_charset', 'UTF-8');

        $file = file_get_contents($path);
        if ($file === false) {
            return $this->sendError('Error get ticket photo', ['error' => 'Failed to read file']);
        }

        $response = new Response($file, 200);
        
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $contentType = 'image/' . $extension;
        $response->header('Content-Type', $contentType);

        return $response;

    } catch (Exception $error) {
        return $this->sendError('Error get ticket photo', ['error' => $error->getMessage()]);
    }
}

    public function getTicket()
    {
        try
        {
            $auth = Auth::user();
            $tickets = Ticket::with('feature', 'subFeature', 'ticketStatus')
                 ->where('employee_id', $auth->employee_id)
                 ->orderBy('created_at', 'desc')
                 ->get();

                //  dd($tickets->ticket_id);

            foreach ($tickets as $ticket) {

                $ticketId = $ticket->ticket_id;
                $employeeId = $ticket->employee_id;
                $employee = Employee::where('employee_id',$employeeId)->first();
                $spv = Employee::with('organization', 'regional')->find($employeeId);
                $ticket->Employee = Employee::with('organization', 'regional')->find($employeeId);
                $ticket->supervisor = Employee::with('organization', 'regional')->where('employee_id',$employee->supervisor_id)->first();
                $ticket->currentapproval = Employee::select('employee_name')->where('employee_id',$ticket->supervisor_id)->first();
                $ticketHistory = TicketStatusHistory::where('ticket_id', $ticketId)->get();
                $ticket->history = $ticketHistory;
                
                foreach ($ticketHistory as $spv) {
                    $spvId = $spv->supervisor_id;
                    $spvHistory = Employee::where('employee_id', $spvId)->first();
                    $spv['supervisor'] = $spvHistory;
                }      
            }            


            return $this->sendResponse($tickets, 'Tickets collected.'); 

        } catch (Exception $error) {
            return $this->sendError('Error get tickets', ['error' => $error->getMessage()]);
        }
    }

    public function getApproval()
    {
        try
        {
            $auth = Auth::user();

                $tickets = Ticket::with('feature', 'subFeature', 'ticketStatus')
                    ->where('supervisor_id', $auth->employee_id)
                    ->whereBetween('ticket_status_id', [1, 4])
                    ->orderBy('created_at', 'desc')
                    ->get();

                //  dd($tickets->ticket_id);

            foreach ($tickets as $ticket) {

                $ticketId = $ticket->ticket_id;
                $employeeId = $ticket->employee_id;
                $employee = Employee::where('employee_id',$employeeId)->first();
                $spv = Employee::with('organization', 'regional')->find($employeeId);
                $ticket->Employee = Employee::with('organization', 'regional')->find($employeeId);
                $ticket->supervisor = Employee::with('organization', 'regional')->where('employee_id',$employee->supervisor_id)->first();
                $ticket->currentapproval = Employee::select('employee_name')->where('employee_id',$ticket->supervisor_id)->first();
                $ticketHistory = TicketStatusHistory::where('ticket_id', $ticketId)->get();
                $ticket->history = $ticketHistory;
                
                foreach ($ticketHistory as $spv) {
                    $spvId = $spv->supervisor_id;
                    $spvHistory = Employee::where('employee_id',$spvId)->first();
                    $spv->supervisor = $spvHistory;
                }      
            }            


            return $this->sendResponse($tickets, 'Tickets collected.'); 

        } catch (Exception $error) {
            return $this->sendError('Error get tickets', ['error' => $error->getMessage()]);
        }
    }

    public function getHistory()
    {
        try
        {
            $auth = Auth::user();
            $history = TicketStatusHistory::select('ticket_id')->where('supervisor_id', $auth->employee_id)->get();
            $tickets = Ticket::with('feature', 'subFeature', 'ticketStatus')
                ->whereIn('ticket_id', $history->pluck('ticket_id'))
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($tickets as $ticket) {

                $ticketId = $ticket->ticket_id;
                $employeeId = $ticket->employee_id;
                $employee = Employee::where('employee_id',$employeeId)->first();
                $spv = Employee::with('organization', 'regional')->find($employeeId);
                $ticket->Employee = Employee::with('organization', 'regional')->find($employeeId);
                $ticket->supervisor = Employee::with('organization', 'regional')->where('employee_id',$employee->supervisor_id)->first();
                $ticket->currentapproval = Employee::select('employee_name')->where('employee_id',$ticket->supervisor_id)->first();
                $ticketHistory = TicketStatusHistory::where('ticket_id', $ticketId)->get();
                $ticket->history = $ticketHistory;
                
                foreach ($ticketHistory as $spv) {
                    $spvId = $spv->supervisor_id;
                    $spvHistory = Employee::where('employee_id', $spvId)->first();
                    $spv['supervisor'] = $spvHistory;
                }      
            }            

            return $this->sendResponse($tickets, 'Tickets collected.'); 

        } catch (Exception $error) {
            return $this->sendError('Error get tickets', ['error' => $error->getMessage()]);
        }
    }

    public function features()
    {
        try
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
        
        } catch (Exception $error) {
            return $this->sendError('Error creating ticket', ['error' => $error->getMessage()]);
        }
                        
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
            // 'ticket_status_id' => 'required',
            // 'photo' => 'required|mimes:jpg,png',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', ['error' => $validator->errors()]);
        } else {
            $employeeId = Auth::user();

            $image = $request->file('photo');

            if ($image) {
                $filename =  time().'.'.$request->photo->extension();
            
                $image->move(public_path('storage/'), $filename);
            
                $thumbnail = Image::make(public_path('storage/' . $filename));
                if($thumbnail->width() >1280 or $thumbnail->height()>1280){
                  $thumbnail->resize(1280, null, function ($constraint) {
                    $constraint->aspectRatio();
                  });
                }
                $thumbnail->save(public_path('storage/' . $filename));
            } else {
                $filename = '';
            }            

            $ticket = [
                'employee_id' => $employeeId->employee_id,
                'supervisor_id' => $employeeId->supervisor_id,
                'feature_id' => $request->feature_id,
                'sub_feature_id' => $request->sub_feature_id, // Add this line
                'ticket_title' => $request->ticket_title,
                'ticket_description' => $request->ticket_description,
                'ticket_status_id' => 1,
                'photo' => $filename
            ];
            // dd($ticket);

            $storeTicket = Ticket::create($ticket);
            $storeTicket['supervisor_id'] = $employeeId->supervisor_id;

            $input = new Request([
                'ticket_status_id' => 1,
                'id' => $employeeId->supervisor_id,
            ]);
            
            $this->updateStatus($input, $storeTicket->ticket_id);
            

            if ($storeTicket instanceof Ticket) {

                return $this->sendResponse($storeTicket, 'Ticket Created!');
            
            } else {
                return $this->sendError('Error creating ticket', ['error' => $storeTicket]);
            }
        }
    } catch (Exception $error) {
        return $this->sendError('Error creating ticket', ['error' => $error->getMessage()]);
    }
}

public function updateStatus(Request $request, $ticketId)
{
    try {
        $validator = Validator::make($request->all(), [
            'ticket_status_id' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', ['error' => $validator->errors()]);
        } else {

            $auth = Auth::user();
            $ticket = Ticket::with('ticketStatus')->find($ticketId);

            if (!$ticket) {
                return $this->sendError('Ticket not found');
            }

                // Add this code
                $statusHistory = new TicketStatusHistory();
                $statusHistory->ticket_id = $ticket->ticket_id;
                $statusHistory->status_before = $ticket->ticket_status_id;
                $statusHistory->status_after = $request->ticket_status_id;

                $ticket->ticket_status_id = $request->ticket_status_id;
                
                $ticket->supervisor_id = $request->id;
                

                $ticket->save();
                $ticket = Ticket::with('ticketStatus')->find($ticketId);

                if($request->ticket_status_id == $ticket->ticketStatus->ticket_status_id){
                    $statusHistory->description = $ticket->ticketStatus->ticket_status_name;
                } 

                $statusHistory->supervisor_id = $auth->employee_id;

                if ($statusHistory->status_before == $statusHistory->status_after || $statusHistory->status_after < $statusHistory->status_before) {
                // Check if status_before and status_after are both 1
                    if ($statusHistory->status_before == 1 && $statusHistory->status_after == 1) {
                    // Allow the code to pass
                        $statusHistory->save();

                    } else {
                        // Return the error message
                        return $this->sendError('Error updating ticket history', ['error' => 'status_before and status_after are the same or smaller']);
                    }
                } else {

                    $statusHistory->save();

                }
                // End of added code

                $statusHistory['supervisor'] = Employee::where('employee_id',$ticket->supervisor_id)->first();

                $statusNow = TicketStatus::where('ticket_status_id', $statusHistory->status_before)->first();
                $statusNext = TicketStatus::where('ticket_status_id', $statusHistory->status_after)->first();
                $spvId = Employee::where('employee_id',$ticket->supervisor_id)->first();
                $eId = Employee::where('employee_id',$ticket->employee_id)->first();

                //MAIL-START
                try {

                    if($ticket->ticket_status_id == 1){
                        $message = 'Ticket Created!';
                        $params = [
                            'recipients' => [
                                [
                                'email' => $eId->employee_email,
                                'subject' => 'Ticket ID:'.$statusHistory->ticket_id.' Created',
                                'body' => 'Ticket with ID:'.$statusHistory->ticket_id.' Success created. Current status: '.$statusNow->ticket_status_name.', now is '.$statusNow->ticket_status_next,
                              ],
                              [
                                'email' => $spvId->employee_email,
                                'subject' => 'Request approval on ticket ID:'.$statusHistory->ticket_id,
                                'body' => 'From: '.$eId->employee_id.' - '.$eId->employee_name.'| Ticket with ID:'.$statusHistory->ticket_id.' need to be Approved.'
                              ]
                            ],
                          ];

                    }else if ($ticket->ticket_status_id == 5) {
                            $message = 'Ticket Completed!';
                            $params = [
                                'recipients' => [
                                  [
                                    'email' => $eId->employee_email,
                                    'subject' => 'Ticket ID:'.$statusHistory->ticket_id.' Updated -'.$statusNext->ticket_status_name,
                                    'body' => 'Ticket with ID:'.$statusHistory->ticket_id.', now Completed',
                                  ]
                                ],
                              ];

                        } elseif ($ticket->ticket_status_id == 6) {
                            $message = 'Ticket Rejected!';
                            $params = [
                                'recipients' => 
                                [
                                  [
                                    'email' => $eId->employee_email,
                                    'subject' => 'Ticket ID:'.$statusHistory->ticket_id.', '.$statusNow->ticket_status_name,
                                    'body' => 'Ticket with ID:'.$statusHistory->ticket_id.', is Rejected',
                                  ]
                                ],
                              ];

                        } else {
                            $message = 'Ticket status Updated!';
                            $params = [
                                'recipients' => [
                                  [
                                    'email' => $eId->employee_email,
                                    'subject' => 'Ticket ID:'.$statusHistory->ticket_id.' Updated -'.$statusNext->ticket_status_name,
                                    'body' => 'Ticket with ID:'.$statusHistory->ticket_id.' updated to, '.$statusNow->ticket_status_name.', now '.$statusNow->ticket_status_next,
                                  ],
                                  [
                                    'email' => $spvId->employee_email,
                                    'subject' => 'Request approval on ticket ID:'.$statusHistory->ticket_id,
                                    'body' => 'From: '.$eId->employee_id.' - '.$eId->employee_name.'| As a '.$statusNext->ticket_status_name.' of Ticket with ID:'.$statusHistory->ticket_id.' need to be Approved.'
                                  ]
                                ],
                              ];
                        }
                        
                        $this->sendNotifEmail($params);
                        return $this->sendResponse($statusHistory, $message);

                } catch (Exception $e) {
                    return $this->sendError('Error updating ticket', ['error' => $e->getMessage()]);
                }
                
                //MAIL-END

            }
        } catch (Exception $error) {
            return $this->sendError('Error updating ticket', ['error' => $error->getMessage()]);
    }
}

    function sendNotifEmail(array $params) {
        foreach ($params['recipients'] as $recipient) {
          $data = $recipient;
          Mail::to($recipient['email'])->send(new TicketMail($data));
        }
    }

}