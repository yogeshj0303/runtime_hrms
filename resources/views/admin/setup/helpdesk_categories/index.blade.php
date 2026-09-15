@extends('layouts.master')

@section('title')
Helpdesk Categories
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
        <span>Helpdesk Categories</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Helpdesk Categories</h4>
        </div>

        <div class="header-buttons">

            <button type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addCategoryModal">

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
                        <th>CATEGORY NAME</th>
                        <th>PRIMARY APPROVER</th>
                        <th>BACKUP APPROVER</th>
                        <th>STATUS</th>
                        <th width="120">ACTIONS</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($categories as $key => $category)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ $category->category_name }}</td>

                        <td>{{ $category->primary_approver ?? 'Not Defined' }}</td>

                        <td>{{ $category->backup_approver ?? 'Not Defined' }}</td>

                        <td>
                            @if($category->is_active)
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

                                <button class="btn btn-soft-secondary btn-sm"
                                    type="button"
                                    data-bs-toggle="dropdown">

                                    <i class="ri-more-fill align-middle"></i>

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>

                                        <button type="button"
                                            class="dropdown-item editCategoryBtn"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->category_name }}"
                                            data-primary="{{ $category->primary_approver }}"
                                            data-backup="{{ $category->backup_approver }}"
                                            data-active="{{ $category->is_active }}">

                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>

                                            Edit

                                        </button>

                                    </li>

                                    <li>

                                        <form action="{{ route('helpdesk-categories.destroy',$category->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this Category?')">

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
                            No Categories Found
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Add Modal -->

<div class="modal fade" id="addCategoryModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('helpdesk-categories.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Category
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Category Name
                        </label>

                        <input type="text"
                            name="category_name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Primary Approver
                        </label>

                        <input type="text"
                            name="primary_approver"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Backup Approver
                        </label>

                        <input type="text"
                            name="backup_approver"
                            class="form-control">

                    </div>

                    <div class="form-check">

                        <input type="checkbox"
                            class="form-check-input"
                            name="is_active"
                            value="1"
                            checked>

                        <label class="form-check-label">
                            Set Active
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

<div class="modal fade" id="editCategoryModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="editForm"
                method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Category
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Category Name
                        </label>

                        <input type="text"
                            id="edit_name"
                            name="category_name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Primary Approver
                        </label>

                        <input type="text"
                            id="edit_primary"
                            name="primary_approver"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Backup Approver
                        </label>

                        <input type="text"
                            id="edit_backup"
                            name="backup_approver"
                            class="form-control">

                    </div>

                    <div class="form-check">

                        <input type="checkbox"
                            id="edit_active"
                            class="form-check-input"
                            name="is_active"
                            value="1">

                        <label class="form-check-label">
                            Set Active
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

    $(document).on('click', '.editCategoryBtn', function() {

        $('#edit_name').val($(this).data('name'));
        $('#edit_primary').val($(this).data('primary'));
        $('#edit_backup').val($(this).data('backup'));

        $('#edit_active').prop(
            'checked',
            $(this).data('active') == 1
        );

        $('#editForm').attr(
            'action',
            "{{ url('business/setup/master/helpdesk-categories') }}/" + $(this).data('id')
        );

        $('#editCategoryModal').modal('show');

    });

});

</script>

@endsection