@extends('admin.employee.profile.layout')

@section('profile_content')

<style>
.salary-input-box {
    background-color: #f1f3f5;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    padding: 6px 12px;
    font-size: 13px;
    color: #495057;
    width: 100%;
    text-align: right;
    transition: border-color 0.2s, background-color 0.2s;
}
.salary-input-box[readonly], .salary-input-box:disabled {
    background-color: #f1f3f5;
    color: #495057;
    cursor: not-allowed;
    border-color: #dee2e6;
}
.salary-input-box.editable-field {
    background-color: #ffffff;
    color: #212529;
    border: 1px solid #ced4da;
    cursor: text;
}
.salary-input-box.editable-field:focus {
    background-color: #fff;
    border-color: #405189;
    outline: none;
    box-shadow: 0 0 0 2px rgba(64, 81, 137, 0.15);
}
.form-check-input:checked {
    background-color: #b83232 !important;
    border-color: #b83232 !important;
}
.form-check-input:focus {
    border-color: #b83232;
    box-shadow: 0 0 0 0.2rem rgba(184, 50, 50, 0.2);
}
.salary-row-label {
    font-size: 13.5px;
    color: #495057;
    margin-bottom: 0;
    font-weight: 500;
}
.badge-paid-days {
    background-color: #4b1b36;
    color: #fff;
    font-size: 11px;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
}
.badge-system {
    background-color: #e9ecef;
    color: #495057;
    font-size: 11px;
    font-weight: 500;
    padding: 3px 8px;
    border-radius: 4px;
    display: inline-block;
}
.salary-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #212529;
    margin-bottom: 12px;
}
.salary-option-label {
    font-size: 13px;
    color: #495057;
    font-weight: 500;
}
.btn-hrms-crimson {
    background-color: #b83232;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.btn-hrms-crimson:hover {
    background-color: #9c2727;
    color: #fff;
}
.btn-circle-edit {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #b83232;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    text-decoration: none;
    transition: background 0.2s;
}
.btn-circle-edit:hover {
    background-color: #9c2727;
    color: #fff;
}
.btn-circle-delete {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background-color: #b83232;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: background 0.2s;
}
.btn-circle-delete:hover {
    background-color: #9c2727;
    color: #fff;
}
.btn-save-salary {
    background-color: #b83232;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 8px 24px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
}
.btn-save-salary:hover {
    background-color: #9c2727;
    color: #fff;
}
.btn-recalc {
    background-color: #6c757d;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 16px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.2s;
}
.btn-recalc:hover {
    background-color: #5a6268;
    color: #fff;
}
.salary-row-label.fw-bold {
    font-weight: 700 !important;
}
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="ri-checkbox-circle-line me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Top Card: Salary Revisions List (Image 1) -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark" style="font-size: 17px;">Salary Revisions</h5>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('employee.profile.salary.export', ['id' => $employee->id]) }}" class="btn-hrms-crimson">
                <i class="ri-file-download-line"></i> Download
            </a>
            <button type="button" class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addSalaryRevisionModal">
                <i class="ri-add-circle-line"></i> Add Revision
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        @if($employee->salaryRevisions->count() > 0)
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="text-muted small" style="background-color: #fafbfc;">
                        <tr>
                            <th class="ps-4 py-3" style="font-weight: 600;">Effective From</th>
                            <th style="font-weight: 600;">Net Salary (In-Hand)</th>
                            <th style="font-weight: 600;">CTC</th>
                            <th style="font-weight: 600;">CTC</th>
                            <th class="text-end pe-4" style="font-weight: 600;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee->salaryRevisions as $revision)
                        <tr class="border-bottom revision-table-row" id="revision-row-{{ $revision->id }}">
                            <td class="ps-4 fw-bold fs-14 text-dark">{{ strtoupper(\Carbon\Carbon::parse($revision->effective_from)->format('d-M-Y')) }}</td>
                            <td class="fw-bold fs-14 text-dark">{{ number_format($revision->net_salary, 0) }} <span class="text-muted fs-12 fw-normal">/m</span></td>
                            <td class="fw-bold fs-14 text-dark">{{ number_format($revision->ctc, 0) }} <span class="text-muted fs-12 fw-normal">/m</span></td>
                            <td class="fw-bold fs-14 text-dark">{{ number_format($revision->ctc * 12, 0) }} <span class="text-muted fs-12 fw-normal">/y</span></td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn-circle-edit me-1 js-btn-edit-revision" data-id="{{ $revision->id }}" data-revision='@json($revision)' title="Edit">
                                    <i class="ri-pencil-fill" style="font-size: 13px;"></i>
                                </button>
                                <form action="{{ route('employee.profile.salary.destroy', ['id' => $employee->id, 'revision_id' => $revision->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this salary revision?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-circle-delete" title="Delete">
                                        <i class="ri-delete-bin-fill" style="font-size: 13px;"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-muted p-4">No salary revisions found. Click "Add Revision" to create one.</div>
        @endif
    </div>
</div>

<!-- Modal Dialog: Add Salary Revision (Exact Match to User Screenshot) -->
<div class="modal fade" id="addSalaryRevisionModal" tabindex="-1" aria-labelledby="addSalaryRevisionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('employee.profile.salary.store', ['id' => $employee->id]) }}" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold text-dark fs-16" id="addSalaryRevisionModalLabel">Add Salary Revision</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 py-4">
                    
                    <!-- Effective From Month / Year Picker -->
                    <div class="d-flex align-items-center mb-4">
                        <label class="form-label mb-0 me-3 fw-medium text-dark" style="min-width: 110px; font-size: 14px;">Effective From</label>
                        <div class="d-flex align-items-center gap-2">
                            <select name="effective_month" class="form-select form-select-sm" style="width: 100px;">
                                @php
                                    $months = [
                                        '1' => 'JAN', '2' => 'FEB', '3' => 'MAR', '4' => 'APR',
                                        '5' => 'MAY', '6' => 'JUN', '7' => 'JUL', '8' => 'AUG',
                                        '9' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DEC'
                                    ];
                                    $currentMonth = (int)date('m');
                                @endphp
                                @foreach($months as $num => $mon)
                                    <option value="{{ $num }}" {{ $num == $currentMonth ? 'selected' : '' }}>{{ $mon }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="effective_year" class="form-control form-control-sm text-center" style="width: 90px;" value="{{ date('Y') }}" min="2000" max="2099">
                        </div>
                    </div>

                    <!-- Consider Increment Checkbox -->
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="is_increment" id="modal_is_increment" value="1" checked>
                        <label class="form-check-label fs-13 text-dark" for="modal_is_increment">
                            Consider Increment
                        </label>
                    </div>

                    <!-- Copy salary from latest revision Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="copy_latest" id="modal_copy_latest" value="1" checked>
                        <label class="form-check-label fs-13 text-dark" for="modal_copy_latest">
                            Copy salary from latest revision
                        </label>
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex justify-content-end align-items-center gap-3">
                    <button type="button" class="btn btn-link text-danger p-0 text-decoration-none fs-14 fw-medium" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn" style="background-color: #b83232; color: #fff; border-radius: 6px; padding: 6px 20px; font-size: 14px; font-weight: 600;">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bottom Card: Revision Edit Form (HIDDEN BY DEFAULT - Opens only when Edit pencil is clicked) -->
<div class="card shadow-sm border-0 mt-4" id="salaryFormCard" style="display: none;">
    <form action="" method="POST" id="salaryForm">
        @csrf
        @method('PUT')
        
        <!-- Header -->
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <span class="fs-15 text-dark fw-bold">Effective From:</span>
                <span class="fs-15 text-dark fw-bold me-2" id="effective_from_badge"></span>
                <input type="month" name="effective_from" id="effective_from_input" class="form-control form-control-sm" style="width: 140px;" required>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="d-flex align-items-center" style="max-width: 220px;">
                    <select name="salary_structure_id" id="salary_structure_id" class="form-select form-select-sm">
                        <option value="">Select Structure</option>
                        @foreach($salaryStructures as $structure)
                            <option value="{{ $structure->id }}">
                                {{ $structure->structure_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="button" class="btn btn-secondary btn-sm d-inline-flex align-items-center gap-1" id="load_structure_btn" style="padding: 5px 12px; font-size: 13px;">
                    <i class="ri-checkbox-circle-line"></i> Load Structure
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center js-btn-close-form" title="Close Form" style="padding: 5px 10px;">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row">
                <!-- Left Column: Amounts & Breakdown -->
                <div class="col-lg-7 border-end pe-lg-4">
                    
                    <!-- Top Recalculate Button -->
                    <div class="d-flex justify-content-end mb-3">
                        <button type="button" class="btn-recalc trigger-recalc-btn">
                            <i class="ri-calculator-line"></i> Recalculate
                        </button>
                    </div>

                    <!-- Dynamic container for loaded structure components -->
                    <div id="dynamic-components-container"></div>

                    <!-- Basic Salary -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-5">
                            <label class="salary-row-label">Basic Salary</label>
                        </div>
                        <div class="col-sm-3 text-end">
                            <span class="badge-paid-days">Paid Days</span>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box editable-field" name="basic_salary" id="basic_salary" value="0">
                        </div>
                    </div>

                    <!-- House Rent Allowance -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-5">
                            <label class="salary-row-label">House Rent Allowance</label>
                        </div>
                        <div class="col-sm-3 text-end">
                            <span class="badge-paid-days">Paid Days</span>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box editable-field" name="hra" id="hra" value="0">
                        </div>
                    </div>

                    <!-- Gross Salary -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-8">
                            <label class="salary-row-label fw-bold text-dark">Gross Salary</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box fw-bold" name="gross_salary" id="gross_salary" value="0" readonly>
                        </div>
                    </div>

                    <!-- Total Salary -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-8">
                            <label class="salary-row-label fw-bold text-dark">Total Salary</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box fw-bold" id="total_salary" value="0" readonly>
                        </div>
                    </div>

                    <!-- Voluntary PF -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-8">
                            <label class="salary-row-label">Voluntary PF</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box editable-field" name="allowances[voluntary_pf]" id="voluntary_pf" value="0">
                        </div>
                    </div>

                    <!-- Professional Tax -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-8">
                            <label class="salary-row-label">Professional Tax</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box" name="pt" id="pt" value="0" readonly>
                        </div>
                    </div>

                    <!-- ESI Deduction -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-8">
                            <label class="salary-row-label">ESI Deduction</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box" name="esi" id="esi" value="0" readonly>
                        </div>
                    </div>

                    <!-- PF Deduction -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-8">
                            <label class="salary-row-label">PF Deduction</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box" name="pf" id="pf" value="0" readonly>
                        </div>
                    </div>

                    <!-- Net Salary -->
                    <div class="row mb-4 align-items-center">
                        <div class="col-sm-8">
                            <label class="salary-row-label fw-bold text-dark">Net Salary</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box fw-bold" name="net_salary" id="net_salary" value="0" readonly>
                        </div>
                    </div>

                    <!-- ESI Contribution -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-5">
                            <label class="salary-row-label">ESI Contribution</label>
                        </div>
                        <div class="col-sm-3 text-end">
                            <span class="badge-system">System</span>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box" name="contributions[esi]" id="esi_cont" value="0" readonly>
                        </div>
                    </div>

                    <!-- PF Contribution -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-5">
                            <label class="salary-row-label">PF Contribution</label>
                        </div>
                        <div class="col-sm-3 text-end">
                            <span class="badge-system">System</span>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box" name="contributions[pf]" id="pf_cont" value="0" readonly>
                        </div>
                    </div>

                    <!-- Pension Contribution -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-5">
                            <label class="salary-row-label">Pension Contribution</label>
                        </div>
                        <div class="col-sm-3 text-end">
                            <span class="badge-system">System</span>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box" name="contributions[pension]" id="pension_cont" value="0" readonly>
                        </div>
                    </div>

                    <!-- EDLI & Admin Charges -->
                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-5">
                            <label class="salary-row-label">EDLI & Admin Charges</label>
                        </div>
                        <div class="col-sm-3 text-end">
                            <span class="badge-system">System</span>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box" name="contributions[edli]" id="edli_cont" value="0" readonly>
                        </div>
                    </div>

                    <!-- Total CTC -->
                    <div class="row mb-4 align-items-center">
                        <div class="col-sm-8">
                            <label class="salary-row-label fw-bold text-dark">Total CTC</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="number" step="any" class="salary-input-box fw-bold" name="ctc" id="ctc" value="0" readonly>
                        </div>
                    </div>

                    <!-- Bottom Buttons -->
                    <div class="d-flex justify-content-end mb-4">
                        <button type="button" class="btn-recalc trigger-recalc-btn">
                            <i class="ri-calculator-line"></i> Recalculate
                        </button>
                    </div>

                    <div class="mt-4 d-flex align-items-center gap-2">
                        <button type="submit" class="btn-save-salary">
                            <i class="ri-save-line"></i> Save
                        </button>
                        <button type="button" class="btn btn-light border px-3 py-2 fs-14 fw-medium text-muted js-btn-close-form" style="border-radius: 6px;">
                            Cancel
                        </button>
                    </div>

                </div>

                <!-- Right Column: Salary Options & Statutory Toggles (Image 2) -->
                <div class="col-lg-5 ps-lg-4 mt-4 mt-lg-0">
                    
                    <!-- Salary Options -->
                    <div class="mb-4">
                        <h6 class="salary-section-title">Salary Options</h6>
                        <p class="text-muted small mb-3">Select options for the selected salary revision.</p>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_increment" id="is_increment" value="1">
                            <label class="form-check-label salary-option-label" for="is_increment">This is an Increment</label>
                        </div>
                    </div>

                    <!-- ESI -->
                    <div class="card p-3 border mb-3" style="background-color: #fdfdfd; border-radius: 8px;">
                        <h6 class="salary-section-title">Employee's State Insurance (ESI)</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[esi_do_not_deduct]" id="esi_do_not_deduct" value="1">
                            <label class="form-check-label salary-option-label" for="esi_do_not_deduct">DO NOT Deduct ESI</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="options[esi_above_ceiling]" id="esi_above_ceiling" value="1">
                            <label class="form-check-label salary-option-label" for="esi_above_ceiling">Deduct ESI Above Ceiling</label>
                        </div>
                    </div>

                    <!-- PF -->
                    <div class="card p-3 border mb-3" style="background-color: #fdfdfd; border-radius: 8px;">
                        <h6 class="salary-section-title">Provident Fund (PF)</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_do_not_deduct]" id="pf_do_not_deduct" value="1">
                            <label class="form-check-label salary-option-label" for="pf_do_not_deduct">DO NOT Deduct PF</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_no_pension]" id="pf_no_pension" value="1">
                            <label class="form-check-label salary-option-label" for="pf_no_pension">DO NOT Deduct Pension (PF)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_above_ceiling_employee]" id="pf_above_ceiling_employee" value="1">
                            <label class="form-check-label salary-option-label" for="pf_above_ceiling_employee">Deduct PF Above Ceiling (Employee)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_above_ceiling_employer]" id="pf_above_ceiling_employer" value="1">
                            <label class="form-check-label salary-option-label" for="pf_above_ceiling_employer">Deduct PF Above Ceiling (Employer)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_on_gross]" id="pf_on_gross" value="1">
                            <label class="form-check-label salary-option-label" for="pf_on_gross">Deduct PF on Gross Salary</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="options[pf_extra]" id="pf_extra" value="1">
                            <label class="form-check-label salary-option-label" for="pf_extra">Deduct PF Extra Contribution <i class="ri-information-fill text-muted"></i></label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="salary-option-label mb-0">Min. Deduction <i class="ri-information-fill text-muted"></i></label>
                            <input type="number" class="salary-input-box" style="width: 130px;" name="options[pf_min_deduction]" id="pf_min_deduction" value="0">
                        </div>
                    </div>

                    <!-- Professional Tax -->
                    <div class="card p-3 border mb-3" style="background-color: #fdfdfd; border-radius: 8px;">
                        <h6 class="salary-section-title">Professional Tax</h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="options[pt_do_not_deduct]" id="pt_do_not_deduct" value="1">
                            <label class="form-check-label salary-option-label" for="pt_do_not_deduct">DO NOT Deduct <i class="ri-information-fill text-muted"></i></label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="salary-option-label mb-0">Tax State</label>
                            <select name="options[pt_state]" id="pt_state" class="form-select form-select-sm" style="width: 60%;">
                                <option value="">Select State</option>
                                <option value="Madhya Pradesh" selected>Madhya Pradesh</option>
                                @if(isset($statesData['states']))
                                    @foreach($statesData['states'] as $state)
                                        @if($state['state'] != 'Madhya Pradesh')
                                            <option value="{{ $state['state'] }}">{{ $state['state'] }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <!-- Income Tax (TDS) -->
                    <div class="card p-3 border mb-3" style="background-color: #fdfdfd; border-radius: 8px;">
                        <h6 class="salary-section-title">Income Tax (TDS)</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[tds_do_not_deduct]" id="tds_do_not_deduct" value="1">
                            <label class="form-check-label salary-option-label" for="tds_do_not_deduct">DO NOT Deduct <i class="ri-information-fill text-muted"></i></label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="options[tds_metro]" id="tds_metro" value="1">
                            <label class="form-check-label salary-option-label" for="tds_metro">Metro City Resident (HRA Exemption)</label>
                        </div>
                    </div>

                    <!-- Labour Welfare Fund (LWF) -->
                    <div class="card p-3 border mb-3" style="background-color: #fdfdfd; border-radius: 8px;">
                        <h6 class="salary-section-title">Labour Welfare Fund (LWF)</h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="options[lwf_do_not_deduct]" id="lwf_do_not_deduct" value="1">
                            <label class="form-check-label salary-option-label" for="lwf_do_not_deduct">DO NOT Deduct <i class="ri-information-fill text-muted"></i></label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="salary-option-label mb-0">Tax State</label>
                            <select name="options[lwf_state]" id="lwf_state" class="form-select form-select-sm" style="width: 60%;">
                                <option value="">Select State</option>
                                <option value="Madhya Pradesh" selected>Madhya Pradesh</option>
                                @if(isset($statesData['states']))
                                    @foreach($statesData['states'] as $state)
                                        @if($state['state'] != 'Madhya Pradesh')
                                            <option value="{{ $state['state'] }}">{{ $state['state'] }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('page_script')
<!-- Bootstrap Bundle for Modal JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const salaryFormCard = document.getElementById('salaryFormCard');
    const salaryForm = document.getElementById('salaryForm');
    const loadStructureBtn = document.getElementById('load_structure_btn');
    const structureSelect = document.getElementById('salary_structure_id');
    const dynamicContainer = document.getElementById('dynamic-components-container');
    
    // Inputs
    const basicInput = document.getElementById('basic_salary');
    const hraInput = document.getElementById('hra');
    const grossInput = document.getElementById('gross_salary');
    const totalInput = document.getElementById('total_salary');
    const voluntaryPfInput = document.getElementById('voluntary_pf');
    const ptInput = document.getElementById('pt');
    const esiInput = document.getElementById('esi');
    const pfInput = document.getElementById('pf');
    const netInput = document.getElementById('net_salary');
    const ctcInput = document.getElementById('ctc');
    
    const esiContInput = document.getElementById('esi_cont');
    const pfContInput = document.getElementById('pf_cont');
    const pensionContInput = document.getElementById('pension_cont');
    const edliContInput = document.getElementById('edli_cont');

    // Toggles
    const isIncrementCheckbox = document.getElementById('is_increment');
    const doNotDeductPf = document.getElementById('pf_do_not_deduct');
    const doNotDeductEsi = document.getElementById('esi_do_not_deduct');
    const doNotDeductPt = document.getElementById('pt_do_not_deduct');
    const doNotDeductPension = document.getElementById('pf_no_pension');
    const esiAboveCeiling = document.getElementById('esi_above_ceiling');
    const pfAboveCeilingEmployee = document.getElementById('pf_above_ceiling_employee');
    const pfAboveCeilingEmployer = document.getElementById('pf_above_ceiling_employer');
    const pfOnGross = document.getElementById('pf_on_gross');
    const pfExtra = document.getElementById('pf_extra');
    const pfMinDeduction = document.getElementById('pf_min_deduction');
    const ptStateSelect = document.getElementById('pt_state');
    const tdsDoNotDeduct = document.getElementById('tds_do_not_deduct');
    const tdsMetro = document.getElementById('tds_metro');
    const lwfDoNotDeduct = document.getElementById('lwf_do_not_deduct');
    const lwfStateSelect = document.getElementById('lwf_state');

    // Statutory Settings from Backend
    const epfSetting = @json($epfSetting ?? null);
    const esiSetting = @json($esiSetting ?? null);
    const ptaxSetting = @json($ptaxSetting ?? null);
    const ptaxSlabs = @json($ptaxSlabs ?? []);

    let structureRules = [];
    let currentCustomComponents = {};

    // 1. Hook Edit Buttons in the Table
    document.querySelectorAll('.js-btn-edit-revision').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Highlight selected row
            document.querySelectorAll('.revision-table-row').forEach(r => r.classList.remove('bg-light'));
            const row = this.closest('.revision-table-row');
            if (row) row.classList.add('bg-light');

            let rev = null;
            try {
                rev = JSON.parse(this.getAttribute('data-revision') || '{}');
            } catch (err) {
                console.error("Failed to parse revision data", err);
                return;
            }
            if (!rev || !rev.id) return;

            // Update form action URL
            const updateUrl = "{{ url('business/employee/profile/salary') }}/" + rev.id + "?id={{ $employee->id }}";
            salaryForm.action = updateUrl;

            // Date Badge & Month input
            if (rev.effective_from) {
                const dateParts = rev.effective_from.split('-');
                const yr = dateParts[0];
                const mo = parseInt(dateParts[1], 10);
                const day = dateParts[2] ? dateParts[2].substring(0, 2) : '01';
                const monthNames = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
                const mon = monthNames[mo - 1] || 'JAN';
                document.getElementById('effective_from_badge').innerText = `${day}-${mon}-${yr}`;
                document.getElementById('effective_from_input').value = `${yr}-${String(mo).padStart(2, '0')}`;
            }

            // Structure dropdown
            if (structureSelect) {
                structureSelect.value = rev.salary_structure_id || '';
            }

            // Direct amounts
            if (basicInput) basicInput.value = rev.basic_salary ?? 0;
            if (hraInput) hraInput.value = rev.hra ?? 0;
            if (grossInput) grossInput.value = rev.gross_salary ?? 0;
            if (totalInput) totalInput.value = rev.gross_salary ?? 0;
            if (ptInput) ptInput.value = rev.pt ?? 0;
            if (esiInput) esiInput.value = rev.esi ?? 0;
            if (pfInput) pfInput.value = rev.pf ?? 0;
            if (netInput) netInput.value = rev.net_salary ?? 0;
            if (ctcInput) ctcInput.value = rev.ctc ?? 0;

            // Allowances
            const allowances = typeof rev.allowances === 'string' ? JSON.parse(rev.allowances || '{}') : (rev.allowances || {});
            if (voluntaryPfInput) voluntaryPfInput.value = allowances.voluntary_pf ?? 0;

            // Contributions
            const contributions = typeof rev.contributions === 'string' ? JSON.parse(rev.contributions || '{}') : (rev.contributions || {});
            if (esiContInput) esiContInput.value = contributions.esi ?? 0;
            if (pfContInput) pfContInput.value = contributions.pf ?? 0;
            if (pensionContInput) pensionContInput.value = contributions.pension ?? 0;
            if (edliContInput) edliContInput.value = contributions.edli ?? 0;

            // Options
            const opts = typeof rev.salary_options === 'string' ? JSON.parse(rev.salary_options || '{}') : (rev.salary_options || {});
            if (isIncrementCheckbox) isIncrementCheckbox.checked = Boolean(rev.is_increment);
            if (doNotDeductEsi) doNotDeductEsi.checked = Boolean(opts.esi_do_not_deduct);
            if (esiAboveCeiling) esiAboveCeiling.checked = Boolean(opts.esi_above_ceiling);
            if (doNotDeductPf) doNotDeductPf.checked = Boolean(opts.pf_do_not_deduct);
            if (doNotDeductPension) doNotDeductPension.checked = Boolean(opts.pf_no_pension);
            if (pfAboveCeilingEmployee) pfAboveCeilingEmployee.checked = Boolean(opts.pf_above_ceiling_employee);
            if (pfAboveCeilingEmployer) pfAboveCeilingEmployer.checked = Boolean(opts.pf_above_ceiling_employer);
            if (pfOnGross) pfOnGross.checked = Boolean(opts.pf_on_gross);
            if (pfExtra) pfExtra.checked = Boolean(opts.pf_extra);
            if (pfMinDeduction) pfMinDeduction.value = opts.pf_min_deduction ?? 0;
            if (doNotDeductPt) doNotDeductPt.checked = Boolean(opts.pt_do_not_deduct);
            if (ptStateSelect) ptStateSelect.value = opts.pt_state || 'Madhya Pradesh';
            if (tdsDoNotDeduct) tdsDoNotDeduct.checked = Boolean(opts.tds_do_not_deduct);
            if (tdsMetro) tdsMetro.checked = Boolean(opts.tds_metro);
            if (lwfDoNotDeduct) lwfDoNotDeduct.checked = Boolean(opts.lwf_do_not_deduct);
            if (lwfStateSelect) lwfStateSelect.value = opts.lwf_state || 'Madhya Pradesh';

            // Custom Components & Structure
            currentCustomComponents = typeof rev.custom_components === 'string' ? JSON.parse(rev.custom_components || '{}') : (rev.custom_components || {});
            
            if (rev.salary_structure_id) {
                loadStructureAndRender(rev.salary_structure_id, currentCustomComponents);
            } else {
                if (dynamicContainer) dynamicContainer.innerHTML = '';
            }

            // SHOW FORM CARD
            if (salaryFormCard) {
                salaryFormCard.style.display = 'block';
                salaryFormCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // 2. Close & Cancel buttons
    document.querySelectorAll('.js-btn-close-form').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (salaryFormCard) {
                salaryFormCard.style.display = 'none';
            }
            document.querySelectorAll('.revision-table-row').forEach(r => r.classList.remove('bg-light'));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });

    // 3. Load Structure via AJAX on Button Click
    if (loadStructureBtn) {
        loadStructureBtn.addEventListener('click', function() {
            const structureId = structureSelect ? structureSelect.value : null;
            if (!structureId) {
                alert('Please select a Salary Structure first.');
                return;
            }
            loadStructureAndRender(structureId, currentCustomComponents);
        });
    }

    function loadStructureAndRender(structureId, savedCustomComponents) {
        fetch(`{{ url('business/employee/profile/salary/structure') }}/${structureId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' && data.structure && data.structure.rules) {
                    structureRules = data.structure.rules;
                    renderDynamicComponents(data.structure.rules, savedCustomComponents);
                }
            })
            .catch(error => {
                console.error('Error fetching structure:', error);
            });
    }

    function renderDynamicComponents(rules, savedCustomComponents) {
        structureRules = rules || [];
        if (dynamicContainer) dynamicContainer.innerHTML = '';
        
        let basicVal = parseFloat(basicInput ? basicInput.value : 0) || 12000;
        let hraVal = parseFloat(hraInput ? hraInput.value : 0) || 0;
        let saved = savedCustomComponents || {};
        
        rules.forEach(rule => {
            const comp = rule.component;
            const compName = comp ? comp.name : 'Component';
            const compId = comp ? comp.id : rule.salary_component_id;
            const unitType = comp && comp.unit_type ? comp.unit_type : 'Paid Days';
            const lowerName = compName.toLowerCase();
            
            let defaultVal = 0;
            if (saved && saved[compId] !== undefined) {
                defaultVal = parseFloat(saved[compId]) || 0;
            } else if (rule.calculate_percentage && rule.calculate_percentage > 0) {
                let baseVal = basicVal;
                if (rule.base_component && rule.base_component.toLowerCase().includes('gross')) {
                    baseVal = (grossInput ? parseFloat(grossInput.value) : basicVal) || basicVal;
                }
                defaultVal = Math.round((baseVal * rule.calculate_percentage) / 100);
                if (rule.minimum_amount && defaultVal < rule.minimum_amount) defaultVal = rule.minimum_amount;
                if (rule.maximum_amount && defaultVal > rule.maximum_amount) defaultVal = rule.maximum_amount;
            }
            
            if (lowerName === 'basic' || lowerName === 'basic salary') {
                if (basicInput && (!basicInput.value || basicInput.value == 0)) basicInput.value = defaultVal || basicVal;
            } else if (lowerName === 'hra' || lowerName === 'house rent allowance') {
                if (hraInput && (!hraInput.value || hraInput.value == 0)) hraInput.value = defaultVal || hraVal;
            } else {
                let badgeClass = unitType.toLowerCase() === 'system' ? 'badge-system' : 'badge-paid-days';
                let html = `
                <div class="row mb-3 align-items-center dynamic-component-row">
                    <div class="col-sm-5">
                        <label class="salary-row-label component-name-label">${compName}</label>
                    </div>
                    <div class="col-sm-3 text-end">
                        <span class="${badgeClass}">${unitType}</span>
                    </div>
                    <div class="col-sm-4">
                        <input type="number" step="any" class="salary-input-box dynamic-amount-input" name="custom_components[${compId}]" data-comp-id="${compId}" value="${defaultVal}">
                    </div>
                </div>`;
                if (dynamicContainer) dynamicContainer.insertAdjacentHTML('beforeend', html);
            }
        });
        
        document.querySelectorAll('.dynamic-amount-input').forEach(input => {
            input.addEventListener('input', calculateSalary);
        });
    }

    // 4. Salary Calculation Engine
    function calculateSalary() {
        let basic = parseFloat(basicInput ? basicInput.value : 0) || 0;
        let hra = parseFloat(hraInput ? hraInput.value : 0) || 0;
        let otherEarnings = 0;

        document.querySelectorAll('.dynamic-amount-input').forEach(input => {
            otherEarnings += parseFloat(input.value) || 0;
        });

        let gross = basic + hra + otherEarnings;
        if (grossInput) grossInput.value = gross;
        if (totalInput) totalInput.value = gross;

        let epfWage = basic;
        if (pfOnGross && pfOnGross.checked) {
            epfWage = gross;
        }

        // PF Calculation
        let pfDeduction = 0, pfCont = 0, pensionCont = 0, edliCont = 0;
        if (epfSetting && epfSetting.is_enabled && doNotDeductPf && !doNotDeductPf.checked && epfWage > 0) {
            let ceiling = parseFloat(epfSetting.wage_ceiling) || 15000;
            let empWageBase = (ceiling > 0 && (!pfAboveCeilingEmployee || !pfAboveCeilingEmployee.checked) && epfWage > ceiling) ? ceiling : epfWage;
            let emprWageBase = (ceiling > 0 && (!pfAboveCeilingEmployer || !pfAboveCeilingEmployer.checked) && epfWage > ceiling) ? ceiling : epfWage;
            let pensionWageBase = (ceiling > 0 && epfWage > ceiling) ? ceiling : epfWage;

            let empRate = parseFloat(epfSetting.employee_contribution_rate) || 12;
            let emprRate = parseFloat(epfSetting.employer_contribution_rate) || 12;
            let penRate = parseFloat(epfSetting.pension_contribution_rate) || 8.33;
            let edliRate = parseFloat(epfSetting.edli_contribution_rate) || 0.5;

            pfDeduction = Math.round((empWageBase * empRate) / 100);
            if (pfMinDeduction && parseFloat(pfMinDeduction.value) > pfDeduction) {
                pfDeduction = parseFloat(pfMinDeduction.value);
            }

            if (doNotDeductPension && doNotDeductPension.checked) {
                pfCont = Math.round((emprWageBase * emprRate) / 100);
                pensionCont = 0;
            } else {
                pensionCont = Math.round((pensionWageBase * penRate) / 100);
                let totalEmpr = Math.round((emprWageBase * emprRate) / 100);
                pfCont = Math.max(0, totalEmpr - pensionCont);
            }
            edliCont = Math.round((pensionWageBase * edliRate) / 100);
        }

        // ESI Calculation
        let esiDeduction = 0, esiCont = 0;
        if (esiSetting && esiSetting.is_enabled && doNotDeductEsi && !doNotDeductEsi.checked && gross > 0) {
            let ceiling = parseFloat(esiSetting.gross_wage_ceiling) || 21000;
            if (gross <= ceiling || (esiAboveCeiling && esiAboveCeiling.checked)) {
                let empRate = parseFloat(esiSetting.employee_contribution) || 0.75;
                let emprRate = parseFloat(esiSetting.employer_contribution) || 3.25;
                esiDeduction = Math.ceil((gross * empRate) / 100);
                esiCont = Math.ceil((gross * emprRate) / 100);
            }
        }

        // PTAX Calculation
        let ptDeduction = 0;
        if (ptaxSetting && ptaxSetting.is_enabled && doNotDeductPt && !doNotDeductPt.checked && ptStateSelect && ptStateSelect.value && gross > 0) {
            const applicableSlab = ptaxSlabs.find(slab => 
                slab.state === ptStateSelect.value &&
                gross >= parseFloat(slab.salary_from) && 
                gross <= parseFloat(slab.salary_to)
            );
            if (applicableSlab) {
                ptDeduction = parseFloat(applicableSlab.tax_amount) || 0;
            }
        }

        if (pfInput) pfInput.value = pfDeduction;
        if (esiInput) esiInput.value = esiDeduction;
        if (ptInput) ptInput.value = ptDeduction;

        if (pfContInput) pfContInput.value = pfCont;
        if (pensionContInput) pensionContInput.value = pensionCont;
        if (edliContInput) edliContInput.value = edliCont;
        if (esiContInput) esiContInput.value = esiCont;

        let voluntaryPf = parseFloat(voluntaryPfInput ? voluntaryPfInput.value : 0) || 0;
        let totalDeductions = pfDeduction + esiDeduction + ptDeduction + voluntaryPf;
        let net = gross - totalDeductions;
        if (netInput) netInput.value = net;

        let totalContributions = pfCont + pensionCont + edliCont + esiCont;
        let ctc = gross + totalContributions;
        if (ctcInput) ctcInput.value = ctc;
    }

    // Attach recalculate handlers
    document.querySelectorAll('.trigger-recalc-btn').forEach(btn => {
        btn.addEventListener('click', calculateSalary);
    });

    if (basicInput) basicInput.addEventListener('input', calculateSalary);
    if (hraInput) hraInput.addEventListener('input', calculateSalary);
    if (voluntaryPfInput) voluntaryPfInput.addEventListener('input', calculateSalary);
    if (pfMinDeduction) pfMinDeduction.addEventListener('input', calculateSalary);

    [
        doNotDeductPf, doNotDeductEsi, doNotDeductPt, doNotDeductPension,
        esiAboveCeiling, pfAboveCeilingEmployee, pfAboveCeilingEmployer,
        pfOnGross, pfExtra, tdsDoNotDeduct, tdsMetro, lwfDoNotDeduct
    ].forEach(el => {
        if (el) el.addEventListener('change', calculateSalary);
    });

    if (ptStateSelect) ptStateSelect.addEventListener('change', calculateSalary);
    if (lwfStateSelect) lwfStateSelect.addEventListener('change', calculateSalary);
});
</script>
@endsection