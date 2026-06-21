<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class AttendanceTimeDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_attendance_page_displays_current_date_and_time_in_correct_format(): void
    {
        $testDateTime = Carbon::create(2026, 6, 20, 14, 30, 0);
        Carbon::setTestNow($testDateTime);
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertViewHas('dateString', '2026年6月20日(土)');
        $response->assertViewHas('timeString', '14:30');
        $response->assertSee('2026年6月20日(土)');
        $response->assertSee('14:30');
        Carbon::setTestNow();
    }
}