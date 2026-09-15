@extends('layouts.master')

@section('title')
Exit Reasons
@endsection

@section('css')

<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="helpdesk-header">

    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Master Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Exit Reasons</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Exit Reasons</h4>
        </div>

        <div class="header-buttons">

            <button type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addExitReasonModal">

                <i class="ri-add-line"></i>
                Add New

            </button>

        </div>

    </div>

</div>

@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    {{ session('success') }}

    <button type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif

<div class="card">

    <div class="card-body">

        <div class="table-responsive">

            <table id="fixed-header"
                class="table table-bordered table-striped dt-responsive nowrap align-middle"
                style="width:100%">

                <thead class="table-light">

                    <tr>
                        <th>SR NO.</th>
                        <th>REASON NAME</th>
                        <th>ESI MAPPING</th>
                        <th width="120">ACTIONS</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($exitReasons as $key => $exitReason)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ $exitReason->reason_name }}</td>

                        <td>{{ $exitReason->esi_mapping ?? 'None' }}</td>

                        <td>

                            <div class="dropdown d-inline-block">

                                <button class="btn btn-soft-secondary btn-sm"
                                    type="button"
                                    data-bs-toggle="dropdown">

                                    <i class="ri-more-fill align-middle"></i>

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>

                                        <button type="button"
                                            class="dropdown-item editExitReasonBtn"
                                            data-id="{{ $exitReason->id }}"
                                            data-name="{{ $exitReason->reason_name }}"
                                            data-mapping="{{ $exitReason->esi_mapping }}">

                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>

                                            Edit

                                        </button>

                                    </li>

                                    <li>

                                        <form action="{{ route('exit-reasons.destroy',$exitReason->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this Exit Reason?')">

                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>

                                                Delete

                                            </button>

                                        </form>

                                    </li>

                                </ul>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4" class="text-center">
                            No Exit Reasons Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Add Modal -->

<div class="modal fade" id="addExitReasonModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('exit-reasons.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Exit Reason
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Reason Name
                        </label>

                        <input type="text"
                            name="reason_name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            ESI Mapping
                        </label>

                        <select name="esi_mapping"
                            class="form-select">

                            <option value="None">None</option>
                            <option value="On Leave">On Leave</option>
                            <option value="Left Service">Left Service</option>
                            <option value="Retired">Retired</option>
                            <option value="Out of Coverage">Out of Coverage</option>
                            <option value="Expired">Expired</option>
                            <option value="Non Implemented Area">Non Implemented Area</option>
                            <option value="Compliance by Immediate Employer">Compliance by Immediate Employer</option>
                            <option value="Suspension of Work">Suspension of Work</option>
                            <option value="Strike/Lockout">Strike/Lockout</option>
                            <option value="Retrenchment">Retrenchment</option>
                            <option value="No Work">No Work</option>
                            <option value="Doesn't belong to this employer">Doesn't belong to this employer</option>
                            <option value="Duplicate IP">Duplicate IP</option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                        class="btn btn-primary">

                        Save

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Edit Modal -->

<div class="modal fade" id="editExitReasonModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="editForm"
                method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Exit Reason
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Reason Name
                        </label>

                        <input type="text"
                            id="edit_reason_name"
                            name="reason_name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            ESI Mapping
                        </label>

                        <select id="edit_esi_mapping"
                            name="esi_mapping"
                            class="form-select">

                            <option value="None">None</option>
                            <option value="On Leave">On Leave</option>
                            <option value="Left Service">Left Service</option>
                            <option value="Retired">Retired</option>
                            <option value="Out of Coverage">Out of Coverage</option>
                            <option value="Expired">Expired</option>
                            <option value="Non Implemented Area">Non Implemented Area</option>
                            <option value="Compliance by Immediate Employer">Compliance by Immediate Employer</option>
                            <option value="Suspension of Work">Suspension of Work</option>
                            <option value="Strike/Lockout">Strike/Lockout</option>
                            <option value="Retrenchment">Retrenchment</option>
                            <option value="No Work">No Work</option>
                            <option value="Doesn't belong to this employer">Doesn't belong to this employer</option>
                            <option value="Duplicate IP">Duplicate IP</option>

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit"
                        class="btn btn-primary">

                        Update

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@section('script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>

$(document).ready(function () {

    $('#fixed-header').DataTable({
        responsive: true,
        pageLength: 10,
        fixedHeader: true
    });

    $(document).on('click', '.editExitReasonBtn', function() {

        let id = $(this).data('id');
        let name = $(this).data('name');
        let mapping = $(this).data('mapping');

        $('#edit_reason_name').val(name);
        $('#edit_esi_mapping').val(mapping);

        $('#editForm').attr(
            'action',
            "{{ url('business/setup/master/exit-reasons') }}/" + id
        );

        $('#editExitReasonModal').modal('show');

    });

});

</script>

@endsection