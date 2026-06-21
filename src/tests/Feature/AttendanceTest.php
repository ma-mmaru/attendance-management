<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'is_admin' => false
        ]);
        Carbon::setTestNow(Carbon::create(2026, 6, 1, 9, 0, 0));
    }
    public function test_rest_in_button_functions_correctly()
    {
        $attendance = AttendanceRecord::create([
            'user_id' => $this->user->id,
            'date'    => '2026-06-01',
            'clock_in' => '09:00:00',
            'status'  => '出勤中',
        ]);
        $response = $this->actingAs($this->user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertSee('休憩入');
        $response->assertSee('出勤中');
        Carbon::setTestNow(Carbon::create(2026, 6, 1, 12, 0, 0));
        $restResponse = $this->post(route('attendance.rest-in'));
        $restResponse->assertRedirect(route('attendance.index'));
        $finalResponse = $this->get(route('attendance.index'));
        $finalResponse->assertSee('休憩中');
        $finalResponse->assertDontSee('出勤中');
    }
    public function test_can_take_rest_multiple_times_a_day()
    {
        $attendance = AttendanceRecord::create([
            'user_id' => $this->user->id,
            'date'    => '2026-06-01',
            'clock_in' => '09:00:00',
            'status'  => '出勤中',
        ]);
        $attendance->restRecords()->create([
            'rest_in'  => '12:00:00',
            'rest_out' => '13:00:00'
        ]);
        $attendance->update(['status' => '出勤中']);
        $response = $this->actingAs($this->user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertSee('休憩入');
    }
    public function test_rest_out_button_functions_correctly()
    {
        $attendance = AttendanceRecord::create([
            'user_id' => $this->user->id,
            'date'    => '2026-06-01',
            'clock_in' => '09:00:00',
            'status'  => '休憩中',
        ]);
        $attendance->restRecords()->create([
            'rest_in' => '12:00:00'
        ]);
        $response = $this->actingAs($this->user)->get(route('attendance.index'));
        $response->assertSee('休憩戻');
        $response->assertSee('休憩中');
        Carbon::setTestNow(Carbon::create(2026, 6, 1, 13, 0, 0));
        $restOutResponse = $this->post(route('attendance.rest-out'));
        $restOutResponse->assertRedirect(route('attendance.index'));
        $finalResponse = $this->get(route('attendance.index'));
        $finalResponse->assertSee('出勤中');
        $finalResponse->assertDontSee('休憩中');
    }
    public function test_can_rest_out_multiple_times_a_day()
    {
        $attendance = AttendanceRecord::create([
            'user_id' => $this->user->id,
            'date'    => '2026-06-01',
            'clock_in' => '09:00:00',
            'status'  => '休憩中',
        ]);
        $attendance->restRecords()->create(['rest_in' => '12:00:00', 'rest_out' => '13:00:00']);
        Carbon::setTestNow(Carbon::create(2026, 6, 1, 15, 0, 0));
        $attendance->restRecords()->create(['rest_in' => '15:00:00']);
        $attendance->update(['status' => '休憩中']);
        $attendance->refresh();
        $response = $this->actingAs($this->user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertSee('休憩戻');
    }
    public function test_rest_time_is_visible_on_attendance_list()
    {
        $attendance = AttendanceRecord::create([
            'user_id'  => $this->user->id,
            'date'     => '2026-06-01',
            'clock_in' => '09:00:00',
            'clock_out' => '18:00:00',
            'status'   => '退勤済み',
        ]);
        $attendance->restRecords()->create([
            'rest_in'  => '12:00:00',
            'rest_out' => '13:00:00'
        ]);
        $attendance->refresh();
        $response = $this->actingAs($this->user)->get(route('attendance.list'));
        $response->assertStatus(200);
        $response->assertSee('06/01');
        $response->assertSee('01:00');
    }
}