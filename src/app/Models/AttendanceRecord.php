<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class AttendanceRecord extends Model
{
    protected $fillable = ['user_id', 'date', 'clock_in', 'clock_out', 'status', 'comment'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function restRecords(): HasMany
    {
        return $this->hasMany(RestRecord::class);
    }
    public function attendanceRequests(): HasMany
    {
        return $this->hasMany(AttendanceRequest::class);
    }
    // 時間計算
    public function getTotalRestTimeAttribute(): string
    {
        $totalSeconds = 0;
        foreach ($this->restRecords as $rest) {
            // 休憩終了の打刻なしは計算スキップ
            if (!$rest->rest_in || !$rest->rest_out) {
                continue;
            }
            $in = Carbon::parse($rest->rest_in);
            $out = Carbon::parse($rest->rest_out);
            $totalSeconds += $in->diffInSeconds($out);
        }
        // 秒をH:iに変換
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }
    // 勤務合計時間を計算してH:iに
    public function getTotalWorkTimeAttribute(): string
    {
        if (!$this->clock_in || !$this->clock_out) {
            return '';
        }
        $start = Carbon::parse($this->clock_in);
        $end = Carbon::parse($this->clock_out);
        $totalWorkSeconds = $start->diffInSeconds($end);
        $totalRestSeconds = 0;
        foreach ($this->restRecords as $rest) {
            if ($rest->rest_in && $rest->rest_out) {
                $totalRestSeconds += Carbon::parse($rest->rest_in)->diffInSeconds(Carbon::parse($rest->rest_out));
            }
        }
        $actualSeconds = $totalWorkSeconds - $totalRestSeconds;
        if ($actualSeconds < 0) {
            $actualSeconds = 0;
        }
        $hours = floor($actualSeconds / 3600);
        $minutes = floor(($actualSeconds % 3600) / 60);
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}