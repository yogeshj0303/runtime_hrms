<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AttendanceModuleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Assuming user ID 1 exists and is an admin
        $user = User::first();
        if($user) {
            $this->actingAs($user);
        } else {
            $this->withoutMiddleware();
        }
    }

    public function test_monthly_attendance_page_loads()
    {
        $response = $this->get(route("attendance.monthly", ["month_year" => date("Y-m")]));
        if ($response->status() !== 200) {
            dump($response->exception ? $response->exception->getMessage() : "No exception message");
        }
        $response->assertStatus(200);
    }

    public function test_manual_attendance_page_loads()
    {
        $response = $this->get(route("attendance.manual"));
        if ($response->status() !== 200) {
            dump($response->exception ? $response->exception->getMessage() : "No exception message");
        }
        $response->assertStatus(200);
    }

    public function test_shift_roster_page_loads()
    {
        $response = $this->get(route("attendance.shift-roster"));
        if ($response->status() !== 200) {
            dump($response->exception ? $response->exception->getMessage() : "No exception message");
        }
        $response->assertStatus(200);
    }
}
