<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ticket;
use App\Models\Department;
use App\Models\Employee;
use App\Models\TicketHistory;
use Illuminate\Support\Facades\Auth;

class TicketWebController extends Controller
{
    public function index()
    {
        $businessId = Auth::user()->active_business_id;
        $tickets = Ticket::with(['creator', 'assignee', 'department'])
            ->where('business_id', $businessId)
            ->latest()
            ->get();
            
        return view('tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $businessId = Auth::user()->active_business_id;
        $ticket = Ticket::with(['creator', 'assignee', 'department', 'messages.sender', 'histories.actor'])
            ->where('business_id', $businessId)
            ->findOrFail($id);
            
        $departmentName = $ticket->department ? $ticket->department->name : '';
            
        $departmentEmployees = Employee::where('business_id', $businessId)
            ->where('department', $departmentName)
            ->whereIn('status', ['Active', 'active'])
            ->get();

        $adminEmp = Employee::where('user_id', Auth::id())->first();
        $adminEmpId = $adminEmp ? $adminEmp->id : null;
            
        return view('tickets.show', compact('ticket', 'departmentEmployees', 'adminEmpId'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'assignee_id' => 'required|exists:employees,id'
        ]);

        $ticket = Ticket::where('business_id', Auth::user()->active_business_id)->findOrFail($id);
        $oldAssignee = $ticket->assignee_id;
        
        $ticket->assignee_id = $request->assignee_id;
        if ($ticket->status == 'Open') {
            $ticket->status = 'Assigned';
        }
        $ticket->save();

        $adminEmp = Employee::where('user_id', Auth::id())->first();

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'actor_id' => $adminEmp ? $adminEmp->id : 0, 
            'action' => $oldAssignee ? 'Re-assigned' : 'Assigned',
            'old_value' => $oldAssignee,
            'new_value' => $request->assignee_id,
        ]);

        return redirect()->back()->with('success', 'Ticket assigned successfully.');
    }

    public function forceClose(Request $request, $id)
    {
        $ticket = Ticket::where('business_id', Auth::user()->active_business_id)->findOrFail($id);
        
        $oldStatus = $ticket->status;
        $ticket->status = 'Closed';
        $ticket->closed_at = now();
        $ticket->save();

        $adminEmp = Employee::where('user_id', Auth::id())->first();

        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'actor_id' => $adminEmp ? $adminEmp->id : 0,
            'action' => 'Force Closed',
            'old_value' => $oldStatus,
            'new_value' => 'Closed',
        ]);

        return redirect()->back()->with('success', 'Ticket has been forcefully closed.');
    }

    public function accessRequests()
    {
        $businessId = Auth::user()->active_business_id;
        $requests = \App\Models\HelpdeskPermissionRequest::with('employee')
            ->where('business_id', $businessId)
            ->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Rejected')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.access_requests', compact('requests'));
    }

    public function approveAccessRequest($id)
    {
        $businessId = Auth::user()->active_business_id;
        $req = \App\Models\HelpdeskPermissionRequest::where('business_id', $businessId)->findOrFail($id);
        
        $req->status = 'Approved';
        $req->save();

        if ($req->employee) {
            $req->employee->allow_cross_department_tickets = 1;
            $req->employee->save();
        }

        return redirect()->back()->with('success', 'Access request approved! The employee can now raise cross-department tickets.');
    }

    public function rejectAccessRequest($id)
    {
        $businessId = Auth::user()->active_business_id;
        $req = \App\Models\HelpdeskPermissionRequest::where('business_id', $businessId)->findOrFail($id);
        
        $req->status = 'Rejected';
        $req->save();

        return redirect()->back()->with('success', 'Access request rejected.');
    }
}
