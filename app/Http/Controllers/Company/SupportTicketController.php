<?php

namespace App\Http\Controllers\Company;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MediaFolder;
use Illuminate\Support\Str;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use Illuminate\Support\Facades\Auth;
use App\Traits\Media\HandlesMediaFolders;

class SupportTicketController extends Controller
{
	use HandlesMediaFolders;

	public function index()
	{
		$tickets = SupportTicket::where(['company_id' => Auth::user()->creatorId(), 'user_id' => Auth::user()->id])->orderBy('created_at', 'desc')->groupBy('ticket_no')->get();
		return view('company.ticket.tickets', compact('tickets'));
	}


	public function create()
	{
		return view('company.ticket.ticket-form');
	}

	public function store(Request $request)
	{
		$request->validate([
			'subject' => 'required',
			'body' => 'required',
			'type' => 'required',
			'priority' => 'required'
		]);

		$companyId = auth()->user()->creatorId();

		// Find or create 'support-ticket' folder
		$folder = MediaFolder::where('name', 'support-ticket')->where('company_id', $companyId)->first();

		if (!$folder) {
			$folder = $this->CreateFolder($companyId, 'support-ticket'); // Removed ->first()
		}

		$ticket = new SupportTicket();
		$ticket->subject      = $request->subject;
		$ticket->body         = $request->body;
		$ticket->user_id      = Auth::user()->id;
		$ticket->company_id   = $companyId;
		$ticket->to           = 'admin';
		$ticket->type         = $request->type;
		$ticket->priority     = $request->priority;
		$ticket->staff_id     = null;
		$ticket->ticket_no    = ''; // Temporarily blank

		try {
			$ticket->save();

			// Generate ticket number after save
			$latestTicket = SupportTicket::orderBy('created_at', 'desc')->first();
			$date = date('ymd');
			$ticket->ticket_no = $date . ($latestTicket->id + 1);
			$ticket->save();

			// Handle uploaded files
			$files = $request->file('documents', []);

			foreach ($files ?? [] as $file) {
				$fileId = $this->uploadAndSaveFile($file, $companyId, $folder->name ?? null);

				$attachment = new SupportTicketAttachment();
				$attachment->file_id   = $fileId;
				$attachment->ticket_id = $ticket->id;
				$attachment->save();
			}

			return response()->json(['success' => true]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
		}
	}


	public function view($id, $no)
	{
		$tickets =  SupportTicket::where(['company_id' => $id, 'ticket_no' => $no])->orderBy('created_at', 'asc')->get();

		foreach ($tickets->where('to', 'company') as $ticket) {
			$ticket->read_at = date('Y-m-d H:i:s');
			$ticket->save();
		}
		return view('company.ticket.ticket-view', compact('tickets'));
	}

	public function sendreply(Request $request, $ticket_no)
	{
		$companyId     		= Auth::user()->creatorId();
		$parent_ticket     	= SupportTicket::where(['ticket_no' => $ticket_no, 'company_id' => $companyId])->first();

		$ticket            	= new SupportTicket();
		$ticket->ticket_no 	= $parent_ticket->ticket_no;
		$ticket->subject   	= 'Re: ' . $parent_ticket->subject;
		$ticket->body      	= $request->body;
		$ticket->user_id   	= Auth::user()->id;
		$ticket->company_id = $companyId;
		$ticket->type       = $parent_ticket->type;
		$ticket->priority   = $parent_ticket->priority;
		$ticket->to        	= 'admin';
		try {
			$ticket->save();

			$folder = MediaFolder::where('name', 'support-ticket')->where('company_id', $companyId)->first();

			if (!$folder) {
				$folder = $this->CreateFolder($companyId, 'support-ticket'); // Removed ->first()
			}
			// Handle uploaded files
			$files = $request->file('documents', []);

			foreach ($files ?? [] as $file) {
				$fileId = $this->uploadAndSaveFile($file, $companyId, $folder->name ?? null);

				$attachment = new SupportTicketAttachment();
				$attachment->file_id   = $fileId;
				$attachment->ticket_id = $ticket->id;
				$attachment->save();
			}


			return response()->json(['success' => true]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
		}
	}
}
