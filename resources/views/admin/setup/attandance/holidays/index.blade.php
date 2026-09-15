@extends('layouts.master')

@section('title')
Holidays
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .filter-group {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    .filter-group select {
        min-width: 150px;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Leaves & Attendance</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Holidays</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Holidays</h4>
            <p class="text-muted fs-13 mb-0">Define holidays for different locations</p>
        </div>
        
        <!-- Filters Area -->
        <div class="d-flex w-100 justify-content-between align-items-center mt-3">
            <form action="{{ route('holidays.index') }}" method="GET" class="filter-group" id="filterForm">
                <div>
                    <label class="form-label text-muted fs-11 mb-1">Location</label>
                    <select class="form-select form-select-sm" name="location_id" onchange="document.getElementById('filterForm').submit();">
                        <option value="">ALL LOCATIONS</option>
                        @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ $selectedLocation == $loc->id ? 'selected' : '' }}>{{ strtoupper($loc->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label text-muted fs-11 mb-1">Year</label>
                    <select class="form-select form-select-sm" name="year" onchange="document.getElementById('filterForm').submit();">
                        @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </form>
            
            <div class="header-buttons d-flex align-items-center">
                <div class="form-check form-switch me-4">
                    <input class="form-check-input" type="checkbox" id="holidays_payable" {{ $settings->holidays_payable ? 'checked' : '' }}>
                    <label class="form-check-label fs-13" for="holidays_payable">Holidays are payable</label>
                </div>
                <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addHolidayModal">
                    <i class="ri-add-line"></i> Add Holiday
                </button>
                <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#copyHolidayModal">
                    <i class="ri-file-copy-line"></i> Copy
                </button>
                <button class="btn btn-outline-info btn-sm">
                    <i class="ri-question-line"></i> Read Help
                </button>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mt-3">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mt-3">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card mt-3">
    <div class="card-body">
        <div class="table-responsive">
            <table id="holidays-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>DATE</th>
                        <th>DAY</th>
                        <th>HOLIDAY NAME</th>
                        <th>LOCATION</th>
                        <th width="120">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $holiday)
                    <tr>
                        <td class="fw-bold">{{ \Carbon\Carbon::parse($holiday->date)->format('d-M-Y') }}</td>
                        <td>{{ $holiday->day }}</td>
                        <td>{{ $holiday->holiday_name }}</td>
                        <td>{{ $holiday->location ? strtoupper($holiday->location->name) : 'ALL LOCATIONS' }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm btn-icon rounded-circle edit-btn" data-id="{{ $holiday->id }}">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mx-1" onclick="return confirm('Delete this holiday?')">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="ri-calendar-event-line" style="font-size:2rem;"></i>
                            <br>No Holidays Found for the selected filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addHolidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalTitle">Edit Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('holidays.store') }}" method="POST" id="holidayForm">
                @csrf
                <div id="methodContainer"></div>
                
                <div class="modal-body p-4">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-12">Location</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="location_id" id="modal_location_id">
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ strtoupper($loc->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-12">Date of Holiday</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control w-75" name="date" id="date" required>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-12">Holiday Name</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="holiday_name" id="holiday_name" placeholder="Republic Day" required>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Copy Holidays Modal -->
<div class="modal fade" id="copyHolidayModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">Copy Holidays</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('holidays.copy') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-12">From Location <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select w-75" name="from_location" required>
                                <option value="">- Select -</option>
                                <option value="all">All Locations</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ strtoupper($loc->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-12">To Location <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select w-75" name="to_location" required>
                                <option value="">- Select -</option>
                                <option value="all">All Locations</option>
                                @foreach($locations as $loc)
                                <option value="{{ $loc->id }}">{{ strtoupper($loc->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-12">From Year <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select w-75" name="from_year" required>
                                @for($i = date('Y') - 2; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ date('Y') - 1 == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-12">To Year <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-select w-75" name="to_year" required>
                                @for($i = date('Y') - 1; $i <= date('Y') + 2; $i++)
                                <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="ri-file-copy-line me-1"></i> Copy</button>
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
    if($('#holidays-table').length) {
        $('#holidays-table').DataTable({
            responsive: true,
            pageLength: 25,
            ordering: false
        });
    }

    // Edit Modal AJAX
    $('.edit-btn').on('click', function () {
        let id = $(this).data('id');
        let url = `{{ url('business/setup/attendance/holidays') }}/${id}/edit`;
        
        $.get(url, function (data) {
            $('#modalTitle').text('Edit Holiday');
            let formUrl = `{{ url('business/setup/attendance/holidays') }}/${id}`;
            $('#holidayForm').attr('action', formUrl);
            $('#methodContainer').html('<input type="hidden" name="_method" value="PUT">');

            $('#modal_location_id').val(data.location_id || '');
            $('#date').val(data.date);
            $('#holiday_name').val(data.holiday_name);

            $('#addHolidayModal').modal('show');
        });
    });

    // Reset Modal on Close
    $('#addHolidayModal').on('hidden.bs.modal', function () {
        $('#modalTitle').text('Add Holiday');
        $('#holidayForm').attr('action', '{{ route('holidays.store') }}');
        $('#methodContainer').empty();
        $('#holidayForm')[0].reset();
    });

    // Payable Toggle AJAX
    $('#holidays_payable').on('change', function() {
        let isPayable = $(this).is(':checked') ? 1 : 0;
        
        $.ajax({
            url: '{{ route("holidays.update-payable") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                holidays_payable: isPayable
            },
            success: function(response) {
                if(response.success) {
                    // Optional toastr message
                    console.log('Payable status updated');
                }
            }
        });
    });
});
</script>
@endsection
