<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassSchedule extends Model
{
    use HasFactory, SoftDeletes;

    public function teacher_list()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function course_list()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function bookings()
    {
        return $this->hasMany(ClassBooking::class, 'class_schedule_id', 'id');
    }

}
