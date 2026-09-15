@extends('layouts.master')

@section('title')
Labour Welfare Fund (LWF)
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
.form-switch .form-check-input {
    width: 2.5em;
    height: 1.25em;
}
.component-checkbox {
    width: 1.2em;
    height: 1.2em;
    cursor: pointer;
}
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Statutory Options</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Labour Welfare Fund</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>Labour Welfare Fund (LWF)</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Configure Labour Welfare Fund settings and applicability.</p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-success btn-sm">
                <i class="ri-book-read-line"></i> Read Help
            </button>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success mt-3">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mt-3">
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger mt-3">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row mt-3">
    <!-- Left Column: Components -->
    <div class="col-md-7">
        <div class="mb-3">
            <h6 class="mb-1 fw-bold" style="font-size: 13px;">Salary Components Applicability</h6>
            <p class="text-muted" style="font-size: 12px;">Select the components that should be considered to calculate LWF slab for deduction.</p>
        </div>
        
        <div class="table-responsive border rounded bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-white text-muted" style="font-size: 11px; text-transform: uppercase;">
                    <tr>
                        <th class="border-bottom-0 py-3 px-3">COMPONENT NAME</th>
                        <th class="border-bottom-0 py-3">COMPONENT TYPE</th>
                        <th class="border-bottom-0 py-3 text-center">SELECT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($components as $component)
                    <tr>
                        <td class="px-3" style="font-size: 13px;">{{ $component->name }} @if($component->short_name) [{{ $component->short_name }}] @endif</td>
                        <td style="font-size: 13px;">
                            <span class="badge bg-primary text-white rounded-pill px-3" style="font-size: 11px; font-weight: 500;">
                                {{ $component->unit_type ?? 'Paid Days' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="component-checkbox form-check-input lwf-component-checkbox" 
                                   data-component-id="{{ $component->id }}" 
                                   {{ in_array($component->id, $selectedComponentIds) ? 'checked' : '' }}>
                        </td>
                    </tr>
                    @endforeach
                    @if($components->isEmpty())
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">No Salary Components found.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Global Setting -->
    <div class="col-md-5">
        <div class="mb-3">
            <h6 class="mb-1 fw-bold" style="font-size: 13px;">Global Setting</h6>
            <p class="text-muted" style="font-size: 12px;">Disabling will stop the deduction for all employees, irrespective of employee level settings.</p>
        </div>
        
        <div class="d-flex align-items-center mt-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="globalLwfToggle" {{ $lwfEnabled ? 'checked' : '' }}>
                <label class="form-check-label ms-2" for="globalLwfToggle" style="font-size: 13px;">
                    Enable LWF Deduction
                </label>
            </div>
        </div>


@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // Checkbox toggle for components
    $('.lwf-component-checkbox').on('change', function() {
        let checkbox = $(this);
        checkbox.prop('disabled', true); // prevent multiple clicks
        let componentId = checkbox.data('component-id');
        let isSelected = checkbox.is(':checked');
        
        $.ajax({
            url: "{{ route('setup.statutory.lwf.update-component') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                component_id: componentId,
                is_selected: isSelected
            },
            success: function(res) {
                if(res.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    checkbox.prop('checked', !isSelected);
                    alert(res.message || 'Error updating component.');
                }
            },
            error: function() {
                checkbox.prop('checked', !isSelected);
                alert('An error occurred while updating the component.');
            },
            complete: function() {
                checkbox.prop('disabled', false);
            }
        });
    });

    // Global toggle
    $('#globalLwfToggle').on('change', function() {
        let toggle = $(this);
        toggle.prop('disabled', true);
        let isSelected = toggle.is(':checked');

        $.ajax({
            url: "{{ route('setup.statutory.lwf.toggle-global') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                status: isSelected ? 1 : 0
            },
            success: function(res) {
                if(res.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    toggle.prop('checked', !isSelected);
                    alert(res.message || 'Error updating status.');
                }
            },
            error: function() {
                toggle.prop('checked', !isSelected);
                alert('An error occurred while updating the status.');
            },
            complete: function() {
                toggle.prop('disabled', false);
            }
        });
    });

});
</script>
@endsection
