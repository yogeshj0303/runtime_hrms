<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\{
    User, Business, BusinessUnit, Location, Department, Designation, Grade, Employee,
    ShiftPolicy, WeekOffPolicy, LeavePolicy, OvertimePolicy, TimeRule, SalaryStructure,
    EmployeeAddress, EmployeeIdentity, EmployeeAsset, EmployeeFamilyMember, EmployeeDocument,
    EmployeeAdditionalInformation, EmployeeProfile, EmployeePermission, EmployeeLoginAccess,
    EmployeeWorkProfile, EmployeePolicy, EmployeeSalaryRevision, EmployeeShiftHistory,
    ShiftRoster, AttendanceDaily, AttendanceDailyDetail, LeaveRequest, LeaveBalance, Shift,
    LeaveType, UserBusinessSession, EmployeeTdsCapture, EmployeeItDeclarationDetail,
    EpfSetting, EsiSetting, PtaxSetting, LwfSetting, SalaryComponent
};

class FullEmployeeTestSeeder extends Seeder
{
    public function run(): void
    {
        $userId = 1;
        
        // 1. Core Business Setup
        $business = Business::updateOrCreate(
            ['business_name' => 'Test Business Corp'],
            [
                'business_code' => 'TBC01',
                'address' => '123 Test Street',
                'city' => 'Test City',
                'state' => 'Test State',
                'pincode' => '123456',
                'business_constitution' => 'Private Limited',
                'current_step' => 6,
                'is_completed' => true,
                'status' => 'active',
                'user_id' => $userId,
            ]
        );

        $businessUnit = BusinessUnit::updateOrCreate(
            ['business_id' => $business->id, 'unit_name' => 'HQ Unit'],
            ['report_title' => 'HQ', 'user_id' => $userId]
        );

        // Statutory Settings Setup
        EpfSetting::updateOrCreate(
            ['business_id' => $business->id],
            [
                'user_id' => $userId,
                'effective_from' => '2023-01-01',
                'employee_contribution_rate' => 12.00,
                'employer_contribution_rate' => 12.00,
                'pension_contribution_rate' => 8.33,
                'edli_contribution_rate' => 0.50,
                'admin_charges_rate' => 0.50,
                'wage_ceiling' => 15000,
                'is_enabled' => true,
            ]
        );

        EsiSetting::updateOrCreate(
            ['business_id' => $business->id],
            [
                'user_id' => $userId,
                'effective_from' => '2023-01-01',
                'employee_contribution' => 0.75,
                'employer_contribution' => 3.25,
                'gross_wage_ceiling' => 21000,
                'is_enabled' => true,
            ]
        );

        PtaxSetting::updateOrCreate(
            ['business_id' => $business->id],
            [
                'user_id' => $userId,
                'calculation_basis' => 'Gross Salary',
                'is_enabled' => true,
            ]
        );
        
        \App\Models\PtaxSlab::updateOrCreate(
            ['business_id' => $business->id, 'state' => 'Madhya Pradesh', 'salary_from' => 0],
            ['user_id' => $userId, 'salary_to' => 999999, 'tax_amount' => 208, 'effective_date' => '2023-01-01', 'gender' => 'Male']
        );
        
        \App\Models\PtaxSlab::updateOrCreate(
            ['business_id' => $business->id, 'state' => 'Andhra Pradesh', 'salary_from' => 0],
            ['user_id' => $userId, 'salary_to' => 999999, 'tax_amount' => 150, 'effective_date' => '2023-01-01', 'gender' => 'Male']
        );

        LwfSetting::updateOrCreate(
            ['business_id' => $business->id, 'state' => 'Maharashtra'],
            [
                'user_id' => $userId,
                'effective_from' => '2023-01-01',
                'employee_contribution_rate' => 0,
                'employer_contribution_rate' => 0,
                'employee_contribution_type' => 'Fixed Amount',
                'employer_contribution_type' => 'Fixed Amount',
                'employee_contribution_amount' => 12,
                'employer_contribution_amount' => 36,
                'is_enabled' => true,
            ]
        );
        
        LwfSetting::updateOrCreate(
            ['business_id' => $business->id, 'state' => 'Andhra Pradesh'],
            [
                'user_id' => $userId,
                'effective_from' => '2023-01-01',
                'employee_contribution_rate' => 0,
                'employer_contribution_rate' => 0,
                'employee_contribution_type' => 'Fixed Amount',
                'employer_contribution_type' => 'Fixed Amount',
                'employee_contribution_amount' => 30,
                'employer_contribution_amount' => 70,
                'is_enabled' => true,
            ]
        );

        $location = Location::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Corporate Office'],
            ['state' => 'Test State', 'user_id' => $userId]
        );

        $department = Department::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Engineering'],
            ['user_id' => $userId]
        );

        $designation1 = Designation::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Junior Developer'],
            ['user_id' => $userId]
        );
        $designation2 = Designation::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Senior Developer'],
            ['user_id' => $userId]
        );

        $grade = Grade::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'A1'],
            ['user_id' => $userId]
        );

        // 2. Setup Policies
        $shift = Shift::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Standard General Shift'],
            [
                'code' => 'GEN01',
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'break_time_minutes' => 60,
                'shift_type' => 'fixed',
                'status' => 'active',
                'user_id' => $userId,
            ]
        );

        $shiftPolicy = ShiftPolicy::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Default General Policy'],
            [
                'is_default' => true,
                'default_shift_id' => $shift->id,
                'user_id' => $userId,
            ]
        );

        $weekOffPolicy = WeekOffPolicy::updateOrCreate(
            ['business_id' => $business->id, 'name' => '5-Day Work Week'],
            [
                'is_default' => true,
                'saturday_weeks' => json_encode([1, 2, 3, 4, 5]),
                'sunday_weeks' => json_encode([1, 2, 3, 4, 5]),
                'user_id' => $userId,
            ]
        );

        $leaveType = LeaveType::updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Annual Leave'],
            ['short_name' => 'AL', 'is_paid_leave' => true, 'user_id' => $userId]
        );

        $leavePolicy = LeavePolicy::updateOrCreate(
            ['business_id' => $business->id, 'leave_type_id' => $leaveType->id],
            [
                'policy_description' => 'Standard Annual Leave',
                'jan_grant' => 2,
                'feb_grant' => 2,
                'grant_leaves' => true,
                'user_id' => $userId,
            ]
        );

        $overtimePolicy = OvertimePolicy::updateOrCreate(
            ['business_id' => $business->id, 'policy_name' => 'Standard OT'],
            ['auth_id' => $userId]
        );

        $salaryStructure = SalaryStructure::updateOrCreate(
            ['business_id' => $business->id, 'structure_name' => 'Standard Developer Structure'],
            ['auth_id' => $userId]
        );

        // 3. Base Employee Profile
        $employee = Employee::updateOrCreate(
            ['employee_code' => 'EMP-TEST-001', 'business_id' => $business->id],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe.test@example.com',
                'phone' => '9988776655',
                'gender' => 'Male',
                'dob' => '1990-01-01',
                'joining_date' => '2023-01-01',
                'status' => 'active',
                'user_id' => $userId,
            ]
        );

        EmployeeAddress::updateOrCreate(
            ['employee_id' => $employee->id, 'type' => 'current'],
            ['address1' => '456 Test Ave', 'city' => 'Testville', 'state' => 'Test State', 'zipcode' => '654321']
        );

        EmployeeIdentity::updateOrCreate(
            ['employee_id' => $employee->id],
            ['pan_number' => 'ABCDE1234F', 'aadhaar_number' => '123456789012']
        );

        EmployeeFamilyMember::updateOrCreate(
            ['employee_id' => $employee->id, 'name' => 'Jane Doe'],
            ['relation' => 'Spouse', 'dob' => '1992-05-05', 'phone' => '8877665544']
        );

        EmployeePermission::updateOrCreate(
            ['employee_id' => $employee->id],
            ['web_chat_punch' => true, 'selfie_at_all_locations' => false, 'live_travel_attendance' => true, 'auto_punch_in_out' => false]
        );

        // 4. Versioned Records (Promotion Simulation)
        // Old Work Profile
        EmployeeWorkProfile::updateOrCreate(
            ['employee_id' => $employee->id, 'designation_id' => $designation1->id],
            [
                'effective_from' => '2023-01-01',
                'business_unit_id' => $businessUnit->id,
                'location_id' => $location->id,
                'department_id' => $department->id,
                'grade_id' => $grade->id,
                'is_current' => false,
                'is_promotion' => false,
            ]
        );
        // Current Work Profile (Promotion)
        EmployeeWorkProfile::updateOrCreate(
            ['employee_id' => $employee->id, 'designation_id' => $designation2->id],
            [
                'effective_from' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                'business_unit_id' => $businessUnit->id,
                'location_id' => $location->id,
                'department_id' => $department->id,
                'grade_id' => $grade->id,
                'is_current' => true,
                'is_promotion' => true,
            ]
        );

        // Current Policy Assignment
        $policy = EmployeePolicy::updateOrCreate(
            ['employee_id' => $employee->id, 'is_current' => true],
            [
                'business_id' => $business->id,
                'effective_from' => Carbon::now()->startOfMonth()->format('Y-m-d'),
                'shift_policy_id' => $shiftPolicy->id,
                'week_off_policy_id' => $weekOffPolicy->id,
                'overtime_policy_id' => $overtimePolicy->id,
                'auto_shift_selection' => false,
                'updated_by' => $userId,
            ]
        );
        $policy->leavePolicies()->sync([$leavePolicy->id]);

        // Current Salary Revision
        EmployeeSalaryRevision::updateOrCreate(
            ['employee_id' => $employee->id, 'effective_from' => Carbon::now()->startOfMonth()->format('Y-m-d')],
            [
                'ctc' => 1200000,
                'gross_salary' => 100000,
                'basic_salary' => 50000,
                'is_increment' => true,
            ]
        );

        // 5. Attendance & Roster
        $today = Carbon::now();
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            ShiftRoster::updateOrCreate(
                ['employee_id' => $employee->id, 'roster_date' => $date],
                [
                    'business_id' => $business->id,
                    'shift_id' => $shift->id,
                    'is_published' => true,
                    'assigned_by' => $userId,
                ]
            );

            if (!Carbon::parse($date)->isWeekend()) {
                $daily = AttendanceDaily::updateOrCreate(
                    ['employee_id' => $employee->id, 'attendance_date' => $date],
                    [
                        'business_id' => $business->id,
                        'day' => Carbon::parse($date)->format('l'),
                        'status' => 'Present',
                        'working_time_for_day' => 540,
                        'total_working_time' => 540,
                    ]
                );
                AttendanceDailyDetail::updateOrCreate(
                    ['attendance_daily_id' => $daily->id, 'employee_id' => $employee->id, 'business_id' => $business->id],
                    [
                        'punch_in_time' => $date . ' 09:00:00',
                        'punch_out_time' => $date . ' 18:00:00',
                        'device_name' => 'Web',
                        'total_working_time' => 540,
                        'status_daily' => 'punched_out'
                    ]
                );
            }
        }

        LeaveBalance::updateOrCreate(
            ['employee_id' => $employee->id, 'leave_type_id' => $leaveType->id],
            ['balance' => 15, 'business_id' => $business->id]
        );

        // 6. Statutory & IT
        EmployeeTdsCapture::updateOrCreate(
            ['employee_id' => $employee->id, 'payroll_month' => Carbon::now()->format('Y-m')],
            ['amount' => 5000]
        );

    }
}
