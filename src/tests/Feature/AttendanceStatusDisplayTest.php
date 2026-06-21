<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class AttendanceStatusDisplayTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::create(2026, 6, 20, 10, 0, 0));
    }
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }
    public function test_status_is_off_work_when_no_record_exists(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertViewHas('status', '勤務外');
        $response->assertSee('勤務外');
    }
    public function test_status_is_working_when_clocked_in(): void
    {
        $user = User::factory()->create();
        AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => '2026-06-20',
            'clock_in' => '09:00:00',
        ]);
        $response = $this->actingAs($user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertViewHas('status', '出勤中');
        $response->assertSee('出勤中');
    }
    public function test_status_is_resting_when_rest_in_active(): void
    {
        $user = User::factory()->create();
        $attendance = AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => '2026-06-20',
            'clock_in' => '09:00:00',
        ]);
        $attendance->restRecords()->create([
            'rest_in' => '12:00:00',
        ]);
        $response = $this->actingAs($user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertViewHas('status', '休憩中');
        $response->assertSee('休憩中');
    }
    public function test_status_is_clocked_out_when_attendance_completed(): void
    {
        $user = User::factory()->create();
        AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => '2026-06-20',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
        ]);
        $response = $this->actingAs($user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertViewHas('status', '退勤済');
        $response->assertSee('退勤済');
    }
}