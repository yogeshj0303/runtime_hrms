@extends('admin.employee.profile.layout')

@section('profile_title', 'Login & Access')
@section('profile_description', 'Manage employee access for mobile and web logins.')

@section('profile_actions')
<button type="submit" form="loginAccessForm" class="btn-hrms-crimson">
    <i class="ri-save-line"></i> Save Changes
</button>
@endsection

@section('profile_content')
@php
    $access = $employee->loginAccess;
    $business = $employee->business;
@endphp

<div class="row">
    <!-- Left Column: Settings Form -->
    <div class="col-md-5 mb-4 border-end pe-4">
        <form id="loginAccessForm" action="{{ route('employee.profile.login-access.update', ['id' => $employee->id]) }}" method="POST">
            @csrf
            
            <div class="row mb-2">
                <div class="col-5">
                    <span class="fs-13 text-muted">Company ID</span>
                </div>
                <div class="col-7">
                    <span class="fs-13 fw-bold">{{ $business->business_code ?? 'SOMYAH' }}</span>
                    <a href="#" class="fs-12 text-decoration-none ms-2">How to Change? <i class="ri-external-link-line"></i></a>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-5">
                    <span class="fs-13 text-muted">Employee ID</span>
                </div>
                <div class="col-7">
                    <span class="fs-13 fw-bold">{{ $employee->employee_code }}</span>
                </div>
            </div>
            
            <h6 class="fw-bold mb-3 fs-13">Mobile Login Options</h6>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="pin_never_expires" name="pin_never_expires" value="1" {{ $access && $access->pin_never_expires ? 'checked' : '' }}>
                <label class="form-check-label fs-13" for="pin_never_expires">PIN Never Expires <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
            
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" role="switch" id="multi_device" name="multi_device" value="1" {{ $access && $access->multi_device ? 'checked' : '' }}>
                <label class="form-check-label fs-13" for="multi_device">Multi-Device Logins <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
            
            <div class="d-flex gap-2 mb-2">
                <button type="button" class="btn btn-light btn-sm fs-12 px-3 border"><i class="ri-mail-send-line me-1"></i> Send Mobile Login</button>
                <button type="button" class="btn btn-light btn-sm fs-12 px-3 border"><i class="ri-refresh-line me-1"></i> Reset Mobile PIN</button>
            </div>
            
            <p class="fs-12 text-primary mb-4"><a href="#" class="text-decoration-none">Click here</a> to send login details to multiple employees</p>
            
            <h6 class="fw-bold mb-3 fs-13">Web Login Options</h6>
            
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" role="switch" id="web_login" name="web_login" value="1" {{ $access && $access->web_login ? 'checked' : '' }}>
                <label class="form-check-label fs-13" for="web_login">Allow Web Access</label>
            </div>
            
            <button type="button" class="btn btn-light border btn-sm fs-12 px-3 mb-2">Reset Web Password</button>
            
            <p class="fs-12 text-danger mb-4"><i class="ri-error-warning-line me-1"></i> Please update official email address to send web access details.</p>
            
            <h6 class="fw-bold mb-3 fs-13">Org Wall Access</h6>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="make_wall_admin" name="make_wall_admin" value="1" {{ $access && $access->make_wall_admin ? 'checked' : '' }}>
                <label class="form-check-label fs-13" for="make_wall_admin">Make Wall Admin</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="allow_wall_posting" name="allow_wall_posting" value="1" {{ $access && $access->allow_wall_posting ? 'checked' : '' }}>
                <label class="form-check-label fs-13" for="allow_wall_posting">Allow Wall Posting</label>
            </div>
            
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" role="switch" id="allow_wall_comments" name="allow_wall_comments" value="1" {{ $access && $access->allow_wall_comments ? 'checked' : '' }}>
                <label class="form-check-label fs-13" for="allow_wall_comments">Allow Wall Comments</label>
            </div>
            
            <div class="mt-4 border-top pt-3">
                <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save Changes</button>
            </div>
        </form>
    </div>
    
    <!-- Right Column: Login Sessions & Mobile Promo -->
    <div class="col-md-7 ps-4">
        
        <h6 class="fw-bold mb-3 fs-13">Login Sessions</h6>
        
        <div class="table-responsive bg-light rounded border mb-5">
            <table class="table table-hover align-middle mb-0 fs-13">
                <thead class="table-light text-muted small">
                    <tr>
                        <th>DEVICE</th>
                        <th>OS VER.</th>
                        <th>APP VER.</th>
                        <th>LAST SEEN</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-medium">SM-M346B (samsung)</td>
                        <td>Android 14 (SDK 34)</td>
                        <td>7.6.20 <i class="ri-smartphone-line text-primary ms-1"></i></td>
                        <td class="text-muted">30-Jun-2026 10:52:26 AM</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-soft-danger px-2 py-1 fs-12">Logout</button>
                        </td>
                    </tr>
                    <!-- Add more dynamic sessions here if available -->
                </tbody>
            </table>
        </div>
        
        <div class="row align-items-center mt-5">
            <div class="col-md-6 text-center position-relative">
                <img src="{{ asset('build/images/mobile-app-mockup.png') }}" onerror="this.src='https://placehold.co/200x400?text=Mobile+App'" alt="Mobile App" class="img-fluid" style="max-height: 350px;">
            </div>
            <div class="col-md-6 text-center">
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" style="height: 40px;">
                    <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="App Store" style="height: 40px;">
                </div>
                
                <div class="mb-4">
                    <p class="fs-12 text-muted fw-bold mb-1">Mobile App Download Link</p>
                    <a href="https://bms.link/s/sYspgf" class="fs-13 text-decoration-none">https://bms.link/s/sYspgf</a>
                    <div class="d-flex justify-content-center gap-2 mt-2">
                        <button class="btn btn-sm btn-success px-3 fs-12"><i class="ri-whatsapp-line me-1"></i> Share</button>
                        <button class="btn btn-sm btn-primary px-3 fs-12"><i class="ri-mail-line me-1"></i> Email</button>
                    </div>
                </div>
                
                <div>
                    <p class="fs-12 text-muted fw-bold mb-1">Web Portal Link</p>
                    <a href="https://app.runtimehrms.com" class="fs-13 text-decoration-none">https://app.runtimehrms.com <i class="ri-external-link-line ms-1"></i></a>
                    <div class="d-flex justify-content-center gap-2 mt-2">
                        <button class="btn btn-sm btn-success px-3 fs-12"><i class="ri-whatsapp-line me-1"></i> Share</button>
                        <button class="btn btn-sm btn-primary px-3 fs-12"><i class="ri-mail-line me-1"></i> Email</button>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</div>

@endsection