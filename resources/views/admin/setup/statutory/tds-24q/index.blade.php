@extends('layouts.master')

@section('title')
TDS 24Q Info
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
        <span>TDS 24Q Info</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>TDS 24Q Info</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Enter employer information to generate TDS quarterly returns.</p>
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
        <form id="tds24qForm">
            <!-- General Info -->
            <h5 class="section-title">General Info</h5>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Deductor Type *</label>
                    <select class="form-select" name="deductor_type" id="deductor_type" required>
                        <option value="">- Select -</option>
                        <option value="Central Government">Central Government</option>
                        <option value="State Government">State Government</option>
                        <option value="Statutory Body">Statutory Body</option>
                        <option value="Local Authority">Local Authority</option>
                        <option value="Company">Company</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">AIN Number <i class="ri-information-line text-info"></i></label>
                    <input type="text" class="form-control" name="ain_number" id="ain_number">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Section Code *</label>
                    <select class="form-select" name="section_code" id="section_code" required>
                        <option value="">- Select -</option>
                        <option value="92A">92A</option>
                        <option value="92B">92B</option>
                        <option value="92C">92C</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">PAO Code <i class="ri-information-line text-info"></i></label>
                    <input type="text" class="form-control" name="pao_code" id="pao_code">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">State <i class="ri-information-line text-info"></i></label>
                    <input type="text" class="form-control" name="state" id="state" placeholder="Not Applicable">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">PAO Registration # <i class="ri-information-line text-info"></i></label>
                    <input type="text" class="form-control" name="pao_registration_number" id="pao_registration_number">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Ministry <i class="ri-information-line text-info"></i></label>
                    <input type="text" class="form-control" name="ministry" id="ministry" placeholder="Not Applicable">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">DDO Code <i class="ri-information-line text-info"></i></label>
                    <input type="text" class="form-control" name="ddo_code" id="ddo_code">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Ministry Name (If Others)</label>
                    <input type="text" class="form-control" name="ministry_name" id="ministry_name">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">DDO Registration # <i class="ri-information-line text-info"></i></label>
                    <input type="text" class="form-control" name="ddo_registration_number" id="ddo_registration_number">
                </div>
            </div>

            <!-- Employer Details -->
            <h5 class="section-title">Employer Details</h5>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Employer Name *</label>
                    <input type="text" class="form-control" name="employer_name" id="employer_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">PAN *</label>
                    <input type="text" class="form-control text-uppercase" name="emp_pan" id="emp_pan" required pattern="^[A-Z]{5}[0-9]{4}[A-Z]{1}$" title="Valid PAN required (e.g., ABCDE1234F)">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Branch/Division *</label>
                    <input type="text" class="form-control" name="branch_division" id="branch_division" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">TAN *</label>
                    <input type="text" class="form-control text-uppercase" name="emp_tan" id="emp_tan" required pattern="^[A-Z]{4}[0-9]{5}[A-Z]{1}$" title="Valid TAN required (e.g., ABCD12345E)">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 1 *</label>
                    <input type="text" class="form-control" name="emp_address_line_1" id="emp_address_line_1">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">E-Mail *</label>
                    <input type="email" class="form-control" name="emp_email" id="emp_email">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 2</label>
                    <input type="text" class="form-control" name="emp_address_line_2" id="emp_address_line_2">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">STD Code</label>
                    <input type="text" class="form-control" name="emp_std_code" id="emp_std_code">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 3</label>
                    <input type="text" class="form-control" name="emp_address_line_3" id="emp_address_line_3">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="emp_phone" id="emp_phone">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 4</label>
                    <input type="text" class="form-control" name="emp_address_line_4" id="emp_address_line_4">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">E-Mail (Alternate)</label>
                    <input type="email" class="form-control" name="emp_alternate_email" id="emp_alternate_email">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 5</label>
                    <input type="text" class="form-control" name="emp_address_line_5" id="emp_address_line_5">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">STD Code (Alternate)</label>
                    <input type="text" class="form-control" name="emp_alternate_std_code" id="emp_alternate_std_code">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">State *</label>
                    <select class="form-select" name="emp_state" id="emp_state" required>
                        <option value="">- Select -</option>
                        <option value="Maharashtra">Maharashtra</option>
                        <option value="Karnataka">Karnataka</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Tamil Nadu">Tamil Nadu</option>
                        <option value="Gujarat">Gujarat</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone (Alternate)</label>
                    <input type="text" class="form-control" name="emp_alternate_phone" id="emp_alternate_phone">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">PIN *</label>
                    <input type="text" class="form-control" name="emp_pin" id="emp_pin" required pattern="^[0-9]{6}$" title="6 digit PIN required">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">GST Number</label>
                    <input type="text" class="form-control text-uppercase" name="emp_gst_number" id="emp_gst_number" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$" title="Valid GST required">
                </div>
            </div>

            <!-- Responsible Person Details -->
            <h5 class="section-title">Responsible Person Details</h5>
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name *</label>
                    <input type="text" class="form-control" name="resp_name" id="resp_name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">PAN *</label>
                    <input type="text" class="form-control text-uppercase" name="resp_pan" id="resp_pan" required pattern="^[A-Z]{5}[0-9]{4}[A-Z]{1}$" title="Valid PAN required (e.g., ABCDE1234F)">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Designation *</label>
                    <input type="text" class="form-control" name="resp_designation" id="resp_designation" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Mobile *</label>
                    <input type="text" class="form-control" name="resp_mobile" id="resp_mobile" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 1 *</label>
                    <input type="text" class="form-control" name="resp_address_line_1" id="resp_address_line_1">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">E-Mail *</label>
                    <input type="email" class="form-control" name="resp_email" id="resp_email" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 2</label>
                    <input type="text" class="form-control" name="resp_address_line_2" id="resp_address_line_2">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">STD Code</label>
                    <input type="text" class="form-control" name="resp_std_code" id="resp_std_code">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 3</label>
                    <input type="text" class="form-control" name="resp_address_line_3" id="resp_address_line_3">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="resp_phone" id="resp_phone">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 4</label>
                    <input type="text" class="form-control" name="resp_address_line_4" id="resp_address_line_4">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">E-Mail (Alternate)</label>
                    <input type="email" class="form-control" name="resp_alternate_email" id="resp_alternate_email">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Address Line 5</label>
                    <input type="text" class="form-control" name="resp_address_line_5" id="resp_address_line_5">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">STD Code (Alternate)</label>
                    <input type="text" class="form-control" name="resp_alternate_std_code" id="resp_alternate_std_code">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">State *</label>
                    <select class="form-select" name="resp_state" id="resp_state" required>
                        <option value="">- Select -</option>
                        <option value="Maharashtra">Maharashtra</option>
                        <option value="Karnataka">Karnataka</option>
                        <option value="Delhi">Delhi</option>
                        <option value="Tamil Nadu">Tamil Nadu</option>
                        <option value="Gujarat">Gujarat</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone (Alternate)</label>
                    <input type="text" class="form-control" name="resp_alternate_phone" id="resp_alternate_phone">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">PIN *</label>
                    <input type="text" class="form-control" name="resp_pin" id="resp_pin" required pattern="^[0-9]{6}$" title="6 digit PIN required">
                </div>
                <div class="col-md-6 mb-3 d-flex align-items-end justify-content-end">
                     
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
            url: "{{ url('business/api/statutory/tds-24q-info') }}",
            type: "GET",
            success: function(response) {
                if(response.status && response.data) {
                    let data = response.data;
                    $.each(data, function(key, value) {
                        if(value !== null) {
                            $('#' + key).val(value);
                        }
                    });
                }
            },
            error: function(xhr) {
                console.error("Failed to load TDS 24Q Info");
            }
        });
    }

    loadData();

    // Save Data
    $('#tds24qForm').on('submit', function(e) {
        e.preventDefault();
        
        let btn = $('#saveBtn');
        let originalText = btn.text();
        btn.prop('disabled', true).text('Saving...');

        // Convert form data to object, appending CSRF token
        let formDataArray = $(this).serializeArray();
        let data = {
            _token: '{{ csrf_token() }}'
        };
        $.each(formDataArray, function() {
            data[this.name] = this.value;
        });

        $.ajax({
            url: "{{ url('business/api/statutory/tds-24q-info') }}",
            type: "PUT",
            data: data,
            success: function(response) {
                if(response.status) {
                    Swal.fire({
                        icon: 'success', 
                        title: 'Success', 
                        text: response.message, 
                        timer: 2000, 
                        showConfirmButton: false
                    });
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
