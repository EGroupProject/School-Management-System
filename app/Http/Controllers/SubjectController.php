<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * List all subjects
     */
    public function index()
    {
        $subjects = Subject::with('teacher.user')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.subjects.create', compact('teachers'));
    }

    /**
     * Store new subject
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:subjects,name',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        Subject::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Subject $subject)
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.subjects.edit', compact('subject', 'teachers'));
    }

    /**
     * Update subject
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|unique:subjects,name,' . $subject->id,
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $subject->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
        ]);

        return redirect()->route('subjects.index')
            ->with('success', 'Subject updated successfully');
    }

    /**
     * Delete subject
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')
            ->with('success', 'Subject deleted successfully');
    }
}
