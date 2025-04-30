<?php

namespace App\Http\Controllers\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\SupportTicket;
use App\ClientDetails;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index() {
        $tickets = SupportTicket::where(['company_id'=>Auth::user()->creatorId(),'user_id'=>Auth::user()->id])->orderBy('created_at','desc')->groupBy('ticket_no')->get();
        return view('company.ticket.tickets',compact('tickets'));
    }


    public function create() {
   		return view('company.ticket.ticket-form');
    }


    public function store(Request $request) {
    	$request->validate(['subject' => 'required', 'body' => 'required','type'=>'required','priority'=>'required']);
    	
    	
    	$ticket                 = new SupportTicket();
    	$ticket->subject 	    = $request->subject;
    	$ticket->body 	    	= $request->body;
    	$ticket->user_id        =  Auth::user()->id;
    	$ticket->company_id     =  Auth::user()->creatorId();
    	$ticket->to             =  'admin';
    	$ticket->type           = $request->type;
    	$ticket->priority       = $request->priority;
    	$ticket->staff_id       =  null;
    	$date                   = date('ymd');
    	$ticket->ticket_no      =  '';
       
        try{
    	$ticket->save(); 
        	$latestticket           =  SupportTicket::orderBy('created_at','desc')->first();
            $ticket->ticket_no      =  $date."".$latestticket->id+1 ;
            $ticket->save(); 
        	session()->flash('message','<div class="alert alert-success">Successfully created the Tickets</div>');
        	return redirect()->back();
        }
        catch(\Exception $e) {
	        die($e->getMessage());
	         session()->flash('message','<div class="alert alert-danger">UnSuccessfully replied the Tickets</div>');
    	    return redirect()->back();
	    }	
        
    }
    
    public function view($id,$no) {
    	$tickets =  SupportTicket::with('users')->where(['company_id'=>$id,'ticket_no'=>$no])->orderBy('created_at','desc')->get();
    		foreach($tickets as $ticket){
        	$ticket->read_at=date('Y-m-d H:i:s');
            $ticket->save();
    	}
    	return view('company.ticket.ticket-view',compact('tickets'));
    }
    
    public function reply($id){
         $ticket = SupportTicket::where('id',$id)->first();
       return view('company.ticket.ticket-reply',compact('ticket'));
        
    }
    
    public function sendreply(Request $request){
        $ticket_no         = $request->ticketno;
        $ticket_client     = $request->clientid;
        $parent_ticket     = SupportTicket::where(['ticket_no'=>$ticket_no,'company_id'=>$ticket_client])->first();

        $ticket            = new SupportTicket();
        $ticket->ticket_no = $parent_ticket->ticket_no;
	    $ticket->subject   = 'Re: '. $parent_ticket->subject;
	    $ticket->body      = $request->body;
	    $ticket->user_id   = Auth::user()->id;
	    $ticket->company_id = $parent_ticket->company_id;
	    $ticket->to        = 'admin';
	  
	   // die(print_r($ticket));
	    try {
	        $ticket->save();
	        session()->flash('message','<div class="alert alert-success">Successfully replied the Tickets</div>');
    	    return redirect()->back();
	    }
	    catch(\Exception $e) {
	        die($e->getMessage());
	         session()->flash('message','<div class="alert alert-danger">UnSuccessfully replied the Tickets</div>');
    	    return redirect()->back();
	    }	
        
    }
}
