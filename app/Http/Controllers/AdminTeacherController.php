<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTeacherController extends Controller
{
    /**
     * List all teachers
     */
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.teachers.create');
    }

    /**
     * Store new teacher
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email',
        ]);

        // Create user account
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make('password'), // default password
            'role'     => 'teacher',
        ]);

        // Create teacher profile
        Teacher::create([
            'user_id' => $user->id,
        ]);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully');
    }

    /**
     * Show edit form
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load('user');
        return view('admin.teachers.edit', compact('teacher'));
    }

    /**
     * Update teacher
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email',
        ]);

        // update user info
        $teacher->user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully');
    }

    /**
     * Delete teacher
     */
    public function destroy(Teacher $teacher)
    {
        // delete user + teacher (cascade depends on FK)
        $teacher->user->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully');
    }
}
