<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function attendance() {
        return $this->hasMany(Attendance::class);
    }
}
