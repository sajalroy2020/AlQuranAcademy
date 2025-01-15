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

}
