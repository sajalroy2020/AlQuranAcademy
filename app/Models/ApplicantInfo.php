<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicantInfo extends Model
{
    use HasFactory;
    
    public function course_list()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
