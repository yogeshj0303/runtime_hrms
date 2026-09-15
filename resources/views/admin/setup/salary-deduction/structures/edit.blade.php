@extends('layouts.master')

@section('title')
Salary Structure
@endsection

@section('content')

<div class="helpdesk-header">

    <div class="breadcrumb-section">

        <span>Setup</span>

        <i class="ri-arrow-right-s-line"></i>

        <span>Salary & Deductions</span>

        <i class="ri-arrow-right-s-line"></i>

        <span>Salary Structure</span>

    </div>

    <div class="header-content">

        <div class="header-left">

            <h4>

                Salary Structure

            </h4>

        </div>

    </div>

</div>
<div class="card">

    <div class="card-header">

        <h5>

            Structure Information

        </h5>

    </div>

    <div class="card-body">

        <form
            action="{{ route('salary.structures.update',$structure->id) }}"
            method="POST">

            @csrf

            @method('PUT')

            <div class="row">

                <div class="col-md-6">

                    <label class="form-label">

                        Structure Name

                    </label>

                    <input
                        type="text"
                        class="form-control"
                        name="structure_name"
                        value="{{ $structure->structure_name }}"
                        required>

                </div>

                <div class="col-md-2 mt-4">

                    <button
                        class="btn btn-primary">

                        Update

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
<div class="card">

    <div class="card-header">

        <h5>

            Add Allocation Rule

        </h5>

    </div>

 <div class="card-body">

    <form
        action="{{ route('salary.structure.rule.store',$structure->id) }}"
        method="POST">

        @csrf

        <!-- Row 1 -->

        <div class="row">

            <div class="col-md-2">

                <label>
                    Salary Component
                </label>

                <select
                    class="form-select"
                    name="salary_component_id">

                    <option value="">
                        - Select -
                    </option>

                    @foreach($components as $component)

                        <option value="{{ $component->id }}">
                            {{ $component->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-1">

                <label>
                    Order No
                </label>

                <input
                    type="number"
                    class="form-control"
                    name="order_no"
                    value="1">

            </div>

        </div>

        <!-- Conditional Definition -->

        <div class="mt-3">

            <label>
                Conditional Definition
            </label>

        </div>

        <!-- IF -->

        <div class="row align-items-center mt-2">

            <div class="col-auto">

                If

            </div>

            <div class="col-md-2">

                <select
                    class="form-select"
                    name="condition_component">

                    @foreach($components as $component)

                        <option value="{{ $component->id }}">
                            {{ $component->name }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-auto">

                is

            </div>

            <div class="col-md-2">

                <select
                    class="form-select"
                    name="condition_operator">

                    <option value=">">
                        more than
                    </option>

                    <option value="<=">
                        less than or equals
                    </option>

                </select>

            </div>

            <div class="col-md-1">

                <input
                    type="number"
                    class="form-control"
                    name="condition_value">

            </div>

        </div>

        <!-- THEN CALCULATE -->

        <div class="row align-items-center mt-3">

            <div class="col-auto">

                then calculate

            </div>

            <div class="col-md-1">

                <input
                    type="number"
                    class="form-control"
                    name="calculate_percentage"
                    value="0">

            </div>

            <div class="col-auto">

                % of

            </div>

            <div class="col-md-2">

                <select
                    class="form-select"
                    name="base_component">

                    @foreach($components as $component)

                        <option value="{{ $component->id }}">
                            {{ $component->name }}
                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <!-- KEEP BETWEEN -->

        <div class="row align-items-center mt-3">

            <div class="col-auto">

                keep between

            </div>

            <div class="col-md-1">

                <input
                    type="number"
                    class="form-control"
                    name="minimum_amount"
                    value="0">

            </div>

            <div class="col-auto">

                to

            </div>

            <div class="col-md-1">

                <input
                    type="number"
                    class="form-control"
                    name="maximum_amount"
                    value="0">

            </div>

        </div>

        <!-- SWITCH -->

        <div class="mt-3">

            <div class="form-check form-switch">

                <input
                    class="form-check-input"
                    type="checkbox"
                    value="1"
                    name="do_not_exceed_gross_salary">

                <label class="form-check-label">

                    Do not exceed Gross Salary

                </label>

            </div>

        </div>

        <!-- BUTTON -->

        <div class="mt-4">

            <button
                type="submit"
                class="btn btn-primary">

                Save Rule

            </button>

        </div>

    </form>

</div>

</div>


<!-- Allocation Rules List -->

<div class="card">

    <div class="card-header">

        <h5>

            Allocation Rules

        </h5>

    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="rules-table"
                class="table table-bordered table-striped align-middle">

                <thead class="table-light">

                <tr>

                    <th>

                        SR NO.

                    </th>

                    <th>

                        COMPONENT

                    </th>

                    <th>

                        ORDER

                    </th>

                    <th>

                        %

                    </th>

                    <th>

                        BASE COMPONENT

                    </th>

                    <th>

                        MIN AMOUNT

                    </th>

                    <th>

                        MAX AMOUNT

                    </th>

                    <th>

                        GROSS LIMIT

                    </th>

                    <th>

                        ACTION

                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($rules as $key => $rule)

                <tr>

                    <td>

                        {{ $key + 1 }}

                    </td>

                    <td>

                        {{ $rule->component?->name }}

                    </td>

                    <td>

                        {{ $rule->order_no }}

                    </td>

                    <td>

                        {{ $rule->calculate_percentage }}

                    </td>

                    <td>

                        {{ $rule->base_component }}

                    </td>

                    <td>

                        {{ number_format($rule->minimum_amount,2) }}

                    </td>

                    <td>

                        {{ number_format($rule->maximum_amount,2) }}

                    </td>

                    <td>

                        @if($rule->do_not_exceed_gross_salary)

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

                        <form
                            action="{{ route('salary.structure.rule.delete',$rule->id) }}"
                            method="POST">

                            @csrf

                            @method('DELETE')

                            <button
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete this Rule?')">

                                <i class="ri-delete-bin-fill"></i>

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="9"
                        class="text-center">

                        No Allocation Rules Found

                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

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

    $('#rules-table').DataTable({

        responsive:true,

        pageLength:10

    });

});

</script>

@endsection

