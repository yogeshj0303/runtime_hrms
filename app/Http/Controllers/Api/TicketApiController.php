<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketMessage;
use App\Models\TicketHistory;
use Illuminate\Support\Facades\Validator;

class TicketApiController extends Controller
{
    // GET /api/employee/tickets/departments
    public function departments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'employee_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee profile not found for this user'], 404);

        $query = Department::where('business_id', $request->business_id);

        // If the employee is not allowed to raise tickets in other departments, filter it to only their department
        if (!$employee->allow_cross_department_tickets && $employee->department) {
            $query->where('name', $employee->department);
        }

        $departments = $query->get();
        return response()->json(['status' => true, 'message' => 'Departments fetched successfully', 'data' => $departments]);
    }

    // GET /api/employee/ticket-categories
    public function categories(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'employee_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee profile not found for this user'], 404);

        $query = Department::where('business_id', $request->business_id);

        if (!$employee->allow_cross_department_tickets && $employee->department) {
            $query->where('name', $employee->department);
        }

        $departments = $query->get();
        return response()->json(['status' => true, 'message' => 'Categories fetched successfully', 'data' => $departments]);
    }

    // POST /api/employee/tickets/create
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'user_id' => 'required_without:employee_id|exists:users,id',
            'employee_id' => 'required_without:user_id|exists:users,id',
            'category_id' => 'required_without:department_id|exists:departments,id',
            'department_id' => 'required_without:category_id|exists:departments,id',
            'subject' => 'required|string',
            'description' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'attachments' => 'nullable|file', // Can be updated to handle array if multiple files are sent
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $userId = $request->user_id ?? $request->employee_id;
        $categoryId = $request->category_id ?? $request->department_id ?? $request->department;

        $employee = \App\Models\Employee::where('user_id', $userId)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee profile not found for this user'], 404);

        $ticket = Ticket::create([
            'business_id' => $request->business_id,
            'creator_id' => $employee->id,
            'department_id' => $categoryId,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'Open',
        ]);

        if ($request->hasFile('attachments') || $request->hasFile('attachment')) {
            $file = $request->hasFile('attachments') ? $request->file('attachments') : $request->file('attachment');
            $path = $file->store('ticket_attachments', 'public');
            TicketMessage::create([
                'ticket_id' => $ticket->id,
                'sender_id' => $employee->id,
                'message' => 'Attached a file with the ticket request.',
                'attachment_url' => asset('storage/' . $path),
                'attachment_type' => $file->getMimeType(),
            ]);
        }

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'actor_id' => $employee->id,
            'action' => 'Created',
        ]);

        return response()->json(['status' => true, 'message' => 'Ticket created successfully', 'data' => $ticket]);
    }

    // GET /api/employee/tickets/my-raised
    public function myRaised(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'employee_id' => 'required|exists:users,id',
            'status' => 'nullable|in:Open,Assigned,In Progress,Resolved,Closed'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee profile not found for this user'], 404);

        $query = Ticket::with(['department', 'assignee'])
            ->where('business_id', $request->business_id)
            ->where('creator_id', $employee->id);
            
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $tickets = $query->latest()->get();
        $mappedTickets = $tickets->map(function($t) {
            return [
                'ticket_id' => $t->id,
                'status' => $t->status,
                'priority' => $t->priority,
                'created_at' => $t->created_at->format('Y-m-d H:i:s'),
                'subject' => $t->subject
            ];
        });
        return response()->json(['status' => true, 'message' => 'Raised tickets fetched', 'data' => $mappedTickets]);
    }

    // GET /api/employee/tickets/my-assigned (Active Tickets)
    public function myAssigned(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'employee_id' => 'required|exists:users,id',
            'status' => 'nullable|in:Open,Assigned,In Progress,Resolved'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee profile not found for this user'], 404);

        $query = Ticket::with(['department', 'creator'])
            ->where('business_id', $request->business_id)
            ->where('assignee_id', $employee->id);
            
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        } else {
            // By default, exclude closed tickets in this API
            $query->where('status', '!=', 'Closed');
        }

        $tickets = $query->latest()->get();
        $mappedTickets = $tickets->map(function($t) {
            return [
                'ticket_id' => $t->id,
                'status' => $t->status,
                'priority' => $t->priority,
                'created_at' => $t->created_at->format('Y-m-d H:i:s'),
                'subject' => $t->subject
            ];
        });
        return response()->json(['status' => true, 'message' => 'Active assigned tickets fetched', 'data' => $mappedTickets]);
    }

    // GET /api/employee/tickets/my-closed (Completed Tickets)
    public function myClosed(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'employee_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee profile not found for this user'], 404);

        $tickets = Ticket::with(['department', 'creator'])
            ->where('business_id', $request->business_id)
            ->where('assignee_id', $employee->id)
            ->where('status', 'Closed')
            ->latest()
            ->get();
            
        $mappedTickets = $tickets->map(function($t) {
            return [
                'ticket_id' => $t->id,
                'status' => $t->status,
                'priority' => $t->priority,
                'created_at' => $t->created_at->format('Y-m-d H:i:s'),
                'subject' => $t->subject
            ];
        });
        return response()->json(['status' => true, 'message' => 'Closed assigned tickets fetched', 'data' => $mappedTickets]);
    }

    // GET /api/employee/tickets/{id}
    public function show($id)
    {
        $ticket = Ticket::with([
            'department', 
            'creator', 
            'assignee', 
            'messages' => function($q) {
                $q->orderBy('created_at', 'asc')->with('sender');
            }, 
            'histories' => function($q) {
                $q->orderBy('created_at', 'asc')->with('actor');
            }
        ])->find($id);
        
        if (!$ticket) return response()->json(['status' => false, 'message' => 'Ticket not found'], 404);

        $replies = $ticket->messages->map(function($m) {
            return [
                'reply_text' => $m->message,
                'replied_by' => $m->sender ? ($m->sender->first_name . ' ' . $m->sender->last_name) : 'Unknown',
                'timestamp' => $m->created_at->format('Y-m-d H:i:s')
            ];
        });

        // Append mapped replies to the ticket object
        $ticketData = $ticket->toArray();
        $ticketData['replies'] = $replies;

        return response()->json(['status' => true, 'message' => 'Ticket details fetched', 'data' => $ticketData]);
    }

    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'message' => 'required_without:attachment',
            'attachment' => 'nullable|file',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }
        
        $employee = \App\Models\Employee::where('user_id', $request->user_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee profile not found for this user'], 404);

        $ticket = Ticket::find($id);
        if (!$ticket) return response()->json(['status' => false, 'message' => 'Ticket not found'], 404);

        $attachmentUrl = null;
        $attachmentType = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('ticket_attachments', 'public');
            $attachmentUrl = asset('storage/' . $path);
            $attachmentType = $request->file('attachment')->getMimeType();
        }

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => $employee->id,
            'message' => $request->message,
            'attachment_url' => $attachmentUrl,
            'attachment_type' => $attachmentType,
        ]);

        // Fetch updated messages to return the replies array
        $updatedTicket = Ticket::with(['messages' => function($q) {
            $q->orderBy('created_at', 'asc')->with('sender');
        }])->find($id);

        $replies = $updatedTicket->messages->map(function($m) {
            return [
                'reply_text' => $m->message,
                'replied_by' => $m->sender ? ($m->sender->first_name . ' ' . $m->sender->last_name) : 'Unknown',
                'timestamp' => $m->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json(['status' => true, 'message' => 'Reply sent', 'data' => $replies]);
    }

    // POST /api/employee/tickets/{id}/status
    public function status(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'actor_id' => 'required|exists:users,id',
            'status' => 'required|in:Open,Assigned,In Progress,Resolved,Closed',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $ticket = Ticket::find($id);
        if (!$ticket) return response()->json(['status' => false, 'message' => 'Ticket not found'], 404);

        $employee = \App\Models\Employee::where('user_id', $request->actor_id)->where('business_id', $ticket->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee profile not found for this user'], 404);

        $oldStatus = $ticket->status;
        $ticket->status = $request->status;
        if ($request->status == 'Closed') {
            $ticket->closed_at = now();
        }
        $ticket->save();

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'actor_id' => $employee->id,
            'action' => 'Status Changed',
            'old_value' => $oldStatus,
            'new_value' => $request->status,
        ]);

        return response()->json(['status' => true, 'message' => 'Ticket status updated']);
    }

    // POST /api/employee/helpdesk-permission/request
    public function requestPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'employee_id' => 'required|exists:users,id',
            'reason' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee not found'], 404);

        if ($employee->allow_cross_department_tickets) {
            return response()->json(['status' => false, 'message' => 'You already have cross-department permissions.'], 400);
        }

        $existingRequest = \App\Models\HelpdeskPermissionRequest::where('employee_id', $employee->id)
                                ->where('status', 'Pending')
                                ->first();
        if ($existingRequest) {
            return response()->json(['status' => false, 'message' => 'You already have a pending request.'], 400);
        }

        $permRequest = \App\Models\HelpdeskPermissionRequest::create([
            'business_id' => $request->business_id,
            'employee_id' => $employee->id,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        return response()->json(['status' => true, 'message' => 'Permission request submitted successfully', 'data' => $permRequest]);
    }

    // GET /api/employee/helpdesk-permission/status
    public function checkPermissionStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'employee_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee not found'], 404);

        $permRequest = \App\Models\HelpdeskPermissionRequest::where('employee_id', $employee->id)
                                ->orderBy('created_at', 'desc')
                                ->first();

        return response()->json([
            'status' => true, 
            'is_allowed' => (bool)$employee->allow_cross_department_tickets,
            'latest_request' => $permRequest
        ]);
    }
}
