@extends('layouts.master')

@section('title')
    Onboarding Forms
@endsection

@section('css')
<style>
    .page-header-title {
        font-size: 16px;
        font-weight: 600;
        color: #343a40;
    }
    .page-header-subtitle {
        font-size: 12px;
        color: #6c757d;
    }
    .breadcrumb-text {
        font-size: 11px;
        color: #162d50;
    }
    .filter-label {
        font-size: 11px;
        font-weight: 500;
        color: #878a99;
        margin-bottom: 2px;
    }
    .table-custom th {
        font-size: 11px;
        font-weight: 600;
        color: #878a99;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e9ebec;
        padding-top: 12px;
        padding-bottom: 12px;
    }
    .table-custom td {
        font-size: 13px;
        vertical-align: middle;
        padding-top: 12px;
        padding-bottom: 12px;
    }
    .faq-container {
        background-color: #fcfcfc;
        border: 1px solid #f3f3f9;
        border-radius: 8px;
    }
    .faq-item {
        padding: 12px 20px;
        border-bottom: 1px solid #f3f3f9;
        font-size: 13px;
        font-weight: 500;
        color: #162d50;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }
    .faq-item:last-child {
        border-bottom: none;
    }
    .faq-item:hover {
        background-color: #f9f9f9;
    }
    .action-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    .btn-edit { background-color: #f06548; }
    .btn-delete { background-color: #f06548; opacity: 0.8; }
</style>
@endsection

@section('content')

<div class="mb-4">
    <!-- Breadcrumb -->
    <div class="d-flex align-items-center mb-2">
        <i class="ri-arrow-left-s-line text-muted"></i>
        <a href="{{ route('onboarding.index') }}" class="text-decoration-none breadcrumb-text">Onboarding</a>
        <span class="mx-1 text-muted fs-12">/</span>
        <span class="breadcrumb-text">Onboarding Forms</span>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h4 class="page-header-title mb-1">Onboarding Forms</h4>
            <p class="page-header-subtitle mb-0">Create onboarding forms for new hires. Approve or reject submitted forms.</p>
        </div>
        
        <div class="d-flex gap-2">
            <a href="{{ route('onboarding.forms.create') }}" class="btn btn-sm text-white px-3" style="background-color: #162d50;">
                <i class="ri-file-add-line align-middle me-1"></i> Add New Form
            </a>
            <button class="btn btn-sm text-white px-3" style="background-color: #162d50;">
                <i class="ri-group-add-line align-middle me-1"></i> Add Forms in Bulk
            </button>
            <button class="btn btn-sm text-white px-3" style="background-color: #162d50;">
                <i class="ri-settings-3-line align-middle me-1"></i> Form Settings
            </button>
            <button class="btn btn-sm text-white px-3 rounded-pill" style="background-color: #0ab39c; border-color: #0ab39c;">
                <i class="ri-question-line align-middle me-1"></i> Read Help
            </button>
        </div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="d-flex justify-content-between align-items-end mb-3">
    <form action="{{ route('onboarding.forms.index') }}" method="GET" class="d-flex gap-2 align-items-end">
        <div>
            <div class="filter-label">Form Status</div>
            <select name="status" class="form-select form-select-sm shadow-none border-light" style="width: 140px;">
                <option value="All" {{ $status == 'All' ? 'selected' : '' }}>All</option>
                <option value="Draft" {{ $status == 'Draft' ? 'selected' : '' }}>Draft</option>
                <option value="Sent" {{ $status == 'Sent' ? 'selected' : '' }}>Sent</option>
                <option value="Submitted" {{ $status == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                <option value="Approved" {{ $status == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ $status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div>
            <div class="filter-label">Search</div>
            <input type="text" class="form-control form-control-sm shadow-none border-light" placeholder="Candidate name, mobile or email" style="width: 250px;">
        </div>
        <button type="submit" class="btn btn-sm btn-light border shadow-none px-3 text-muted fw-medium">
            <i class="ri-checkbox-circle-line align-middle me-1"></i> Load
        </button>
    </form>
    
    <div>
        <button class="btn btn-sm btn-light border shadow-none px-3 text-muted fw-medium">
            <i class="ri-download-2-line align-middle me-1"></i> Download
        </button>
    </div>
</div>

<!-- Data Table -->
<div class="card border border-light shadow-none mb-5">
    <div class="card-body p-0">
        @if(session('success'))
            <div class="alert alert-success m-3 mb-0">{{ session('success') }}</div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-custom table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>CANDIDATE</th>
                        <th>CONTACT DETAILS</th>
                        <th>DATE OF JOINING</th>
                        <th>INFO</th>
                        <th>STATUS</th>
                        <th class="text-center pe-4">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms as $form)
                    <tr>
                        <td class="ps-4 text-muted">{{ $form->id }}/8</td>
                        <td>
                            <strong class="text-dark">{{ $form->employee->first_name ?? '' }} {{ $form->employee->last_name ?? '' }}</strong>
                            <div class="text-muted fs-11 mt-1">Created: {{ $form->created_at->format('d-M-Y') }}</div>
                        </td>
                        <td>
                            <div class="text-dark">{{ $form->employee->email ?? 'N/A' }}</div>
                            <div class="text-muted fs-12 mt-1">{{ $form->employee->phone ?? 'N/A' }}</div>
                        </td>
                        <td class="text-dark">
                            {{ $form->employee->joining_date ? \Carbon\Carbon::parse($form->employee->joining_date)->format('d-M-Y') : 'N/A' }}
                        </td>
                        <td>
                            <a href="#" class="text-decoration-none" style="color: #f06548; font-size: 12px;">
                                View Form <i class="ri-external-link-line align-middle ms-1"></i>
                            </a>
                        </td>
                        <td>
                            @if($form->status == 'Approved')
                                <span class="badge rounded-pill bg-success text-white px-3 py-1 fw-normal">Approved</span>
                            @elseif($form->status == 'Sent')
                                <span class="badge rounded-pill bg-info text-white px-3 py-1 fw-normal">Sent</span>
                            @elseif($form->status == 'Submitted')
                                <span class="badge rounded-pill bg-primary text-white px-3 py-1 fw-normal">Submitted</span>
                            @elseif($form->status == 'Rejected')
                                <span class="badge rounded-pill bg-danger text-white px-3 py-1 fw-normal">Rejected</span>
                            @else
                                <span class="badge rounded-pill" style="background-color: #63779e; color: white; padding: 4px 12px; font-weight: normal;">Draft</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <a href="{{ route('onboarding.forms.create', ['employee_id' => $form->employee_id]) }}" class="action-btn btn-edit text-decoration-none me-1">
                                <i class="ri-pencil-fill fs-14"></i>
                            </a>
                            <form action="{{ route('onboarding.forms.destroy', $form->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this form?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn btn-delete border-0">
                                    <i class="ri-delete-bin-fill fs-14"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="ri-file-search-line fs-24 d-block mb-2 text-light"></i>
                            No onboarding forms found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<div class="faq-container mt-4 mb-5 shadow-sm">
    <div class="d-flex align-items-center p-3 border-bottom border-light bg-white rounded-top" style="gap: 10px;">
        <div style="background-color: #fff3cd; color: #ffc107; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <i class="ri-lightbulb-flash-line fs-18"></i>
        </div>
        <h6 class="mb-0 fw-bold text-dark fs-14">Frequently Asked Questions</h6>
    </div>
    <div class="bg-white rounded-bottom">
        <div class="faq-item">
            Is it permissible to request employees to submit documents in accordance with company policies through the onboarding form?
            <i class="ri-add-line text-muted fs-16"></i>
        </div>
        <div class="faq-item">
            Where can I view and take action on onboarding forms submitted by employees?
            <i class="ri-add-line text-muted fs-16"></i>
        </div>
        <div class="faq-item">
            Can I resend the onboarding form to an employee for corrections or additional information after they have submitted it?
            <i class="ri-add-line text-muted fs-16"></i>
        </div>
        <div class="faq-item">
            Is it possible to recall or retract an onboarding form after it has been sent to an employee, especially if it has expired?
            <i class="ri-add-line text-muted fs-16"></i>
        </div>
        <div class="faq-item">
            Can PAN, Aadhaar, and mobile number be verified through the onboarding form shared with employees?
            <i class="ri-add-line text-muted fs-16"></i>
        </div>
        <div class="faq-item">
            Are Aadhaar, PAN, bank details, and mobile number verifications free of charge?
            <i class="ri-add-line text-muted fs-16"></i>
        </div>
        <div class="faq-item">
            Can we Preview the Offer Letter before sending it to employees?
            <i class="ri-add-line text-muted fs-16"></i>
        </div>
    </div>
</div>

@endsection
