@extends('admin.employee.profile.layout')

@section('profile_title', 'Addresses')
@section('profile_description', "Keep record of employee's present and permanent address")

@section('profile_actions')
<button type="button" class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addressModal" onclick="resetAddressForm()">
    <i class="ri-add-line"></i> Add Address
</button>
@endsection

@section('profile_content')

@if($employee->addresses->count() == 0)
<div class="text-center text-muted p-5 mt-4">
    <i class="ri-mailbox-line" style="font-size: 64px; color: #cbd5e1;"></i>
    <p class="mt-3 fw-bold text-secondary">No addresses saved</p>
</div>
@else
<div class="row">
    @foreach($employee->addresses as $address)
    <div class="col-md-6 mb-3">
        <div class="card shadow-none border h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="badge bg-light text-dark border">{{ $address->type }}</span>
                    <div>
                        <a href="javascript:void(0)" class="text-primary me-2" onclick="editAddress({{ $address }})"><i class="ri-pencil-line"></i></a>
                        <a href="javascript:void(0)" class="text-danger" onclick="confirmDelete(event, 'delete-address-{{ $address->id }}', 'Are you sure you want to delete this address?');"><i class="ri-delete-bin-line"></i></a>
                        <form id="delete-address-{{ $address->id }}" action="{{ route('employee.profile.address.destroy', $address->id) }}" method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
                <p class="mb-1 text-dark fs-6">{{ $address->address1 }} {{ $address->address2 }}</p>
                <p class="mb-0 text-muted small">{{ $address->city }}, {{ $address->state }} {{ $address->zipcode }}</p>
                <p class="mb-0 text-muted small">{{ $address->country }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

<!-- Address Modal -->
<div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h6 class="modal-title fw-bold" id="addressModalLabel">Update Address</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employee.profile.address.store') }}" method="POST" id="addressForm">
                @csrf
                <input type="hidden" name="_method" value="POST" id="addressMethod">
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <div class="modal-body">
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4"><label class="mb-0 fs-6">Address Type <span class="text-danger">*</span></label></div>
                        <div class="col-md-8">
                            <select name="type" id="type" class="form-select form-select-sm" required>
                                <option value="Permanent">Permanent</option>
                                <option value="Current">Current</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Work">Work</option>
                                <option value="Temporary">Temporary</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4">
                            <label class="mb-0 fs-6">Address Line 1 <span class="text-danger">*</span></label>
                            <br>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1" onclick="getCurrentLocation()" id="locateBtn" style="font-size: 0.75rem;">
                                <i class="ri-map-pin-2-line"></i> Locate Me
                            </button>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="address1" id="address1" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4"><label class="mb-0 fs-6">Address Line 2</label></div>
                        <div class="col-md-8">
                            <input type="text" name="address2" id="address2" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4"><label class="mb-0 fs-6">City & Pincode <span class="text-danger">*</span></label></div>
                        <div class="col-md-4">
                            <input type="text" name="city" id="city" class="form-control form-control-sm" placeholder="City" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="zipcode" id="zipcode" class="form-control form-control-sm" placeholder="Pincode">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4"><label class="mb-0 fs-6">State <span class="text-danger">*</span></label></div>
                        <div class="col-md-8">
                            <select name="state" id="state" class="form-select form-select-sm" required>
                                <option value="">- Select -</option>
                                @foreach($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4"><label class="mb-0 fs-6">Country</label></div>
                        <div class="col-md-8">
                            <input type="text" name="country" id="country" class="form-control form-control-sm" value="India">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-sm btn-light text-primary fw-bold px-3 border-0" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4"><i class="ri-save-line me-1"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function resetAddressForm() {
        document.getElementById('addressForm').reset();
        document.getElementById('addressForm').action = "{{ route('employee.profile.address.store') }}";
        document.getElementById('addressMethod').value = "POST";
        document.getElementById('addressModalLabel').innerText = "Add Address";
        document.getElementById('country').value = "India";
    }

    function editAddress(address) {
        document.getElementById('addressModalLabel').innerText = "Update Address";
        document.getElementById('addressForm').action = "/business/employee/profile/address/update/" + address.id;
        document.getElementById('addressMethod').value = "PUT";
        
        // Select matching type (case-insensitive)
        const typeSelect = document.getElementById('type');
        let matched = false;
        if (address.type) {
            for (let i = 0; i < typeSelect.options.length; i++) {
                if (typeSelect.options[i].value.toLowerCase() === address.type.toString().toLowerCase().trim()) {
                    typeSelect.selectedIndex = i;
                    matched = true;
                    break;
                }
            }
            if (!matched) {
                let opt = document.createElement('option');
                opt.value = address.type;
                opt.text = address.type;
                typeSelect.add(opt);
                typeSelect.value = address.type;
            }
        }
        
        document.getElementById('address1').value = address.address1 || '';
        document.getElementById('address2').value = address.address2 || '';
        document.getElementById('city').value = address.city || '';
        document.getElementById('zipcode').value = address.zipcode || '';
        
        // Match state
        const stateSelect = document.getElementById('state');
        if (address.state) {
            let stateMatched = false;
            for (let i = 0; i < stateSelect.options.length; i++) {
                if (stateSelect.options[i].value.toLowerCase() === address.state.toString().toLowerCase().trim()) {
                    stateSelect.selectedIndex = i;
                    stateMatched = true;
                    break;
                }
            }
            if (!stateMatched) {
                stateSelect.value = address.state;
            }
        } else {
            stateSelect.selectedIndex = 0;
        }

        document.getElementById('country').value = address.country || 'India';
        
        var addressModal = new bootstrap.Modal(document.getElementById('addressModal'));
        addressModal.show();
    }

    function getCurrentLocation() {
        if (navigator.geolocation) {
            const btn = document.getElementById('locateBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Locating...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;

                // Call OpenStreetMap Nominatim for free reverse geocoding
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=18&addressdetails=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.address) {
                            const addr = data.address;
                            
                            // Try to construct address line 1
                            let address1 = [];
                            if (addr.house_number) address1.push(addr.house_number);
                            if (addr.road) address1.push(addr.road);
                            if (addr.neighbourhood) address1.push(addr.neighbourhood);
                            if (addr.suburb) address1.push(addr.suburb);
                            
                            document.getElementById('address1').value = address1.join(', ') || data.display_name.split(',')[0];
                            
                            // City
                            document.getElementById('city').value = addr.city || addr.town || addr.village || addr.county || '';
                            
                            // Pincode
                            document.getElementById('zipcode').value = addr.postcode || '';
                            
                            // State
                            if (addr.state) {
                                // Try to match state in the dropdown
                                const stateSelect = document.getElementById('state');
                                for (let i = 0; i < stateSelect.options.length; i++) {
                                    if (stateSelect.options[i].text.toLowerCase() === addr.state.toLowerCase() || 
                                        stateSelect.options[i].text.toLowerCase().includes(addr.state.toLowerCase())) {
                                        stateSelect.selectedIndex = i;
                                        break;
                                    }
                                }
                            }
                            
                            // Country
                            document.getElementById('country').value = addr.country || 'India';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching address:', error);
                        alert('Could not fetch address details from coordinates.');
                    })
                    .finally(() => {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
            }, function(error) {
                console.error("Geolocation error:", error);
                alert("Please allow location access to use this feature.");
                const btn = document.getElementById('locateBtn');
                btn.innerHTML = '<i class="ri-map-pin-2-line"></i> Locate Me';
                btn.disabled = false;
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>
@endsection