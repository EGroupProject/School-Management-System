<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return Attendance::with(['student', 'schedule.subject', 'schedule.group'])
            ->get();
    }

    public function store(Request $request)
    {
        $teacherId = auth()->user()->teacher->id;
        $schedule = Schedule::findOrFail($request->schedule_id);

        if ($schedule->subject->teacher_id !== $teacherId) {
            abort(403);
        }

        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'required|in:present,absent,late',
            'date' => 'required|date',
        ]);

        return Attendance::create($data);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->update(
            $request->only(['status', 'date'])
        );

        return $attendance;
    }

    public function byStudent($studentId)
    {
        return Attendance::with('schedule')
            ->where('student_id', $studentId)
            ->get();
    }

    public function byGroup($groupId)
    {
        return Attendance::whereHas('schedule', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            })
            ->with(['student', 'schedule'])
            ->get();
    }
}