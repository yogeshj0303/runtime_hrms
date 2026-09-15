<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\DailyAttendanceApiController;
use App\Http\Controllers\Api\RequestApiController;
use App\Http\Controllers\Api\LeaveApiController;
use App\Http\Controllers\Api\AttendanceRelaxationApiController;
use App\Http\Controllers\Api\HolidayApiController;
use App\Http\Controllers\Api\LeavePolicyApiController;
use App\Http\Controllers\Api\EmployeeDocumentApiController;
use App\Http\Controllers\Api\ShiftChangeRequestApiController;
use App\Http\Controllers\Api\MaternityPolicyApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post(
'/shift-change-request',
[ShiftChangeRequestApiController::class,'store']
);

Route::get(
'/shift-change-request-list/{employee_id}',
[ShiftChangeRequestApiController::class,'requestList']
);

Route::get(
'/approved-shift-change-list/{employee_id}',
[ShiftChangeRequestApiController::class,'approvedList']
);
Route::post(
    '/employee-documents/upload',
    [EmployeeDocumentApiController::class, 'upload']
);

Route::get(
    '/employee-documents/{employee_id}',
    [EmployeeDocumentApiController::class, 'index']
);
Route::get(
    '/leave-policies/{business_id}',
    [LeavePolicyApiController::class, 'index']
);

Route::get('/maternity-policy', [MaternityPolicyApiController::class, 'index']);
Route::post('/maternity-policy/update', [MaternityPolicyApiController::class, 'update']);

Route::get('/holidays/{business_id}', [HolidayApiController::class, 'index']);
Route::put('/employee-profile/{user_id}', [EmployeeApiController::class, 'updateProfile']);
Route::get('/profile/{user_id}', [EmployeeApiController::class, 'profile']);
Route::get('/employee/profile/{user_id}', [EmployeeApiController::class, 'employeeProfile']);
Route::get('/employee/assets/{user_id}', [EmployeeApiController::class, 'getAssets']);
Route::get('/employee/permissions/{user_id}', [EmployeeApiController::class, 'getPermissions']);
Route::post('/employee/register-face', [EmployeeApiController::class, 'registerFace']);
Route::post('/employee/verify-face', [EmployeeApiController::class, 'verifyFace']);
Route::get('/employee/get-face/{user_id}', [EmployeeApiController::class, 'getFace']);
Route::get('/today-attendance/{employee_id}', [EmployeeApiController::class, 'todayAttendance']);
Route::post(
    '/attendance-relaxation/request',
    [AttendanceRelaxationApiController::class,'store']
);

Route::get(
    '/attendance-relaxation/pending/{employee_id}',
    [AttendanceRelaxationApiController::class,'pending']
);

Route::get(
    '/attendance-relaxation/approved/{employee_id}',
    [AttendanceRelaxationApiController::class,'approved']
);
Route::post(
    '/leave/request',
    [RequestApiController::class, 'requestLeave']
);

Route::get(
    '/leave/employee/{employee_id}',
    [LeaveApiController::class, 'employeeLeaves']
);

Route::get(
    '/leave/approved/{employee_id}',
    [LeaveApiController::class, 'employeeApprovedLeaves']
);

Route::get(
    '/leave/rejected/{employee_id}',
    [LeaveApiController::class, 'employeeRejectedLeaves']
);

Route::get(
    '/leave-types/{business_id}',
    [LeaveApiController::class, 'leaveTypes']
);

Route::post('/employee/login', [EmployeeApiController::class, 'login']);
Route::post('/employee/logout', [EmployeeApiController::class, 'logout']);
Route::post('/employee/change-password', [EmployeeApiController::class, 'changePassword']);
Route::get('/employee/dashboard-summary', [EmployeeApiController::class, 'dashboardSummary']);
Route::post('/employee/mark-viewed', [EmployeeApiController::class, 'markViewed']);
Route::get('/employee/app-settings/{user_id}', [EmployeeApiController::class, 'getAppSettings']);
Route::post('/employee/app-settings', [EmployeeApiController::class, 'updateAppSettings']);
Route::get('/employee/hr-policies', [EmployeeApiController::class, 'hrPolicies']);
Route::post(
    '/missing-punch-request',
    [RequestApiController::class,'storeMissingPunch']
);

Route::get(
    '/missing-punch-request/{employee_id}',
    [RequestApiController::class,'employeeMissingPunchRequests']
);

Route::get(
    '/approved-missing-punch-request/{employee_id}',
    [RequestApiController::class,'employeeApprovedMissingPunchRequests']
);
Route::get(
    '/attendance/{employee_id}',
    [DailyAttendanceApiController::class,'index']
);

Route::get(
    '/today-attendance/{employee_id}',
    [DailyAttendanceApiController::class,'todayAttendance']
);

Route::post(
    '/punch-in',
    [DailyAttendanceApiController::class,'punchIn']
);

Route::post(
    '/punch-out',
    [DailyAttendanceApiController::class,'punchOut']
);

Route::get('/employee/daily-attendance', [DailyAttendanceApiController::class, 'dailyAttendance']);
Route::get('/employee/monthly-attendance', [DailyAttendanceApiController::class, 'monthlyAttendance']);

Route::post(
    '/face-punch-in',
    [DailyAttendanceApiController::class,'facePunchIn']
);

Route::post(
    '/face-punch-out',
    [DailyAttendanceApiController::class,'facePunchOut']
);

Route::get(
    '/attendance/show/{id}',
    [DailyAttendanceApiController::class,'show']
);


Route::prefix('employee/wall')->group(function () {
    Route::get('/feed', [\App\Http\Controllers\Api\WallController::class, 'feed']);
    Route::post('/post', [\App\Http\Controllers\Api\WallController::class, 'storePost']);
    Route::post('/{post_id}/comment', [\App\Http\Controllers\Api\WallController::class, 'storeComment']);
    Route::post('/{post_id}/like', [\App\Http\Controllers\Api\WallController::class, 'toggleLike']);
    Route::delete('/{post_id}', [\App\Http\Controllers\Api\WallController::class, 'destroy']);
    Route::put('/{post_id}', [\App\Http\Controllers\Api\WallController::class, 'updatePost']);
    Route::delete('/comment/{comment_id}', [\App\Http\Controllers\Api\WallController::class, 'destroyComment']);
    Route::get('/suggestions', [\App\Http\Controllers\Api\WallController::class, 'suggestEmployees']);
});

Route::get('/employee/ticket-categories', [\App\Http\Controllers\Api\TicketApiController::class, 'categories']);

Route::prefix('employee/tickets')->group(function () {
    Route::get('/departments', [\App\Http\Controllers\Api\TicketApiController::class, 'departments']);
    Route::post('/create', [\App\Http\Controllers\Api\TicketApiController::class, 'store']);
    Route::get('/my-raised', [\App\Http\Controllers\Api\TicketApiController::class, 'myRaised']);
    Route::get('/my-assigned', [\App\Http\Controllers\Api\TicketApiController::class, 'myAssigned']);
    Route::get('/my-closed', [\App\Http\Controllers\Api\TicketApiController::class, 'myClosed']);
    Route::get('/{id}', [\App\Http\Controllers\Api\TicketApiController::class, 'show']);
    Route::post('/{id}/reply', [\App\Http\Controllers\Api\TicketApiController::class, 'reply']);
    Route::post('/{id}/status', [\App\Http\Controllers\Api\TicketApiController::class, 'status']);
});

Route::prefix('employee/helpdesk-permission')->group(function () {
    Route::post('/request', [\App\Http\Controllers\Api\TicketApiController::class, 'requestPermission']);
    Route::get('/status', [\App\Http\Controllers\Api\TicketApiController::class, 'checkPermissionStatus']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Alert & Notification Module APIs
    Route::prefix('alerts')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Api\AlertController::class, 'dashboard']);
        Route::get('/', [\App\Http\Controllers\Api\AlertController::class, 'index']);
        Route::post('/broadcast', [\App\Http\Controllers\Api\AlertController::class, 'broadcast']);
        Route::post('/{id}/read', [\App\Http\Controllers\Api\AlertController::class, 'markAsRead']);
        Route::post('/{id}/resolve', [\App\Http\Controllers\Api\AlertController::class, 'resolve']);
    });

    Route::prefix('alert-rules')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\AlertRuleController::class, 'index']);
        Route::put('/{id}', [\App\Http\Controllers\Api\AlertRuleController::class, 'update']);
    });
});
