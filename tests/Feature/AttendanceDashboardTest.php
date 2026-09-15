<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AttendanceDashboardTest extends TestCase
{
    public function test_attendance_dashboard_loads()
    {
        $user = User::first();
        if(!$user) {
            $this->markTestSkipped("No user found.");
        }
        $response = $this->actingAs($user)->get("/business/dashboards/attendance");
        if ($response->status() !== 200) {
            dump($response->exception ? $response->exception->getMessage() : "No exception message");
        }
        $response->assertStatus(200);
    }
}
