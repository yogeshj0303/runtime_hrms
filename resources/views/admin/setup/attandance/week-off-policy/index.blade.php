@extends('layouts.master')

@section('title')
Week Off Policies
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .week-table th, .week-table td {
        text-align: center;
        vertical-align: middle;
    }
    .week-table td:first-child {
        text-align: left;
        font-weight: 500;
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
        <span>Week Off Policies</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Week Off Policies</h4>
            <p class="text-muted fs-13 mb-0">Define general and alternating week off rules for your business.</p>
        </div>
        <div class="header-buttons">
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addWeekOffPolicyModal">
                <i class="ri-add-line"></i> Add Policy
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
            <table id="policies-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th width="80">SR NO.</th>
                        <th>POLICY NAME</th>
                        <th>DESCRIPTION</th>
                        <th>DEFAULT POLICY</th>
                        <th>PAYABLE</th>
                        <th width="120">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($policies as $key => $policy)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <span class="fw-medium text-dark">{{ $policy->name }}</span>
                        </td>
                        <td>{{ $policy->description ?? '-' }}</td>
                        <td>
                            @if($policy->is_default)
                            <span class="badge bg-success rounded-pill px-3">Default</span>
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($policy->is_payable)
                            <span class="badge bg-primary rounded-pill px-3">Yes</span>
                            @else
                            <span class="badge bg-secondary rounded-pill px-3">No</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm btn-icon rounded-circle edit-btn" data-id="{{ $policy->id }}">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            @if(!$policy->is_default)
                            <form action="{{ route('week-off-policies.destroy', $policy->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mx-1" onclick="return confirm('Delete this policy?')">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="ri-calendar-event-line" style="font-size:2rem;"></i>
                            <br>No Week Off Policies Found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addWeekOffPolicyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalTitle">Add Week Off Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('week-off-policies.store') }}" method="POST" id="policyForm">
                @csrf
                <div id="methodContainer"></div>
                
                <div class="modal-body bg-light">
                    <div class="row">
                        <!-- Left Column (Basic Config) -->
                        <div class="col-md-5 pe-md-4 border-end">
                            <div class="mb-3">
                                <label class="form-label text-muted fs-12 mb-1">Policy Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" required placeholder="E.g. Sunday Off">
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted fs-12 mb-1">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="2" placeholder="Optional notes"></textarea>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                                <label class="form-check-label fs-13" for="is_default">Set as Default Policy</label>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_payable" name="is_payable" value="1" checked>
                                <label class="form-check-label fs-13" for="is_payable">Week Off is Payable</label>
                            </div>
                        </div>

                        <!-- Right Column (Week Off Grid) -->
                        <div class="col-md-7 ps-md-4">
                            <h6 class="fs-13 fw-bold mb-3">Select Week Off Days</h6>
                            <p class="fs-12 text-muted mb-3">Check the boxes for the specific weeks a day is considered off. For a full General Weekly Off, check all 5 weeks for that day.</p>
                            
                            <div class="table-responsive bg-white rounded border">
                                <table class="table table-sm table-borderless week-table mb-0">
                                    <thead class="table-light border-bottom">
                                        <tr>
                                            <th>Day</th>
                                            <th>1st</th>
                                            <th>2nd</th>
                                            <th>3rd</th>
                                            <th>4th</th>
                                            <th>5th</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                                        @endphp
                                        @foreach($days as $day)
                                        <tr class="border-bottom">
                                            <td class="text-capitalize text-muted fs-13">{{ $day }}</td>
                                            @for($i=1; $i<=5; $i++)
                                            <td>
                                                <input type="checkbox" class="form-check-input cb-{{ $day }}" name="{{ $day }}_weeks[]" value="{{ $i }}">
                                            </td>
                                            @endfor
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top bg-white py-2">
                    <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">Close</button>
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
    if($('#policies-table').length) {
        $('#policies-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']]
        });
    }

    // Edit Policy AJAX
    $('.edit-btn').on('click', function () {
        let id = $(this).data('id');
        let url = `{{ url('business/setup/attendance/week-off-policies') }}/${id}/edit`;
        
        $.get(url, function (data) {
            $('#modalTitle').text('Edit Week Off Policy');
            let formUrl = `{{ url('business/setup/attendance/week-off-policies') }}/${id}`;
            $('#policyForm').attr('action', formUrl);
            $('#methodContainer').html('<input type="hidden" name="_method" value="PUT">');

            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#is_default').prop('checked', data.is_default);
            $('#is_payable').prop('checked', data.is_payable);

            // Reset checkboxes
            $('input[type="checkbox"][name$="_weeks[]"]').prop('checked', false);

            // Check specific boxes
            let days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            days.forEach(day => {
                let key = day + '_weeks';
                if(data[key] && Array.isArray(data[key])) {
                    data[key].forEach(val => {
                        $(`input[name="${key}[]"][value="${val}"]`).prop('checked', true);
                    });
                }
            });

            $('#addWeekOffPolicyModal').modal('show');
        });
    });

    // Reset Modal on Close
    $('#addWeekOffPolicyModal').on('hidden.bs.modal', function () {
        $('#modalTitle').text('Add Week Off Policy');
        $('#policyForm').attr('action', '{{ route('week-off-policies.store') }}');
        $('#methodContainer').empty();
        $('#policyForm')[0].reset();
        $('input[type="checkbox"][name$="_weeks[]"]').prop('checked', false);
    });
});
</script>
@endsection
