@extends('layouts.master')

@section('title')
Form 16 Info
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
.section-title {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eaeaea;
}
.form-label {
    font-size: 13px;
}
.form-control, .form-select {
    font-size: 13px;
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
        <span>Form 16 Info</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>Form 16 Info</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Enter employer information to generate FORM 16 for employees.</p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-success btn-sm">
                <i class="ri-book-read-line"></i> Read Help
            </button>
        </div>
    </div>
</div>

<div class="card bg-white border-0 shadow-sm mt-3">
    <div class="card-body p-4">
        <form id="form16Form" enctype="multipart/form-data">
            
            <!-- Person Responsible for Deduction of Tax -->
            <h5 class="section-title">Person Responsible for Deduction of Tax</h5>
            <div class="row mb-4">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" class="form-control" name="full_name" id="full_name" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Designation *</label>
                    <input type="text" class="form-control" name="designation" id="designation" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Father's Name *</label>
                    <input type="text" class="form-control" name="father_name" id="father_name" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Signature Image</label>
                    <input type="file" class="form-control" name="signature_image" id="signature_image" accept="image/*">
                    <div id="current_signature" class="mt-2" style="display: none;">
                        <img src="" id="signature_preview" style="max-height: 80px; border: 1px solid #ccc; padding: 5px; border-radius: 4px;">
                    </div>
                </div>
            </div>

            <!-- Employer Information -->
            <h5 class="section-title">Employer Information</h5>
            <div class="row mb-4">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" name="employer_name" id="employer_name" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address Line 1 *</label>
                    <input type="text" class="form-control" name="address_line_1" id="address_line_1" placeholder="Flat, Block, Building" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address Line 2 *</label>
                    <input type="text" class="form-control" name="address_line_2" id="address_line_2" placeholder="Street, Lane, Area" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address Line 3</label>
                    <input type="text" class="form-control" name="address_line_3" id="address_line_3" placeholder="City, PIN, State">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Place of Issue *</label>
                    <input type="text" class="form-control" name="place_of_issue" id="place_of_issue" required>
                </div>
            </div>

            <!-- CIT (TDS) Information -->
            <h5 class="section-title">CIT (TDS) Information</h5>
            <div class="row mb-4">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" name="cit_name" id="cit_name" placeholder="The Commissioner of Income Tax (TDS)" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address Line 1 *</label>
                    <input type="text" class="form-control" name="cit_address_line_1" id="cit_address_line_1" placeholder="Flat, Block, Building" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address Line 2 *</label>
                    <input type="text" class="form-control" name="cit_address_line_2" id="cit_address_line_2" placeholder="Street, Lane, Area" required>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Address Line 3</label>
                    <input type="text" class="form-control" name="cit_address_line_3" id="cit_address_line_3" placeholder="City, PIN, State">
                </div>
            </div>

            <div class="d-flex justify-content-start border-top pt-3">
                <button type="submit" class="btn btn-primary px-4" id="saveBtn">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // Load Data
    function loadData() {
        $.ajax({
            url: "{{ url('business/api/statutory/form16-info') }}",
            type: "GET",
            success: function(response) {
                if(response.status && response.data) {
                    let data = response.data;
                    $.each(data, function(key, value) {
                        if(value !== null && key !== 'signature_image' && key !== 'signature_image_url') {
                            $('#' + key).val(value);
                        }
                    });
                    
                    if (data.signature_image_url) {
                        $('#signature_preview').attr('src', data.signature_image_url);
                        $('#current_signature').show();
                    }
                }
            },
            error: function(xhr) {
                console.error("Failed to load Form 16 Info");
            }
        });
    }

    loadData();

    // Save Data
    $('#form16Form').on('submit', function(e) {
        e.preventDefault();
        
        let btn = $('#saveBtn');
        let originalText = btn.text();
        btn.prop('disabled', true).text('Saving...');

        let formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: "{{ url('business/api/statutory/form16-info') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response.status) {
                    Swal.fire({
                        icon: 'success', 
                        title: 'Success', 
                        text: response.message, 
                        timer: 2000, 
                        showConfirmButton: false
                    });
                    
                    if (response.data && response.data.signature_image_url) {
                        $('#signature_preview').attr('src', response.data.signature_image_url);
                        $('#current_signature').show();
                    }
                    
                    // clear file input
                    $('#signature_image').val('');
                }
            },
            error: function(xhr) {
                let msg = 'Failed to save information.';
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    let firstError = Object.values(xhr.responseJSON.errors)[0][0];
                    msg = firstError;
                } else if(xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire('Error', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text(originalText);
            }
        });
    });
});
</script>
@endsection
