@extends('layouts.master')

@section('title')
Income Tax Settings
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
.form-switch .form-check-input {
    width: 2.5em;
    height: 1.25em;
}
.component-checkbox {
    width: 1.2em;
    height: 1.2em;
    cursor: pointer;
}
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Statutory Options</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Income Tax</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>Income Tax Settings</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Configure Income Tax settings, salary mappings, and tax slabs.</p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-success btn-sm">
                <i class="ri-book-read-line"></i> Read Help
            </button>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Left Column: Settings -->
    <div class="col-md-5">
        <div class="card bg-white border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-1 fw-bold" style="font-size: 14px;">Global TDS Setting</h6>
                <p class="text-muted" style="font-size: 12px;">Enable or disable Income Tax (TDS) deduction company-wide.</p>
                
                <div class="d-flex align-items-center mt-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enableTdsToggle" {{ $settings->enable_tds_deduction ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="enableTdsToggle" style="font-size: 13px;">
                            Enable TDS Deduction
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="mb-1 fw-bold" style="font-size: 14px;">Declaration Submission Window</h6>
                <p class="text-muted" style="font-size: 12px;">Open window for employees to submit IT declarations.</p>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="declarationWindowToggle" {{ $settings->declaration_window_open ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="declarationWindowToggle" style="font-size: 13px;">
                            Declaration Window Open
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label class="form-label" style="font-size: 12px;">Financial Year</label>
                        <select class="form-select form-select-sm" id="settingFinancialYear">
                            <option value="">Select Financial Year</option>
                            @foreach($financialYears as $fy)
                                <option value="{{ $fy->id }}" {{ $settings->financial_year_id == $fy->id ? 'selected' : '' }}>{{ $fy->year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label" style="font-size: 12px;">Start Date</label>
                        <input type="date" class="form-control form-control-sm" id="windowStartDate" value="{{ $settings->window_start_date ? \Carbon\Carbon::parse($settings->window_start_date)->format('Y-m-d') : '' }}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label" style="font-size: 12px;">End Date</label>
                        <input type="date" class="form-control form-control-sm" id="windowEndDate" value="{{ $settings->window_end_date ? \Carbon\Carbon::parse($settings->window_end_date)->format('Y-m-d') : '' }}">
                    </div>
                    <div class="col-md-12 text-end mt-2">
                        <button class="btn btn-primary btn-sm" id="saveSettingsBtn">Save Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Components -->
    <div class="col-md-7">
        <div class="card bg-white border-0 shadow-sm">
            <div class="card-body">
                <h6 class="mb-1 fw-bold" style="font-size: 14px;">Salary Components Applicability</h6>
                <p class="text-muted mb-3" style="font-size: 12px;">Map salary components to their tax categories.</p>
                
                <div class="table-responsive border rounded bg-white">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted" style="font-size: 11px; text-transform: uppercase;">
                            <tr>
                                <th class="py-2 px-3">COMPONENT</th>
                                <th class="py-2 text-center" title="Basic Salary">BASIC</th>
                                <th class="py-2 text-center" title="House Rent Allowance">HRA</th>
                                <th class="py-2 text-center" title="Entirely Taxable">TAXABLE</th>
                                <th class="py-2 text-center" title="Exempt under Old Scheme">EXEMPT (OLD)</th>
                                <th class="py-2 text-center" title="Exempt under New Scheme">EXEMPT (NEW)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaryComponents as $component)
                            @php
                                $mapping = $salaryMappings->get($component->id);
                            @endphp
                            <tr>
                                <td class="px-3" style="font-size: 13px;">{{ $component->name }}</td>
                                <td class="text-center">
                                    <input type="checkbox" class="component-checkbox form-check-input tax-mapping-chk" data-type="basic" data-id="{{ $component->id }}" {{ $mapping && $mapping->basic ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="component-checkbox form-check-input tax-mapping-chk" data-type="hra" data-id="{{ $component->id }}" {{ $mapping && $mapping->hra ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="component-checkbox form-check-input tax-mapping-chk" data-type="entire_taxable" data-id="{{ $component->id }}" {{ $mapping && $mapping->entire_taxable ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="component-checkbox form-check-input tax-mapping-chk" data-type="exempt" data-id="{{ $component->id }}" {{ $mapping && $mapping->exempt ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="component-checkbox form-check-input tax-mapping-chk" data-type="new_exempt" data-id="{{ $component->id }}" {{ $mapping && $mapping->new_exempt ? 'checked' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                            @if($salaryComponents->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No Active Salary Components found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card bg-white border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-1 fw-bold" style="font-size: 14px;">Tax Rates (Slabs)</h6>
                        <p class="text-muted mb-0" style="font-size: 12px;">Configure tax slabs for the active financial year.</p>
                    </div>
                    <div>
                        @if($activeFinancialYear)
                        <span class="badge bg-success me-2">FY: {{ $activeFinancialYear->year }}</span>
                        @else
                        <span class="badge bg-danger me-2">No Active FY</span>
                        @endif
                    </div>
                </div>

                <div class="table-responsive border rounded bg-white">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted" style="font-size: 11px; text-transform: uppercase;">
                            <tr>
                                <th class="py-2 px-3">Scheme</th>
                                <th class="py-2">Age Category</th>
                                <th class="py-2 text-end">Income From</th>
                                <th class="py-2 text-end">Income To</th>
                                <th class="py-2 text-end">Fixed Tax</th>
                                <th class="py-2 text-end">Tax %</th>
                                <th class="py-2 text-end">Cess %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($taxSlabs as $slab)
                            <tr>
                                <td class="px-3" style="font-size: 13px;">{{ $slab->scheme }}</td>
                                <td style="font-size: 13px;">{{ $slab->age_category }}</td>
                                <td class="text-end" style="font-size: 13px;">₹{{ number_format($slab->income_from) }}</td>
                                <td class="text-end" style="font-size: 13px;">{{ $slab->income_to ? '₹'.number_format($slab->income_to) : 'Above' }}</td>
                                <td class="text-end" style="font-size: 13px;">₹{{ number_format($slab->fixed_tax) }}</td>
                                <td class="text-end" style="font-size: 13px;">{{ $slab->tax_percentage }}%</td>
                                <td class="text-end" style="font-size: 13px;">{{ $slab->cess }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No tax slabs configured for the active financial year.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // API Route Base (Since we are in a web session, we can just use normal AJAX if we had web routes, but we made API routes).
    // API Routes are protected by Sanctum. Assuming session-based Sanctum auth is configured, Laravel handles cookies for us,
    // so we can just hit /api/... routes without a token if 'EnsureFrontendRequestsAreStateful' middleware is active in kernel.

    // Save Settings
    $('#saveSettingsBtn, #enableTdsToggle, #declarationWindowToggle').on('change click', function(e) {
        if(e.type == 'click' && $(this).attr('id') != 'saveSettingsBtn') return;
        
        let btn = $('#saveSettingsBtn');
        btn.prop('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ url('business/api/statutory/income-tax-settings') }}",
            type: "PUT",
            data: {
                _token: '{{ csrf_token() }}',
                enable_tds_deduction: $('#enableTdsToggle').is(':checked') ? 1 : 0,
                declaration_window_open: $('#declarationWindowToggle').is(':checked') ? 1 : 0,
                financial_year_id: $('#settingFinancialYear').val(),
                window_start_date: $('#windowStartDate').val(),
                window_end_date: $('#windowEndDate').val()
            },
            success: function(response) {
                if(e.type == 'click'){
                    Swal.fire({icon: 'success', title: 'Success', text: 'Settings updated successfully.', timer: 2000, showConfirmButton: false});
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to update settings.', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Save Settings');
            }
        });
    });

    // Save Mappings
    $('.tax-mapping-chk').on('change', function() {
        let chk = $(this);
        let tr = chk.closest('tr');
        
        // Disable to prevent multiple clicks
        tr.find('.tax-mapping-chk').prop('disabled', true);
        
        let data = {
            _token: '{{ csrf_token() }}',
            salary_component_id: chk.data('id'),
            basic: tr.find('[data-type="basic"]').is(':checked') ? 1 : 0,
            hra: tr.find('[data-type="hra"]').is(':checked') ? 1 : 0,
            entire_taxable: tr.find('[data-type="entire_taxable"]').is(':checked') ? 1 : 0,
            exempt: tr.find('[data-type="exempt"]').is(':checked') ? 1 : 0,
            new_exempt: tr.find('[data-type="new_exempt"]').is(':checked') ? 1 : 0,
        };

        $.ajax({
            url: "{{ url('business/api/statutory/salary-tax-mappings') }}",
            type: "POST",
            data: data,
            success: function(response) {
                // Success
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to update mapping.', 'error');
                chk.prop('checked', !chk.is(':checked')); // Revert
            },
            complete: function() {
                tr.find('.tax-mapping-chk').prop('disabled', false);
            }
        });
    });

});
</script>
@endsection
