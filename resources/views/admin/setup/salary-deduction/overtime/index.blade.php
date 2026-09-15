@extends('layouts.master')

@section('title')
Overtime Rules
@endsection

@section('css')

<link rel="stylesheet"
      href="{{ asset('/assets/admin/css/business.css') }}">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css"
      rel="stylesheet">

<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"
      rel="stylesheet">

@endsection


@section('content')

<div class="helpdesk-header">

    <div class="breadcrumb-section">

        <span>Setup</span>

        <i class="ri-arrow-right-s-line"></i>

        <span>Salary & Deductions</span>

        <i class="ri-arrow-right-s-line"></i>

        <span>Overtime Rules</span>

    </div>

    <div class="header-content">

        <div class="header-left">

            <h4>

                Overtime Rules

            </h4>

        </div>

        <div class="header-buttons">

            <button
                class="btn-add"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#policyModal">

                <i class="ri-add-line"></i>

                Add New

            </button>

        </div>

    </div>

</div>


@if(session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif


<div class="card">

    <div class="card-body">

        <div class="row">

            <div class="col-md-3">

                <label>

                    Select Overtime Policy

                </label>

                <select class="form-select">

                    @foreach($policies as $policy)

                    <option>

                        {{ $policy->policy_name }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-2 mt-4">

                <button
                    class="btn btn-primary btn-sm">

                    <i class="ri-edit-fill"></i>

                </button>

                <button
                    class="btn btn-danger btn-sm">

                    <i class="ri-delete-bin-fill"></i>

                </button>

            </div>

        </div>

    </div>

</div>


<div class="card mt-3">

    <div class="card-body">

        <h6>

            Select Payable Components

        </h6>

        @foreach($components as $component)

        <div class="form-check form-switch">

            <input
                class="form-check-input"
                type="checkbox">

            <label>

                {{ $component->name }}

            </label>

        </div>

        @endforeach

    </div>

</div>


<div class="card mt-3">

    <div class="card-header">

        <button
            class="btn btn-primary btn-sm float-end"
            data-bs-toggle="modal"
            data-bs-target="#ruleModal">

            <i class="ri-add-line"></i>

            Add New

        </button>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="rules-table"
                class="table table-bordered">

                <thead>

                <tr>

                    <th>SR NO.</th>

                    <th>ATTENDANCE TYPE</th>

                    <th>TIME BASIS</th>

                    <th>FROM</th>

                    <th>TO</th>

                    <th>MULTIPLIER</th>

                    <th>OVERTIME MINUTES</th>

                    <th>ACTION</th>

                </tr>

                </thead>

                <tbody>

                @forelse($rules as $key=>$rule)

                <tr>

                    <td>

                        {{ $key+1 }}

                    </td>

                    <td>

                        {{ str_replace('_',' ',$rule->attendance_type) }}

                    </td>

                    <td>

                        {{ str_replace('_',' ',$rule->time_basis) }}

                    </td>

                    <td>

                        {{ sprintf('%02d',$rule->from_hours) }}:
                        {{ sprintf('%02d',$rule->from_minutes) }}

                    </td>

                    <td>

                        {{ sprintf('%02d',$rule->to_hours) }}:
                        {{ sprintf('%02d',$rule->to_minutes) }}

                    </td>

                    <td>

                        {{ $rule->multiplier }}

                    </td>

                    <td>

                        {{ $rule->overtime_minutes }}

                    </td>

                    <td>

                        <form
                            action="{{ route('overtime.rule.delete',$rule->id) }}"
                            method="POST">

                            @csrf
                            @method('DELETE')

                            <button
                                class="btn btn-danger btn-sm">

                                <i class="ri-delete-bin-fill"></i>

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="8"
                        class="text-center">

                        No Overtime Rules Found

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- Add Policy Modal -->

<div class="modal fade"
     id="policyModal"
     tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="{{ route('overtime.policy.store') }}"
                method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        Add Overtime Policy

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label>

                            Policy Name

                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="policy_name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label>

                            Salary Treatment

                        </label>

                        <select
                            class="form-select"
                            name="salary_treatment">

                            <option value="Include">

                                Include

                            </option>

                            <option value="Exclude">

                                Exclude

                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>

                            Days In Month

                        </label>

                        <select
                            class="form-select"
                            name="days_in_month">

                            <option value="Actual Days">

                                Actual Days

                            </option>

                            <option value="30 Days">

                                30 Days

                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label>

                            Hours In Day

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            name="hours_in_day"
                            value="9">

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-primary">

                        Save

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- Add Rule Modal -->



@if($selectedPolicy)

<div class="modal fade"
     id="ruleModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <form action="{{ route('overtime.rule.store',$selectedPolicy->id) }}"
                  method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">

                        Overtime Rule

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="mb-3 row">

                        <label class="col-md-4 col-form-label">

                            Attendance Type <span class="text-danger">*</span>

                        </label>

                        <div class="col-md-8">

                            <select class="form-select"
                                    name="attendance_type">

                                <option value="">- Select -</option>

                                <option value="Present">Present</option>

                                <option value="Absent">Absent</option>

                                <option value="Holiday">Holiday</option>

                                <option value="Week Off">Week Off</option>

                            </select>

                        </div>

                    </div>



                    <div class="mb-3 row">

                        <label class="col-md-4 col-form-label">

                            Time Basis <span class="text-danger">*</span>

                        </label>

                        <div class="col-md-8">

                            <select class="form-select"
                                    name="time_basis">

                                <option value="Early Coming">

                                    Early Coming

                                </option>

                                <option value="Late Going">

                                    Late Going

                                </option>

                                <option value="Net Late">

                                    Net Late

                                </option>

                                <option value="Total In Minutes">

                                    Total In Minutes

                                </option>

                            </select>

                        </div>

                    </div>



                    <div class="mb-3 row">

                        <label class="col-md-4 col-form-label">

                            From <span class="text-danger">*</span>

                        </label>

                        <div class="col-md-8">

                            <div class="d-flex">

                                <select class="form-select me-2"
                                        name="from_hours">

                                    @for($i=0;$i<=23;$i++)

                                    <option value="{{ $i }}">

                                        {{ sprintf('%02d',$i) }}

                                    </option>

                                    @endfor

                                </select>

                                <span class="mt-2 me-2">

                                    hrs

                                </span>

                                <select class="form-select"
                                        name="from_minutes">

                                    @for($i=0;$i<=59;$i++)

                                    <option value="{{ $i }}">

                                        {{ sprintf('%02d',$i) }}

                                    </option>

                                    @endfor

                                </select>

                                <span class="mt-2 ms-2">

                                    mins

                                </span>

                            </div>

                        </div>

                    </div>



                    <div class="mb-3 row">

                        <label class="col-md-4 col-form-label">

                            To <span class="text-danger">*</span>

                        </label>

                        <div class="col-md-8">

                            <div class="d-flex">

                                <select class="form-select me-2"
                                        name="to_hours">

                                    @for($i=0;$i<=23;$i++)

                                    <option value="{{ $i }}">

                                        {{ sprintf('%02d',$i) }}

                                    </option>

                                    @endfor

                                </select>

                                <span class="mt-2 me-2">

                                    hrs

                                </span>

                                <select class="form-select"
                                        name="to_minutes">

                                    @for($i=0;$i<=59;$i++)

                                    <option value="{{ $i }}">

                                        {{ sprintf('%02d',$i) }}

                                    </option>

                                    @endfor

                                </select>

                                <span class="mt-2 ms-2">

                                    mins

                                </span>

                            </div>

                        </div>

                    </div>



                    <div class="mb-3 row">

                        <label class="col-md-4 col-form-label">

                            Calculation Method <span class="text-danger">*</span>

                        </label>

                        <div class="col-md-8">

                            <div class="form-check form-check-inline">

                                <input class="form-check-input"
                                       type="radio"
                                       name="calculation_method"
                                       value="Exclusive"
                                       checked>

                                <label class="form-check-label">

                                    Exclusive

                                </label>

                            </div>

                            <div class="form-check form-check-inline">

                                <input class="form-check-input"
                                       type="radio"
                                       name="calculation_method"
                                       value="Progressive">

                                <label class="form-check-label">

                                    Progressive

                                </label>

                            </div>

                        </div>

                    </div>



                    <div class="mb-3 row">

                        <label class="col-md-4 col-form-label">

                            Multiplier <span class="text-danger">*</span>

                        </label>

                        <div class="col-md-3">

                            <input type="number"
                                   step="0.1"
                                   class="form-control"
                                   name="multiplier"
                                   value="1">

                        </div>

                    </div>



                    <div class="mb-3 row">

                        <label class="col-md-4 col-form-label">

                            Overtime Mins <span class="text-danger">*</span>

                        </label>

                        <div class="col-md-8">

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="overtime_min_type"
                                       value="Actual"
                                       checked>

                                <label class="form-check-label">

                                    Actual

                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="overtime_min_type"
                                       value="Above">

                                <label class="form-check-label">

                                    Above

                                </label>

                            </div>

                            <div class="form-check">

                                <input class="form-check-input"
                                       type="radio"
                                       name="overtime_min_type"
                                       value="Fixed">

                                <label class="form-check-label">

                                    Fixed

                                </label>

                            </div>

                            <div class="mt-2">

                                <input type="number"
                                       class="form-control"
                                       style="width:90px"
                                       name="overtime_minutes"
                                       value="0">

                            </div>

                        </div>

                    </div>

                </div>



                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal">

                        Close

                    </button>

                    <button class="btn btn-primary">

                        <i class="ri-save-line"></i>

                        Save

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endif






@endsection


@section('script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>

$(function(){

    $('#rules-table').DataTable({

        responsive:true,

        pageLength:10

    });

});

</script>

@endsection