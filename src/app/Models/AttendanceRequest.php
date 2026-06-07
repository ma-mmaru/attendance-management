<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceRequest extends Model
{
    protected $fillable = ['attendance_record_id', 'user_id', 'status', 'requested_data', 'reason'];
    protected function casts(): array
    {
        return [
            'requested_data' => 'array',
        ];
    }
    public function attendanceRecord(): BelongsTo
    {
        return $this->belongsTo(AttendanceRecord::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}