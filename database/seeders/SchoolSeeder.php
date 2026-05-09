<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Attendance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'Admin School',
            'email' => 'admin@school.ma',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // GROUPS
        $groupA = Group::create(['name' => 'DEV101']);
        $groupB = Group::create(['name' => 'DEV102']);

        // TEACHERS
        $teachers = [];

        $teacherNames = [
            'Hassan El Amrani',
            'Youssef Bennis',
            'Mehdi Zahir',
            'Omar Kettani'
        ];

        foreach ($teacherNames as $name) {
            $user = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '', $name)) . '@school.ma',
                'password' => Hash::make('password'),
                'role' => 'teacher',
            ]);

            $teachers[] = Teacher::create([
                'user_id' => $user->id,
            ]);
        }

        $subjects = [
            Subject::create(['name' => 'Laravel', 'teacher_id' => $teachers[0]->id]),
            Subject::create(['name' => 'React', 'teacher_id' => $teachers[1]->id]),
            Subject::create(['name' => 'Databases', 'teacher_id' => $teachers[2]->id]),
            Subject::create(['name' => 'DevOps', 'teacher_id' => $teachers[3]->id]),
        ];

        $students = collect();

        for ($i = 1; $i <= 30; $i++) {

            $group = $i <= 15 ? $groupA : $groupB;

            $user = User::create([
                'name' => "Student $i",
                'email' => "student$i@school.ma",
                'password' => Hash::make('password'),
                'role' => 'student',
            ]);

            $students->push(
                Student::create([
                    'user_id' => $user->id,
                    'group_id' => $group->id,
                ])
            );
        } 

        $schedules = [];
        $baseDate = now()->startOfWeek();

        foreach ($subjects as $i => $subject) {

            $schedules[] = Schedule::create([
                'group_id' => $groupA->id,
                'subject_id' => $subject->id,
                'teacher_id' => $subject->teacher_id,
                'date' => $baseDate->copy()->addDays($i),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00',
            ]);

            $schedules[] = Schedule::create([
                'group_id' => $groupB->id,
                'subject_id' => $subject->id,
                'teacher_id' => $subject->teacher_id,
                'date' => $baseDate->copy()->addDays($i),
                'start_time' => '11:00:00',
                'end_time' => '13:00:00',
            ]);
        }

        foreach ($schedules as $schedule) {

            $groupStudents = $students->where('group_id', $schedule->group_id);

            foreach ($groupStudents as $student) {

                Attendance::create([
                    'student_id' => $student->id,
                    'schedule_id' => $schedule->id,
                    'status' => 'present',
                    'date' => $schedule->date,
                ]);
            }
        }
    }
}