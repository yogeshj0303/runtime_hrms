<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Onboarding Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f3f9;
            font-family: 'Inter', sans-serif;
        }
        .portal-header {
            background: linear-gradient(135deg, #405189 0%, #0ab39c 100%);
            padding: 40px 0;
            color: white;
            margin-bottom: -50px;
        }
        .card {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-radius: 12px;
            margin-bottom: 24px;
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #f0f0f0;
            padding: 20px 24px;
            font-weight: 600;
        }
        .card-body {
            padding: 24px;
        }
    </style>
</head>
<body>

    <div class="portal-header text-center">
        <h2>Welcome to Your Onboarding Portal</h2>
        <p class="opacity-75">Please complete the following details to finish your onboarding process.</p>
    </div>

    <div class="container pb-5" style="position: relative; z-index: 10;">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                @if(session('success'))
                    <div class="alert alert-success shadow-sm mb-4">
                        <i class="ri-check-line me-2"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if($form->status == 'Approved')
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="display-1 text-success mb-3"><i class="ri-checkbox-circle-fill"></i></div>
                            <h3>Onboarding Completed</h3>
                            <p class="text-muted">You have successfully submitted your details. HR will be in touch shortly.</p>
                        </div>
                    </div>
                @else

                <form action="{{ route('candidate.onboarding.submit', $form->token) }}" method="POST">
                    @csrf
                    
                    <!-- Candidate Info -->
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <i class="ri-user-line fs-20 me-2 text-primary"></i> 1. Personal Details
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Name</label>
                                    <div class="fw-medium">{{ $form->employee->first_name }} {{ $form->employee->last_name }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-muted small">Email</label>
                                    <div class="fw-medium">{{ $form->employee->email }}</div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <label class="form-label">Complete Address <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" rows="3" required placeholder="Enter your full residential address">{{ $form->employee->address }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Identity -->
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <i class="ri-profile-line fs-20 me-2 text-primary"></i> 2. Identity Verification
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Identity Document Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="identity_type" required>
                                        <option value="">Select Document</option>
                                        <option value="Aadhaar">Aadhaar Card</option>
                                        <option value="PAN">PAN Card</option>
                                        <option value="Passport">Passport</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Document Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="identity_number" required placeholder="Enter document number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Policies & Offer -->
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <i class="ri-file-text-line fs-20 me-2 text-primary"></i> 3. Documents & Acknowledgement
                        </div>
                        <div class="card-body">
                            
                            @if(isset($form->part_c_data['offer_letter']))
                            <div class="d-flex align-items-center p-3 bg-light rounded border mb-4">
                                <i class="ri-file-pdf-fill fs-24 text-danger me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">Your Offer Letter</h6>
                                    <small class="text-muted">Please download and review</small>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary">Download</a>
                            </div>
                            @endif

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" id="ack1" required>
                                <label class="form-check-label" for="ack1">
                                    I acknowledge that I have read and agree to the company policies.
                                </label>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="ack2" required>
                                <label class="form-check-label" for="ack2">
                                    I declare that the information provided above is true and correct.
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 py-2 fs-16">Submit Onboarding Details</button>
                        </div>
                    </div>
                </form>
                @endif
                
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
