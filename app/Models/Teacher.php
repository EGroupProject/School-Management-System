<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function subjects() {
        return $this->hasMany(Subject::class);
    }
}
