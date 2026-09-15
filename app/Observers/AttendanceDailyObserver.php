<?php

namespace App\Observers;

use App\Models\AttendanceDaily;

class AttendanceDailyObserver
{
    /**
     * Handle the AttendanceDaily "created" event.
     */
    public function created(AttendanceDaily $attendanceDaily): void
    {
        $engine = new \App\Services\AlertEngineService();
        $engine->evaluateRealtimeAttendance($attendanceDaily);
    }

    /**
     * Handle the AttendanceDaily "updated" event.
     */
    public function updated(AttendanceDaily $attendanceDaily): void
    {
        // Only evaluate if 'is_late' or related fields changed
        if ($attendanceDaily->wasChanged('is_late') && $attendanceDaily->is_late) {
            $engine = new \App\Services\AlertEngineService();
            $engine->evaluateRealtimeAttendance($attendanceDaily);
        }
    }

    /**
     * Handle the AttendanceDaily "deleted" event.
     */
    public function deleted(AttendanceDaily $attendanceDaily): void
    {
        //
    }

    /**
     * Handle the AttendanceDaily "restored" event.
     */
    public function restored(AttendanceDaily $attendanceDaily): void
    {
        //
    }

    /**
     * Handle the AttendanceDaily "force deleted" event.
     */
    public function forceDeleted(AttendanceDaily $attendanceDaily): void
    {
        //
    }
}
