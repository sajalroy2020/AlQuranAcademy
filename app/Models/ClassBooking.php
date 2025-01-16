<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassBooking extends Model
{
    use HasFactory, SoftDeletes;

    public function classSchedule()
    {
        return $this->belongsTo(ClassSchedule::class, 'class_schedule_id', 'id');
    }

}
