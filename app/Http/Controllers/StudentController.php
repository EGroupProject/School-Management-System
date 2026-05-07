<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Http\Request;

class StudentController extends Controller
{ 
    public function profile()
    {
        return Student::with('group')
            ->where('user_id', auth()->id())
            ->first();
    }
 
    public function schedule()
    {
        $student = Student::where('user_id', auth()->id())->first();

        return Schedule::with(['subject', 'teacher'])
            ->where('group_id', $student->group_id)
            ->get();
    }
 
    public function attendance()
    {
        $student = Student::where('user_id', auth()->id())->first();

        return Attendance::with('schedule')
            ->where('student_id', $student->id)
            ->get();
    }
}