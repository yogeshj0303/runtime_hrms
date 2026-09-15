@extends('layouts.master')

@section('title')
Salary Structures
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

        <span>Salary Structures</span>

    </div>

    <div class="header-content">

        <div class="header-left">

            <h4>

                Salary Structures

            </h4>

        </div>

        <div class="header-buttons">

            <button
                class="btn-add"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#addStructureModal">

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
                class="table table-bordered table-striped align-middle">

                <thead class="table-light">

                <tr>

                    <th>

                        SR NO.

                    </th>

                    <th>

                        STRUCTURE NAME

                    </th>

                    <th>

                        ALLOCATION RULES

                    </th>

                    <th>

                        LAST MODIFIED

                    </th>

                    <th width="120">

                        ACTIONS

                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($structures as $key => $structure)

                <tr>

                    <td>

                        {{ $key+1 }}

                    </td>

                    <td>

                        {{ $structure->structure_name }}

                    </td>

                    <td>

                        <span class="badge bg-info">

                            {{ $structure->rules()->count() }}

                        </span>

                    </td>

                    <td>

                        {{ $structure->updated_at->format('d M Y h:i A') }}

                    </td>

                    <td>

                        <div class="dropdown d-inline-block">

                            <button
                                class="btn btn-soft-secondary btn-sm"
                                type="button"
                                data-bs-toggle="dropdown">

                                <i class="ri-more-fill"></i>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>

                                    <a
                                        href="{{ route('salary.structures.edit',$structure->id) }}"
                                        class="dropdown-item">

                                        <i class="ri-settings-3-fill me-2"></i>

                                        Allocation Rules

                                    </a>

                                </li>

                                <li>

                                    <form
                                        action="{{ route('salary.structures.destroy',$structure->id) }}"
                                        method="POST">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            class="dropdown-item"
                                            onclick="return confirm('Delete this Structure?')">

                                            <i class="ri-delete-bin-fill me-2"></i>

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

                    <td
                        colspan="5"
                        class="text-center">

                        No Salary Structures Found

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<div
    class="modal fade"
    id="addStructureModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('salary.structures.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        Add Salary Structure

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

                            Structure Name

                        </label>

                        <input
                            type="text"
                            name="structure_name"
                            class="form-control"
                            required>

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
@endsection


@section('script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>

$(document).ready(function () {

    $('#fixed-header').DataTable({

        pageLength:10,

        responsive:true

    });

});

</script>

@endsection