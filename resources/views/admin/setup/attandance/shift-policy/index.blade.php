@extends('layouts.master')

@section('title')
Shift Policies
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .policy-list-item {
        border: 1px solid #e9ebec;
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 12px;
        background: #fff;
    }
    .policy-list-item:hover {
        border-color: #d1d5db;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .policy-title {
        font-size: 15px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 4px;
    }
    .policy-desc {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .shift-info {
        font-size: 12px;
        color: #4b5563;
    }
    .shift-info .label {
        color: #9ca3af;
        margin-bottom: 2px;
        display: block;
        font-size: 11px;
    }
    .editor-card {
        background: #fff;
        border: 1px solid #e9ebec;
        border-radius: 6px;
        padding: 24px;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Attendance & Leaves</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Shift Policies</span>
        @if($editPolicy || request()->has('create'))
        <i class="ri-arrow-right-s-line"></i>
        <span>Shift Policy Editor</span>
        @endif
    </div>

    <div class="header-content">
        <div class="header-left">
            @if($editPolicy || request()->has('create'))
            <h4>Shift Policy Editor</h4>
            <p class="text-muted fs-13 mb-0">Edit shift policy by selecting default shifts and weekly rotational shifts.</p>
            @else
            <h4>Shift Policies</h4>
            <p class="text-muted fs-13 mb-0">Define shift policies with fixed or weekly rotational shifts.</p>
            @endif
        </div>
        <div class="header-buttons">
            @if(!$editPolicy && !request()->has('create'))
            <a href="{{ route('shift-policies.index', ['create' => 1]) }}" class="btn-add">
                <i class="ri-add-line"></i> Add New
            </a>
            @else
            <a href="{{ route('shift-policies.index') }}" class="btn btn-light btn-sm border">
                <i class="ri-arrow-left-line"></i> Back to Policies
            </a>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($editPolicy || request()->has('create'))
{{-- =========================================================
     EDITOR VIEW
     ========================================================= --}}
<form action="{{ $editPolicy ? route('shift-policies.update', $editPolicy->id) : route('shift-policies.store') }}" method="POST">
    @csrf
    @if($editPolicy)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="editor-card mb-4">
                <h6 class="mb-4">Basic Information</h6>
                
                <div class="mb-3">
                    <label class="form-label">Policy Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" placeholder="E.g. Office Staff" value="{{ old('name', $editPolicy ? $editPolicy->name : '') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" placeholder="Enter a description for your reference" value="{{ old('description', $editPolicy ? $editPolicy->description : '') }}">
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $editPolicy ? $editPolicy->is_default : false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_default">Set as Default Policy</label>
                </div>

                <div class="mb-3">
                    <label class="form-label">Default Shift <span class="text-danger">*</span></label>
                    <select class="form-select" name="default_shift_id" required>
                        <option value="">- Select -</option>
                        @foreach($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('default_shift_id', $editPolicy ? $editPolicy->default_shift_id : '') == $shift->id ? 'selected' : '' }}>
                                {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} to {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}) [{{ $shift->duration_hours }} hrs.]
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="editor-card mb-4">
                <h6 class="mb-4">Weekly Rotating Shifts <small class="text-muted fw-normal">(Optional)</small></h6>
                
                @php
                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                @endphp

                @foreach($days as $day)
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <label class="form-label mb-0 text-capitalize">{{ $day }}</label>
                    </div>
                    <div class="col-sm-9">
                        <select class="form-select" name="{{ $day }}_shift_id">
                            <option value="">- No Change -</option>
                            @foreach($shifts as $shift)
                                <option value="{{ $shift->id }}" {{ old($day.'_shift_id', $editPolicy ? $editPolicy->{$day.'_shift_id'} : '') == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }} to {{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}) [{{ $shift->duration_hours }} hrs.]
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary px-4">
            <i class="ri-save-line me-1"></i> Save
        </button>

        @if($editPolicy && !$editPolicy->is_default)
        <button type="button" class="btn btn-danger" onclick="if(confirm('Delete this policy?')){ document.getElementById('delete-form').submit(); }">
            <i class="ri-delete-bin-line me-1"></i> Delete
        </button>
        @endif
    </div>
</form>

@if($editPolicy)
<form id="delete-form" action="{{ route('shift-policies.destroy', $editPolicy->id) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>
@endif

@else
{{-- =========================================================
     LIST VIEW
     ========================================================= --}}
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="policies-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>SR NO.</th>
                        <th>POLICY NAME</th>
                        <th>DESCRIPTION</th>
                        <th>DEFAULT SHIFT</th>
                        <th>DEFAULT POLICY</th>
                        <th width="100">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($policies as $key => $policy)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td class="fw-medium text-dark">{{ $policy->name }}</td>
                        <td>{{ $policy->description ?? '-' }}</td>
                        <td>
                            @if($policy->defaultShift)
                            {{ $policy->defaultShift->name }} <br>
                            <small class="text-muted">
                            {{ \Carbon\Carbon::parse($policy->defaultShift->start_time)->format('h:i A') }} to 
                            {{ \Carbon\Carbon::parse($policy->defaultShift->end_time)->format('h:i A') }}
                            [{{ $policy->defaultShift->duration_hours }} hrs.]
                            </small>
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($policy->is_default)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-light text-dark">No</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-soft-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="ri-more-fill align-middle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a href="{{ route('shift-policies.edit', $policy->id) }}" class="dropdown-item">
                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                                        </a>
                                    </li>
                                    @if(!$policy->is_default)
                                    <li>
                                        <form action="{{ route('shift-policies.destroy', $policy->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this policy?')">
                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="ri-calendar-event-line" style="font-size:2rem;"></i>
                            <br>No Shift Policies Found. Click <strong>Add New</strong> to create one.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    if($('#policies-table').length) {
        $('#policies-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']]
        });
    }
});
</script>
@endsection
