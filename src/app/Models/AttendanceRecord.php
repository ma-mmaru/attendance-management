<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}