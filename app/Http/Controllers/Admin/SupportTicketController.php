<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    
    public function index(Request $request) {
        
        
        if(Auth::user()->type =='admin'){
            $tickets  = SupportTicket::leftJoin('client_details','client_details.user_id','tickets.company_id')
                        ->select('tickets.*')->orderBy('tickets.created_at','desc')
                        ->groupBy('tickets.ticket_no');
        }
        else{
            $tickets  = SupportTicket::leftJoin('client_details','client_details.user_id','tickets.company_id')
                        ->where('staff_id',auth()->user()->id)
                        ->select('tickets.*')->orderBy('tickets.created_at','desc')
                        ->groupBy('tickets.ticket_no');
        }

       if($request->has('search') && $request->filled('search'))
        {
            $tickets->where(function($query) use ($request) {
                $query->where('tickets.ticket_no','LIKE','%'.str_replace('TNO','',$request->search).'%')
                    ->orWhere('tickets.subject','LIKE','%'.$request->search.'%');
            });
        }
        if($request->has('client') && $request->filled('client'))
        {
            $tickets->where('tickets.company_id','=',$request->client);
        }
        if($request->has('type') && $request->filled('type'))
        {
            $tickets->where('tickets.type','=',$request->type);
        }
        if($request->has('priority') && $request->filled('priority'))
        {
            $tickets->where('tickets.priority','=',$request->priority);
        }

         if($request->has('acc_mnger') && $request->filled('acc_mnger'))
        {
            $tickets->where('client_details.acc_mngr','=',$request->acc_mnger);
        }
        
        if($request->has('rep') && $request->filled('rep'))
        {
            $tickets->where('tickets.staff_asign','=',$request->rep);
        }
        
        $tickets = $tickets->paginate(20);
    

        
        
        // $tickets = SupportTicket::with(['users'=>function($q) {$q->with('client');
        //   }])->orderBy('created_at','desc')->groupBy('ticket_no')->paginate(20);
           
           
        $client_acc = User::where('type','admin-staff')->whereHas('roles', function($q){
        $q->where('name', 'Account Manager');})->get();
        
        $client_rep = User::where('type','admin-staff') ->whereHas('roles', function($q){
        $q->where('name','!=','Account Manager');})->get();
        
       
       
        return view('admin.ticket.tickets',compact('tickets','client_rep','client_acc'));
    }

    public function create() {
   		return view('admin.ticket.ticket-form');
    }

    public function view($id,$no) {
    	$tickets = SupportTicket::with('users')->where(['company_id'=>$id,'ticket_no'=>$no])->orderBy('created_at','desc')->get();
    
        
    	foreach($tickets as $ticket){
        	$ticket->read_at=date('Y-m-d H:i:s');
            $ticket->save();
    	}
    	return view('admin.ticket.ticket-view',compact('tickets'));
    }
    public function reply($id){
         $ticket = SupportTicket::where('id',$id)->first();
       return view('admin.ticket.ticket-reply',compact('ticket'));
        
    }
    public function sendreply(Request $request){
        $ticket_no         = $request->ticketno;
        $ticket_client     = $request->clientid;
        
        if($request->submit == 'Reply')
        {
            $parent_ticket     = SupportTicket::where(['ticket_no'=>$ticket_no,'company_id'=>$ticket_client])->first();
            $ticket            = new SupportTicket();
            $ticket->ticket_no =  $parent_ticket->ticket_no;
    	    $ticket->subject   ='Re: '. $parent_ticket->subject;
    	    $ticket->body      = $request->body;
    	    $ticket->user_id   = auth()->id();
    	    $ticket->company_id = $parent_ticket->company_id;
    	    $ticket->to        = 'client';
    	 
    	    $ticket->save();
    	   
        }
	   else{
	        $tickets = SupportTicket::where('ticket_no',$ticket_no )->get();
            	foreach($tickets as $ticket){
                	$ticket->status = 0;
                    $ticket->save();
             	}
	       
	        }
	     return redirect()->back();
    }   
    public function closedTicket($no){
        $tickets = SupportTicket::where('ticket_no',$no)->get();
        	foreach($tickets as $ticket){
            	$ticket->status = 0;
                $ticket->save();
         	}
        return redirect()->back();
       
    }
   
   
    public function assigned_staff(Request $request){
       
       $tckt_no     = $request->tckt_no;
       $staff_id    = $request->staff_id;
       SupportTicket::where('ticket_no',$tckt_no)->update(['staff_id'=>$staff_id]);
    
       return 1;
       
       
    }

    
    
}
