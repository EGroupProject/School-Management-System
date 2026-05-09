<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return Attendance::with(['student', 'schedule.subject', 'schedule.group'])->get();
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user || !$user->teacher) {
            abort(403);
        }

        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'required|in:present,absent,late',
            'date' => 'required|date',
        ]);

        $schedule = Schedule::findOrFail($data['schedule_id']);

        if ($schedule->teacher_id !== $user->teacher->id) {
            abort(403);
        }

        return Attendance::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'schedule_id' => $data['schedule_id'],
                'date' => $data['date'],
            ],
            [
                'status' => $data['status'],
            ]
        );
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $data = $request->validate([
            'status' => 'sometimes|in:present,absent,late',
            'date' => 'sometimes|date',
        ]);

        $attendance->update($data);

        return $attendance;
    }

    public function byStudent($studentId)
    {
        return Attendance::with(['schedule'])
            ->where('student_id', $studentId)
            ->get();
    }

    public function byGroup($groupId)
    {
        return Attendance::whereHas('schedule', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            })
            ->with(['student', 'schedule.subject', 'schedule.group'])
            ->get();
    }
}