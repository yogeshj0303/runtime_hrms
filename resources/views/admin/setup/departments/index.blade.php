@extends('layouts.master')

@section('title')
Departments
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
        <span>Departments</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Departments</h4>
        </div>

        <div class="header-buttons">

            <button type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addDepartmentModal">

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
        data-bs-dismiss="alert"></button>
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
                        <th>DEPARTMENT NAME</th>
                        <th>HEAD</th>
                        <th>DEPUTY HEAD</th>
                        <th>DEFAULT</th>
                        <th width="120">ACTIONS</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($departments as $key => $department)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ $department->name }}</td>

                        <td>{{ $department->head ?? '-' }}</td>

                        <td>{{ $department->deputy_head ?? '-' }}</td>

                        <td>
                            @if($department->is_default)
                                <span class="badge bg-success">
                                    Yes
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    No
                                </span>
                            @endif
                        </td>

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
                                            class="dropdown-item editDepartmentBtn"
                                            data-id="{{ $department->id }}"
                                            data-name="{{ $department->name }}"
                                            data-head="{{ $department->head }}"
                                            data-deputy="{{ $department->deputy_head }}"
                                            data-default="{{ $department->is_default }}">

                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>

                                            Edit

                                        </button>

                                    </li>

                                    <li>

                                        <form action="{{ route('departments.destroy',$department->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this Department?')">

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
                            No Departments Found
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Add Modal -->

<div class="modal fade" id="addDepartmentModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('departments.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Department
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">
                            Department Name
                        </label>

                        <input type="text"
                            name="name"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Head
                        </label>

                        <input type="text"
                            name="head"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            Deputy Head
                        </label>

                        <input type="text"
                            name="deputy_head"
                            class="form-control">
                    </div>

                    <div class="form-check form-switch">

                        <input class="form-check-input"
                            type="checkbox"
                            name="is_default"
                            value="1">

                        <label class="form-check-label">
                            Make Default
                        </label>

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

<div class="modal fade" id="editDepartmentModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="editForm"
                method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Department
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Department Name
                        </label>

                        <input type="text"
                            id="edit_name"
                            name="name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Head
                        </label>

                        <input type="text"
                            id="edit_head"
                            name="head"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Deputy Head
                        </label>

                        <input type="text"
                            id="edit_deputy_head"
                            name="deputy_head"
                            class="form-control">

                    </div>

                    <div class="form-check form-switch">

                        <input class="form-check-input"
                            type="checkbox"
                            id="edit_default"
                            name="is_default"
                            value="1">

                        <label class="form-check-label">
                            Make Default
                        </label>

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

    $(document).on('click', '.editDepartmentBtn', function() {

        let id = $(this).data('id');
        let name = $(this).data('name');
        let head = $(this).data('head');
        let deputy = $(this).data('deputy');
        let isDefault = $(this).data('default');

        $('#edit_name').val(name);
        $('#edit_head').val(head);
        $('#edit_deputy_head').val(deputy);

        $('#edit_default').prop(
            'checked',
            isDefault == 1
        );

        $('#editForm').attr(
            'action',
            "{{ url('business/setup/master/departments') }}/" + id
        );

        $('#editDepartmentModal').modal('show');
    });

});
</script>

@endsection