@extends('layouts.master')

@section('title')
Salary Components
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
        <span>Salary & Deductions</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Components</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Salary Components</h4>
        </div>

        <div class="header-buttons">

            <button
                type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addComponentModal">

                <i class="ri-add-line"></i>
                Add New

            </button>

        </div>

    </div>

</div>


@if(session('success'))

<div class="alert alert-success alert-dismissible fade show">

    {{ session('success') }}

    <button
        type="button"
        class="btn-close"
        data-bs-dismiss="alert">
    </button>

</div>

@endif


<div class="card">

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="fixed-header"
                class="table table-bordered table-striped dt-responsive nowrap align-middle"
                style="width:100%">

                <thead class="table-light">

                <tr>

                    <th>SR NO.</th>

                    <th>NAME</th>

                    <th>SHORT NAME</th>

                    <th>UNIT TYPE</th>

                    <th>STATUS</th>

                    <th width="120">
                        ACTIONS
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($components as $key => $component)

                <tr>

                    <td>{{ $key+1 }}</td>

                    <td>{{ $component->name }}</td>

                    <td>{{ $component->short_name }}</td>

                    <td>{{ $component->unit_type }}</td>

                    <td>

                        @if($component->active)

                            <span class="badge bg-success">
                                Active
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Inactive
                            </span>

                        @endif

                    </td>

                    <td>

                        <div class="dropdown d-inline-block">

                            <button
                                class="btn btn-soft-secondary btn-sm"
                                type="button"
                                data-bs-toggle="dropdown">

                                <i class="ri-more-fill align-middle"></i>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>

                                    <button
                                        type="button"
                                        class="dropdown-item editComponentBtn"

                                        data-id="{{ $component->id }}"
                                        data-name="{{ $component->name }}"
                                        data-short_name="{{ $component->short_name }}"
                                        data-unit_type="{{ $component->unit_type }}"
                                        data-active="{{ $component->active }}"
                                        data-exclude="{{ $component->exclude_from_gross_salary }}"
                                        data-hide="{{ $component->hide_in_ctc_reports }}"
                                        data-not_payable="{{ $component->not_payable }}">

                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>

                                        Edit

                                    </button>

                                </li>

                                <li>

                                    <form
                                        action="{{ route('salary.components.destroy',$component->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="dropdown-item"
                                            onclick="return confirm('Delete this Component?')">

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

                    <td colspan="6" class="text-center">
                        No Components Found
                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- Add Modal -->

<div class="modal fade" id="addComponentModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('salary.components.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Salary Component
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Short Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="short_name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Unit Type
                        </label>

                        <select
                            class="form-select"
                            name="unit_type"
                            required>

                            <option value="Paid Days">Paid Days</option>

                            <option value="Paid Hours">Paid Hours</option>

                            <option value="Fixed Salary">Fixed Salary</option>

                            <option value="Daily Salary">Daily Salary</option>

                            <option value="Rate Based">Rate Based</option>

                            <option value="Variable">Variable</option>

                        </select>

                    </div>


                    <div class="form-check form-switch mb-2">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="active">

                        <label class="form-check-label">
                            Active
                        </label>

                    </div>


                    <div class="form-check form-switch mb-2">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="exclude_from_gross_salary">

                        <label class="form-check-label">
                            Exclude From Gross Salary
                        </label>

                    </div>


                    <div class="form-check form-switch mb-2">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="hide_in_ctc_reports">

                        <label class="form-check-label">
                            Hide In CTC Reports
                        </label>

                    </div>


                    <div class="form-check form-switch">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="not_payable">

                        <label class="form-check-label">
                            Not Payable
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Save

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- Edit Modal -->

<div class="modal fade" id="editComponentModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="editForm" method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Salary Component
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input type="text"
                           class="form-control mb-3"
                           id="edit_name"
                           name="name">

                    <input type="text"
                           class="form-control mb-3"
                           id="edit_short_name"
                           name="short_name">

                    <select
                        class="form-select mb-3"
                        id="edit_unit_type"
                        name="unit_type">

                        <option value="Paid Days">Paid Days</option>
                        <option value="Paid Hours">Paid Hours</option>
                        <option value="Fixed Salary">Fixed Salary</option>
                        <option value="Daily Salary">Daily Salary</option>
                        <option value="Rate Based">Rate Based</option>
                        <option value="Variable">Variable</option>

                    </select>
                    
<div class="form-check form-switch mb-2">

    <input
        class="form-check-input"
        type="checkbox"
        id="edit_active"
        name="active"
        value="1">

    <label class="form-check-label">
        Active
    </label>

</div>


<div class="form-check form-switch mb-2">

    <input
        class="form-check-input"
        type="checkbox"
        id="edit_exclude"
        name="exclude_from_gross_salary"
        value="1">

    <label class="form-check-label">
        Exclude From Gross Salary
    </label>

</div>


<div class="form-check form-switch mb-2">

    <input
        class="form-check-input"
        type="checkbox"
        id="edit_hide"
        name="hide_in_ctc_reports"
        value="1">

    <label class="form-check-label">
        Hide In CTC Reports
    </label>

</div>


<div class="form-check form-switch">

    <input
        class="form-check-input"
        type="checkbox"
        id="edit_not_payable"
        name="not_payable"
        value="1">

    <label class="form-check-label">
        Not Payable
    </label>

</div>
                </div>

                <div class="modal-footer">

                    <button
                        type="submit"
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

<script>

$(document).ready(function() {

    $('#fixed-header').DataTable();

});


$(document).on('click','.editComponentBtn',function(){

    let id = $(this).data('id');

    $('#edit_name').val($(this).data('name'));

    $('#edit_short_name').val($(this).data('short_name'));

    $('#edit_unit_type').val($(this).data('unit_type'));

    $('#edit_active').prop(
        'checked',
        $(this).data('active') == 1
    );

    $('#edit_exclude').prop(
        'checked',
        $(this).data('exclude') == 1
    );

    $('#edit_hide').prop(
        'checked',
        $(this).data('hide') == 1
    );

    $('#edit_not_payable').prop(
        'checked',
        $(this).data('not_payable') == 1
    );

    $('#editForm').attr(
    'action',
    "{{ url('setup/salary/components/update') }}/" + id
);

    $('#editComponentModal').modal('show');

});

</script>

@endsection