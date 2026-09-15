@extends('admin.employee.profile.layout')

@section('profile_title', 'Documents')
@section('profile_description', 'Add, download and manage employee documents.')

@section('profile_actions')
<button type="button" class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
    <i class="ri-add-line"></i> Add Document
</button>
@endsection

@section('profile_content')
<div class="row">
    <div class="col-lg-8 pe-lg-4 border-end">
        @if($employee->documents->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th>Document Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Visibility</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employee->documents as $doc)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs me-2">
                                        <div class="avatar-title bg-soft-primary text-primary rounded fs-16">
                                            <i class="ri-file-text-line"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="{{ asset($doc->document_file) }}" target="_blank" class="fw-semibold text-primary fs-13">{{ $doc->document_name }}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted small">{{ $doc->description ?? '-' }}</td>
                            <td>
                                @if($doc->status == 'verified')
                                    <span class="badge bg-success-subtle text-success border border-success">Verified</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($doc->is_hidden)
                                    <span class="badge bg-light text-muted border"><i class="ri-eye-off-line align-bottom me-1"></i> Hidden</span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info"><i class="ri-eye-line align-bottom me-1"></i> Visible</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ asset($doc->document_file) }}" target="_blank" class="btn btn-sm btn-light btn-icon me-1" title="View/Download">
                                    <i class="ri-download-2-line"></i>
                                </a>
                                <form action="{{ route('employee.profile.documents.destroy', $doc->id) }}" method="POST" class="d-inline-block" onsubmit="return confirmDelete(event, this, 'Are you sure you want to delete this document?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-circle-delete" title="Delete Document">
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
                <i class="ri-folder-open-line text-muted mb-2" style="font-size: 54px; color: #cbd5e1;"></i>
                <h6 class="mt-2 fw-bold text-secondary">No documents added</h6>
                <p class="mb-0 text-muted small">Upload documents to manage and view them securely.</p>
            </div>
        @endif
    </div>

    <!-- Sidebar / Guidelines -->
    <div class="col-lg-4 ps-lg-4 mt-4 mt-lg-0">
        <div class="p-3 bg-light rounded border">
            <h6 class="fw-bold mb-3 fs-14 text-dark">Document Upload Guidelines</h6>
            <ul class="text-muted small ps-3 mb-0" style="line-height: 1.8;">
                <li>Maximum file size allowed: 10 MB per document.</li>
                <li>File Types allowed: jpg, jpeg, png, pdf, doc, docx, xls, xlsx, txt.</li>
                <li>File name should not be more than 100 characters long.</li>
                <li>Maximum 50 documents can be uploaded for one employee.</li>
                <li>Hidden documents are not visible to employees on mobile and web app.</li>
            </ul>
        </div>
    </div>
</div>

<!-- Add Document Modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold fs-15" id="addDocumentModalLabel">Upload Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employee.profile.documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <input type="hidden" name="id" value="{{ $employee->id }}">
                    
                    <div class="mb-3">
                        <label for="document_file" class="form-label fs-13 text-muted">Select File <span class="text-danger">*</span></label>
                        <input type="file" class="form-control fs-13" id="document_file" name="document_file" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label fs-13 text-muted">Description</label>
                        <input type="text" class="form-control fs-13" id="description" name="description" placeholder="e.g. Previous Experience Letter">
                    </div>
                    
                    <div class="form-check form-switch mb-2 mt-4">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_hidden" name="is_hidden" value="1">
                        <label class="form-check-label fs-13 fw-semibold text-muted" for="is_hidden">Hidden</label>
                        <div class="text-muted small">If enabled, employee won't be able to see this document.</div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-medium" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save Document</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection