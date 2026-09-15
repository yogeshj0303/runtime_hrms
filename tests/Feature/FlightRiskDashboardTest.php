<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class FlightRiskDashboardTest extends TestCase
{
    public function test_flight_risk_dashboard_loads()
    {
        $user = User::first();
        if(!$user) {
            $this->markTestSkipped("No user found.");
        }
        
        $response = $this->actingAs($user)->get("/business/dashboards/flight-risk");
        $response->assertStatus(200);
        $response->assertSee("Flight Risk");
    }
}
