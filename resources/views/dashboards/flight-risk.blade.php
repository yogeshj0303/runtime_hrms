@extends('layouts.master')
@section('title')
    Flight Risk Dashboard
@endsection
@section('content')

@php
    $untracked = $riskCounts['Untracked'] ?? 0;
    $uncalculated = $riskCounts['Uncalculated'] ?? 0;
    $noRisk = $riskCounts['No Risk'] ?? 0;
    $modRisk = $riskCounts['Moderate Risk'] ?? 0;
    $highRisk = $riskCounts['High Risk'] ?? 0;
@endphp

<!-- Header Row -->
<div class="row mb-3 pb-1">
    <div class="col-12">
        <div class="d-flex align-items-lg-center flex-lg-row flex-column justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <span class="avatar-title bg-light text-dark rounded-circle fs-20 border">
                        <i class="ri-alert-line text-muted"></i>
                    </span>
                </div>
                <div>
                    <h4 class="fs-16 mb-1 fw-bold">Flight Risk Assessment</h4>
                    <p class="text-muted mb-0 fs-13">Identify potential flight risks based on employee data & activity</p>
                </div>
            </div>
            <div class="mt-3 mt-lg-0 d-flex gap-4 align-items-center">
                <div class="text-muted fs-12 d-flex align-items-center gap-1">
                    <i class="ri-refresh-line fs-14"></i> Updated every 7 days
                </div>
                <button class="btn btn-success btn-sm px-3 rounded-pill">Read Help</button>
            </div>
        </div>
    </div>
</div>

<!-- Cards Row -->
<div class="row mb-4">
    <!-- Untracked / Uncalculated -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate border border-light shadow-none h-100 mb-0">
            <div class="card-body p-0 d-flex align-items-center h-100">
                <div class="row w-100 m-0 text-center py-3">
                    <div class="col-6 border-end">
                        <a href="{{ route('dashboards.flight-risk', ['status' => 'Untracked']) }}" class="text-decoration-none">
                            <h6 class="text-muted text-uppercase fs-11 fw-semibold">UNTRACKED</h6>
                            <h5 class="mb-0 text-dark fw-bold">{{ $untracked }}</h5>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('dashboards.flight-risk', ['status' => 'Uncalculated']) }}" class="text-decoration-none">
                            <h6 class="text-muted text-uppercase fs-11 fw-semibold">UNCALCULATED</h6>
                            <h5 class="mb-0 text-dark fw-bold">{{ $uncalculated }}</h5>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- No Risk -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('dashboards.flight-risk', ['status' => 'No Risk']) }}" class="text-decoration-none h-100 d-block">
            <div class="card card-animate border {{ $statusFilter == 'No Risk' ? 'border-success' : 'border-light' }} shadow-none h-100 mb-0">
                <div class="card-body d-flex align-items-center justify-content-center gap-3">
                    <h6 class="text-success text-uppercase fs-13 fw-bold mb-0">NO RISK</h6>
                    <div class="avatar-xs flex-shrink-0">
                        <span class="avatar-title bg-success rounded-circle fs-13 fw-bold">{{ $noRisk }}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Moderate Risk -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('dashboards.flight-risk', ['status' => 'Moderate Risk']) }}" class="text-decoration-none h-100 d-block">
            <div class="card card-animate border {{ $statusFilter == 'Moderate Risk' ? 'border-warning' : 'border-light' }} shadow-none h-100 mb-0">
                <div class="card-body d-flex align-items-center justify-content-center gap-3">
                    <h6 class="text-warning text-uppercase fs-13 fw-bold mb-0" style="color: #f7b84b !important;">MODERATE RISK</h6>
                    <div class="avatar-xs flex-shrink-0">
                        <span class="avatar-title bg-warning rounded-circle fs-13 fw-bold" style="background-color: #f7b84b !important;">{{ $modRisk }}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- High Risk -->
    <div class="col-xl-3 col-md-6">
        <a href="{{ route('dashboards.flight-risk', ['status' => 'High Risk']) }}" class="text-decoration-none h-100 d-block">
            <div class="card card-animate border {{ $statusFilter == 'High Risk' ? 'border-danger' : 'border-light' }} shadow-none h-100 mb-0">
                <div class="card-body d-flex align-items-center justify-content-center gap-3">
                    <h6 class="text-danger text-uppercase fs-13 fw-bold mb-0" style="color: #f06548 !important;">HIGH RISK</h6>
                    <div class="avatar-xs flex-shrink-0">
                        <span class="avatar-title bg-danger rounded-circle fs-13 fw-bold" style="background-color: #f06548 !important;">{{ $highRisk }}</span>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Table Section -->
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
            <div class="text-muted fs-13">Viewing: <strong class="text-dark">{{ $statusFilter }}</strong></div>
            <div class="text-muted fs-12"><i class="ri-information-line align-middle text-muted"></i> Click on a number above to view list of employees</div>
        </div>
        
        <div class="card border border-light shadow-none">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-borderless mb-0">
                        <thead class="text-muted bg-light border-bottom fs-10 fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                            <tr>
                                <th scope="col" class="py-3 px-4">EMPLOYEE</th>
                                <th scope="col" class="py-3">DEPUTATION</th>
                                <th scope="col" class="py-3">LAST UPDATED</th>
                                <th scope="col" class="py-3">RISK SCORE &darr;</th>
                                <th scope="col" class="py-3">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $emp)
                                @php
                                    $workProfile = $emp->workProfiles->first();
                                    $locationName = $workProfile && $workProfile->location ? $workProfile->location->name : 'N/A';
                                    $departmentName = $workProfile && $workProfile->department ? $workProfile->department->name : 'N/A';
                                    $color = $emp->flight_risk_status == 'High Risk' ? 'danger' : ($emp->flight_risk_status == 'Moderate Risk' ? 'warning' : 'success');
                                    $riskLabel = str_replace(' Risk', '', $emp->flight_risk_status);
                                    if($riskLabel == 'Moderate'){
                                        $color = 'warning';
                                    } elseif($riskLabel == 'High'){
                                        $color = 'danger';
                                    }
                                @endphp
                            <tr class="border-bottom border-light cursor-pointer risk-row" 
                                data-emp-name="{{ $emp->first_name }} {{ $emp->last_name }}"
                                data-emp-code="{{ $emp->employee_code }}"
                                data-score="{{ $emp->flight_risk_score ?? 0 }}"
                                data-label="{{ strtoupper($riskLabel) }}"
                                data-color="{{ $color }}"
                                data-updated="{{ $emp->last_risk_calculated_at ? \Carbon\Carbon::parse($emp->last_risk_calculated_at)->format('d-M-Y') : 'Never' }}"
                                data-signals="{{ $emp->flight_risk_signals ?? '[]' }}"
                                onclick="showRiskDetails(this)">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm">
                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary fw-bold fs-14">
                                                    {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fs-13 mb-1 text-dark text-uppercase fw-bold">{{ $emp->first_name }} {{ $emp->last_name }}</h6>
                                            <p class="text-muted mb-0 fs-12">{{ $emp->employee_code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <p class="mb-1 text-dark fs-12 fw-medium"><i class="ri-building-4-line text-muted me-1 align-middle text-primary"></i> {{ $locationName }}</p>
                                    <p class="text-muted mb-0 fs-12"><i class="ri-briefcase-4-line me-1 align-middle text-info"></i> {{ $departmentName }}</p>
                                </td>
                                <td class="py-3">
                                    <span class="text-dark fs-12">{{ $emp->last_risk_calculated_at ? \Carbon\Carbon::parse($emp->last_risk_calculated_at)->format('d-M-Y') : 'Never' }}</span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-muted border px-2 py-1 fs-11 fw-semibold">{{ $emp->flight_risk_score ?? 0 }}%</span>
                                        @if($emp->flight_risk_status != 'Untracked' && $emp->flight_risk_status != 'Uncalculated')
                                        <span class="badge bg-{{ $color }} px-2 py-1 fs-10 fw-semibold">{{ strtoupper($riskLabel) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($emp->flight_risk_status != 'Untracked')
                                    <form action="{{ route('dashboards.flight-risk.untrack', $emp->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger d-flex align-items-center gap-1 fs-11 rounded-pill px-3 shadow-sm border-0 bg-danger">
                                            <i class="ri-stop-circle-fill"></i> Stop Tracking
                                        </button>
                                    </form>
                                    @else
                                    <span class="text-muted fs-12 fw-medium"><i class="ri-checkbox-circle-fill text-success align-middle me-1"></i> Untracked</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <div class="my-4">
                                        <i class="ri-user-search-line fs-1 display-6 text-light mb-3"></i>
                                        <h5 class="fs-15 text-muted">No employees found in this category.</h5>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Risk Details Modal -->
<div class="modal fade" id="riskDetailsModal" tabindex="-1" aria-labelledby="riskDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold fs-15 text-dark" id="riskDetailsModalLabel">Flight Risk - Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                
                <div class="row mb-3 align-items-center">
                    <div class="col-4">
                        <span class="text-muted fs-12 fw-medium">Employee</span>
                    </div>
                    <div class="col-8">
                        <span class="text-dark fs-12 fw-bold text-uppercase" id="modal-emp-name"></span>
                        <span class="text-muted fs-12" id="modal-emp-code"></span>
                    </div>
                </div>

                <div class="row mb-4 align-items-center">
                    <div class="col-4">
                        <span class="text-muted fs-12 fw-medium">Risk Score</span>
                    </div>
                    <div class="col-8">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-muted border px-2 py-1 fs-11 fw-semibold" id="modal-score"></span>
                            <span class="badge px-2 py-1 fs-10 fw-semibold" id="modal-label"></span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="text-muted fs-12 fw-medium">Risk Signals:</span>
                </div>
                
                <div class="card shadow-none border border-light mb-4 rounded-3 overflow-hidden">
                    <ul class="list-group list-group-flush fs-12" id="modal-signals-list" style="max-height: 200px; overflow-y: auto;">
                        <!-- JS injected signals -->
                    </ul>
                </div>

                <div class="text-muted fs-11 fw-medium mb-1">
                    Last updated: <span class="text-dark fw-bold" id="modal-updated"></span>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-sm text-danger fw-medium px-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@section('script')
<script>
    function showRiskDetails(row) {
        // Prevent modal if user clicked a button inside the row
        if (event.target.closest('form') || event.target.tagName.toLowerCase() === 'button') {
            return;
        }

        // Fetch data
        const empName = row.getAttribute('data-emp-name');
        const empCode = row.getAttribute('data-emp-code');
        const score = row.getAttribute('data-score');
        const label = row.getAttribute('data-label');
        const color = row.getAttribute('data-color');
        const updated = row.getAttribute('data-updated');
        let signals = [];
        try {
            signals = JSON.parse(row.getAttribute('data-signals') || '[]');
        } catch(e) {}

        // Populate header metrics
        document.getElementById('modal-emp-name').innerText = empName;
        document.getElementById('modal-emp-code').innerText = '[' + empCode + ']';
        document.getElementById('modal-score').innerText = score + '%';
        
        const labelEl = document.getElementById('modal-label');
        labelEl.innerText = label;
        labelEl.className = 'badge px-2 py-1 fs-10 fw-semibold bg-' + color;

        document.getElementById('modal-updated').innerText = updated;

        // Populate signals
        const list = document.getElementById('modal-signals-list');
        list.innerHTML = '';

        if(signals.length === 0) {
            list.innerHTML = '<li class="list-group-item px-3 py-2 text-muted">No specific risk signals detected.</li>';
        } else {
            signals.forEach(s => {
                const iconColor = s.is_negative ? 'text-danger' : 'text-success';
                const iconClass = s.is_negative ? 'ri-error-warning-fill' : 'ri-checkbox-circle-fill';
                const textColor = s.is_negative ? 'text-dark' : 'text-muted';
                
                list.innerHTML += `
                    <li class="list-group-item px-3 py-2 border-light d-flex align-items-center gap-2">
                        <i class="${iconClass} ${iconColor} fs-14"></i>
                        <span class="${textColor}">${s.text}</span>
                    </li>
                `;
            });
        }

        // Show modal
        var riskModal = new bootstrap.Modal(document.getElementById('riskDetailsModal'));
        riskModal.show();
    }
</script>
@endsection
@endsection
