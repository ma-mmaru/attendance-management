<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class ClockInFunctionTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::create(2026, 6, 20, 9, 0, 0));
    }
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }
    public function test_clock_in_button_works_and_changes_status_to_working(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('attendance.index'));
        $response->assertStatus(200);
        $response->assertSee('出勤');
        $response = $this->actingAs($user)->post(route('attendance.clock-in'));
        $response->assertRedirect(route('attendance.index'));
        $response = $this->actingAs($user)->get(route('attendance.index'));
        $response->assertSee('出勤中');
    }
    public function test_clock_in_button_is_not_visible_when_already_clocked_out(): void
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
        $response->assertDontSee('出勤');
    }

    // 勤怠一覧画面未作成のため作成後にテストを実行する
    public function test_clock_in_time_is_recorded_and_visible_on_summary_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('attendance.clock-in'));
        $response = $this->actingAs($user)->get(route('attendance.list'));
        $response->assertStatus(200);
        $response->assertSee('2026-06-20');
        $response->assertSee('09:00');
    }
}