

<?php $__env->startSection('title'); ?>
Locations
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

<link rel="stylesheet" href="<?php echo e(asset('/assets/admin/css/business.css')); ?>">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="helpdesk-header">


<div class="breadcrumb-section">
    <span>Setup</span>
    <i class="ri-arrow-right-s-line"></i>
    <span>Master Setup</span>
    <i class="ri-arrow-right-s-line"></i>
    <span>Locations</span>
</div>

<div class="header-content">

    <div class="header-left">
        <h4>Locations</h4>
    </div>

    <div class="header-buttons">

        <a href="<?php echo e(route('locations.create')); ?>" class="btn-add">
            <i class="ri-add-line"></i>
            Add New
        </a>

    </div>

</div>


</div>

<?php if(session('success')): ?>

<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo e(session('success')); ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>
</div>
<?php endif; ?>

<div class="row">

    <?php $__empty_1 = true; $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0" style="background-color: <?php echo e($location->is_default ? '#eef2fb' : '#fff'); ?>; border-radius: 12px;">
            <div class="card-body text-center p-4 d-flex flex-column">
                <div class="mb-3">
                    <i class="ri-map-pin-line text-muted" style="font-size: 40px; opacity: 0.3;"></i>
                </div>
                <div class="mb-4">
                    <?php if($location->latitude && $location->longitude): ?>
                        <h6 class="mb-1 text-success"><i class="ri-map-pin-2-fill"></i> Map location set</h6>
                        <small class="text-muted"><?php echo e($location->latitude); ?>, <?php echo e($location->longitude); ?></small>
                    <?php else: ?>
                        <h6 class="mb-1">Map location not set.</h6>
                        <small class="text-muted">Click the green icon below to set.</small>
                    <?php endif; ?>
                </div>

                <div class="text-start mb-4">
                    <h5 class="fw-bold mb-1 text-uppercase" style="font-size: 15px;">
                        <?php echo e($location->name); ?> 
                        <?php if($location->is_default): ?> 
                            <span class="badge bg-dark ms-1 rounded-pill" style="font-size: 10px;">Default</span> 
                        <?php endif; ?>
                    </h5>
                    <div class="text-muted fs-13"><?php echo e($location->work_profiles_count ?? 0); ?> employee(s)</div>
                </div>

                <div class="d-flex justify-content-between text-start mb-4">
                    <div>
                        <div class="text-muted" style="font-size: 11px;">Location Head</div>
                        <div class="fw-medium text-dark" style="font-size: 13px;"><?php echo e($location->site_head ?? 'Not Defined'); ?></div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted" style="font-size: 11px;">Deputy Head</div>
                        <div class="fw-medium text-dark" style="font-size: 13px;"><?php echo e($location->deputy_head ?? 'Not Defined'); ?></div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-auto">
                    <div class="d-flex align-items-center bg-light rounded-pill px-3 py-2 shadow-sm" style="gap: 15px;">
                        <a href="<?php echo e(route('locations.edit', $location->id)); ?>" class="btn btn-sm btn-icon text-secondary p-0" title="Edit">
                            <i class="ri-pencil-fill fs-5"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon text-success p-0" title="Set Map Location" onclick="openMapModal(<?php echo e($location->id); ?>, '<?php echo e($location->latitude); ?>', '<?php echo e($location->longitude); ?>', '<?php echo e(addslashes($location->name)); ?>')">
                            <i class="ri-map-pin-user-fill fs-5"></i>
                        </button>
                        <form action="<?php echo e(route('locations.destroy', $location->id)); ?>" method="POST" class="m-0 p-0" onsubmit="return confirm('Are you sure you want to delete this Location?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-icon text-danger p-0" title="Delete">
                                <i class="ri-delete-bin-fill fs-5"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="col-12">
        <div class="alert alert-info text-center">No Locations Found</div>
    </div>
    <?php endif; ?>

</div>

<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title" id="mapModalLabel">Set Map Location - <span id="modalLocationName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-2 fs-13">Drag the marker or click on the map to set the location.</p>
        <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
        <input type="hidden" id="currentLocationId">
        <input type="hidden" id="currentLat">
        <input type="hidden" id="currentLng">
      </div>
      <div class="modal-footer border-top-0 pt-0">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="saveLocationBtn">
            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Save Location
        </button>
      </div>
    </div>
  </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
let map = null;
let marker = null;
let defaultLat = 22.7196; // Indore roughly
let defaultLng = 75.8577;

function openMapModal(id, lat, lng, name) {
    $('#currentLocationId').val(id);
    $('#modalLocationName').text(name);
    
    let initialLat = lat ? parseFloat(lat) : defaultLat;
    let initialLng = lng ? parseFloat(lng) : defaultLng;

    $('#currentLat').val(initialLat);
    $('#currentLng').val(initialLng);

    var myModal = new bootstrap.Modal(document.getElementById('mapModal'), {
        keyboard: false
    });
    myModal.show();
}

document.getElementById('mapModal').addEventListener('shown.bs.modal', function () {
    let lat = parseFloat($('#currentLat').val());
    let lng = parseFloat($('#currentLng').val());

    if (!map) {
        map = L.map('map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        // Update inputs on marker drag
        marker.on('dragend', function(e) {
            var position = marker.getLatLng();
            $('#currentLat').val(position.lat);
            $('#currentLng').val(position.lng);
        });

        // Update marker on map click
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            $('#currentLat').val(e.latlng.lat);
            $('#currentLng').val(e.latlng.lng);
        });
    } else {
        map.setView([lat, lng], 13);
        marker.setLatLng([lat, lng]);
        map.invalidateSize(); // Fix gray tiles issue
    }
});

$('#saveLocationBtn').click(function() {
    let id = $('#currentLocationId').val();
    let lat = $('#currentLat').val();
    let lng = $('#currentLng').val();
    let btn = $(this);
    
    btn.prop('disabled', true);
    btn.find('.spinner-border').removeClass('d-none');

    let updateUrl = "<?php echo e(route('locations.update_map', ':id')); ?>".replace(':id', id);

    $.ajax({
        url: updateUrl,
        type: 'POST',
        data: {
            _token: '<?php echo e(csrf_token()); ?>',
            latitude: lat,
            longitude: lng
        },
        success: function(response) {
            if(response.success) {
                location.reload();
            } else {
                alert('Failed to save location.');
                btn.prop('disabled', false);
                btn.find('.spinner-border').addClass('d-none');
            }
        },
        error: function(xhr) {
            console.error(xhr);
            alert('An error occurred while saving the location.');
            btn.prop('disabled', false);
            btn.find('.spinner-border').addClass('d-none');
        }
    });
});

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/setup/locations/index.blade.php ENDPATH**/ ?>