<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'group_id',
        'subject_id',
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }
}
