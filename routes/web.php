<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Employee\ProfileController;
use App\Http\Controllers\Employee\WorkProfileController;
use App\Http\Controllers\Employee\SalaryController;
use App\Http\Controllers\Employee\PolicyController;
use App\Http\Controllers\Employee\EmployeePolicyController;
use App\Http\Controllers\Employee\IdentityController;
use App\Http\Controllers\Employee\AddressController;
use App\Http\Controllers\Employee\DocumentController;
use App\Http\Controllers\Employee\AssetController;
use App\Http\Controllers\Employee\FamilyController;
use App\Http\Controllers\Employee\PermissionController;
use App\Http\Controllers\Employee\LoginController;
use App\Http\Controllers\Employee\AdditionalInfoController;
use App\Http\Controllers\Employee\ActivityLogController;
use App\Http\Controllers\Employee\OnboardingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Setup\Master\BusinessUnitController;
use App\Http\Controllers\Setup\Master\LocationController;
use App\Http\Controllers\Setup\Master\CostCenterController;
use App\Http\Controllers\Setup\Master\DepartmentController;
use App\Http\Controllers\Setup\Master\GradeController;
use App\Http\Controllers\Setup\Master\DesignationController;
use App\Http\Controllers\Setup\Master\VisitTypeController;
use App\Http\Controllers\Setup\Master\HelpdeskCategoryController;
use App\Http\Controllers\Setup\Master\WorkflowController;
use App\Http\Controllers\Setup\Master\ExitReasonController;
use App\Http\Controllers\Setup\Attendance\ShiftController;
use App\Http\Controllers\Setup\Attendance\TimeRuleController;
use App\Http\Controllers\Setup\Attendance\WeekOffPolicyController;
use App\Http\Controllers\Setup\Attendance\AttendanceSettingController;
use App\Http\Controllers\Setup\Attendance\LeaveTypeController;
use App\Http\Controllers\Setup\Attendance\LeavePolicyController;
use App\Http\Controllers\Setup\Attendance\HolidayController;
use App\Http\Controllers\Setup\Attendance\CompOffRuleController;
use App\Http\Controllers\Setup\Attendance\StrikeRuleController;
use App\Http\Controllers\Setup\Attendance\MaternityLeavePolicyController;
use App\Http\Controllers\Setup\Salary\SalaryComponentController;
use App\Http\Controllers\Setup\Salary\SalaryDeductionController;
use App\Http\Controllers\Setup\Salary\SalaryStructureController;
use App\Http\Controllers\Setup\Salary\OvertimeController;
use App\Http\Controllers\Setup\Salary\SalaryClaimController;
use App\Http\Controllers\Setup\Statutory\EsiSettingController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
|--------------------------------------------------------------------------
| Reports Main
|--------------------------------------------------------------------------
*/

// Candidate Public Onboarding Portal
Route::get('/candidate/onboarding/{token}', [\App\Http\Controllers\CandidateOnboardingController::class, 'verifyToken'])->name('candidate.onboarding');
Route::post('/candidate/onboarding/{token}', [\App\Http\Controllers\CandidateOnboardingController::class, 'submit'])->name('candidate.onboarding.submit');

Route::get('/report', function () {
    return view('admin.report.index');
})->name('report');

/*
|--------------------------------------------------------------------------
| Salary Reports
|--------------------------------------------------------------------------
*/

Route::get('/salary_summary', function () {
    return view('admin.report.salary_summary.index');
})->name('salary_summary');

Route::get('/salary_register', function () {
    return view('admin.report.salary_register.index');
})->name('salary_register');

Route::get('/salary_slip', function () {
    return view('admin.report.salary_slip.index');
})->name('salary_slip');

Route::get('/overtime_register', function () {
    return view('admin.report.overtime_register.index');
})->name('overtime_register');

Route::get('/cost_to_company', [\App\Http\Controllers\Report\EmployeeReportController::class, 'costToCompany'])->name('cost_to_company');

Route::get('/variable_salary', function () {
    return view('admin.report.variable_salary.index');
})->name('variable_salary');

Route::get('/time_salary', function () {
    return view('admin.report.time_salary.index');
})->name('time_salary');

Route::get('/rate_salary', function () {
    return view('admin.report.rate_salary.index');
})->name('rate_salary');

Route::get('/leave_encashment', function () {
    return view('admin.report.leave_encashment.index');
})->name('leave_encashment');

Route::get('/statutory_bonus', function () {
    return view('admin.report.statutory_bonus.index');
})->name('statutory_bonus');

Route::get('/salary_deductions', function () {
    return view('admin.report.salary_deductions.index');
})->name('salary_deductions');

Route::get('/time_deductions', function () {
    return view('admin.report.time_deductions.index');
})->name('time_deductions');

Route::get('/employee_loans', [\App\Http\Controllers\Report\EmployeeReportController::class, 'employeeLoans'])->name('employee_loans');

Route::get('/sap_export', function () {
    return view('admin.report.sap_export.index');
})->name('sap_export');

/*
|--------------------------------------------------------------------------
| Attendance Reports
|--------------------------------------------------------------------------
*/

Route::get('/attendance_register', [\App\Http\Controllers\Report\AttendanceReportController::class, 'attendanceRegister'])->name('attendance_register');

Route::get('/leave_register', function () {
    return view('admin.report.leave_register.index');
})->name('leave_register');

Route::get('/time_register', function () {
    return view('admin.report.time_register.index');
})->name('time_register');

Route::get('/time_rules_register', function () {
    return view('admin.report.time_rules_register.index');
})->name('time_rules_egister');

Route::get('/strike_register', function () {
    return view('admin.report.strike_register.index');
})->name('strike_register');

Route::get('/daily_attendance', function () {
    return view('admin.report.daily_attendance.index');
})->name('daily_attendance');

Route::get('/daily_punches', function () {
    return view('admin.attendance.daily_punches.index');
})->name('daily_punches');

Route::get('/punch_details', function () {
    return view('admin.report.punch_details.index');
})->name('punch_details');

Route::get('/manual_updates', function () {
    return view('admin.report.manual_updates.index');
})->name('manual_updates');

/*
|--------------------------------------------------------------------------
| Employee Reports
|--------------------------------------------------------------------------
*/

Route::get('/employee_register', [\App\Http\Controllers\Report\EmployeeReportController::class, 'employeeRegister'])->name('employee_register');

Route::get('/employee_addresses', [\App\Http\Controllers\Report\EmployeeReportController::class, 'employeeAddresses'])->name('employee_addresses');

Route::get('/employee_event', [\App\Http\Controllers\Report\EmployeeReportController::class, 'employeeEvents'])->name('employee_event');

Route::get('/promotion_ageing', function () {
    return view('admin.report.promotion_ageing.index');
})->name('promotion_ageing');

Route::get('/increment_ageing', [\App\Http\Controllers\Report\EmployeeReportController::class, 'incrementAgeing'])->name('increment_ageing');

Route::get('/employee_joinings', [\App\Http\Controllers\Report\EmployeeReportController::class, 'employeeJoinings'])->name('employee_joinings');

Route::get('/employee_exits', [\App\Http\Controllers\Report\EmployeeReportController::class, 'employeeExits'])->name('employee_exits');

Route::get('/workman_status', function () {
    return view('admin.report.workman_status.index');
})->name('workman_status');

Route::get('/employee_assets', [\App\Http\Controllers\Report\EmployeeReportController::class, 'employeeAssets'])->name('employee_assets');

Route::get('/employee_relatives', [\App\Http\Controllers\Report\EmployeeReportController::class, 'employeeRelatives'])->name('employee_relatives');

Route::get('/inactive_employees', [\App\Http\Controllers\Report\EmployeeReportController::class, 'inactiveEmployees'])->name('inactive_employees');

Route::get('/export_records', [\App\Http\Controllers\Report\EmployeeReportController::class, 'exportRecords'])->name('export_records');

/*
|--------------------------------------------------------------------------
| Statutory Reports
|--------------------------------------------------------------------------
*/

Route::get('/esi_deduction', [\App\Http\Controllers\Report\StatutoryReportController::class, 'esiDeduction'])->name('esi_deduction');

Route::get('/esi_coverage', [\App\Http\Controllers\Report\StatutoryReportController::class, 'esiCoverage'])->name('esi_coverage');


Route::prefix('setup/salary')
    ->middleware(['auth'])
    ->group(function () {

    /*
|--------------------------------------------------------------------------
| Overtime Module
|--------------------------------------------------------------------------
*/

Route::get(
    '/overtime',
    [OvertimeController::class, 'index']
)->name(
    'overtime.index'
);


Route::post(
    '/overtime/policy/store',
    [OvertimeController::class, 'storePolicy']
)->name(
    'overtime.policy.store'
);


Route::put(
    '/overtime/policy/{id}/update',
    [OvertimeController::class, 'updatePolicy']
)->name(
    'overtime.policy.update'
);


Route::delete(
    '/overtime/policy/{id}/delete',
    [OvertimeController::class, 'deletePolicy']
)->name(
    'overtime.policy.delete'
);


Route::post(
    '/overtime/rule/{policyId}/store',
    [OvertimeController::class, 'storeRule']
)->name(
    'overtime.rule.store'
);


Route::delete(
    '/overtime/rule/{id}/delete',
    [OvertimeController::class, 'deleteRule']
)->name(
    'overtime.rule.delete'
);

    Route::get(
            '/structures',
            [SalaryStructureController::class, 'index']
        )->name('salary.structures.index');


        Route::post(
            '/structures/store',
            [SalaryStructureController::class, 'store']
        )->name('salary.structures.store');


        Route::get(
            '/structure/{id}',
            [SalaryStructureController::class, 'edit']
        )->name('salary.structures.edit');


        Route::put(
            '/structure/update/{id}',
            [SalaryStructureController::class, 'update']
        )->name('salary.structures.update');


        Route::delete(
            '/structure/delete/{id}',
            [SalaryStructureController::class, 'destroy']
        )->name('salary.structures.destroy');


        /*
        |--------------------------------------------------------------------------
        | Allocation Rules
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/structure/{id}/rule/store',
            [SalaryStructureController::class, 'storeRule']
        )->name('salary.structure.rule.store');


        Route::delete(
            '/rule/delete/{id}',
            [SalaryStructureController::class, 'deleteRule']
        )->name('salary.structure.rule.delete');

        

     Route::get(
        '/structures',
        [SalaryStructureController::class,'index']
    )->name('salary.structures.index');

    Route::post(
        '/structures/store',
        [SalaryStructureController::class,'store']
    )->name('salary.structures.store');

    Route::get(
        '/structure/{id}',
        [SalaryStructureController::class,'edit']
    )->name('salary.structures.edit');

    Route::put(
        '/structure/update/{id}',
        [SalaryStructureController::class,'update']
    )->name('salary.structures.update');

    Route::delete(
        '/structure/delete/{id}',
        [SalaryStructureController::class,'destroy']
    )->name('salary.structures.destroy');


     Route::get(
        '/deductions',
        [SalaryDeductionController::class,'index']
    )->name('salary.deductions.index');


    Route::post(
        '/deductions/store',
        [SalaryDeductionController::class,'store']
    )->name('salary.deductions.store');


    Route::put(
        '/deductions/update/{id}',
        [SalaryDeductionController::class,'update']
    )->name('salary.deductions.update');


    Route::delete(
        '/deductions/delete/{id}',
        [SalaryDeductionController::class,'destroy']
    )->name('salary.deductions.destroy');

        Route::get(
            '/components',
            [SalaryComponentController::class,'index']
        )->name('salary.components.index');

        Route::post(
            '/components/store',
            [SalaryComponentController::class,'store']
        )->name('salary.components.store');

        Route::put(
    '/components/update/{id}',
    [SalaryComponentController::class,'update']
)->name('salary.components.update');

        Route::delete(
            '/components/delete/{id}',
            [SalaryComponentController::class,'destroy']
        )->name('salary.components.destroy');

    /*
    |--------------------------------------------------------------------------
    | Salary Claims
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/claims',
        [SalaryClaimController::class, 'index']
    )->name('salary.claims.index');

    Route::post(
        '/claims/store',
        [SalaryClaimController::class, 'store']
    )->name('salary.claims.store');

    Route::get(
        '/claims/edit/{componentId}',
        [SalaryClaimController::class, 'edit']
    )->name('salary.claims.edit');

    Route::put(
        '/claims/update/{id}',
        [SalaryClaimController::class, 'update']
    )->name('salary.claims.update');

    Route::delete(
        '/claims/delete/{id}',
        [SalaryClaimController::class, 'destroy']
    )->name('salary.claims.destroy');

    Route::post(
        '/claims/component/store',
        [SalaryClaimController::class, 'storeComponent']
    )->name('salary.claims.component.store');

    Route::get(
        '/claims/components/list',
        [SalaryClaimController::class, 'getComponents']
    )->name('salary.claims.components.list');

    Route::post(
        '/claims/status/{id}',
        [SalaryClaimController::class, 'changeStatus']
    )->name('salary.claims.status');

});
Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::prefix('business')->middleware(['auth'])->group(function () {
/*
|--------------------------------------------------------------------------
| Attendance Setup > Work Shifts
|--------------------------------------------------------------------------
*/

Route::prefix('setup/attendance')->group(function () {

    Route::resource(
        'shifts',
        ShiftController::class
    )->only([
        'index',
        'store',
        'update',
        'destroy'
    ]);
    // Shift Policies
    Route::get('/shift-policies', [ShiftController::class, 'policyIndex'])->name('shift-policies.index');
    Route::get('/shift-policies/edit/{id}', [ShiftController::class, 'policyIndex'])->name('shift-policies.edit');
    Route::post('/shift-policies/store', [ShiftController::class, 'policyStore'])->name('shift-policies.store');
    Route::put('/shift-policies/update/{id}', [ShiftController::class, 'policyUpdate'])->name('shift-policies.update');
    Route::delete('/shift-policies/delete/{id}', [ShiftController::class, 'policyDestroy'])->name('shift-policies.destroy');

    Route::resource('time-rules', TimeRuleController::class);
    Route::resource('week-off-policies', WeekOffPolicyController::class);
    Route::get('/settings', [AttendanceSettingController::class, 'index'])->name('attendance-settings.index');
    Route::post('/settings', [AttendanceSettingController::class, 'store'])->name('attendance-settings.store');
    Route::resource('leave-types', LeaveTypeController::class);
    Route::post('leave-policies/recalculate', [LeavePolicyController::class, 'recalculate'])->name('leave-policies.recalculate');
    Route::resource('leave-policies', LeavePolicyController::class);
    
    Route::post('holidays/copy', [HolidayController::class, 'copyHolidays'])->name('holidays.copy');
    Route::post('holidays/update-payable', [HolidayController::class, 'updatePayableStatus'])->name('holidays.update-payable');
    Route::resource('holidays', HolidayController::class);

    // Comp Off Rules
    Route::get('/comp-off-rules', [CompOffRuleController::class, 'index'])->name('comp-off-rules.index');
    Route::post('/comp-off-rules', [CompOffRuleController::class, 'store'])->name('comp-off-rules.store');

    // Strike Rules
    Route::resource('strike-rules', StrikeRuleController::class)->only([
        'index', 'store', 'edit', 'update', 'destroy'
    ]);

    // Maternity Leave Policy
    Route::get('/maternity-leave-policy', [MaternityLeavePolicyController::class, 'index'])->name('maternity-leave-policy.index');
    Route::post('/maternity-leave-policy/update', [\App\Http\Controllers\Setup\Attendance\MaternityLeavePolicyController::class, 'update'])->name('maternity-leave-policy.update');
    Route::get('daily-punches', [\App\Http\Controllers\AttendanceDailyController::class, 'dailyPunches'])->name('attendance.daily-punches');
    Route::get('punch-details', [\App\Http\Controllers\AttendanceDailyController::class, 'getPunchDetails'])->name('attendance.punch-details');
    Route::post('update-punches', [\App\Http\Controllers\AttendanceDailyController::class, 'updatePunches'])->name('attendance.update-punches');
    Route::get('daily', [\App\Http\Controllers\AttendanceDailyController::class, 'dailyAttendance'])->name('attendance.daily');
    Route::get('monthly', [\App\Http\Controllers\AttendanceDailyController::class, 'monthlyAttendance'])->name('attendance.monthly');
    Route::get('export-template', [\App\Http\Controllers\AttendanceDailyController::class, 'exportTemplate'])->name('attendance.export-template');
    Route::post('upload-excel', [\App\Http\Controllers\AttendanceDailyController::class, 'uploadExcel'])->name('attendance.upload-excel');
    Route::get('download-data', [\App\Http\Controllers\AttendanceDailyController::class, 'downloadData'])->name('attendance.download-data');
    Route::get('manual', [\App\Http\Controllers\ManualAttendanceController::class, 'index'])->name('attendance.manual');
    Route::post('manual', [\App\Http\Controllers\ManualAttendanceController::class, 'store'])->name('attendance.manual.store');
    Route::get('manual/{employeeId}/history', [\App\Http\Controllers\ManualAttendanceController::class, 'history'])->name('attendance.manual.history');
    
    // Shift Roster
    Route::get('shift-roster', [\App\Http\Controllers\ShiftRosterController::class, 'index'])->name('attendance.shift-roster');
    Route::post('shift-roster/update-bulk', [\App\Http\Controllers\ShiftRosterController::class, 'updateBulk'])->name('attendance.shift-roster.update-bulk');
    Route::post('shift-roster/auto-generate', [\App\Http\Controllers\ShiftRosterController::class, 'autoGenerate'])->name('attendance.shift-roster.auto-generate');
    Route::get('shift-roster/download-template', [\App\Http\Controllers\ShiftRosterController::class, 'downloadTemplate'])->name('attendance.shift-roster.download-template');
    Route::post('shift-roster/upload-excel', [\App\Http\Controllers\ShiftRosterController::class, 'uploadExcel'])->name('attendance.shift-roster.upload-excel');
    Route::get('shift-roster/export-roster', [\App\Http\Controllers\ShiftRosterController::class, 'exportRoster'])->name('attendance.shift-roster.export-roster');
    
    Route::resource('attendances', \App\Http\Controllers\AttendanceDailyController::class);
});

Route::prefix('capture')->group(function () {
    // Salary-Variable
    Route::get('salaryvariable', [\App\Http\Controllers\DataCapture\SalaryVariableController::class, 'index'])->name('capture.salaryvariable');
    Route::post('salaryvariable/store', [\App\Http\Controllers\DataCapture\SalaryVariableController::class, 'store'])->name('capture.salaryvariable.store');
    Route::get('salaryvariable/export-template', [\App\Http\Controllers\DataCapture\SalaryVariableController::class, 'exportTemplate'])->name('capture.salaryvariable.export-template');
    Route::post('salaryvariable/upload-excel', [\App\Http\Controllers\DataCapture\SalaryVariableController::class, 'uploadExcel'])->name('capture.salaryvariable.upload-excel');
    Route::get('salaryvariable/download-data', [\App\Http\Controllers\DataCapture\SalaryVariableController::class, 'downloadData'])->name('capture.salaryvariable.download-data');
    Route::post('salaryvariable/transfer-non-cash', [\App\Http\Controllers\DataCapture\SalaryVariableController::class, 'transferNonCash'])->name('capture.salaryvariable.transfer-non-cash');
    Route::get('salaryvariable/details', [\App\Http\Controllers\DataCapture\SalaryVariableController::class, 'getDetails'])->name('capture.salaryvariable.details');
    Route::delete('salaryvariable/delete/{id}', [\App\Http\Controllers\DataCapture\SalaryVariableController::class, 'deleteDetail'])->name('capture.salaryvariable.delete');

    // Salary-Units
    Route::get('salaryunits', [\App\Http\Controllers\DataCapture\SalaryUnitsController::class, 'index'])->name('capture.salaryunits');
    Route::post('salaryunits/store', [\App\Http\Controllers\DataCapture\SalaryUnitsController::class, 'store'])->name('capture.salaryunits.store');
    Route::get('salaryunits/export-excel', [\App\Http\Controllers\DataCapture\SalaryUnitsController::class, 'exportExcel'])->name('capture.salaryunits.export-excel');
    Route::post('salaryunits/upload-excel', [\App\Http\Controllers\DataCapture\SalaryUnitsController::class, 'uploadExcel'])->name('capture.salaryunits.upload-excel');
    Route::post('salaryunits/import-travel', [\App\Http\Controllers\DataCapture\SalaryUnitsController::class, 'importTravel'])->name('capture.salaryunits.import-travel');
    Route::get('salaryunits/details', [\App\Http\Controllers\DataCapture\SalaryUnitsController::class, 'getDetails'])->name('capture.salaryunits.details');
    Route::delete('salaryunits/delete/{id}', [\App\Http\Controllers\DataCapture\SalaryUnitsController::class, 'deleteDetail'])->name('capture.salaryunits.delete');

    // Deduction-Variable
    Route::get('deductionvariable', [\App\Http\Controllers\DataCapture\DeductionVariableController::class, 'index'])->name('capture.deductionvariable');
    Route::post('deductionvariable/store', [\App\Http\Controllers\DataCapture\DeductionVariableController::class, 'store'])->name('capture.deductionvariable.store');
    Route::get('deductionvariable/export-template', [\App\Http\Controllers\DataCapture\DeductionVariableController::class, 'exportTemplate'])->name('capture.deductionvariable.export-template');
    Route::post('deductionvariable/upload-excel', [\App\Http\Controllers\DataCapture\DeductionVariableController::class, 'uploadExcel'])->name('capture.deductionvariable.upload-excel');
    Route::get('deductionvariable/download-data', [\App\Http\Controllers\DataCapture\DeductionVariableController::class, 'downloadData'])->name('capture.deductionvariable.download-data');
    Route::post('deductionvariable/transfer-non-cash', [\App\Http\Controllers\DataCapture\DeductionVariableController::class, 'transferNonCash'])->name('capture.deductionvariable.transfer-non-cash');
    Route::get('deductionvariable/details', [\App\Http\Controllers\DataCapture\DeductionVariableController::class, 'getDetails'])->name('capture.deductionvariable.details');
    Route::delete('deductionvariable/delete/{id}', [\App\Http\Controllers\DataCapture\DeductionVariableController::class, 'deleteDetail'])->name('capture.deductionvariable.delete');

    // Income Tax (TDS)
    Route::get('tds', [\App\Http\Controllers\DataCapture\TdsController::class, 'index'])->name('capture.tds');
    Route::post('tds/store', [\App\Http\Controllers\DataCapture\TdsController::class, 'store'])->name('capture.tds.store');
    Route::post('tds/copy', [\App\Http\Controllers\DataCapture\TdsController::class, 'copyPrevious'])->name('capture.tds.copy_previous');
            
    // Extra Days
    Route::get('extradays', [\App\Http\Controllers\DataCapture\ExtraDayController::class, 'index'])->name('capture.extradays');
    Route::post('extradays/store', [\App\Http\Controllers\DataCapture\ExtraDayController::class, 'store'])->name('capture.extradays.store');
    Route::post('extradays/copy', [\App\Http\Controllers\DataCapture\ExtraDayController::class, 'copyPrevious'])->name('capture.extradays.copy');
            
    // Extra Hours
    Route::get('extrahours', [\App\Http\Controllers\DataCapture\ExtraHourController::class, 'index'])->name('capture.extrahours');
    Route::post('extrahours/store', [\App\Http\Controllers\DataCapture\ExtraHourController::class, 'store'])->name('capture.extrahours.store');
    Route::post('extrahours/copy', [\App\Http\Controllers\DataCapture\ExtraHourController::class, 'copyPrevious'])->name('capture.extrahours.copy');
    
    // OT Hours
    Route::get('othours', [\App\Http\Controllers\DataCapture\OtHourController::class, 'index'])->name('capture.othours');
    Route::post('othours/store', [\App\Http\Controllers\DataCapture\OtHourController::class, 'store'])->name('capture.othours.store');
    Route::post('othours/copy', [\App\Http\Controllers\DataCapture\OtHourController::class, 'copyPrevious'])->name('capture.othours.copy');

    // Loans
    Route::get('loans', [\App\Http\Controllers\DataCapture\LoanController::class, 'index'])->name('capture.loans');
    Route::post('loans/store', [\App\Http\Controllers\DataCapture\LoanController::class, 'store'])->name('capture.loans.store');
    Route::delete('loans/{id}', [\App\Http\Controllers\DataCapture\LoanController::class, 'destroy'])->name('capture.loans.destroy');

    // IT Exemptions
    Route::get('itexemptions', [\App\Http\Controllers\DataCapture\ItExemptionController::class, 'index'])->name('capture.itexemptions');
    Route::post('itexemptions/store', [\App\Http\Controllers\DataCapture\ItExemptionController::class, 'store'])->name('capture.itexemptions.store');

    // IT Declarations
    Route::get('itdeclarations', [\App\Http\Controllers\DataCapture\ItDeclarationController::class, 'index'])->name('capture.itdeclarations');
    Route::get('itdeclarations/details', [\App\Http\Controllers\DataCapture\ItDeclarationController::class, 'getDetails'])->name('capture.itdeclarations.details');
    Route::post('itdeclarations/store', [\App\Http\Controllers\DataCapture\ItDeclarationController::class, 'storeDetails'])->name('capture.itdeclarations.store');

    // TDS Challans
    Route::get('tdschallans', [\App\Http\Controllers\DataCapture\TdsChallanController::class, 'index'])->name('capture.tdschallans');
    Route::post('tdschallans/store', [\App\Http\Controllers\DataCapture\TdsChallanController::class, 'store'])->name('capture.tdschallans.store');

    // TDS Returns
    Route::get('tdsreturns', [\App\Http\Controllers\DataCapture\TdsReturnController::class, 'index'])->name('capture.tdsreturns');
    Route::post('tdsreturns/store', [\App\Http\Controllers\DataCapture\TdsReturnController::class, 'store'])->name('capture.tdsreturns.store');
});

   Route::prefix('hr/requests')->group(function () {
       Route::get('attendance', [\App\Http\Controllers\HR\RequestController::class, 'attendance'])->name('hr.requests.attendance');
       Route::post('attendance/{id}', [\App\Http\Controllers\HR\RequestController::class, 'attendanceAction'])->name('hr.requests.attendance.action');
       
       Route::get('leave', [\App\Http\Controllers\HR\RequestController::class, 'leave'])->name('hr.requests.leave');
       Route::post('leave/{id}', [\App\Http\Controllers\HR\RequestController::class, 'leaveAction'])->name('hr.requests.leave.action');
       
       Route::get('helpdesk', [\App\Http\Controllers\HR\RequestController::class, 'helpdesk'])->name('hr.requests.helpdesk');
       Route::post('helpdesk/{id}', [\App\Http\Controllers\HR\RequestController::class, 'helpdeskAction'])->name('hr.requests.helpdesk.action');
   });

   Route::get('hr/policies', [\App\Http\Controllers\HR\PolicyController::class, 'index'])->name('hr.policies');
   
   Route::resource('hr/letters', \App\Http\Controllers\HR\LetterController::class, ['as' => 'hr']);
   
   Route::get('hr/alerts/broadcast', [\App\Http\Controllers\HR\AlertBroadcastController::class, 'create'])->name('hr.alerts.broadcast');
   Route::post('hr/alerts/broadcast', [\App\Http\Controllers\HR\AlertBroadcastController::class, 'store'])->name('hr.alerts.broadcast.store');
   Route::prefix('employee')->group(function () {
    Route::get('/employee', [EmployeeController::class, 'index'])->name('business.employee');
       Route::get('/employee/{id}/statement', [EmployeeController::class, 'downloadStatement'])->name('employee.statement');
       Route::post('/employee/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employee.toggle-status');
       
       Route::prefix('onboarding')->name('onboarding.')->group(function () {
            Route::get('/', [OnboardingController::class, 'index'])->name('index');
            Route::get('/create', [OnboardingController::class, 'create'])->name('create');
            Route::post('/', [OnboardingController::class, 'store'])->name('store');
            
            // Settings
            Route::get('/settings', [\App\Http\Controllers\Employee\OnboardingSettingsController::class, 'index'])->name('settings');
            Route::post('/settings', [\App\Http\Controllers\Employee\OnboardingSettingsController::class, 'update'])->name('settings.update');
            
            // Bulk Onboarding
            Route::get('/bulk', [\App\Http\Controllers\Employee\BulkOnboardingController::class, 'index'])->name('bulk.index');
            Route::post('/bulk', [\App\Http\Controllers\Employee\BulkOnboardingController::class, 'store'])->name('bulk.store');
           
           // Onboarding Forms Wizard
           Route::get('/forms', [\App\Http\Controllers\Employee\OnboardingFormController::class, 'index'])->name('forms.index');
           Route::get('/forms/create', [\App\Http\Controllers\Employee\OnboardingFormController::class, 'create'])->name('forms.create');
           Route::post('/forms/store', [\App\Http\Controllers\Employee\OnboardingFormController::class, 'store'])->name('forms.store');
           Route::post('/forms/{id}/finalize', [\App\Http\Controllers\Employee\OnboardingFormController::class, 'finalize'])->name('forms.finalize');
           Route::post('/forms/{id}/recall', [\App\Http\Controllers\Employee\OnboardingFormController::class, 'recall'])->name('forms.recall');
           Route::post('/forms/{id}/action', [\App\Http\Controllers\Employee\OnboardingFormController::class, 'action'])->name('forms.action');
           Route::delete('/forms/{id}', [\App\Http\Controllers\Employee\OnboardingFormController::class, 'destroy'])->name('forms.destroy');
       });

        Route::get('/org-chart', [\App\Http\Controllers\Employee\OrgChartController::class, 'index'])->name('org-chart');

         Route::prefix('separation')->name('separation.')->group(function () {
             Route::get('/dashboard', [\App\Http\Controllers\Employee\SeparationDashboardController::class, 'index'])->name('dashboard');
             Route::get('/create', [\App\Http\Controllers\Employee\SeparationController::class, 'create'])->name('create');
             Route::post('/store', [\App\Http\Controllers\Employee\SeparationController::class, 'store'])->name('store');
             Route::get('/employee-details', [\App\Http\Controllers\Employee\SeparationController::class, 'getEmployeeDetails'])->name('employee-details');
             
             // Pending Actions
             Route::get('/pending', [\App\Http\Controllers\Employee\SeparationController::class, 'pending'])->name('pending');
             Route::post('/{id}/cancel', [\App\Http\Controllers\Employee\SeparationController::class, 'cancelExit'])->name('cancel');
             Route::post('/{id}/process', [\App\Http\Controllers\Employee\SeparationController::class, 'processExit'])->name('process');
             Route::post('/{id}/finalize', [\App\Http\Controllers\Employee\SeparationController::class, 'finalizeExit'])->name('finalize');
             
             // Ex-Employees
             Route::get('/ex-employees', [\App\Http\Controllers\Employee\SeparationController::class, 'exEmployees'])->name('ex-employees');
             Route::get('/{id}/download-fnf', [\App\Http\Controllers\Employee\SeparationController::class, 'downloadFnf'])->name('download-fnf');
             Route::post('/{id}/reprocess', [\App\Http\Controllers\Employee\SeparationController::class, 'reprocessExit'])->name('reprocess');
             Route::post('/{id}/rehire', [\App\Http\Controllers\Employee\SeparationController::class, 'rehire'])->name('rehire');
             Route::delete('/{id}', [\App\Http\Controllers\Employee\SeparationController::class, 'destroy'])->name('destroy');
         });

        Route::get('/bulk-upload', [\App\Http\Controllers\Employee\BulkUploadController::class, 'index'])->name('bulk-upload');
       Route::post('/bulk-upload', [\App\Http\Controllers\Employee\BulkUploadController::class, 'store'])->name('bulk-upload.store');
       Route::get('/bulk-upload/download-template', [\App\Http\Controllers\Employee\BulkUploadController::class, 'downloadTemplate'])->name('bulk-upload.download-template');

       Route::get('/bulk-upload-address', [\App\Http\Controllers\Employee\BulkAddressUploadController::class, 'index'])->name('bulk-upload-address');
       Route::post('/bulk-upload-address', [\App\Http\Controllers\Employee\BulkAddressUploadController::class, 'store'])->name('bulk-upload-address.store');
       Route::get('/bulk-upload-address/download-template', [\App\Http\Controllers\Employee\BulkAddressUploadController::class, 'downloadTemplate'])->name('bulk-upload-address.download-template');

       Route::get('/bulk-upload-bank', [\App\Http\Controllers\Employee\BulkBankUploadController::class, 'index'])->name('bulk-upload-bank');
       Route::post('/bulk-upload-bank', [\App\Http\Controllers\Employee\BulkBankUploadController::class, 'store'])->name('bulk-upload-bank.store');
       Route::get('/bulk-upload-bank/download-template', [\App\Http\Controllers\Employee\BulkBankUploadController::class, 'downloadTemplate'])->name('bulk-upload-bank.download-template');

       Route::get('/bulk-upload-work-profile', [\App\Http\Controllers\Employee\BulkWorkProfileUploadController::class, 'index'])->name('bulk-upload-work-profile');
       Route::post('/bulk-upload-work-profile', [\App\Http\Controllers\Employee\BulkWorkProfileUploadController::class, 'store'])->name('bulk-upload-work-profile.store');
       Route::get('/bulk-upload-work-profile/download-template', [\App\Http\Controllers\Employee\BulkWorkProfileUploadController::class, 'downloadTemplate'])->name('bulk-upload-work-profile.download-template');
       
       Route::prefix('profile')->name('employee.profile.')->group(function () {
           Route::get('/summary', [ProfileController::class, 'index'])->name('summary');
           Route::get('/basic', [ProfileController::class, 'basic'])->name('basic');
           Route::post('/basic', [ProfileController::class, 'updateBasic'])->name('basic.update');
           Route::post('/face', [ProfileController::class, 'saveFace'])->name('face.update');
           Route::post('/add-tag', [ProfileController::class, 'addTag'])->name('tags.add');
           Route::post('/remove-tag', [ProfileController::class, 'removeTag'])->name('tags.remove');
           Route::get('/work-profile', [WorkProfileController::class, 'index'])->name('work-profile');
           Route::post('/work-profile', [WorkProfileController::class, 'store'])->name('work-profile.store');
           Route::post('/work-profile/{work_profile_id}', [WorkProfileController::class, 'update'])->name('work-profile.update');
           Route::post('/work-profile/{work_profile_id}/remove-manager/{type}', [WorkProfileController::class, 'removeManager'])->name('work-profile.remove-manager');
           Route::delete('/work-profile/{work_profile_id}', [WorkProfileController::class, 'destroy'])->name('work-profile.destroy');
           Route::get('/policies/{id}', [EmployeePolicyController::class, 'edit'])->name('policies');
           Route::put('/policies/{id}', [EmployeePolicyController::class, 'update'])->name('policies.update');
           Route::delete('/policies/{id}/destroy/{policy_id}', [EmployeePolicyController::class, 'destroy'])->name('policies.destroy');
           Route::get('/salary', [SalaryController::class, 'index'])->name('salary');
           Route::post('/salary', [SalaryController::class, 'store'])->name('salary.store');
           Route::put('/salary/{revision_id}', [SalaryController::class, 'update'])->name('salary.update');
           Route::delete('/salary/{revision_id}', [SalaryController::class, 'destroy'])->name('salary.destroy');
           Route::get('/salary/structure/{structure_id}', [SalaryController::class, 'getStructureDetails'])->name('salary.structure_details');
           Route::get('/identity', [IdentityController::class, 'index'])->name('identity');
           Route::post('/identity/update', [IdentityController::class, 'update'])->name('identity.update');
           Route::get('/address', [AddressController::class, 'index'])->name('address');
           Route::post('/address/store', [AddressController::class, 'store'])->name('address.store');
           Route::put('/address/update/{id}', [AddressController::class, 'update'])->name('address.update');
           Route::delete('/address/delete/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
           Route::get('/documents', [DocumentController::class, 'index'])->name('documents');
           Route::post('/documents/store', [DocumentController::class, 'store'])->name('documents.store');
           Route::delete('/documents/delete/{id}', [DocumentController::class, 'destroy'])->name('documents.destroy');
           
           Route::get('/assets', [AssetController::class, 'index'])->name('assets');
           Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
           Route::delete('/assets/{asset_id}', [AssetController::class, 'destroy'])->name('assets.destroy');

           Route::get('/family', [FamilyController::class, 'index'])->name('family');
           Route::post('/family', [FamilyController::class, 'store'])->name('family.store');
           Route::delete('/family/{member_id}', [FamilyController::class, 'destroy'])->name('family.destroy');
            Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
            Route::post('/permissions', [PermissionController::class, 'update'])->name('permissions.update');
            Route::get('/login-access', [LoginController::class, 'index'])->name('login-access');
            Route::post('/login-access', [LoginController::class, 'update'])->name('login-access.update');
           Route::get('/additional-info', [AdditionalInfoController::class, 'index'])->name('additional-info');
           Route::post('/additional-info', [AdditionalInfoController::class, 'update'])->name('additional-info.update');
           
           // BG Check
           Route::get('/bg-check', [\App\Http\Controllers\Employee\Profile\EmployeeBgCheckController::class, 'index'])->name('bg-check');
           Route::post('/bg-check', [\App\Http\Controllers\Employee\Profile\EmployeeBgCheckController::class, 'store'])->name('bg-check.store');
           Route::delete('/bg-check/{id}', [\App\Http\Controllers\Employee\Profile\EmployeeBgCheckController::class, 'destroy'])->name('bg-check.destroy');
           
           // Deactivate
           Route::get('/deactivate', [\App\Http\Controllers\Employee\Profile\DeactivateController::class, 'index'])->name('deactivate');
           Route::post('/deactivate', [\App\Http\Controllers\Employee\Profile\DeactivateController::class, 'deactivate'])->name('deactivate.submit');
            Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs');
            
            Route::get('/strikes/{id}', [\App\Http\Controllers\Employee\EmployeeStrikeController::class, 'index'])->name('strikes');
            Route::post('/strikes/{id}', [\App\Http\Controllers\Employee\EmployeeStrikeController::class, 'store'])->name('strikes.store');
            Route::post('/strikes/{strike}/waive', [\App\Http\Controllers\Employee\EmployeeStrikeController::class, 'waive'])->name('strikes.waive');

            Route::get('/loans/{id}', [\App\Http\Controllers\Employee\EmployeeLoanController::class, 'index'])->name('loans');
            Route::post('/loans/{id}', [\App\Http\Controllers\Employee\EmployeeLoanController::class, 'store'])->name('loans.store');
        });
   });
    // Dashboards
    Route::get('/dashboards/attendance', [\App\Http\Controllers\Dashboards\AttendanceDashboardController::class, 'index'])
        ->name('dashboards.attendance');

    Route::get('/dashboards/flight-risk', [\App\Http\Controllers\Dashboards\FlightRiskController::class, 'index'])
        ->name('dashboards.flight-risk');
        
    Route::post('/dashboards/flight-risk/{employee_id}/untrack', [\App\Http\Controllers\Dashboards\FlightRiskController::class, 'markUntracked'])
        ->name('dashboards.flight-risk.untrack');

    // Business Dashboard
    Route::get('/dashboard', [BusinessController::class, 'index'])
        ->name('business.dashboard');

    Route::get('/add', [BusinessController::class, 'create'])
        ->name('business.add');

    Route::post('/store', [BusinessController::class, 'store'])
        ->name('business.store');

    Route::get('/edit/{id}', [BusinessController::class, 'edit'])
        ->name('business.edit');

    Route::get('/show/{id}', [BusinessController::class, 'show'])
        ->name('business.show');

    Route::post('/update/{id}', [BusinessController::class, 'update'])
        ->name('business.update');

    Route::get('/delete/{id}', [BusinessController::class, 'destroy'])
        ->name('business.delete');

    /*
    |--------------------------------------------------------------------------
    | Setup
    |--------------------------------------------------------------------------
    */

    Route::get('/setup', function () {
        return view('admin.setup.index');
    })->name('setup');

    /*
    |--------------------------------------------------------------------------
    | Master Setup > Business Units
    |--------------------------------------------------------------------------
    */
   

  Route::prefix('setup/statutory')->group(function () {
      Route::get('/esi-settings', [EsiSettingController::class, 'index'])->name('esi-settings.index');
      Route::post('/esi-settings', [EsiSettingController::class, 'store'])->name('esi-settings.store');
      Route::get('/esi-settings/{id}/edit', [EsiSettingController::class, 'edit'])->name('esi-settings.edit');
      Route::put('/esi-settings/{id}', [EsiSettingController::class, 'update'])->name('esi-settings.update');
      Route::delete('/esi-settings/{id}', [EsiSettingController::class, 'destroy'])->name('esi-settings.destroy');
      Route::post('/esi-settings/{id}/toggle', [EsiSettingController::class, 'toggleStatus'])->name('esi-settings.toggle');
      Route::post('/esi-settings-component', [EsiSettingController::class, 'updateComponent'])->name('esi-settings.component.update');

      // EPF Settings
      Route::get('/epf-settings', [\App\Http\Controllers\Setup\Statutory\EpfSettingController::class, 'index'])->name('epf-settings.index');
      Route::post('/epf-settings', [\App\Http\Controllers\Setup\Statutory\EpfSettingController::class, 'store'])->name('epf-settings.store');
      Route::get('/epf-settings/{id}/edit', [\App\Http\Controllers\Setup\Statutory\EpfSettingController::class, 'edit'])->name('epf-settings.edit');
      Route::put('/epf-settings/{id}', [\App\Http\Controllers\Setup\Statutory\EpfSettingController::class, 'update'])->name('epf-settings.update');
      Route::delete('/epf-settings/{id}', [\App\Http\Controllers\Setup\Statutory\EpfSettingController::class, 'destroy'])->name('epf-settings.destroy');
      Route::post('/epf-settings/{id}/toggle', [\App\Http\Controllers\Setup\Statutory\EpfSettingController::class, 'toggleStatus'])->name('epf-settings.toggle');
      Route::post('/epf-settings-component', [\App\Http\Controllers\Setup\Statutory\EpfSettingController::class, 'updateComponent'])->name('epf-settings.component.update');

      // LWF Settings
      Route::get('/lwf-settings', [\App\Http\Controllers\Setup\Statutory\LwfSettingController::class, 'index'])->name('setup.statutory.lwf.index');
      Route::post('/lwf-settings', [\App\Http\Controllers\Setup\Statutory\LwfSettingController::class, 'store'])->name('setup.statutory.lwf.store');
      Route::post('/lwf-settings-component', [\App\Http\Controllers\Setup\Statutory\LwfSettingController::class, 'updateComponent'])->name('setup.statutory.lwf.update-component');
      Route::get('/lwf-settings/{id}/edit', [\App\Http\Controllers\Setup\Statutory\LwfSettingController::class, 'edit'])->name('setup.statutory.lwf.edit');
      Route::put('/lwf-settings/{id}', [\App\Http\Controllers\Setup\Statutory\LwfSettingController::class, 'update'])->name('setup.statutory.lwf.update');
      Route::delete('/lwf-settings/{id}', [\App\Http\Controllers\Setup\Statutory\LwfSettingController::class, 'destroy'])->name('setup.statutory.lwf.destroy');
      Route::post('/lwf-settings/toggle-global', [\App\Http\Controllers\Setup\Statutory\LwfSettingController::class, 'toggleGlobal'])->name('setup.statutory.lwf.toggle-global');

      Route::get('/income-tax', [\App\Http\Controllers\Setup\Statutory\IncomeTaxController::class, 'index'])->name('setup.statutory.itax.index');
      
      Route::get('/tds-24q-info', [\App\Http\Controllers\Setup\Statutory\Tds24QInfoController::class, 'index'])->name('setup.statutory.tds24q.index');
      
      Route::get('/form16-info', [\App\Http\Controllers\Setup\Statutory\Form16InfoController::class, 'index'])->name('setup.statutory.form16.index');

      // PT Settings
      Route::get('/ptax-settings', [\App\Http\Controllers\Setup\Statutory\PtaxController::class, 'index'])->name('ptax-settings.index');
      Route::post('/ptax-settings', [\App\Http\Controllers\Setup\Statutory\PtaxController::class, 'updateSettings'])->name('ptax-settings.update');
      Route::post('/ptax-settings-component', [\App\Http\Controllers\Setup\Statutory\PtaxController::class, 'updateComponent'])->name('ptax-settings.component.update');
      Route::get('/ptax-settings/slabs', [\App\Http\Controllers\Setup\Statutory\PtaxController::class, 'getSlabs'])->name('ptax-settings.slabs.get');
      Route::post('/ptax-settings/slabs', [\App\Http\Controllers\Setup\Statutory\PtaxController::class, 'storeSlab'])->name('ptax-settings.slabs.store');
      Route::put('/ptax-settings/slabs/{id}', [\App\Http\Controllers\Setup\Statutory\PtaxController::class, 'updateSlab'])->name('ptax-settings.slabs.update');
      Route::delete('/ptax-settings/slabs/{id}', [\App\Http\Controllers\Setup\Statutory\PtaxController::class, 'destroySlab'])->name('ptax-settings.slabs.destroy');
  });

  Route::prefix('api/statutory')->group(function () {
      Route::get('financial-years/active', [\App\Http\Controllers\Api\Statutory\FinancialYearApiController::class, 'listActive']);
      Route::apiResource('financial-years', \App\Http\Controllers\Api\Statutory\FinancialYearApiController::class);

      Route::get('income-tax-settings', [\App\Http\Controllers\Api\Statutory\IncomeTaxSettingApiController::class, 'show']);
      Route::put('income-tax-settings', [\App\Http\Controllers\Api\Statutory\IncomeTaxSettingApiController::class, 'update']);

      Route::apiResource('salary-tax-mappings', \App\Http\Controllers\Api\Statutory\SalaryTaxMappingApiController::class)->except(['show', 'update']);

      Route::apiResource('tax-slabs', \App\Http\Controllers\Api\Statutory\TaxSlabApiController::class);

      Route::get('tds-24q-info', [\App\Http\Controllers\Api\Statutory\Tds24QInfoApiController::class, 'show']);
      Route::put('tds-24q-info', [\App\Http\Controllers\Api\Statutory\Tds24QInfoApiController::class, 'update']);
      
      Route::get('form16-info', [\App\Http\Controllers\Api\Statutory\Form16InfoApiController::class, 'show']);
      Route::post('form16-info', [\App\Http\Controllers\Api\Statutory\Form16InfoApiController::class, 'update']);
  });

  Route::prefix('setup/master')->group(function () {

    Route::get('business-units/{business_unit}/images', [BusinessUnitController::class, 'images'])->name('business-units.images');
    Route::post('business-units/{business_unit}/images', [BusinessUnitController::class, 'updateImages'])->name('business-units.update-images');

    Route::resource(
        'business-units',
        BusinessUnitController::class
    );

    Route::resource(
        'locations',
        LocationController::class
    );
    Route::post('locations/{id}/map', [LocationController::class, 'updateMapLocation'])->name('locations.update_map');

    Route::resource(
    'cost-centers',
    CostCenterController::class
);

Route::resource(
    'departments',
    DepartmentController::class
)->only([
    'index',
    'store',
    'update',
    'destroy'
]);

Route::resource(
    'grades',
    GradeController::class
)->only([
    'index',
    'store',
    'update',
    'destroy'
]);

Route::resource(
    'designations',
    DesignationController::class
)->only([
    'index',
    'store',
    'update',
    'destroy'
]);

Route::resource(
    'visit-types',
    VisitTypeController::class
)->only([
    'index',
    'store',
    'update',
    'destroy'
]);

Route::resource(
    'helpdesk-categories',
    HelpdeskCategoryController::class
)->only([
    'index',
    'store',
    'update',
    'destroy'
]);

Route::resource(
    'workflows',
    WorkflowController::class
)->only([
    'index',
    'store',
    'update',
    'destroy'
]);

Route::resource(
    'exit-reasons',
    ExitReasonController::class
)->only([
    'index',
    'store',
    'update',
    'destroy'
]);

    Route::resource(
        'letter-templates',
        \App\Http\Controllers\Setup\LetterTemplateController::class,
        ['as' => 'setup']
    );

});

});





//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::middleware(['auth'])->group(function () {
    Route::get('/wall', [App\Http\Controllers\WallWebController::class, 'index'])->name('wall.index');
    Route::post('/wall/store-event-post', [App\Http\Controllers\WallWebController::class, 'storeEventPost'])->name('wall.store_event');
    Route::delete('/wall/event/{id}', [App\Http\Controllers\WallWebController::class, 'destroyEventPost'])->name('wall.destroy_event');

    // Helpdesk Tickets
    Route::get('/tickets', [App\Http\Controllers\TicketWebController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [App\Http\Controllers\TicketWebController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/assign', [App\Http\Controllers\TicketWebController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{id}/force-close', [App\Http\Controllers\TicketWebController::class, 'forceClose'])->name('tickets.force_close');

    Route::get('/ticket-access-requests', [App\Http\Controllers\TicketWebController::class, 'accessRequests'])->name('tickets.access_requests');
    Route::post('/ticket-access-requests/{id}/approve', [App\Http\Controllers\TicketWebController::class, 'approveAccessRequest'])->name('tickets.approve_access');
    Route::post('/ticket-access-requests/{id}/reject', [App\Http\Controllers\TicketWebController::class, 'rejectAccessRequest'])->name('tickets.reject_access');

    // Alerts Web UI & AJAX
    Route::prefix('alerts')->group(function () {
        Route::view('/dashboard', 'alerts.dashboard')->name('alerts.dashboard');
        Route::view('/rules', 'alerts.rules')->name('alerts.rules');
        Route::view('/history', 'alerts.history')->name('alerts.history');
        Route::view('/create', 'alerts.create')->name('alerts.create');

        // AJAX endpoints (using Web Session)
        Route::get('/ajax/dashboard', [\App\Http\Controllers\Api\AlertController::class, 'dashboard']);
        Route::get('/ajax/list', [\App\Http\Controllers\Api\AlertController::class, 'index']);
        Route::post('/ajax/{id}/resolve', [\App\Http\Controllers\Api\AlertController::class, 'resolve']);
        Route::get('/ajax/rules', [\App\Http\Controllers\Api\AlertRuleController::class, 'index']);
        Route::post('/ajax/broadcast', [\App\Http\Controllers\Api\AlertController::class, 'broadcast']);
    });
});

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

Route::get('/auto-login-test', function() {
    Auth::loginUsingId(\App\Models\User::first()->id);
    return redirect('/business/employee/profile/salary?id=1');
});
