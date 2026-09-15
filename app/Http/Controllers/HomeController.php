<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Business;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if ($request->path() == 'index') {
            return redirect()->route('root');
        }
        
        if (view()->exists($request->path())) {
            return view($request->path());
        }
        return abort(404);
    }

public function root(\Illuminate\Http\Request $request)
{
    $user = Auth::user();

    $business = Business::where(
        'id',
        $user->active_business_id
    )
    ->where(
        'user_id',
        $user->id
    )
    ->first();
  

    if (!$business) {
        $user->update([
            'active_business_id' => null
        ]);
        return redirect()->route('business.dashboard');
    }

    $business_id = $business->id;

    // 1. Employees
    $maxEmployees = $business->max_employees ?? 'Unlimited';
    $totalEmployees = \App\Models\Employee::where('business_id', $business_id)->count();
    $activeEmployees = \App\Models\Employee::where('business_id', $business_id)->where('status', 'Active')->count();
    $inactiveEmployees = $totalEmployees - $activeEmployees;

    // 2. Active Mobile Users
    $activeMobileUsers = \App\Models\EmployeeLoginAccess::whereHas('employee', function($q) use ($business_id) {
        $q->where('business_id', $business_id)->where('status', 'Active')
          ->whereExists(function ($query) {
              $query->select(\Illuminate\Support\Facades\DB::raw(1))
                    ->from('employee_activity_logs')
                    ->whereColumn('employee_activity_logs.employee_id', 'employees.id')
                    ->where('created_at', '>=', now()->subDays(30));
          });
    })->where('mobile_login', 1)->count();

    // 3. Open Requests
    $missedPunches = \App\Models\MissingPunchRequest::where('business_id', $business_id)->where('status', 'Pending')->count();
    $helpdeskRequests = \App\Models\HelpdeskPermissionRequest::where('business_id', $business_id)->where('status', 'Pending')->count();
    $pendingLeaves = \App\Models\LeaveRequest::where('business_id', $business_id)->where('status', 'Pending')->count();

    // 4. Upcoming Events (Birthdays and Anniversaries this month)
    $currentMonth = date('m');
    $upcomingBirthdays = \App\Models\Employee::where('business_id', $business_id)
        ->whereHas('profile', function($q) use ($currentMonth) {
            $q->whereMonth('dob', $currentMonth);
        })
        ->with('profile')
        ->get()
        ->map(function($emp) {
            return [
                'name' => $emp->first_name . ' ' . $emp->last_name,
                'type' => 'Birthday',
                'date' => $emp->profile->dob
            ];
        });

    $upcomingAnniversaries = \App\Models\Employee::where('business_id', $business_id)
        ->whereMonth('joining_date', $currentMonth)
        ->get()
        ->map(function($emp) {
            return [
                'name' => $emp->first_name . ' ' . $emp->last_name,
                'type' => 'Work Anniversary',
                'date' => $emp->joining_date
            ];
        });

    $todayDay = (int)date('d');
    $upcomingEvents = $upcomingBirthdays->concat($upcomingAnniversaries)->filter(function($event) use ($todayDay) {
        return (int)date('d', strtotime($event['date'])) >= $todayDay;
    })->sortBy(function($event) {
        return date('d', strtotime($event['date']));
    })->take(5);

    // 5. Chart Data
    $filter = $request->input('chart_filter', 'week'); // 'today', 'week', 'month', 'year'

    $chartLabels = [];
    $presentData = [];
    $absentData = [];
    $halfDayData = [];
    $holidayData = [];
    $weekOffData = [];
    
    $allUserIds = \App\Models\Employee::where('business_id', $business_id)->pluck('user_id')->toArray();
    $allEmployeeIds = \App\Models\Employee::where('business_id', $business_id)->pluck('id')->toArray();
    $employeesCount = count($allUserIds);

    // Prepare date ranges and labels
    $dates = [];
    $today = date('Y-m-d');

    if ($filter == 'today') {
        $dates[] = $today;
        $chartLabels[] = 'Today';
    } elseif ($filter == 'week') {
        $startOfWeek = \Carbon\Carbon::now()->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            $dates[] = $date;
            $chartLabels[] = \Carbon\Carbon::parse($date)->format('D'); // Mon, Tue...
        }
    } elseif ($filter == 'month') {
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
        $daysInMonth = \Carbon\Carbon::now()->daysInMonth;
        for ($i = 0; $i < $daysInMonth; $i++) {
            $date = $startOfMonth->copy()->addDays($i)->format('Y-m-d');
            $dates[] = $date;
            $chartLabels[] = (string)($i + 1); // 1, 2, ...
        }
    } elseif ($filter == 'year') {
        for ($i = 1; $i <= 12; $i++) {
            $chartLabels[] = \Carbon\Carbon::create()->month($i)->format('M'); // Jan, Feb...
        }
    }

    if ($filter != 'year') {
        // Daily calculations for today, week, month
        foreach ($dates as $date) {
            if ($date > $today) {
                $presentData[] = 0;
                $halfDayData[] = 0;
                $absentData[] = 0;
                $holidayData[] = 0;
                $weekOffData[] = 0;
                continue;
            }

            $present = \App\Models\AttendanceDaily::where('business_id', $business_id)
                ->whereIn('employee_id', $allUserIds)
                ->where('attendance_date', $date)
                ->where('status', 'Present')
                ->count();

            $halfDay = \App\Models\AttendanceDaily::where('business_id', $business_id)
                ->whereIn('employee_id', $allUserIds)
                ->where('attendance_date', $date)
                ->where('status', 'Half_Day')
                ->count();

            $leaves = \App\Models\LeaveRequest::where('business_id', $business_id)
                ->whereIn('employee_id', $allEmployeeIds)
                ->where('status', 'Approved')
                ->where('from_date', '<=', $date)
                ->where('to_date', '>=', $date)
                ->count();

            $isWeekend = (date('w', strtotime($date)) == 0 || date('w', strtotime($date)) == 6);
            $isHoliday = \App\Models\Holiday::where('business_id', $business_id)->where('date', $date)->exists();

            if ($isWeekend) {
                $expected = 0;
                $absent = max(0, 0 - $present - $halfDay - $leaves); 
                $weekOff = $employeesCount;
                $holiday = 0;
            } elseif ($isHoliday) {
                $expected = 0;
                $absent = max(0, 0 - $present - $halfDay - $leaves); 
                $weekOff = 0;
                $holiday = $employeesCount;
            } else {
                $expected = $employeesCount;
                $absent = max(0, $expected - $present - $halfDay - $leaves);
                $weekOff = 0;
                $holiday = 0;
            }

            $presentData[] = $present;
            $halfDayData[] = $halfDay;
            $absentData[] = $absent;
            $holidayData[] = $holiday;
            $weekOffData[] = $weekOff;
        }
    } else {
        // Yearly calculation (aggregate by month)
        $currentYear = date('Y');
        $currentMonthIndex = (int)date('m');
        
        $presentData = array_fill(0, 12, 0);
        $halfDayData = array_fill(0, 12, 0);
        $absentData = array_fill(0, 12, 0);
        $holidayData = array_fill(0, 12, 0);
        $weekOffData = array_fill(0, 12, 0);
        
        $monthlyAttendance = \App\Models\AttendanceDaily::where('business_id', $business_id)
            ->whereIn('employee_id', $allUserIds)
            ->whereYear('attendance_date', $currentYear)
            ->selectRaw('MONTH(attendance_date) as month, status, count(*) as total')
            ->groupBy('month', 'status')
            ->get();

        foreach($monthlyAttendance as $att) {
            $mIndex = $att->month - 1;
            if(strtolower($att->status) == 'present') {
                $presentData[$mIndex] += $att->total;
            } else if (strtolower($att->status) == 'half_day') {
                $halfDayData[$mIndex] += $att->total;
            }
        }
        
        for ($month = 1; $month <= 12; $month++) {
            $mIndex = $month - 1;
            
            if ($month > $currentMonthIndex) {
                continue;
            }

            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
            $weekends = 0;
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = sprintf('%04d-%02d-%02d', $currentYear, $month, $d);
                if ($date > $today) break; 
                $dayOfWeek = date('w', strtotime($date));
                if ($dayOfWeek == 0 || $dayOfWeek == 6) { 
                    $weekends++;
                }
            }

            $holidays = \App\Models\Holiday::where('business_id', $business_id)
                ->whereYear('date', $currentYear)
                ->whereMonth('date', $month)
                ->where('date', '<=', $today)
                ->count();

            $daysElapsed = ($month == $currentMonthIndex) ? (int)date('d') : $daysInMonth;
            
            $workingDays = max(0, $daysElapsed - $weekends - $holidays);
            $totalExpected = $workingDays * $employeesCount;

            $leaves = \App\Models\LeaveRequest::where('business_id', $business_id)
                ->whereIn('employee_id', $allEmployeeIds)
                ->where('status', 'Approved')
                ->whereYear('from_date', $currentYear)
                ->whereMonth('from_date', $month)
                ->sum('total_days');

            $absent = max(0, $totalExpected - $presentData[$mIndex] - $leaves - $halfDayData[$mIndex]);

            // Avoid huge fake absents if system was totally unused
            if ($presentData[$mIndex] == 0 && $halfDayData[$mIndex] == 0 && $leaves == 0) {
                $absent = 0;
            }
            
            $absentData[$mIndex] = $absent;
            $holidayData[$mIndex] = $holidays * $employeesCount;
            $weekOffData[$mIndex] = $weekends * $employeesCount;
        }
    }

    // Convert to percentages
    foreach ($presentData as $k => $v) {
        $expected = $presentData[$k] + $halfDayData[$k] + $absentData[$k] + $holidayData[$k] + $weekOffData[$k];
        if ($expected > 0) {
            $presentData[$k] = round(($presentData[$k] / $expected) * 100, 1);
            $halfDayData[$k] = round(($halfDayData[$k] / $expected) * 100, 1);
            $absentData[$k] = round(($absentData[$k] / $expected) * 100, 1);
            $holidayData[$k] = round(($holidayData[$k] / $expected) * 100, 1);
            $weekOffData[$k] = round(($weekOffData[$k] / $expected) * 100, 1);
        } else {
            $presentData[$k] = 0;
            $halfDayData[$k] = 0;
            $absentData[$k] = 0;
            $holidayData[$k] = 0;
            $weekOffData[$k] = 0;
        }
    }

    $subscriptionValidity = 'Active';
    $subscriptionDueDate = '2026-12-31';

    return view('index', compact(
        'maxEmployees', 'subscriptionValidity', 'subscriptionDueDate',
        'totalEmployees', 'activeEmployees', 'inactiveEmployees',
        'activeMobileUsers', 'missedPunches', 'helpdeskRequests', 'pendingLeaves',
        'upcomingEvents', 'presentData', 'absentData', 'halfDayData', 'holidayData', 'weekOffData', 'chartLabels', 'filter'
    ));
}

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $user = User::find($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');

        if ($request->file('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = public_path('/images/');
            $avatar->move($avatarPath, $avatarName);
            $user->avatar =  $avatarName;
        }

        $user->update();
        if ($user) {
            Session::flash('message', 'User Details Updated successfully!');
            Session::flash('alert-class', 'alert-success');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "User Details Updated successfully!"
            // ], 200); // Status code here
            return redirect()->back();
        } else {
            Session::flash('message', 'Something went wrong!');
            Session::flash('alert-class', 'alert-danger');
            // return response()->json([
            //     'isSuccess' => true,
            //     'Message' => "Something went wrong!"
            // ], 200); // Status code here
            return redirect()->back();

        }
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            return response()->json([
                'isSuccess' => false,
                'Message' => "Your Current password does not matches with the password you provided. Please try again."
            ], 200); // Status code
        } else {
            $user = User::find($id);
            $user->password = Hash::make($request->get('password'));
            $user->update();
            if ($user) {
                Session::flash('message', 'Password updated successfully!');
                Session::flash('alert-class', 'alert-success');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Password updated successfully!"
                ], 200); // Status code here
            } else {
                Session::flash('message', 'Something went wrong!');
                Session::flash('alert-class', 'alert-danger');
                return response()->json([
                    'isSuccess' => true,
                    'Message' => "Something went wrong!"
                ], 200); // Status code here
            }
        }
    }
}
