<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MediaFolder;
use Illuminate\Support\Str;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\Media\HandlesMediaFolders;
use App\Traits\ActivityLogger;

class SupportTicketController extends Controller
{
    use ActivityLogger;
    use HandlesMediaFolders;


    public function index(Request $request)
    {

        if (Auth::user()->type == 'admin') {
            $tickets = SupportTicket::orderBy('created_at', 'desc')->orderBy('status', 'asc')->groupBy('ticket_no');
        } else {
            $tickets = SupportTicket::where('staff_id', auth()->user()->id)->orderBy('created_at', 'desc')->groupBy('ticket_no');
        }

        if ($request->has('search') && $request->filled('search')) {
            $tickets->where(function ($query) use ($request) {
                $query->where('tickets.ticket_no', 'LIKE', '%' . str_replace('TNO', '', $request->search) . '%')
                    ->orWhere('tickets.subject', 'LIKE', '%' . $request->search . '%');
            });
        }

        if ($request->has('type') && $request->filled('type')) {
            $tickets->where('tickets.type', '=', $request->type);
        }

        if ($request->has('priority') && $request->filled('priority')) {
            $tickets->where('tickets.priority', '=', $request->priority);
        }

        if ($request->has('rep') && $request->filled('rep')) {
            $tickets->where('tickets.staff_asign', '=', $request->rep);
        }

        $tickets = $tickets->get();
        $staffs = User::where('type', 'admin-staff')->get();

        return view('admin.ticket.tickets', compact('tickets', 'staffs'));
    }

    public function create()
    {
        return view('admin.ticket.ticket-form');
    }

    public function view($id, $no)
    {
        $tickets = SupportTicket::with('users')->where(['company_id' => $id, 'ticket_no' => $no])->orderBy('created_at', 'asc')->get();

        foreach ($tickets->where('to', 'admin') as $ticket) {
            $ticket->read_at = date('Y-m-d H:i:s');
            $ticket->save();
        }
        return view('admin.ticket.ticket-view', compact('tickets'));
    }
    public function reply($id)
    {
        $ticket = SupportTicket::where('id', $id)->first();
        return view('admin.ticket.ticket-reply', compact('ticket'));
    }

    public function sendreply(Request $request, $ticket_no)
    {
        $ticket_no         = $request->ticket_no;
        $parent_ticket         = SupportTicket::where('ticket_no', $ticket_no)->first();
        $companyId             = $parent_ticket->company_id;

        $ticket            = new SupportTicket();
        $ticket->ticket_no =  $parent_ticket->ticket_no;
        $ticket->subject   = 'Re: ' . $parent_ticket->subject;
        $ticket->body      = $request->body;
        $ticket->user_id   = auth()->id();
        $ticket->company_id = $parent_ticket->company_id;
        $ticket->type       = $parent_ticket->type;
        $ticket->priority   = $parent_ticket->priority;
        $ticket->to        = 'client';
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

            $this->logActivity(
                'Support Ticket Replyed',
                'Ticket Number ' . $ticket->ticket_no,
                route('admin.tickets.index'),
                'Support Ticket Replyed Successfully',
                Auth::user()->creatorId(),
                Auth::user()->id
            );


            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function closedTicket($no)
    {
        $tickets = SupportTicket::where('ticket_no', $no)->get();
        foreach ($tickets as $ticket) {
            $ticket->status = 0;
            $ticket->save();
        }

        $this->logActivity(
            'Support Ticket Closed',
            'Ticket Number ' . $ticket->ticket_no,
            route('admin.tickets.index'),
            'Support Ticket Closed Successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );

        return redirect()->back();
    }

    public function assigned_staff(Request $request)
    {
        $tckt_no     = $request->tckt_no;
        $staff_id    = $request->staff_id;
        SupportTicket::where('ticket_no', $tckt_no)->update(['staff_id' => $staff_id]);
        $this->logActivity(
            'Support Ticket Assigned',
            'Staff id ' . $staff_id,
            route('admin.tickets.index'),
            'Support Ticket Assigned Successfully',
            Auth::user()->creatorId(),
            Auth::user()->id
        );
        return 1;
    }
}
