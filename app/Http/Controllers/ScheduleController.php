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
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        return Schedule::create($data);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $schedule->update($request->validate([
            'group_id' => 'sometimes|exists:groups,id',
            'subject_id' => 'sometimes|exists:subjects,id',
            'teacher_id' => 'sometimes|exists:teachers,id',
            'date' => 'sometimes|date',
            'start_time' => 'sometimes',
            'end_time' => 'sometimes',
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
        return Schedule::with(['group','subject'])
            ->whereHas('subject', function ($q) use ($id) {
                $q->where('teacher_id', $id);
            })
            ->get();
    }
}
