@extends('layouts.master')

@section('title')
    Bulk Onboarding
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<style>
    .candidate-table th {
        background-color: #f8f9fa;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header mb-4">
    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <a href="{{ route('onboarding.index') }}">Onboarding Dashboard</a>
        <i class="ri-arrow-right-s-line"></i>
        <span>Bulk Onboarding</span>
    </div>

    <div class="header-content d-flex justify-content-between align-items-center">
        <div class="header-left">
            <h4>Bulk Add Candidates</h4>
            <p class="text-muted mb-0">Invite up to 25 candidates at once.</p>
        </div>
        <div class="header-right">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#bulkPasteModal">
                <i class="ri-clipboard-line me-1"></i> Paste Bulk Data
            </button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body bg-light rounded">
        <h6 class="mb-3">Quick Add Candidate</h6>
        <div class="row g-2 align-items-center">
            <div class="col-md-3">
                <input type="text" id="quickName" class="form-control" placeholder="Full Name">
            </div>
            <div class="col-md-4">
                <input type="email" id="quickEmail" class="form-control" placeholder="Email Address">
            </div>
            <div class="col-md-3">
                <input type="text" id="quickMobile" class="form-control" placeholder="Mobile Number">
            </div>
            <div class="col-md-2">
                <button type="button" id="btnAddSingle" class="btn btn-primary w-100">
                    <i class="ri-add-line"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('onboarding.bulk.store') }}" method="POST" id="bulkOnboardingForm">
    @csrf

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom pb-0 pt-3">
            <h5 class="mb-3">Candidate List (<span id="candCount">0</span>/25)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table candidate-table table-hover align-middle mb-0" id="candidateTable">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th>Mobile Number</th>
                            <th width="80" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="candidateTableBody">
                        <tr id="emptyRow">
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="ri-group-line fs-24 d-block mb-2"></i>
                                No candidates added yet. Use Quick Add or Paste Bulk Data.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="mb-4">Global Onboarding Settings</h5>
            <p class="text-muted mb-3">These verifications will be required for all candidates in this batch.</p>
            
            <div class="row">
                <div class="col-md-3 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="req_mobile" id="reqMobile" checked>
                        <label class="form-check-label" for="reqMobile">Mobile Verification</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="req_pan" id="reqPan" checked>
                        <label class="form-check-label" for="reqPan">PAN Verification</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="req_bank" id="reqBank" checked>
                        <label class="form-check-label" for="reqBank">Bank Verification</label>
                    </div>
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="req_aadhaar" id="reqAadhaar" checked>
                        <label class="form-check-label" for="reqAadhaar">Aadhaar Verification</label>
                    </div>
                </div>
            </div>

            <div id="hiddenInputsContainer"></div>

            <div class="text-end mt-4 pt-3 border-top">
                <a href="{{ route('onboarding.index') }}" class="btn btn-light me-2">Cancel</a>
                <button type="submit" id="btnSubmitBulk" class="btn btn-success px-4" disabled>
                    <i class="ri-send-plane-fill me-1"></i> Send Invitations
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Paste Bulk Data Modal -->
<div class="modal fade" id="bulkPasteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Paste Bulk Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Format:</strong> Name, Email, Mobile<br>
                    <strong>Example:</strong> John Doe, john@example.com, 9876543210
                </div>
                <textarea id="bulkPasteText" class="form-control" rows="10" placeholder="Paste comma or tab-separated data here..."></textarea>
                <small class="text-muted mt-2 d-block">Max 25 candidates allowed.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnProcessBulk">Process Data</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    let candidates = [];
    const MAX_CANDIDATES = 25;

    function renderTable() {
        const tbody = $('#candidateTableBody');
        const hiddenContainer = $('#hiddenInputsContainer');
        
        tbody.empty();
        hiddenContainer.empty();
        
        $('#candCount').text(candidates.length);
        
        if (candidates.length === 0) {
            tbody.append(`<tr id="emptyRow"><td colspan="5" class="text-center py-4 text-muted"><i class="ri-group-line fs-24 d-block mb-2"></i>No candidates added yet.</td></tr>`);
            $('#btnSubmitBulk').prop('disabled', true);
            return;
        }

        $('#btnSubmitBulk').prop('disabled', false);

        candidates.forEach((cand, index) => {
            // Table row
            tbody.append(`
                <tr>
                    <td>${index + 1}</td>
                    <td>${cand.name}</td>
                    <td>${cand.email}</td>
                    <td>${cand.mobile}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-soft-danger btn-remove" data-index="${index}">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
            `);

            // Hidden inputs for form submission
            hiddenContainer.append(`
                <input type="hidden" name="candidates[${index}][name]" value="${cand.name}">
                <input type="hidden" name="candidates[${index}][email]" value="${cand.email}">
                <input type="hidden" name="candidates[${index}][mobile]" value="${cand.mobile}">
            `);
        });
    }

    function addCandidate(name, email, mobile) {
        if (candidates.length >= MAX_CANDIDATES) {
            alert(`Maximum of ${MAX_CANDIDATES} candidates reached.`);
            return false;
        }
        
        if (!name || !email || !mobile) {
            alert('Name, Email, and Mobile are required.');
            return false;
        }
        
        // Basic email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Invalid email format for: ' + email);
            return false;
        }

        candidates.push({ name: name.trim(), email: email.trim(), mobile: mobile.trim() });
        return true;
    }

    $(document).ready(function() {
        // Quick Add Single
        $('#btnAddSingle').click(function() {
            const name = $('#quickName').val();
            const email = $('#quickEmail').val();
            const mobile = $('#quickMobile').val();
            
            if (addCandidate(name, email, mobile)) {
                renderTable();
                $('#quickName, #quickEmail, #quickMobile').val('');
            }
        });

        // Remove Candidate
        $(document).on('click', '.btn-remove', function() {
            const index = $(this).data('index');
            candidates.splice(index, 1);
            renderTable();
        });

        // Process Bulk Paste
        $('#btnProcessBulk').click(function() {
            const text = $('#bulkPasteText').val();
            const lines = text.split('\n');
            let addedCount = 0;
            
            lines.forEach(line => {
                if (!line.trim()) return;
                
                // Try comma or tab separator
                let parts = line.split(',');
                if (parts.length < 3) parts = line.split('\t');
                
                if (parts.length >= 3) {
                    const name = parts[0];
                    const email = parts[1];
                    const mobile = parts[2];
                    
                    if (addCandidate(name, email, mobile)) {
                        addedCount++;
                    }
                }
            });
            
            if (addedCount > 0) {
                renderTable();
                $('#bulkPasteText').val('');
                $('#bulkPasteModal').modal('hide');
            } else {
                alert('No valid data found or format was incorrect. Ensure Name, Email, Mobile are separated by commas or tabs.');
            }
        });
    });
</script>
@endsection
