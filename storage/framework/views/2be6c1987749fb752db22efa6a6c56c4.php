<?php $__env->startSection('profile_title', 'Salary'); ?>
<?php $__env->startSection('profile_description', 'Manage employee salary, increment and revisions.'); ?>

<?php $__env->startSection('profile_content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-checkbox-circle-line me-1"></i> <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark" style="font-size: 17px;">Salary Revisions</h5>
        <div class="d-flex align-items-center gap-2">
            <a href="<?php echo e(route('employee.statement', ['id' => $employee->id])); ?>" class="btn-hrms-crimson"><i class="ri-file-download-line"></i> Download</a>
            <a href="<?php echo e(route('employee.profile.salary', ['id' => $employee->id, 'action' => 'add'])); ?>#salaryFormCard" class="btn-hrms-crimson"><i class="ri-add-circle-line"></i> Add Revision</a>
        </div>
    </div>
    <div class="card-body">
        <?php if($employee->salaryRevisions->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="text-muted small">
                        <tr>
                            <th style="font-weight: 600;">Effective From</th>
                            <th style="font-weight: 600;">Net Salary (In-Hand)</th>
                            <th style="font-weight: 600;">CTC</th>
                            <th style="font-weight: 600;">CTC</th>
                            <th class="text-end" style="font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $employee->salaryRevisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $revision): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-bottom">
                            <td class="fw-bold fs-14 text-dark"><?php echo e(strtoupper(\Carbon\Carbon::parse($revision->effective_from)->format('d-M-Y'))); ?></td>
                            <td class="fw-bold fs-14 text-dark"><?php echo e(number_format($revision->net_salary, 0)); ?> <span class="text-muted fs-12 fw-normal">/m</span></td>
                            <td class="fw-bold fs-14 text-dark"><?php echo e(number_format($revision->ctc, 0)); ?> <span class="text-muted fs-12 fw-normal">/m</span></td>
                            <td class="fw-bold fs-14 text-dark"><?php echo e(number_format($revision->ctc * 12, 0)); ?> <span class="text-muted fs-12 fw-normal">/y</span></td>
                            <td class="text-end">
                                <a href="<?php echo e(route('employee.profile.salary', ['id' => $employee->id, 'edit_id' => $revision->id])); ?>" class="btn-circle-edit me-1" title="Edit"><i class="ri-pencil-line"></i></a>
                                <form action="<?php echo e(route('employee.profile.salary.destroy', ['id' => $employee->id, 'revision_id' => $revision->id])); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'Are you sure you want to delete this salary revision?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-circle-delete" title="Delete"><i class="ri-delete-bin-line"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-muted py-3">No salary revisions found.</div>
        <?php endif; ?>
    </div>
</div>

<div class="card shadow-sm border-0" id="salaryFormCard">
    <?php 
        $editId = request('edit_id');
        $isAdd = request('action') === 'add';
        
        if ($editId) {
            $latestRev = $employee->salaryRevisions->firstWhere('id', $editId);
        } elseif (!$isAdd && $employee->salaryRevisions->count() > 0) {
            $latestRev = $employee->salaryRevisions->first();
        } else {
            $latestRev = null;
        }
        
        if ($latestRev) {
            $jsonFields = ['custom_components', 'salary_options', 'contributions', 'allowances'];
            foreach($jsonFields as $field) {
                if (is_string($latestRev->{$field})) {
                    $decoded = json_decode($latestRev->{$field}, true);
                    $latestRev->{$field} = is_array($decoded) ? $decoded : [];
                } elseif (!is_array($latestRev->{$field})) {
                    $latestRev->{$field} = [];
                }
            }
        }
    ?>
    <form action="<?php echo e($editId ? route('employee.profile.salary.update', ['id' => $employee->id, 'revision_id' => $editId]) : route('employee.profile.salary.store', ['id' => $employee->id])); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($editId): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
        
        <div class="card-header bg-white border-bottom py-3">
            <h6 class="mb-3 fw-bold text-primary"><?php echo e($editId ? 'Edit Salary Revision' : ($latestRev ? 'New Salary Revision (Cloned from Latest)' : 'New Salary Revision')); ?></h6>
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center">
                        <span class="fw-bold me-2">Effective From:</span>
                        <input type="month" name="effective_from" class="form-control form-control-sm" style="width: 150px;" required value="<?php echo e(($editId && $latestRev) ? \Carbon\Carbon::parse($latestRev->effective_from)->format('Y-m') : date('Y-m')); ?>">
                    </div>
                <div class="d-flex align-items-center">
                    <span class="fw-bold me-2">Structure:</span>
                    <select name="salary_structure_id" id="salary_structure_id" class="form-select form-select-sm select2" style="width: 200px;" required>
                        <option value="">Select Structure</option>
                        <?php $__currentLoopData = $salaryStructures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $structure): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($structure->id); ?>" <?php echo e(($latestRev && $latestRev->salary_structure_id == $structure->id) ? 'selected' : ''); ?>>
                                <?php echo e($structure->structure_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
                <button type="button" class="btn btn-secondary btn-sm" id="load_structure_btn"><i class="ri-loop-right-line me-1"></i> Load Structure</button>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row">
                <!-- Left Side: Amounts -->
                <div class="col-md-7 border-end pe-4">
                    
                    <div id="dynamic-components-container">
                        <?php if($latestRev && $latestRev->custom_components): ?>
                            <?php $__currentLoopData = $latestRev->custom_components; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp_id => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row mb-3 align-items-center dynamic-component-row">
                                    <label class="col-sm-5 col-form-label fw-bold component-name-label">Component</label>
                                    <div class="col-sm-3 text-muted small text-end">paid days</div>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control text-end dynamic-amount-input" name="custom_components[<?php echo e($comp_id); ?>]" value="<?php echo e($amount); ?>" data-comp-id="<?php echo e($comp_id); ?>">
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted" id="no-structure-msg">
                                <i class="ri-information-line fs-3"></i>
                                <p class="mb-0">Please select a salary structure to load components.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Hidden standard fields to preserve compatibility while transitioning -->
                    <input type="hidden" name="basic_salary" value="<?php echo e($latestRev->basic_salary ?? 0); ?>">
                    <input type="hidden" name="hra" value="<?php echo e($latestRev->hra ?? 0); ?>">
                    
                    <!-- Totals -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-8 col-form-label fw-bold text-dark fs-6">Gross Salary</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end fw-bold" name="gross_salary" value="<?php echo e($latestRev->gross_salary ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-8 col-form-label fw-bold text-dark fs-6">Total Salary</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end fw-bold" name="total_salary" value="<?php echo e($latestRev->total_salary ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <!-- Deductions -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">Professional Tax</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="pt" value="<?php echo e($latestRev->pt ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">ESI Deduction</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="esi" value="<?php echo e($latestRev->esi ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">PF Deduction</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="pf" value="<?php echo e($latestRev->pf ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">LWF Deduction</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="contributions[lwf_deduction]" value="<?php echo e($latestRev->contributions['lwf_deduction'] ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <!-- Net Salary -->
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-8 col-form-label fw-bold text-primary fs-6">Net Salary</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end fw-bold text-primary" name="net_salary" value="<?php echo e($latestRev->net_salary ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <!-- Employer Contributions -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">ESI Contribution</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="contributions[esi]" value="<?php echo e($latestRev->contributions['esi'] ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">PF Contribution</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="contributions[pf]" value="<?php echo e($latestRev->contributions['pf'] ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">Pension Contribution</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="contributions[pension]" value="<?php echo e($latestRev->contributions['pension'] ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">EDLI & Admin Charges</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="contributions[edli]" value="<?php echo e($latestRev->contributions['edli'] ?? 0); ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-8 col-form-label text-muted">LWF Contribution</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end bg-light" name="contributions[lwf_contribution]" value="<?php echo e($latestRev->contributions['lwf_contribution'] ?? 0); ?>" readonly>
                        </div>
                    </div>
                    
                    <!-- CTC -->
                    <div class="row mb-4 align-items-center bg-light p-2 rounded border">
                        <label class="col-sm-8 col-form-label fw-bold text-dark fs-6">Total CTC</label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control text-end fw-bold" name="ctc" value="<?php echo e($latestRev->ctc ?? 0); ?>" readonly>
                        </div>
                    </div>

                    <div class="text-muted small mb-4">Preview shown above. Please save changes if required.</div>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary px-4"><i class="ri-save-line me-1"></i> Save</button>
                        <button type="button" class="btn btn-secondary px-4"><i class="ri-calculator-line me-1"></i> Recalculate</button>
                    </div>
                </div>
                
                <!-- Right Side: Salary Options -->
                <div class="col-md-5 ps-4">
                    <div class="mb-0">
                        <h6 class="fw-bold mb-1">Salary Options</h6>
                        <div class="text-muted small mb-3">Select options for the selected salary revision.</div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_increment" id="is_increment" value="1" <?php echo e($latestRev && $latestRev->is_increment ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_increment">This is an Increment</label>
                        </div>
                    </div>
                    
                    <div class="border-bottom my-3 border-light border-2"></div>

                    <!-- ESI Options -->
                    <div class="mb-0">
                        <h6 class="fw-bold mb-3">Employee's State Insurance (ESI)</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[esi_do_not_deduct]" id="esi_do_not_deduct" value="1" <?php echo e(isset($latestRev->salary_options['esi_do_not_deduct']) && $latestRev->salary_options['esi_do_not_deduct'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="esi_do_not_deduct">DO NOT Deduct ESI</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[esi_above_ceiling]" id="esi_above_ceiling" value="1" <?php echo e(isset($latestRev->salary_options['esi_above_ceiling']) && $latestRev->salary_options['esi_above_ceiling'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="esi_above_ceiling">Deduct ESI Above Ceiling</label>
                        </div>
                    </div>
                    
                    <div class="border-bottom my-3 border-light border-2"></div>

                    <!-- PF Options -->
                    <div class="mb-0">
                        <h6 class="fw-bold mb-3">Provident Fund (PF)</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_do_not_deduct]" id="pf_do_not_deduct" value="1" <?php echo e(isset($latestRev->salary_options['pf_do_not_deduct']) && $latestRev->salary_options['pf_do_not_deduct'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="pf_do_not_deduct">DO NOT Deduct PF</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_no_pension]" id="pf_no_pension" value="1" <?php echo e(isset($latestRev->salary_options['pf_no_pension']) && $latestRev->salary_options['pf_no_pension'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="pf_no_pension">DO NOT Deduct Pension (PF)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_above_ceiling_employee]" id="pf_above_ceiling_employee" value="1" <?php echo e(isset($latestRev->salary_options['pf_above_ceiling_employee']) && $latestRev->salary_options['pf_above_ceiling_employee'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="pf_above_ceiling_employee">Deduct PF Above Ceiling (Employee)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_above_ceiling_employer]" id="pf_above_ceiling_employer" value="1" <?php echo e(isset($latestRev->salary_options['pf_above_ceiling_employer']) && $latestRev->salary_options['pf_above_ceiling_employer'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="pf_above_ceiling_employer">Deduct PF Above Ceiling (Employer)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[pf_on_gross]" id="pf_on_gross" value="1" <?php echo e(isset($latestRev->salary_options['pf_on_gross']) && $latestRev->salary_options['pf_on_gross'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="pf_on_gross">Deduct PF on Gross Salary</label>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="options[pf_extra]" id="pf_extra" value="1" <?php echo e(isset($latestRev->salary_options['pf_extra']) && $latestRev->salary_options['pf_extra'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="pf_extra">Deduct PF Extra Contribution <i class="ri-information-line text-primary"></i></label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <label class="mb-0 fs-6">Min. Deduction <i class="ri-information-line text-primary"></i></label>
                            <input type="number" class="form-control form-control-sm text-end" style="width: 120px;" name="options[pf_min_deduction]" value="<?php echo e($latestRev->salary_options['pf_min_deduction'] ?? 0); ?>">
                        </div>
                    </div>
                    
                    <div class="border-bottom my-3 border-light border-2"></div>

                    <!-- Professional Tax -->
                    <div class="mb-0">
                        <h6 class="fw-bold mb-3">Professional Tax</h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="options[pt_do_not_deduct]" id="pt_do_not_deduct" value="1" <?php echo e(isset($latestRev->salary_options['pt_do_not_deduct']) && $latestRev->salary_options['pt_do_not_deduct'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="pt_do_not_deduct">DO NOT Deduct <i class="ri-information-line text-primary"></i></label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="mb-0 fs-6">Tax State</label>
                            <div style="width: 65%;">
                                <select name="options[pt_state]" class="form-select form-select-sm select2">
                                    <option value="">Select State</option>
                                    <?php if(isset($statesData['states'])): ?>
                                        <?php $__currentLoopData = $statesData['states']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($state['state']); ?>" <?php echo e((isset($latestRev->salary_options['pt_state']) && $latestRev->salary_options['pt_state'] == $state['state']) ? 'selected' : ''); ?>><?php echo e($state['state']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-bottom my-3 border-light border-2"></div>

                    <!-- Income Tax (TDS) -->
                    <div class="mb-0">
                        <h6 class="fw-bold mb-3">Income Tax (TDS)</h6>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[tds_do_not_deduct]" id="tds_do_not_deduct" value="1" <?php echo e(isset($latestRev->salary_options['tds_do_not_deduct']) && $latestRev->salary_options['tds_do_not_deduct'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="tds_do_not_deduct">DO NOT Deduct <i class="ri-information-line text-primary"></i></label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="options[tds_metro]" id="tds_metro" value="1" <?php echo e(isset($latestRev->salary_options['tds_metro']) && $latestRev->salary_options['tds_metro'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="tds_metro">Metro City Resident (HRA Exemption)</label>
                        </div>
                    </div>
                    
                    <div class="border-bottom my-3 border-light border-2"></div>

                    <!-- LWF -->
                    <div class="mb-0">
                        <h6 class="fw-bold mb-3">Labour Welfare Fund (LWF)</h6>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="options[lwf_do_not_deduct]" id="lwf_do_not_deduct" value="1" <?php echo e(isset($latestRev->salary_options['lwf_do_not_deduct']) && $latestRev->salary_options['lwf_do_not_deduct'] ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="lwf_do_not_deduct">DO NOT Deduct <i class="ri-information-line text-primary"></i></label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="mb-0 fs-6">Tax State</label>
                            <div style="width: 65%;">
                                <select name="options[lwf_state]" id="lwf_state_select" class="form-select form-select-sm select2">
                                    <option value="">Select State</option>
                                    <?php if(isset($statesData['states'])): ?>
                                        <?php $__currentLoopData = $statesData['states']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($state['state']); ?>" <?php echo e((isset($latestRev->salary_options['lwf_state']) && $latestRev->salary_options['lwf_state'] == $state['state']) ? 'selected' : ''); ?>><?php echo e($state['state']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_script'); ?>
<!-- Ensure Bootstrap JS is loaded to make modal work if not in master layout -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calculateBtn = document.querySelector('button.btn-secondary.px-4');
    const loadStructureBtn = document.getElementById('load_structure_btn');
    const structureSelect = document.getElementById('salary_structure_id');
    const dynamicContainer = document.getElementById('dynamic-components-container');
    const noStructureMsg = document.getElementById('no-structure-msg');
    
    // Existing Outputs & Options
    const grossInput = document.querySelector('input[name="gross_salary"]');
    const totalInput = document.querySelector('input[name="total_salary"]');
    const pfInput = document.querySelector('input[name="pf"]');
    const esiInput = document.querySelector('input[name="esi"]');
    const ptInput = document.querySelector('input[name="pt"]');
    const netInput = document.querySelector('input[name="net_salary"]');
    const ctcInput = document.querySelector('input[name="ctc"]');
    
    const pfContInput = document.querySelector('input[name="contributions[pf]"]');
    const pensionContInput = document.querySelector('input[name="contributions[pension]"]');
    const edliContInput = document.querySelector('input[name="contributions[edli]"]');
    const esiContInput = document.querySelector('input[name="contributions[esi]"]');
    
    // Toggles
    const doNotDeductPf = document.getElementById('pf_do_not_deduct');
    const doNotDeductEsi = document.getElementById('esi_do_not_deduct');
    const doNotDeductPt = document.getElementById('pt_do_not_deduct');
    const doNotDeductPension = document.getElementById('pf_no_pension');
    
    const esiAboveCeiling = document.getElementById('esi_above_ceiling');
    const pfAboveCeilingEmployee = document.getElementById('pf_above_ceiling_employee');
    const pfAboveCeilingEmployer = document.getElementById('pf_above_ceiling_employer');
    const pfOnGross = document.getElementById('pf_on_gross');
    const pfMinDeduction = document.querySelector('input[name="options[pf_min_deduction]"]');

    let structureRules = [];
    
    // Statutory Settings from Backend
    const epfSetting = <?php echo json_encode($epfSetting, 15, 512) ?>;
    const epfComponentIds = <?php echo json_encode($epfComponentIds ?? [], 15, 512) ?>;
    const esiSetting = <?php echo json_encode($esiSetting, 15, 512) ?>;
    const esiComponentIds = <?php echo json_encode($esiComponentIds ?? [], 15, 512) ?>;
    const ptaxSetting = <?php echo json_encode($ptaxSetting, 15, 512) ?>;
    const ptaxSlabs = <?php echo json_encode($ptaxSlabs ?? [], 15, 512) ?>;
    const ptaxComponentIds = <?php echo json_encode($ptaxComponentIds ?? [], 15, 512) ?>;
    const lwfRules = <?php echo json_encode($lwfRules ?? [], 15, 512) ?>;
    
    // Select2 Init (Safe)
    if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
        $('.select2').select2({ theme: 'bootstrap-5', width: '100%' });
        $('#salary_structure_id').on('change', function() {
            if (!this.value) {
                if (dynamicContainer) dynamicContainer.innerHTML = '';
                if (noStructureMsg) noStructureMsg.style.display = 'block';
                structureRules = [];
                calculateSalary();
            }
        });
    } else if (structureSelect) {
        structureSelect.addEventListener('change', function() {
            if (!this.value) {
                if (dynamicContainer) dynamicContainer.innerHTML = '';
                if (noStructureMsg) noStructureMsg.style.display = 'block';
                structureRules = [];
                calculateSalary();
            }
        });
    }

    // Attach event listeners to toggles to trigger calculation on change
    const toggles = [
        doNotDeductPf, doNotDeductEsi, doNotDeductPt, doNotDeductPension,
        esiAboveCeiling, pfAboveCeilingEmployee, pfAboveCeilingEmployer, pfOnGross,
        pfMinDeduction, document.getElementById('tax_state_id')
    ];
    toggles.forEach(toggle => {
        if (toggle) {
            toggle.addEventListener('change', calculateSalary);
            toggle.addEventListener('input', calculateSalary);
        }
    });

    if (loadStructureBtn) {
        loadStructureBtn.addEventListener('click', function() {
            const structureId = structureSelect.value;
            if (!structureId) {
                alert('Please select a Salary Structure first.');
                return;
            }
            
            // Fetch via AJAX
            fetch(`<?php echo e(url('business/employee/profile/salary/structure')); ?>/${structureId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        renderDynamicComponents(data.structure.rules);
                    }
                })
                .catch(error => {
                    console.error('Error fetching structure:', error);
                    alert('Failed to load salary structure rules.');
                });
        });
    }
    
    function renderDynamicComponents(rules) {
        structureRules = rules.sort((a, b) => a.order_no - b.order_no);
        if (dynamicContainer) dynamicContainer.innerHTML = ''; // Clear container
        if (noStructureMsg) noStructureMsg.style.display = 'none';
        
        let html = '';
        rules.forEach(rule => {
            const compName = rule.component ? rule.component.name : 'Component';
            const compId = rule.component ? rule.component.id : rule.salary_component_id;
            
            html += `
            <div class="row mb-3 align-items-center dynamic-component-row">
                <label class="col-sm-5 col-form-label fw-bold component-name-label">${compName}</label>
                <div class="col-sm-3 text-muted small text-end">amount</div>
                <div class="col-sm-4">
                    <input type="number" class="form-control text-end dynamic-amount-input" name="custom_components[${compId}]" data-comp-id="${compId}" value="0">
                </div>
            </div>`;
        });
        
        if (dynamicContainer) dynamicContainer.innerHTML = html;
        
        document.querySelectorAll('.dynamic-amount-input').forEach(input => {
            input.addEventListener('input', calculateSalary);
        });
        
        calculateSalary();
    }

    function calculateSalary() {
        try {
            let gross = 0;
            let basic = 0;
            let hra = 0;
            let epfWage = 0;
            let esiWage = 0;
            let ptaxWage = 0;
            
            // Get all dynamic input values
            const inputs = document.querySelectorAll('.dynamic-amount-input');
            
            if (inputs.length > 0) {
                inputs.forEach(input => {
                    const val = parseFloat(input.value) || 0;
                    const compId = input.getAttribute('data-comp-id');
                    const compIdInt = parseInt(compId);
                    
                    const rule = structureRules.find(r => r.salary_component_id == compId);
                    if (rule && rule.component) {
                        const name = rule.component.name.toLowerCase();
                        if (name.includes('basic')) basic += val;
                        if (name.includes('hra') || name.includes('house rent')) hra += val;
                        
                        // Add to gross if it's an earning and not excluded
                        if (!rule.component.exclude_from_gross_salary) {
                            gross += val;
                        }
                    }
                    
                    // Add to specific statutory wages based on component mapping
                    if (epfComponentIds && (epfComponentIds.includes(compIdInt) || epfComponentIds.includes(compId))) epfWage += val;
                    if (esiComponentIds && (esiComponentIds.includes(compIdInt) || esiComponentIds.includes(compId))) esiWage += val;
                    if (ptaxComponentIds && (ptaxComponentIds.includes(compIdInt) || ptaxComponentIds.includes(compId))) ptaxWage += val;
                });
                
                // Update hidden standard fields
                const basicInput = document.querySelector('input[name="basic_salary"]');
                if (basicInput) basicInput.value = basic;
                
                const hraInput = document.querySelector('input[name="hra"]');
                if (hraInput) hraInput.value = hra;
                
                if (grossInput) grossInput.value = gross.toFixed(2);
                if (totalInput) totalInput.value = gross.toFixed(2);
            } else {
                // Fallback for old records without dynamic components
                if (grossInput) gross = parseFloat(grossInput.value) || 0;
                const basicInput = document.querySelector('input[name="basic_salary"]');
                if (basicInput) basic = parseFloat(basicInput.value) || 0;
                const hraInput = document.querySelector('input[name="hra"]');
                if (hraInput) hra = parseFloat(hraInput.value) || 0;
                
                // If no component mapping is used, assume all basic goes to EPF, and gross to ESI/PTAX
                epfWage = basic > 0 ? basic : gross * 0.5; // Rough fallback
                esiWage = gross;
                ptaxWage = gross;
                
                if (totalInput) totalInput.value = gross.toFixed(2);
            }
            
            // Overrides based on advanced toggles
            if (pfOnGross && pfOnGross.checked) {
                epfWage = gross;
            }
            
            // --- PF Calculation ---
            let pfDeduction = 0, pfCont = 0, pensionCont = 0, edliCont = 0;
            if (epfSetting && epfSetting.is_enabled && doNotDeductPf && !doNotDeductPf.checked && epfWage > 0) {
                let ceiling = parseFloat(epfSetting.wage_ceiling) || 15000;
                
                // Determine wage bases for employee and employer based on "Above Ceiling" toggles
                let empWageBase = epfWage;
                if (ceiling > 0 && (!pfAboveCeilingEmployee || !pfAboveCeilingEmployee.checked) && empWageBase > ceiling) {
                    empWageBase = ceiling;
                }
                
                let emprWageBase = epfWage;
                if (ceiling > 0 && (!pfAboveCeilingEmployer || !pfAboveCeilingEmployer.checked) && emprWageBase > ceiling) {
                    emprWageBase = ceiling;
                }
                
                // Pension is STRICTLY capped at ceiling in India by law, regardless of above ceiling options
                let pensionWageBase = epfWage;
                if (ceiling > 0 && pensionWageBase > ceiling) {
                    pensionWageBase = ceiling;
                }
                
                let empRate = parseFloat(epfSetting.employee_contribution_rate) || 12;
                let emprRate = parseFloat(epfSetting.employer_contribution_rate) || 12;
                let penRate = parseFloat(epfSetting.pension_contribution_rate) || 8.33;
                let edliRate = parseFloat(epfSetting.edli_contribution_rate) || 0.5;
                
                pfDeduction = (empWageBase * empRate) / 100;
                
                // Minimum Deduction Override
                if (pfMinDeduction) {
                    let minDed = parseFloat(pfMinDeduction.value) || 0;
                    if (minDed > pfDeduction) {
                        pfDeduction = minDed;
                    }
                }
                
                if (doNotDeductPension && doNotDeductPension.checked) {
                    pfCont = (emprWageBase * emprRate) / 100;
                    pensionCont = 0;
                } else {
                    pensionCont = (pensionWageBase * penRate) / 100;
                    // Employer PF is (Total Employer % of Employer Wage) minus (Pension % of Pension Wage)
                    let totalEmprAmount = (emprWageBase * emprRate) / 100;
                    pfCont = totalEmprAmount - pensionCont;
                    if (pfCont < 0) pfCont = 0;
                }
                
                // EDLI is calculated on pension wage base (capped)
                edliCont = (pensionWageBase * edliRate) / 100;
            }
            
            // --- ESI Calculation ---
            let esiDeduction = 0, esiCont = 0;
            if (esiSetting && esiSetting.is_enabled && doNotDeductEsi && !doNotDeductEsi.checked && esiWage > 0) {
                let ceiling = parseFloat(esiSetting.gross_wage_ceiling) || 21000;
                let shouldDeduct = (gross <= ceiling) || (esiAboveCeiling && esiAboveCeiling.checked);
                
                if (shouldDeduct) {
                    let empRate = parseFloat(esiSetting.employee_contribution) || 0.75;
                    let emprRate = parseFloat(esiSetting.employer_contribution) || 3.25;
                    
                    // ESI is calculated on Gross (or ESI Wage mapping), uncapped! (The ceiling only decides eligibility, not capping)
                    esiDeduction = (esiWage * empRate) / 100;
                    esiCont = (esiWage * emprRate) / 100;
                }
            }
            
            // --- PTAX Calculation ---
            let ptDeduction = 0;
            const ptStateSelect = document.querySelector('select[name="options[pt_state]"]');
            
            if (ptaxSetting && ptaxSetting.is_enabled && ptaxSlabs && ptaxSlabs.length > 0 && doNotDeductPt && !doNotDeductPt.checked && ptaxWage > 0 && ptStateSelect && ptStateSelect.value) {
                const applicableSlab = ptaxSlabs.find(slab => 
                    slab.state === ptStateSelect.value &&
                    ptaxWage >= parseFloat(slab.salary_from) && 
                    ptaxWage <= parseFloat(slab.salary_to)
                );
                if (applicableSlab) {
                    ptDeduction = parseFloat(applicableSlab.tax_amount) || 0;
                }
            }
            
            // --- LWF Calculation ---
            let lwfDeductionVal = 0;
            let lwfContVal = 0;
            const doNotDeductLwf = document.getElementById('lwf_do_not_deduct');
            const lwfStateSelect = document.getElementById('lwf_state_select');
            
            if (doNotDeductLwf && !doNotDeductLwf.checked && lwfStateSelect && lwfStateSelect.value && gross > 0) {
                const rule = lwfRules ? lwfRules.find(r => r.state === lwfStateSelect.value) : null;
                if (rule) {
                    let lwfWage = gross; 
                    if (rule.salary_limit && rule.salary_limit > 0 && lwfWage > rule.salary_limit) {
                        lwfWage = rule.salary_limit;
                    }
                    
                    if (rule.employee_contribution_type === 'Fixed Amount') {
                        lwfDeductionVal = parseFloat(rule.employee_contribution_amount) || 0;
                    } else {
                        lwfDeductionVal = (lwfWage * (parseFloat(rule.employee_contribution_rate) || 0)) / 100;
                    }
                    
                    if (rule.employer_contribution_type === 'Fixed Amount') {
                        lwfContVal = parseFloat(rule.employer_contribution_amount) || 0;
                    } else {
                        lwfContVal = (lwfWage * (parseFloat(rule.employer_contribution_rate) || 0)) / 100;
                    }
                }
            }
            
            // Apply to UI
            if (pfInput) pfInput.value = Math.round(pfDeduction);
            if (esiInput) esiInput.value = Math.ceil(esiDeduction); // ESI is typically rounded up to next rupee
            if (ptInput) ptInput.value = Math.round(ptDeduction);
            const lwfDeductionInput = document.querySelector('input[name="contributions[lwf_deduction]"]');
            if (lwfDeductionInput) lwfDeductionInput.value = Math.round(lwfDeductionVal);
            
            if (pfContInput) pfContInput.value = Math.round(pfCont);
            if (pensionContInput) pensionContInput.value = Math.round(pensionCont);
            if (edliContInput) edliContInput.value = Math.round(edliCont);
            if (esiContInput) esiContInput.value = Math.ceil(esiCont);
            const lwfContInput = document.querySelector('input[name="contributions[lwf_contribution]"]');
            if (lwfContInput) lwfContInput.value = Math.round(lwfContVal);
            
            const totalDeductions = Math.round(pfDeduction) + Math.ceil(esiDeduction) + Math.round(ptDeduction) + Math.round(lwfDeductionVal);
            const net = gross - totalDeductions;
            if (netInput) netInput.value = net.toFixed(2);
            
            const ctc = gross + Math.round(pfCont) + Math.round(pensionCont) + Math.round(edliCont) + Math.ceil(esiCont) + Math.round(lwfContVal);
            if (ctcInput) ctcInput.value = ctc.toFixed(2);
            
        } catch (e) {
            console.error("Salary Calculation Error: ", e);
        }
    }
    
    // Bind to all recalculate buttons
    document.querySelectorAll('button').forEach(btn => {
        if (btn.innerText.includes('Recalculate')) {
            btn.addEventListener('click', calculateSalary);
        }
    });
    
    // Indestructible Event Delegation for all inputs
    document.body.addEventListener('change', function(e) {
        if (e.target.matches('input[type="checkbox"], select')) {
            calculateSalary();
        }
    });
    
    // Bridge jQuery change events from Select2 to our engine
    if (typeof $ !== 'undefined') {
        $(document.body).on('change', 'select.select2', function() {
            calculateSalary();
        });
    }
    
    document.body.addEventListener('input', function(e) {
        if (e.target.matches('.dynamic-amount-input, input[name="options[pf_min_deduction]"]')) {
            calculateSalary();
        }
    });
    
    // Initial calculate to update UI on page load
    setTimeout(calculateSalary, 100);
    
    // Optionally fetch structure rules on load if in edit mode so structureRules is populated for basic calculation
    const editStructureId = structureSelect ? structureSelect.value : null;
    if (editStructureId) {
        fetch(`<?php echo e(url('business/employee/profile/salary/structure')); ?>/${editStructureId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    structureRules = data.structure.rules;
                    
                    // Update the labels for cloned components
                    document.querySelectorAll('.dynamic-component-row').forEach(row => {
                        const input = row.querySelector('.dynamic-amount-input');
                        if (input) {
                            const compId = input.getAttribute('data-comp-id');
                            const rule = structureRules.find(r => r.salary_component_id == compId);
                            if (rule && rule.component) {
                                const label = row.querySelector('.component-name-label');
                                if (label) label.textContent = rule.component.name;
                                const subtext = row.querySelector('.text-muted');
                                if (subtext) subtext.textContent = 'amount';
                            }
                        }
                    });
                    
                    calculateSalary();
                }
            })
            .catch(err => console.error(err));
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/salary.blade.php ENDPATH**/ ?>