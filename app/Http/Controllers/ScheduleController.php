<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return Schedule::with(['group','subject','teacher'])->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        return Schedule::create($data);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->update($request->only([
            'group_id',
            'subject_id',
            'teacher_id',
            'day_of_week',
            'start_time',
            'end_time'
        ]));

        return $schedule;
    }

    public function destroy($id)
    {
        return Schedule::destroy($id);
    }

    public function byGroup($id)
    {
        return Schedule::with(['group','subject','teacher'])
            ->where('group_id', $id)
            ->get();
    }

    public function byTeacher($id)
    {
        return Schedule::with(['group','subject','teacher'])
            ->where('teacher_id', $id)
            ->get();
    }
}
