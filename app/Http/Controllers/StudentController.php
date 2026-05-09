<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\Schedule;

class StudentController extends Controller
{
    private function getStudent()
    {
        return Student::where('user_id', auth()->id())->firstOrFail();
    }

    public function profile()
    {
        return $this->getStudent()->load('group');
    }

    public function schedule()
    {
        $student = $this->getStudent();

        return Schedule::with(['subject', 'teacher'])
            ->where('group_id', $student->group_id)
            ->get();
    }

    public function attendance()
    {
        $student = $this->getStudent();

        return Attendance::with(['schedule.subject', 'schedule.group'])
            ->where('student_id', $student->id)
            ->get();
    }
}