<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    public function student() {
        return $this->belongsTo(Student::class);
    }

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }
}
