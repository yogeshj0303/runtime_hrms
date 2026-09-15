@extends('layouts.master')

@section('title')
Maternity Leave Policy
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* Sticky first column for horizontal scrolling */
    .table-responsive .table thead th:first-child,
    .table-responsive .table tbody td:first-child {
        position: sticky;
        left: 0;
        background-color: #f8f9fa;
        z-index: 2;
        border-right: 2px solid #dee2e6;
        font-weight: 600;
    }
    .table-responsive .table tbody td:first-child {
        background-color: #fff;
    }
    .form-control-sm {
        min-width: 70px;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Attendance &amp; Leaves</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Maternity Leave Policy</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Maternity Leave Policy</h4>
            <p class="text-muted fs-13 mb-0">Configure maternity leave rules for each child type.</p>
        </div>
        <div class="header-buttons">
            <button type="submit" form="maternityPolicyForm" class="btn-add">
                <i class="ri-save-line"></i> Save Policy
            </button>
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

<div class="card">
    <div class="card-body">
        
        <form id="maternityPolicyForm" action="{{ route('maternity-leave-policy.update') }}" method="POST">
            @csrf
            
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center text-nowrap" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start" style="min-width: 150px;">Child</th>
                            <th title="Normal Leave Weeks">Normal (wks)</th>
                            <th title="Adoption Leave Weeks">Adoption (wks)</th>
                            <th title="Surrogacy Leave Weeks">Surrogacy (wks)</th>
                            <th title="Tubectomy Leave Weeks">Tubectomy (wks)</th>
                            <th title="Miscarriage Leave Weeks">Miscarriage (wks)</th>
                            <th title="Miscarriage Over & Above">Miscarriage O&A <i class="ri-information-fill text-primary"></i></th>
                            <th title="Maternity Extension Days">Extension (days)</th>
                            <th title="Allow Extension">Allow Ext <i class="ri-information-fill text-primary"></i></th>
                            <th title="Minimum Working Days">Min Working Days</th>
                            <th title="Leave Split">Leave Split <i class="ri-information-fill text-primary"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(App\Models\MaternityLeavePolicy::CHILD_TYPES as $childKey => $childLabel)
                            @php
                                $policy = $policies[$childKey] ?? null;
                            @endphp
                            <tr>
                                <td class="text-start text-dark">{{ $childLabel }}</td>
                                
                                <td>
                                    <input type="number" class="form-control form-control-sm mx-auto" 
                                           name="policies[{{ $childKey }}][normal_leave_weeks]" 
                                           value="{{ $policy->normal_leave_weeks ?? 0 }}" min="0" required>
                                </td>
                                
                                <td>
                                    <input type="number" class="form-control form-control-sm mx-auto" 
                                           name="policies[{{ $childKey }}][adoption_leave_weeks]" 
                                           value="{{ $policy->adoption_leave_weeks ?? 0 }}" min="0" required>
                                </td>
                                
                                <td>
                                    <input type="number" class="form-control form-control-sm mx-auto" 
                                           name="policies[{{ $childKey }}][surrogacy_leave_weeks]" 
                                           value="{{ $policy->surrogacy_leave_weeks ?? 0 }}" min="0" required>
                                </td>
                                
                                <td>
                                    <input type="number" class="form-control form-control-sm mx-auto" 
                                           name="policies[{{ $childKey }}][tubectomy_leave_weeks]" 
                                           value="{{ $policy->tubectomy_leave_weeks ?? 0 }}" min="0" required>
                                </td>
                                
                                <td>
                                    <input type="number" class="form-control form-control-sm mx-auto" 
                                           name="policies[{{ $childKey }}][miscarriage_leave_weeks]" 
                                           value="{{ $policy->miscarriage_leave_weeks ?? 0 }}" min="0" required>
                                </td>
                                
                                <td>
                                    <div class="form-check d-flex justify-content-center m-0">
                                        <input class="form-check-input" type="checkbox" 
                                               name="policies[{{ $childKey }}][miscarriage_over_and_above]" 
                                               value="1" {{ ($policy && $policy->miscarriage_over_and_above) ? 'checked' : '' }}>
                                    </div>
                                </td>
                                
                                <td>
                                    <input type="number" class="form-control form-control-sm mx-auto" 
                                           name="policies[{{ $childKey }}][maternity_extension_days]" 
                                           value="{{ $policy->maternity_extension_days ?? 0 }}" min="0" required>
                                </td>
                                
                                <td>
                                    <div class="form-check d-flex justify-content-center m-0">
                                        <input class="form-check-input" type="checkbox" 
                                               name="policies[{{ $childKey }}][allow_extension]" 
                                               value="1" {{ ($policy && $policy->allow_extension) ? 'checked' : '' }}>
                                    </div>
                                </td>
                                
                                <td>
                                    <input type="number" class="form-control form-control-sm mx-auto" 
                                           name="policies[{{ $childKey }}][minimum_working_days]" 
                                           value="{{ $policy->minimum_working_days ?? 0 }}" min="0" required>
                                </td>
                                
                                <td>
                                    <div class="form-check d-flex justify-content-center m-0">
                                        <input class="form-check-input" type="checkbox" 
                                               name="policies[{{ $childKey }}][leave_split]" 
                                               value="1" {{ ($policy && $policy->leave_split) ? 'checked' : '' }}>
                                    </div>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="ri-save-line me-1"></i> Save All Changes
                </button>
            </div>
        </form>

        {{-- Info section --}}
        <div class="mt-4 pt-3 border-top">
            <div class="d-flex align-items-center mb-2">
                <i class="ri-information-fill text-primary me-2"></i> 
                <span class="fs-13 text-muted"><strong>Miscarriage Over & Above:</strong> If enabled, apply the policy for all miscarriage cases exceeding the configured threshold.</span>
            </div>
            <div class="d-flex align-items-center mb-2">
                <i class="ri-information-fill text-primary me-2"></i> 
                <span class="fs-13 text-muted"><strong>Allow Extension:</strong> If enabled, allow additional leave up to the specified maternity extension days.</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="ri-information-fill text-primary me-2"></i> 
                <span class="fs-13 text-muted"><strong>Leave Split:</strong> If enabled, the employee can split maternity leave into multiple periods. Otherwise, the entire leave must be applied continuously.</span>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
    // Simple script to handle tooltips or additional validation if needed later
</script>
@endsection
