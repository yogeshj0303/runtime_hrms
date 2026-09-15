@extends('layouts.master')

@section('title')
Salary Deductions
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

        <span>Deductions</span>

    </div>

    <div class="header-content">

        <div class="header-left">

            <h4>
                Salary Deductions
            </h4>

        </div>

        <div class="header-buttons">

            <button
                type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addDeductionModal">

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

                    <th>
                        SR NO.
                    </th>

                    <th>
                        NAME
                    </th>

                    <th>
                        SHORT NAME
                    </th>

                    <th>
                        DEDUCTION TYPE
                    </th>

                    <th>
                        STATUS
                    </th>

                    <th width="120">
                        ACTIONS
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($deductions as $key => $deduction)

                <tr>

                    <td>

                        {{ $key + 1 }}

                    </td>

                    <td>

                        {{ $deduction->name }}

                    </td>

                    <td>

                        {{ $deduction->short_name }}

                    </td>

                    <td>

                        @if($deduction->deduction_type == 'Fixed')

                            <span class="badge bg-primary">

                                Fixed

                            </span>

                        @elseif($deduction->deduction_type == 'Variable')

                            <span class="badge bg-dark">

                                Variable

                            </span>

                        @elseif($deduction->deduction_type == 'Percent Based')

                            <span class="badge bg-success">

                                Percent Based

                            </span>

                        @elseif($deduction->deduction_type == 'Strike Based')

                            <span class="badge bg-danger">

                                Strike Based

                            </span>

                        @elseif($deduction->deduction_type == 'Attendance Based')

                            <span class="badge bg-warning">

                                Attendance Based

                            </span>

                        @else

                            <span class="badge bg-info">

                                Time Based

                            </span>

                        @endif

                    </td>

                    <td>

                        @if($deduction->active)

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
                                        class="dropdown-item editDeductionBtn"

                                        data-id="{{ $deduction->id }}"

                                        data-name="{{ $deduction->name }}"

                                        data-short_name="{{ $deduction->short_name }}"

                                        data-deduction_type="{{ $deduction->deduction_type }}"

                                        data-active="{{ $deduction->active }}">

                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>

                                        Edit

                                    </button>

                                </li>

                                <li>

                                    <form
                                        action="{{ route('salary.deductions.destroy',$deduction->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="dropdown-item"

                                            onclick="return confirm('Delete this Deduction?')">

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

                        No Deductions Found

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
<!-- Add Modal -->

<div class="modal fade" id="addDeductionModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('salary.deductions.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        Add Salary Deduction

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

                            Deduction Type

                        </label>

                        <select
                            class="form-select"
                            name="deduction_type"
                            required>

                            <option value="Fixed">
                                Fixed
                            </option>

                            <option value="Variable">
                                Variable
                            </option>

                            <option value="Percent Based">
                                Percent Based
                            </option>

                            <option value="Strike Based">
                                Strike Based
                            </option>

                            <option value="Attendance Based">
                                Attendance Based
                            </option>

                            <option value="Time Based">
                                Time Based
                            </option>

                        </select>

                    </div>


                    <div class="form-check form-switch">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            value="1"
                            name="active">

                        <label class="form-check-label">

                            Active

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

<div class="modal fade" id="editDeductionModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                id="editForm"
                method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">

                        Edit Salary Deduction

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
                            id="edit_name"
                            name="name">

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            Short Name

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="edit_short_name"
                            name="short_name">

                    </div>


                    <div class="mb-3">

                        <label class="form-label">

                            Deduction Type

                        </label>

                        <select
                            class="form-select"
                            id="edit_deduction_type"
                            name="deduction_type">

                            <option value="Fixed">
                                Fixed
                            </option>

                            <option value="Variable">
                                Variable
                            </option>

                            <option value="Percent Based">
                                Percent Based
                            </option>

                            <option value="Strike Based">
                                Strike Based
                            </option>

                            <option value="Attendance Based">
                                Attendance Based
                            </option>

                            <option value="Time Based">
                                Time Based
                            </option>

                        </select>

                    </div>


                    <div class="form-check form-switch">

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

<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap.min.js"></script>


<script>

$(document).ready(function () {

    $('#fixed-header').DataTable({

        responsive: true,

        pageLength: 10,

        fixedHeader: true

    });

});



$(document).on('click', '.editDeductionBtn', function () {

    let id = $(this).data('id');

    let name = $(this).data('name');

    let short_name = $(this).data('short_name');

    let deduction_type = $(this).data('deduction_type');

    let active = $(this).data('active');



    $('#edit_name').val(name);

    $('#edit_short_name').val(short_name);

    $('#edit_deduction_type').val(deduction_type);



    $('#edit_active').prop(

        'checked',

        active == 1

    );



    $('#editForm').attr(

        'action',

        "{{ url('setup/salary/deductions/update') }}/" + id

    );



    $('#editDeductionModal').modal('show');

});

</script>

@endsection