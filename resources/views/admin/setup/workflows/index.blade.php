@extends('layouts.master')

@section('title')
Workflows
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
        <span>Workflows</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Workflows</h4>
        </div>

        <div class="header-buttons">

            <button type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addWorkflowModal">

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
                        <th>WORKFLOW NAME</th>
                        <th>DESCRIPTION</th>
                        <th>ACTIVE</th>
                        <th width="120">ACTIONS</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($workflows as $key => $workflow)

                    <tr>

                        <td>{{ $key + 1 }}</td>

                        <td>{{ $workflow->workflow_name }}</td>

                        <td>{{ $workflow->description ?? '-' }}</td>

                        <td>

                            @if($workflow->is_active)

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
                                            class="dropdown-item editWorkflowBtn"
                                            data-id="{{ $workflow->id }}"
                                            data-name="{{ $workflow->workflow_name }}"
                                            data-description="{{ $workflow->description }}"
                                            data-active="{{ $workflow->is_active }}">

                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>

                                            Edit

                                        </button>

                                    </li>

                                    <li>

                                        <form action="{{ route('workflows.destroy',$workflow->id) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this Workflow?')">

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

                        <td colspan="5" class="text-center">
                            No Workflows Found
                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Add Workflow Modal -->

<div class="modal fade" id="addWorkflowModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('workflows.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        Add Workflow
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Workflow Name
                        </label>

                        <input type="text"
                            name="workflow_name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                            class="form-control"
                            rows="3"></textarea>

                    </div>

                    <div class="form-check">

                        <input class="form-check-input"
                            type="checkbox"
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

<!-- Edit Workflow Modal -->

<div class="modal fade" id="editWorkflowModal" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="editForm"
                method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        Edit Workflow
                    </h5>

                    <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Workflow Name
                        </label>

                        <input type="text"
                            id="edit_name"
                            name="workflow_name"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea id="edit_description"
                            name="description"
                            class="form-control"
                            rows="3"></textarea>

                    </div>

                    <div class="form-check">

                        <input class="form-check-input"
                            type="checkbox"
                            id="edit_active"
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

    $(document).on('click', '.editWorkflowBtn', function() {

        let id = $(this).data('id');
        let name = $(this).data('name');
        let description = $(this).data('description');
        let active = $(this).data('active');

        $('#edit_name').val(name);
        $('#edit_description').val(description);

        $('#edit_active').prop(
            'checked',
            active == 1
        );

        $('#editForm').attr(
            'action',
            "{{ url('business/setup/master/workflows') }}/" + id
        );

        $('#editWorkflowModal').modal('show');

    });

});

</script>

@endsection