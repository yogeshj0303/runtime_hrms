@extends('admin.employee.profile.layout')

@section('profile_title', 'Assets')
@section('profile_description', 'View or manage assets issued to the employee.')

@section('profile_actions')
<button class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addAssetModal">
    <i class="ri-add-line"></i> Add Asset
</button>
@endsection

@section('profile_content')

@if($employee->assets->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small">
                <tr>
                    <th>Asset Name</th>
                    <th>Type</th>
                    <th>Serial Number</th>
                    <th>Estimated Value</th>
                    <th>Issue Date</th>
                    <th>Warranty Expiry</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employee->assets as $asset)
                <tr>
                    <td class="fw-semibold text-dark">{{ $asset->asset_name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $asset->asset_type }}</span></td>
                    <td class="text-muted">{{ $asset->serial_number ?? '-' }}</td>
                    <td>{{ $asset->estimated_value ? '₹' . number_format($asset->estimated_value, 2) : '-' }}</td>
                    <td>{{ $asset->issue_date ? \Carbon\Carbon::parse($asset->issue_date)->format('d M Y') : '-' }}</td>
                    <td>
                        {{ $asset->expiry_date ? \Carbon\Carbon::parse($asset->expiry_date)->format('d M Y') : '-' }}
                        @if($asset->notify_on_expiry)
                            <span class="badge bg-soft-info text-info ms-1" title="Notify on Expiry"><i class="ri-notification-3-line"></i></span>
                        @endif
                    </td>
                    <td class="text-end">
                        <form action="{{ route('employee.profile.assets.destroy', ['id' => $employee->id, 'asset_id' => $asset->id]) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this asset?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-circle-delete" title="Delete Asset">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center text-muted p-5">
        <i class="ri-macbook-line" style="font-size: 54px; color: #cbd5e1;"></i>
        <h6 class="mt-3 fw-bold text-secondary">0 assets added</h6>
        <p class="mb-0 text-muted small">No assets currently issued to this employee.</p>
    </div>
@endif

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold fs-15">Add Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employee.profile.assets.store', ['id' => $employee->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Type of Asset <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="asset_type" class="form-select fs-13" required>
                                <option value="">- Select -</option>
                                <option value="Laptop">Laptop</option>
                                <option value="Desktop">Desktop</option>
                                <option value="Mobile Phone">Mobile Phone</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Tablet">Tablet</option>
                                <option value="Keyboard/Mouse">Keyboard/Mouse</option>
                                <option value="Vehicle">Vehicle</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Asset Name <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" name="asset_name" class="form-control fs-13" placeholder="e.g. Dell Inspiron Laptop" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Serial Number</label>
                        <div class="col-sm-8">
                            <input type="text" name="serial_number" class="form-control fs-13" placeholder="1234567890">
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Estimated Value</label>
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-text bg-light fs-13 border-end-0">₹</span>
                                <input type="number" step="0.01" name="estimated_value" class="form-control fs-13 border-start-0" placeholder="0">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Date of Issue</label>
                        <div class="col-sm-8">
                            <input type="date" name="issue_date" class="form-control fs-13" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Warranty Expiry</label>
                        <div class="col-sm-8">
                            <input type="date" name="expiry_date" class="form-control fs-13">
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-sm-8 offset-sm-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="notify_on_expiry" name="notify_on_expiry" value="1">
                                <label class="form-check-label text-muted fs-13" for="notify_on_expiry">Notify on Expiry <i class="ri-information-line text-primary ms-1"></i></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-medium" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save Asset</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection