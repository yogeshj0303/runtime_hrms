@extends('layouts.master')

@section('title')
    Onboarding Form Settings
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
@endsection

@section('content')

<div class="helpdesk-header mb-4">
    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <a href="{{ route('onboarding.index') }}">Onboarding</a>
        <i class="ri-arrow-right-s-line"></i>
        <span>Settings</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Onboarding Form Requirements</h4>
            <p class="text-muted mb-0">Configure which fields are mandatory for candidates during self-onboarding.</p>
        </div>
    </div>
</div>

<div class="row">
    @foreach($settings as $category => $items)
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom pt-4 pb-3">
                <h5 class="mb-0"><i class="ri-folder-settings-line me-2 text-primary"></i> {{ $category }}</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach($items as $setting)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $labels[$setting->key] ?? $setting->key }}</h6>
                            <small class="text-muted">Make this field mandatory</small>
                        </div>
                        <div class="form-check form-switch form-switch-lg">
                            <input class="form-check-input setting-toggle" type="checkbox" role="switch" 
                                   data-key="{{ $setting->key }}" 
                                   id="switch_{{ $setting->key }}"
                                   {{ $setting->is_required ? 'checked' : '' }}>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    $(document).ready(function() {
        $('.setting-toggle').change(function() {
            var key = $(this).data('key');
            var isRequired = $(this).is(':checked') ? 1 : 0;
            
            $.ajax({
                url: "{{ route('onboarding.settings.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    key: key,
                    is_required: isRequired
                },
                success: function(response) {
                    if (response.success) {
                        Toastify({
                            text: "Setting saved successfully!",
                            duration: 3000,
                            close: true,
                            gravity: "top", 
                            position: "right", 
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        }).showToast();
                    }
                },
                error: function() {
                    Toastify({
                        text: "Error saving setting. Please try again.",
                        duration: 3000,
                        close: true,
                        gravity: "top", 
                        position: "right", 
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    }).showToast();
                }
            });
        });
    });
</script>
@endsection
