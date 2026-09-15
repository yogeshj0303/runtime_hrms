<?php $__env->startSection('profile_title', 'Basic Info'); ?>
<?php $__env->startSection('profile_description', "Manage core HR related employee information."); ?>

<?php $__env->startSection('profile_actions'); ?>
<a href="<?php echo e(route('employee.statement', ['id' => $employee->id])); ?>" class="btn-hrms-crimson me-2">
    <i class="ri-download-line"></i> Statement
</a>
<a href="javascript:void(0);" class="btn-help-read">
    <i class="ri-question-line"></i> Read Help
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('profile_content'); ?>
<form action="<?php echo e(route('employee.profile.basic.update', ['id' => $employee->id])); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="row mb-5 align-items-center">
        <div class="col-md-3 text-center">
            <div class="position-relative d-inline-block mb-2" onclick="document.getElementById('profile_photo').click()" style="cursor: pointer;">
                <?php if($employee->profile && $employee->profile->profile_photo): ?>
                    <img src="<?php echo e(asset('storage/' . $employee->profile->profile_photo)); ?>" class="rounded-circle" style="width: 86px; height: 86px; object-fit: cover;">
                <?php else: ?>
                    <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 86px; height: 86px; font-size: 32px; font-weight: 700; background-color: #bde7f4; color: #0e3d50; margin: 0 auto;">
                        <?php echo e(strtoupper(substr($employee->first_name ?? 'A', 0, 1) . substr($employee->last_name ?? 'Y', 0, 1))); ?>

                    </div>
                <?php endif; ?>
            </div>
            <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*">
            <div class="small text-muted" style="font-size: 11.5px;">Maximum File Size: 1 MB</div>
            <div class="small text-muted" style="font-size: 11.5px;"><i class="ri-mouse-line"></i> Click on the image to change</div>
        </div>
        <div class="col-md-9">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="font-size: 13px;">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" value="<?php echo e($employee->first_name); ?>">
                    <div class="form-text" style="font-size: 11.5px;">Name as per Aadhaar <br><span class="text-danger fw-bold">Not Verified</span></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="font-size: 13px;">Middle Name</label>
                    <input type="text" class="form-control" name="middle_name" value="<?php echo e($employee->middle_name ?? ''); ?>" placeholder="Middle Name">
                    <div class="form-text" style="font-size: 11.5px;">Name as per Bank <br><span class="text-dark fw-bold"><?php echo e(strtoupper($employee->first_name . ' ' . $employee->last_name)); ?></span></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="font-size: 13px;">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?php echo e($employee->last_name); ?>">
                    <div class="form-text" style="font-size: 11.5px;">Name as per PAN <br><span class="text-danger fw-bold">Not Verified</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Form Details -->
        <div class="col-md-8">
            <h6 class="mb-3 fw-bold border-bottom pb-2">Official Record</h6>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Date of Joining *</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="joining_date" value="<?php echo e($employee->profile?->joining_date ?? $employee->joining_date ? \Carbon\Carbon::parse($employee->profile?->joining_date ?? $employee->joining_date)->format('Y-m-d') : ''); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Date of Confirmation</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="confirmation_date" value="<?php echo e($employee->profile?->confirmation_date ?? ''); ?>">
                    <div class="form-text text-danger"><i class="ri-close-circle-line"></i> Unconfirm</div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Mobile Number</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="mobile_number" value="<?php echo e($employee->phone); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Office Phone</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="office_phone" value="<?php echo e($employee->profile?->office_phone ?? ''); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Official E-Mail</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" name="official_email" value="<?php echo e($employee->profile?->official_email ?? $employee->email); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Employee Code</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="employee_code" value="<?php echo e($employee->employee_code); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Biometric Code <i class="ri-information-line text-primary"></i></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="biometric_code" value="<?php echo e($employee->profile?->biometric_code ?? $employee->employee_code); ?>">
                </div>
            </div>

            <div class="row mb-4">
                <label class="col-sm-4 col-form-label">Notice Period</label>
                <div class="col-sm-4 d-flex align-items-center">
                    <input type="number" class="form-control me-2" name="notice_period" value="<?php echo e($employee->profile?->notice_period ?? 0); ?>">
                    <span>days</span>
                </div>
            </div>

            <h6 class="mb-3 fw-bold border-bottom pb-2">Personal Record</h6>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Date of Birth</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="dob" value="<?php echo e($employee->profile?->dob ?? ''); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Gender</label>
                <div class="col-sm-8 pt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" <?php echo e(($employee->profile?->gender ?? '') == 'Male' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="genderMale">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" <?php echo e(($employee->profile?->gender ?? '') == 'Female' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="genderFemale">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="genderTransgender" value="Transgender" <?php echo e(($employee->profile?->gender ?? '') == 'Transgender' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="genderTransgender">Transgender</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Marital Status</label>
                <div class="col-sm-8 pt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marital_status" id="maritalUnmarried" value="Unmarried" <?php echo e(($employee->profile?->marital_status ?? '') == 'Unmarried' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="maritalUnmarried">Unmarried</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marital_status" id="maritalMarried" value="Married" <?php echo e(($employee->profile?->marital_status ?? '') == 'Married' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="maritalMarried">Married</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="marital_status" id="maritalWidow" value="Widow" <?php echo e(($employee->profile?->marital_status ?? '') == 'Widow' ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="maritalWidow">Widow</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Personal E-Mail</label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" name="personal_email" value="<?php echo e($employee->profile?->personal_email ?? ''); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Personal Phone</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="personal_phone" value="<?php echo e($employee->profile?->personal_phone ?? ''); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Emergency Contact</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="emergency_contact" value="<?php echo e($employee->profile?->emergency_contact ?? ''); ?>">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary px-4">Save</button>
            </div>
        </div>

        <!-- Right Side Widgets -->
        <div class="col-md-4">
            <!-- Registered Face Widget -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body text-center">
                    <h6 class="card-title fw-bold">Registered Face</h6>
                    <div class="small text-muted mb-3">(For Selfie Punch)</div>
                    <?php if($employee->face_image): ?>
                        <img src="<?php echo e($employee->face_image); ?>" class="img-fluid rounded mb-3" style="max-height: 200px; object-fit: cover; border: 2px solid #ddd;">
                    <?php else: ?>
                        <div class="bg-light rounded mx-auto d-flex justify-content-center align-items-center mb-3" style="width: 150px; height: 200px; border: 2px dashed #ddd;">
                            <i class="ri-question-mark" style="font-size: 64px; color: #ccc;"></i>
                        </div>
                    <?php endif; ?>
                    <button type="button" id="btnOpenCamera" class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#cameraModal">
                        <i class="ri-camera-line"></i> Capture Live Face
                    </button>
                </div>
            </div>
            
            <!-- Tags Widget -->
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Tags <i class="ri-information-line text-primary"></i></h6>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#tagModal">+ Add New</button>
                </div>
                <div class="card-body">
                    <div id="tagsContainer">
                        <?php
                            $tags = $employee->tags ? json_decode($employee->tags, true) : [];
                        ?>
                        <?php if(empty($tags)): ?>
                            <div class="text-muted small" id="noTagsText">No tags assigned.</div>
                        <?php else: ?>
                            <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-primary me-1 mb-1 tag-badge" style="font-size: 14px;">
                                    <?php echo e($tag); ?> 
                                    <i class="ri-close-line ms-1" style="cursor: pointer;" onclick="removeTag('<?php echo e($tag); ?>')"></i>
                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Capture Live Face</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <video id="cameraStream" width="100%" autoplay playsinline style="border: 2px solid #ddd; border-radius: 8px;"></video>
                <canvas id="cameraCanvas" style="display:none;"></canvas>
                <img id="cameraPreview" class="img-fluid rounded mt-2" style="display:none; max-height: 300px; border: 2px solid #28a745;">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" id="btnRetake" style="display:none;">Retake</button>
                <button type="button" class="btn btn-primary" id="btnCapture">Capture Snapshot</button>
                <button type="button" class="btn btn-success" id="btnSaveFace" style="display:none;">Confirm & Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Tag Modal -->
<div class="modal fade" id="tagModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="newTagInput" class="form-control" placeholder="e.g. VIP, Temporary">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm w-100" id="btnSaveTag">Add Tag</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
    let video = document.getElementById('cameraStream');
    let canvas = document.getElementById('cameraCanvas');
    let preview = document.getElementById('cameraPreview');
    let btnCapture = document.getElementById('btnCapture');
    let btnRetake = document.getElementById('btnRetake');
    let btnSaveFace = document.getElementById('btnSaveFace');
    let btnOpenCamera = document.getElementById('btnOpenCamera');
    let stream = null;
    let base64Image = '';

    const cameraModal = document.getElementById('cameraModal');
    
    function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert("Camera API is not supported in this browser. Please use HTTPS or localhost.");
            return;
        }

        // Start Camera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (mediaStream) {
                stream = mediaStream;
                video.srcObject = mediaStream;
                video.play();
                video.style.display = 'block';
                preview.style.display = 'none';
                btnCapture.style.display = 'inline-block';
                btnRetake.style.display = 'none';
                btnSaveFace.style.display = 'none';
            })
            .catch(function (err) {
                alert("Camera access denied or unavailable: " + err.message);
            });
    }

    if(btnOpenCamera) {
        btnOpenCamera.addEventListener('click', function() {
            setTimeout(startCamera, 500); // Give modal time to open
        });
    }

    cameraModal.addEventListener('hidden.bs.modal', function () {
        // Stop Camera
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
    });

    btnCapture.addEventListener('click', function() {
        if(!stream || video.videoWidth === 0) {
            alert("Camera is not ready yet. Please wait or allow permissions.");
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        
        base64Image = canvas.toDataURL('image/jpeg');
        
        preview.src = base64Image;
        video.style.display = 'none';
        preview.style.display = 'inline-block';
        
        btnCapture.style.display = 'none';
        btnRetake.style.display = 'inline-block';
        btnSaveFace.style.display = 'inline-block';
    });

    btnRetake.addEventListener('click', function() {
        video.style.display = 'block';
        preview.style.display = 'none';
        
        btnCapture.style.display = 'inline-block';
        btnRetake.style.display = 'none';
        btnSaveFace.style.display = 'none';
    });

    btnSaveFace.addEventListener('click', function() {
        // AJAX Save
        btnSaveFace.disabled = true;
        btnSaveFace.innerHTML = 'Saving...';
        
        fetch("<?php echo e(route('employee.profile.face.update', ['id' => $employee->id])); ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ face_image_base64: base64Image })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status) {
                location.reload();
            } else {
                alert("Failed to save face.");
                btnSaveFace.disabled = false;
                btnSaveFace.innerHTML = 'Confirm & Save';
            }
        })
        .catch(error => {
            alert("An error occurred.");
            btnSaveFace.disabled = false;
            btnSaveFace.innerHTML = 'Confirm & Save';
        });
    });

    // Tags Logic
    let btnSaveTag = document.getElementById('btnSaveTag');
    let newTagInput = document.getElementById('newTagInput');
    let tagsContainer = document.getElementById('tagsContainer');
    const tagModalEl = document.getElementById('tagModal');
    let tagModal = null;
    if(tagModalEl) {
        tagModal = new bootstrap.Modal(tagModalEl);
    }

    btnSaveTag.addEventListener('click', function() {
        let tag = newTagInput.value.trim();
        if(!tag) return;

        btnSaveTag.disabled = true;
        btnSaveTag.innerHTML = 'Saving...';

        fetch("<?php echo e(route('employee.profile.tags.add', ['id' => $employee->id])); ?>", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ tag: tag })
        })
        .then(response => response.json())
        .then(data => {
            if(data.status) {
                renderTags(data.tags);
                newTagInput.value = '';
                tagModal.hide();
            } else {
                alert("Failed to add tag.");
            }
        })
        .catch(error => {
            alert("An error occurred.");
        })
        .finally(() => {
            btnSaveTag.disabled = false;
            btnSaveTag.innerHTML = 'Add Tag';
        });
    });

    window.removeTag = function(tag) {
        Swal.fire({
            title: 'Remove Tag?',
            text: 'Are you sure you want to remove the tag "' + tag + '"?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b83a4b',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger px-4 me-2',
                cancelButton: 'btn btn-light px-4 border'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("<?php echo e(route('employee.profile.tags.remove', ['id' => $employee->id])); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ tag: tag })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status) {
                        renderTags(data.tags);
                        showToast('Tag removed successfully.');
                    }
                });
            }
        });
    };

    function renderTags(tags) {
        if(tags.length === 0) {
            tagsContainer.innerHTML = '<div class="text-muted small" id="noTagsText">No tags assigned.</div>';
            return;
        }

        let html = '';
        tags.forEach(t => {
            html += `<span class="badge bg-primary me-1 mb-1 tag-badge" style="font-size: 14px;">
                        ${t} 
                        <i class="ri-close-line ms-1" style="cursor: pointer;" onclick="removeTag('${t}')"></i>
                    </span>`;
        });
        tagsContainer.innerHTML = html;
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/basic.blade.php ENDPATH**/ ?>