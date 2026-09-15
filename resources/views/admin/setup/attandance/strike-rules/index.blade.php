@extends('layouts.master')

@section('title')
Strike Rules
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* Orange badge (Bootstrap doesn't ship this by default) */
    .bg-orange { background-color: #fd7e14 !important; }

    /* Deduction value input: show only when needed */
    #deduction_value_wrap { display: none; }

    .strike-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: .3px;
    }
    .occurrence-badge {
        font-size: 11px;
        background: #f1f5f9;
        color: #475569;
        padding: 3px 9px;
        border-radius: 12px;
        font-weight: 600;
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
        <span>Strike Rules</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Strike Rules</h4>
            <p class="text-muted fs-13 mb-0">
                Configure strike-based rules for early coming, late coming, early going, late going and late lunch events.
            </p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-light btn-sm border me-2">
                <i class="ri-upload-2-line"></i> Easy Add
            </button>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addStrikeRuleModal">
                <i class="ri-add-line"></i> Add Rule
            </button>
            <button class="btn btn-success btn-sm ms-2 border-0">
                <i class="ri-book-read-line"></i> Read Help
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
        <div class="table-responsive">
            <table id="strike-rules-table"
                class="table table-bordered table-striped dt-responsive nowrap align-middle"
                style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th width="60">#</th>
                        <th>RULE TYPE</th>
                        <th>OCCURRENCES</th>
                        <th>STRIKE COLOR</th>
                        <th>DEDUCTION</th>
                        <th>WARNING LETTER</th>
                        <th>STATUS</th>
                        <th width="120">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($strikeRules as $rule)
                    <tr>
                        <td>
                            <span class="fw-medium text-dark">{{ $rule->id }}</span>
                        </td>
                        <td>
                            <strong>{{ $rule->rule_type }}</strong>
                        </td>
                        <td>
                            <span class="occurrence-badge">
                                @if($rule->to_occurrences == 0)
                                    {{ $rule->from_occurrences }}+
                                @else
                                    {{ $rule->from_occurrences }} – {{ $rule->to_occurrences }}
                                @endif
                            </span>
                        </td>
                        <td>
                            @php
                                $colorClass = match($rule->strike_color) {
                                    'Yellow' => 'bg-warning text-dark',
                                    'Orange' => 'bg-orange text-white',
                                    'Red'    => 'bg-danger text-white',
                                    'Blue'   => 'bg-primary text-white',
                                    'Green'  => 'bg-success text-white',
                                    default  => 'bg-secondary text-white',
                                };
                            @endphp
                            <span class="badge strike-badge {{ $colorClass }}">
                                {{ $rule->strike_color }}
                            </span>
                        </td>
                        <td>
                            @if($rule->deduction_type !== 'None')
                                <span class="badge bg-danger rounded-pill">{{ $rule->deduction_type }}</span>
                                @if($rule->deduction_value)
                                    <br><span class="text-muted fs-12">Value: {{ $rule->deduction_value }}</span>
                                @endif
                            @else
                                <span class="text-muted fs-12">None</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted fs-12">{{ $rule->warning_letter ?? '-' }}</span>
                        </td>
                        <td>
                            @if($rule->is_active)
                            <span class="badge bg-success rounded-pill px-3">Active</span>
                            @else
                            <span class="badge bg-secondary rounded-pill px-3">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <button type="button"
                                class="btn btn-primary btn-sm btn-icon rounded-circle edit-btn"
                                data-id="{{ $rule->id }}">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <form action="{{ route('strike-rules.destroy', $rule->id) }}"
                                method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="btn btn-danger btn-sm btn-icon rounded-circle ms-1"
                                    onclick="return confirm('Delete this strike rule?')">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="ri-error-warning-line" style="font-size:2rem;"></i>
                            <br>No Strike Rules Found. Click <strong>Add Rule</strong> to get started.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Info section (mirrors Time Rules pattern) --}}
        <div class="mt-4 pt-3 border-top">
            <div class="d-flex align-items-center mb-2">
                <i class="ri-information-fill text-primary me-2"></i>
                <span class="fs-13 text-muted">
                    Occurrence range: enter <strong>from</strong> and <strong>to</strong> occurrence numbers.
                    Set <strong>To = 0</strong> to mean "this occurrence and onwards".
                </span>
            </div>
            <div class="d-flex align-items-center mb-2">
                <i class="ri-information-fill text-warning me-2"></i>
                <span class="fs-13 text-muted">
                    Strike color is a visual severity indicator — Yellow (mild) → Red (severe).
                </span>
            </div>
            <div class="d-flex align-items-center">
                <i class="ri-information-fill text-success me-2"></i>
                <span class="fs-13 text-muted">
                    Deduction types: <em>Fixed</em> = flat amount, <em>Per Hour / Per Day</em> = proportional, <em>Half / Full Day</em> = attendance-based.
                </span>
            </div>
        </div>
    </div>
</div>

{{-- ───────────────────────────────────────────────
     ADD / EDIT MODAL
──────────────────────────────────────────────── --}}
<div class="modal fade" id="addStrikeRuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="strikeModalTitle">Add Strike Rule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('strike-rules.store') }}" method="POST" id="strikeRuleForm">
                @csrf
                <div id="strikeMethodContainer"></div>

                <div class="modal-body bg-light">
                    <div class="row">

                        {{-- ── Left Column ─────────────────────────────── --}}
                        <div class="col-md-6 pe-md-4 border-end">

                            {{-- Rule Type --}}
                            <div class="mb-4">
                                <label class="form-label text-muted fs-12 mb-1">
                                    Rule Type <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="rule_type" id="rule_type" required>
                                    <option value="Early Coming">Early Coming</option>
                                    <option value="Late Coming">Late Coming</option>
                                    <option value="Early Going">Early Going</option>
                                    <option value="Late Going">Late Going</option>
                                    <option value="Late Lunch">Late Lunch</option>
                                </select>
                            </div>

                            {{-- Occurrence Range --}}
                            <div class="mb-4">
                                <label class="form-label text-muted fs-12 mb-1">
                                    Occurrence Range <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted fs-12">From</span>
                                    <input type="number" class="form-control form-control-sm"
                                        id="from_occurrences" name="from_occurrences"
                                        min="1" value="1" style="width:80px;" required>
                                    <span class="text-muted fs-12">To</span>
                                    <input type="number" class="form-control form-control-sm"
                                        id="to_occurrences" name="to_occurrences"
                                        min="0" value="0" style="width:80px;" required>
                                    <span class="text-muted fs-11">(0 = onwards)</span>
                                </div>
                            </div>

                            {{-- Strike Color --}}
                            <div class="mb-4">
                                <label class="form-label text-muted fs-12 mb-1">
                                    Strike Color <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="strike_color" id="strike_color" required>
                                    <option value="Yellow">🟡 Yellow (Mild)</option>
                                    <option value="Orange">🟠 Orange (Moderate)</option>
                                    <option value="Red">🔴 Red (Severe)</option>
                                    <option value="Blue">🔵 Blue (Info)</option>
                                    <option value="Green">🟢 Green (Minor)</option>
                                </select>
                            </div>

                            {{-- Active --}}
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input"
                                    id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label fs-13" for="is_active">Set Active</label>
                            </div>

                        </div>

                        {{-- ── Right Column ────────────────────────────── --}}
                        <div class="col-md-6 ps-md-4">

                            {{-- Deduction Card --}}
                            <div class="card shadow-none border mb-4" style="background-color:#fdf5f5;">
                                <div class="card-body p-3">
                                    <h6 class="fs-13 fw-bold mb-3">Deduction</h6>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fs-12 mb-1">Deduction Type</label>
                                        <select class="form-select form-select-sm"
                                            id="deduction_type" name="deduction_type" required>
                                            <option value="None">None</option>
                                            <option value="Fixed">Fixed Amount</option>
                                            <option value="Per Day">Per Day</option>
                                            <option value="Per Hour">Per Hour</option>
                                            <option value="Half Day">Half Day</option>
                                            <option value="Full Day">Full Day</option>
                                        </select>
                                    </div>

                                    <div id="deduction_value_wrap" class="mb-3">
                                        <label class="form-label text-muted fs-12 mb-1">Deduction Value</label>
                                        <input type="number" step="0.01" min="0"
                                            class="form-control form-control-sm"
                                            id="deduction_value" name="deduction_value"
                                            placeholder="0.00">
                                        <div class="form-text fs-11 text-muted mt-1">
                                            Required for Fixed, Per Day and Per Hour types.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Warning Letter Card --}}
                            <div class="card shadow-none border mb-0">
                                <div class="card-body bg-white rounded p-3">
                                    <h6 class="fs-13 fw-bold mb-3">Warning Letter</h6>
                                    <div>
                                        <label class="form-label text-muted fs-12 mb-1">
                                            Select warning letter to issue
                                        </label>
                                        <select class="form-select form-select-sm"
                                            id="warning_letter" name="warning_letter">
                                            <option value="">- Select Letter -</option>
                                            @foreach($letterTemplates as $template)
                                                <option value="{{ $template->title }}">{{ $template->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top bg-white py-2">
                    <button type="button" class="btn btn-link text-decoration-none"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ri-save-line me-1"></i> Save
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {

    // ── DataTable ────────────────────────────────────────────────
    if ($('#strike-rules-table').length) {
        $('#strike-rules-table').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[1, 'asc'], [2, 'asc']]
        });
    }

    // ── Deduction value visibility ───────────────────────────────
    function toggleDeductionValue() {
        let type = $('#deduction_type').val();
        if (type === 'Fixed' || type === 'Per Day' || type === 'Per Hour') {
            $('#deduction_value_wrap').show();
        } else {
            $('#deduction_value_wrap').hide();
            $('#deduction_value').val('');
        }
    }

    $('#deduction_type').on('change', toggleDeductionValue);
    toggleDeductionValue(); // run on load

    // ── AJAX Edit ────────────────────────────────────────────────
    $(document).on('click', '.edit-btn', function () {
        let id  = $(this).data('id');
        let url = `{{ url('business/setup/attendance/strike-rules') }}/${id}/edit`;

        $.get(url, function (data) {
            $('#strikeModalTitle').text('Edit Strike Rule');

            let formUrl = `{{ url('business/setup/attendance/strike-rules') }}/${id}`;
            $('#strikeRuleForm').attr('action', formUrl);
            $('#strikeMethodContainer').html('<input type="hidden" name="_method" value="PUT">');

            // Populate fields
            $('#rule_type').val(data.rule_type);
            $('#from_occurrences').val(data.from_occurrences);
            $('#to_occurrences').val(data.to_occurrences);
            $('#strike_color').val(data.strike_color);
            $('#deduction_type').val(data.deduction_type);
            toggleDeductionValue();
            $('#deduction_value').val(data.deduction_value);
            $('#warning_letter').val(data.warning_letter);
            $('#is_active').prop('checked', data.is_active == 1);

            $('#addStrikeRuleModal').modal('show');
        });
    });

    // ── Reset modal on close ─────────────────────────────────────
    $('#addStrikeRuleModal').on('hidden.bs.modal', function () {
        $('#strikeModalTitle').text('Add Strike Rule');
        $('#strikeRuleForm').attr('action', '{{ route('strike-rules.store') }}');
        $('#strikeMethodContainer').empty();
        $('#strikeRuleForm')[0].reset();
        toggleDeductionValue();
    });

});
</script>
@endsection
