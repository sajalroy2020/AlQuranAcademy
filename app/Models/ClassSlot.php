<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSlot extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'day',
        'start_time',
        'end_time',
    ];

    public function schedule() {
        return $this->belongsTo(ClassSchedule::class, 'class_schedule_id');
    }
    
    public function bookings() {
        return $this->hasMany(ClassBooking::class, 'class_slot_id');
    }
}
