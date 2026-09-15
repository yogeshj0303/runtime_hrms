<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Activity;
use App\Models\User;
use App\Models\AttendanceDaily;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class EmployeeApiController extends Controller
{

public function updateProfile(Request $request, $user_id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'first_name' => 'required|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::with('employee')->find($user_id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'User not found'
        ], 404);
    }

    // Update users table
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    // Update employee table
    if ($user->employee) {
        $user->employee->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Profile updated successfully',
        'data' => [
            'user' => $user->fresh(),
            'employee' => $user->employee->fresh()
        ]
    ]);
}
    public function profile($user_id)
    {
        $user = User::with('employee')->find($user_id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile fetched successfully',
            'face_registered' => ($user->employee && !empty($user->employee->face_image)) ? 1 : 0,
            'assigned_latitude' => $user->employee ? $user->employee->assigned_latitude : null,
            'assigned_longitude' => $user->employee ? $user->employee->assigned_longitude : null,
            'assigned_radius' => $user->employee ? $user->employee->assigned_radius : null,
            'data' => [
                'user' => $user,
                'employee' => $user->employee
            ]
        ]);
    }

    public function employeeProfile($user_id)
    {
        $employee = Employee::with([
            'user', 
            'business', 
            'profile', 
            'addresses', 
            'documents' => function($query) {
                $query->where('is_hidden', false);
            }, 
            'assets', 
            'salaryRevisions', 
            'workProfiles', 
            'policyAssignment', 
            'familyMembers', 
            'identity', 
            'permission', 
            'loginAccess', 
            'additionalInformation'
        ])->where('user_id', $user_id)->first();

        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found for this user'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Employee profile fetched successfully',
            'face_registered' => !empty($employee->face_image) ? 1 : 0,
            'assigned_latitude' => $employee->assigned_latitude,
            'assigned_longitude' => $employee->assigned_longitude,
            'assigned_radius' => $employee->assigned_radius,
            'data' => $employee
        ]);
    }

    public function getAssets($user_id)
    {
        $employee = Employee::where('user_id', $user_id)->first();
        
        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found for this user'
            ], 404);
        }

        $mappedAssets = $employee->assets->map(function($a) {
            return [
                'asset_name' => $a->name ?? $a->asset_name ?? '',
                'asset_code' => $a->code ?? $a->asset_code ?? '',
                'assigned_date' => $a->pivot ? ($a->pivot->assigned_date ?? $a->pivot->created_at) : ($a->assigned_date ?? $a->created_at),
                'status' => $a->status ?? 'Working'
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Assets fetched successfully',
            'data' => $mappedAssets
        ]);
    }

    public function getPermissions($user_id)
    {
        $employee = Employee::where('user_id', $user_id)->first();
        
        if (!$employee) {
            return response()->json([
                'status' => false,
                'message' => 'Employee not found for this user'
            ], 404);
        }

        $permissions = $employee->permission ? $employee->permission->toArray() : [];
        $mappedPermissions = [];
        foreach ($permissions as $key => $value) {
            if (!in_array($key, ['id', 'employee_id', 'created_at', 'updated_at'])) {
                $mappedPermissions[$key] = $value ? 1 : 0;
            } else {
                $mappedPermissions[$key] = $value;
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Permissions fetched successfully',
            'data' => $mappedPermissions
        ]);
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'business_code' => 'required|string',
        'employee_code' => 'required|string',
        'otp' => 'required|string',
        'fcm_token' => 'required|string',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $employee = Employee::with(['loginAccess', 'permission'])
        ->where('business_code', $request->business_code)
        ->where('employee_code', $request->employee_code)
        ->first();

    if (!$employee) {
        return response()->json([
            'status' => false,
            'message' => 'Employee not found'
        ], 404);
    }

    if ($employee->otp != $request->otp) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP'
        ], 401);
    }

    $user = User::find($employee->user_id);

    $user->update([
        'fcm_token' => $request->fcm_token
    ]);

    Activity::create([
        'table_name' => 'employees',
        'table_id' => $employee->id,
        'title' => 'Employee Login',
        'description' => $employee->first_name . ' logged in successfully',
        'ip' => $request->ip(),
        'user_id' => $employee->user_id,
        'user_type' => 'employee',
    ]);

    $token = $user->createToken('app_token')->plainTextToken;

    return response()->json([
        'status' => true,
        'message' => 'Login successful',
        'token' => $token,
        'universal_user_id' => $user->id,
        'face_registered' => $employee->face_image ? 1 : 0,
        'employee' => $employee,
        'user' => $user
    ]);
}

public function logout(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::find($request->user_id);
    if ($user) {
        // Clear FCM token so they stop receiving push notifications
        $user->update(['fcm_token' => null]);
        
        // Invalidate active session/bearer token
        $user->tokens()->delete();

        $employee = Employee::where('user_id', $user->id)->first();
        if ($employee) {
            Activity::create([
                'table_name' => 'employees',
                'table_id' => $employee->id,
                'title' => 'Employee Logout',
                'description' => $employee->first_name . ' logged out successfully',
                'ip' => $request->ip(),
                'user_id' => $employee->user_id,
                'user_type' => 'employee',
            ]);
        }
    }

    return response()->json([
        'status' => true,
        'message' => 'Logged out successfully'
    ]);
}

public function changePassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'old_password' => 'required',
        'new_password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    $user = User::find($request->user_id);

    if (!\Hash::check($request->old_password, $user->password)) {
        return response()->json([
            'status' => false,
            'message' => 'Incorrect old password'
        ], 400);
    }

    $user->password = \Hash::make($request->new_password);
    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'Password updated successfully'
    ]);
}

public function dashboardSummary(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'business_id' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    }

    $employee = Employee::where('user_id', $request->user_id)->where('business_id', $request->business_id)->first();
    if (!$employee) return response()->json(['status' => false, 'message' => 'Employee not found'], 404);

    // 1. Today's Attendance
    // AttendanceDaily stores user_id in the employee_id column
    $attendance = AttendanceDaily::with('details')
        ->where('employee_id', $request->user_id)
        ->whereDate('attendance_date', Carbon::today())
        ->first();

    // 2. Requests Count
    $lastReqView = $employee->last_viewed_requests_at ?? Carbon::create(2000, 1, 1);
    $reqCount = 0;
    $reqCount += \App\Models\LeaveRequest::where('employee_id', $employee->id)->where('updated_at', '>', $lastReqView)->count();
    $reqCount += \App\Models\MissingPunchRequest::where('employee_id', $employee->id)->where('updated_at', '>', $lastReqView)->count();
    $reqCount += \App\Models\AttendanceRelaxation::where('employee_id', $employee->id)->where('updated_at', '>', $lastReqView)->count();
    
    // 3. Approvals Count
    $lastAppView = $employee->last_viewed_approvals_at ?? Carbon::create(2000, 1, 1);
    $appCount = 0;
    $appCount += \App\Models\LeaveRequest::where('business_id', $employee->business_id)->where('status', 'Pending')->where('updated_at', '>', $lastAppView)->count();
    $appCount += \App\Models\MissingPunchRequest::where('business_id', $employee->business_id)->where('status', 'Pending')->where('updated_at', '>', $lastAppView)->count();
    $appCount += \App\Models\AttendanceRelaxation::where('business_id', $employee->business_id)->where('status', 'Pending')->where('updated_at', '>', $lastAppView)->count();

    // 4. Status and Notifications
    $todayStatus = 'Absent';
    if ($attendance && $attendance->details->count() > 0) {
        $lastDetail = $attendance->details->last();
        $todayStatus = ($lastDetail->status_daily == 'punched_in') ? 'Punched In' : 'Punched Out';
    } elseif ($attendance) {
        $todayStatus = 'Present';
    }

    $unreadCount = \App\Models\Alert::where('user_id', $employee->user_id)
        ->where('status', 'pending')
        ->count() ?? 0;

    return response()->json([
        'status' => true,
        'pending_requests_count' => $reqCount,
        'pending_approvals_count' => $appCount,
        'unread_notifications_count' => $unreadCount,
        'today_status' => $todayStatus,
        'attendance' => $attendance,
    ]);
}

public function markViewed(Request $request)
{
    $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'type' => 'required|in:requests,approvals'
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    }

    $employee = Employee::where('user_id', $request->user_id)->first();
    if (!$employee) return response()->json(['status' => false, 'message' => 'Employee not found'], 404);

    if ($request->type == 'requests') {
        $employee->last_viewed_requests_at = now();
    } else {
        $employee->last_viewed_approvals_at = now();
    }
    $employee->save();

    return response()->json(['status' => true, 'message' => ucfirst($request->type) . ' marked as viewed']);
}

public function hrPolicies(Request $request)
{
    $validator = Validator::make($request->all(), [
        'business_id' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
    }

    $business_id = $request->business_id;

    $formattedPolicies = [];

    $leavePolicies = \App\Models\LeavePolicy::with('leaveType')->where('business_id', $business_id)->get();
    foreach($leavePolicies as $lp) {
        $formattedPolicies[] = [
            'title' => 'Leave Policy: ' . ($lp->leaveType->name ?? 'General'),
            'content_html' => '<p>' . ($lp->description ?? 'Refer to HR for details.') . '</p>',
            'pdf_url' => $lp->document_url ? asset($lp->document_url) : null,
            'last_updated' => $lp->updated_at ? $lp->updated_at->format('Y-m-d H:i:s') : null
        ];
    }

    $attPolicy = \App\Models\AttendanceSetting::where('business_id', $business_id)->first();
    if ($attPolicy) {
        $formattedPolicies[] = [
            'title' => 'Attendance Policy',
            'content_html' => '<p>General attendance and timing rules.</p>',
            'pdf_url' => null,
            'last_updated' => $attPolicy->updated_at ? $attPolicy->updated_at->format('Y-m-d H:i:s') : null
        ];
    }

    // You can extend this mapping for ShiftPolicy, OvertimePolicy, MaternityLeavePolicy, etc.

    return response()->json([
        'status' => true,
        'message' => 'HR Policies retrieved successfully',
        'data' => $formattedPolicies
    ]);
}

public function todayAttendance($employee_id)
{
    $attendance = AttendanceDaily::with('details')
        ->where('employee_id', $employee_id)
        ->whereDate('attendance_date', Carbon::today())
        ->first();

    if (!$attendance) {
        return response()->json([
            'status' => false,
            'message' => 'No attendance found for today'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'message' => 'Today attendance fetched successfully',
        'data' => $attendance
    ]);
}
    public function registerFace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'face_image' => 'required|string', // Base64 or image file depending on app
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::where('user_id', $request->user_id)->first();
        if (!$employee) {
            return response()->json(['status' => false, 'message' => 'Employee not found'], 404);
        }

        // Using base64 for face storage in this example
        $employee->face_image = $request->face_image;
        $employee->face_registered = 1;
        $employee->save();

        // Also run direct DB update as requested
        \Illuminate\Support\Facades\DB::table('employees')
            ->where('user_id', $request->user_id)
            ->update(['face_registered' => 1]);

        // Flush/delete the Redis cache for this user immediately after the database update
        \Illuminate\Support\Facades\Cache::forget('employee_profile_' . $request->user_id);
        \Illuminate\Support\Facades\Cache::forget('user_' . $request->user_id);

        return response()->json([
            'status' => true,
            'message' => 'Face registered successfully',
            'data' => [
                'face_image' => $employee->face_image,
                'face_registered' => 1
            ]
        ]);
    }

    public function getFace($user_id)
    {
        $employee = Employee::where('user_id', $user_id)->first();
        if (!$employee) {
            return response()->json(['status' => false, 'message' => 'Employee not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Registered face fetched successfully',
            'data' => [
                'face_image' => $employee->face_image
            ]
        ]);
    }

    /**
     * Standalone Verification API (For testing face matching before punch in)
     */
    public function verifyFace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'business_id' => 'required',
            'face_image' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::where(function($q) use ($request) {
            $q->where('user_id', $request->employee_id)
              ->orWhere('employee_code', $request->employee_id)
              ->orWhere('id', $request->employee_id);
        })->where(function($q) use ($request) {
            $q->where('business_id', $request->business_id)
              ->orWhere('business_code', $request->business_id);
        })->first();

        if (!$employee || empty($employee->face_image)) {
            return response()->json([
                'status' => false, 
                'message' => 'Employee has no registered face.'
            ], 404);
        }

        $verificationResult = static::performAIFaceVerification($employee->face_image, $request->face_image);
        $confidence = $verificationResult['confidence'];
        
        $threshold = 80.0;

        if ($confidence >= $threshold) {
            return response()->json([
                'status' => true,
                'confidence' => $confidence,
                'message' => 'Face matched successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'confidence' => $confidence,
                'message' => 'Face match too low. Unauthorized face.'
            ], 400);
        }
    }

    /**
     * Mock AI Face Verification (Always returns true for now)
     * Replace this logic with AWS Rekognition, Azure Face API, or Face++ 
     */
    public static function performAIFaceVerification($registered_face_base64, $live_face_base64)
    {
        $apiKey = env('FACEPLUSPLUS_API_KEY');
        $apiSecret = env('FACEPLUSPLUS_API_SECRET');
        
        // If keys are not set, return mock confidence
        if (empty($apiKey) || empty($apiSecret)) {
            return ['status' => true, 'confidence' => 92.5];
        }

        // Strip data:image/... prefix if present
        $registered = preg_replace('#^data:image/\w+;base64,#i', '', $registered_face_base64);
        $live = preg_replace('#^data:image/\w+;base64,#i', '', $live_face_base64);

        try {
            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://api-us.faceplusplus.com/facepp/v3/compare', [
                'api_key' => $apiKey,
                'api_secret' => $apiSecret,
                'image_base64_1' => $registered,
                'image_base64_2' => $live,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['confidence'])) {
                    return ['status' => true, 'confidence' => $data['confidence']];
                }
            } else {
                \Illuminate\Support\Facades\Log::error('Face++ API Response Error: ' . $response->body());
            }
            
            return ['status' => false, 'confidence' => 0.0];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Face++ API Exception: ' . $e->getMessage());
            return ['status' => false, 'confidence' => 0.0];
        }
    }

    public function getAppSettings($user_id)
    {
        $employee = Employee::where('user_id', $user_id)->first();
        if (!$employee) {
            return response()->json(['status' => false, 'message' => 'Employee not found'], 404);
        }

        $defaultSettings = [
            'push_notifications' => true,
            'email_notifications' => true,
            'biometric_login' => false,
            'dark_mode' => false,
            'language' => 'English',
            'date_format' => 'Y-m-d',
            'font_size' => 'Medium'
        ];

        $currentSettings = $employee->app_settings ? json_decode($employee->app_settings, true) : [];
        $mergedSettings = array_merge($defaultSettings, (array)$currentSettings);

        return response()->json([
            'status' => true,
            'message' => 'Settings fetched successfully',
            'data' => $mergedSettings
        ]);
    }

    public function updateAppSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'settings' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = Employee::where('user_id', $request->user_id)->first();
        if (!$employee) {
            return response()->json(['status' => false, 'message' => 'Employee not found'], 404);
        }

        $currentSettings = $employee->app_settings ? json_decode($employee->app_settings, true) : [];
        $newSettings = array_merge((array)$currentSettings, $request->settings);

        $employee->app_settings = json_encode($newSettings);
        $employee->save();

        return response()->json([
            'status' => true,
            'message' => 'Settings updated successfully',
            'data' => $newSettings
        ]);
    }
}