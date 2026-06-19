<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use App\Models\RestRecord;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $attendance = AttendanceRecord::where('user_id', $user->id)
        ->where('date', $today)
        ->first();
        $status = '勤務外';
        if ($attendance) {
            if ($attendance->clock_out) {
                $status = '退勤済';
            } else {
                $latestRest = $attendance->restRecords()->latest()->first();
                if ($latestRest && is_null($latestRest->clock_out)) {
                    $status = '休憩中';
                } else {
                    $status = '出勤中';
                }
            }
        }
        $now = Carbon::now();
        $weeks = ['日', '月', '火', '水', '木', '金', '土'];
        $dateString = $now->format('Y年n月j日') . '(' . $weeks[$now->dayOfWeek] . ')';
        $timeString = $now->format('H:i');
        return view('attendance.index', compact('status', 'dateString', 'timeString'));
    }
    public function clockIn()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $exists = AttendanceRecord::where('user_id', $user->id)->where('date', $today)->exists();
        if ($exists) {
            return redirect()->back()->with('error', '本日はすでに出勤しています。');
        }
        AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => $today,
            'clock_in' => Carbon::now()->format('H:i:s'),
        ]);
        return redirect()->route('attendance.index');
    }
    public function restIn()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $attendance = AttendanceRecord::where('user_id', $user->id)->where('date', $today)->first();
        if ($attendance && is_null($attendance->clock_out)) {
            $latestRest = $attendance->restRecords()->latest()->first();
            if ($latestRest && is_null($latestRest->clock_out)) {
                return redirect()->back();
            }
            $attendance->restRecords()->create([
                'clock_in' => Carbon::now()->format('H:i:s'),
            ]);
        }
        return redirect()->route('attendance.index');
    }
    public function restOut()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $attendance = AttendanceRecord::where('user_id', $user->id)->where('date', $today)->first();
        if ($attendance) {
            $latestRest = $attendance->restRecords()->whereNull('clock_out')->latest()->first();
            if ($latestRest) {
                $latestRest->update([
                    'clock_out' => Carbon::now()->format('H:i:s'),
                ]);
            }
        }
        return redirect()->route('attendance.index');
    }
    public function clockOut()
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');
        $attendance = AttendanceRecord::where('user_id', $user->id)->where('date', $today)->first();
        if ($attendance && is_null($attendance->clock_out)) {
            $attendance->update([
                'clock_out' => Carbon::now()->format('H:i:s'),
            ]);
        }
        return redirect()->route('attendance.index');
    }
}