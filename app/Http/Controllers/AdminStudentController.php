<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\Group;

class AdminStudentController extends Controller
{
    public function index() {
        $students = Student::with('user', 'group')->get();
        return view('admin.students.index', compact('students'));
    }

    public function create() {
        $groups = Group::all();
        return view('admin.students.create', compact('groups'));
    }

    public function store(Request $request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password'),
            'role' => 'student'
        ]);

        Student::create([
            'user_id' => $user->id,
            'group_id' => $request->group_id
        ]);

        return redirect()->route('students.index');
    }

    public function edit(Student $student) {
        $groups = Group::all();
        return view('admin.students.edit', compact('student', 'groups'));
    }

    public function update(Request $request, Student $student) {
        $student->update([
            'group_id' => $request->group_id
        ]);

        return redirect()->route('students.index');
    }

    public function destroy(Student $student) {
        $student->delete();
        return redirect()->route('students.index');
    }
}
