<?php

use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminTeacherController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    //B
   Route::middleware('role:admin')->group(function () {
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::put('/schedules/{id}', [ScheduleController::class, 'update']);
    Route::delete('/schedules/{id}', [ScheduleController::class, 'destroy']);

    //Admin -> Student 
    Route::resource('students', AdminStudentController::class);
    //Admin -> Teacher 
    Route::resource('teachers', AdminTeacherController::class);
    //Admin -> Group 
    Route::resource('groups', GroupController::class);
    //Admin -> Subject
    Route::resource('subjects', SubjectController::class); 
    });
    
    Route::get('/schedules/group/{id}', [ScheduleController::class, 'byGroup']);
    Route::get('/schedules/teacher/{id}', [ScheduleController::class, 'byTeacher']);

    Route::middleware('role:teacher')->group(function () {
    Route::post('/attendance', [AttendanceController::class, 'store']);
    Route::put('/attendance/{id}', [AttendanceController::class, 'update']);
    });
    
    Route::middleware('role:student')->group(function () {
    Route::get('/student/profile', [StudentController::class, 'profile']);
    Route::get('/student/schedule', [StudentController::class, 'schedule']);
    Route::get('/student/attendance', [StudentController::class, 'attendance']);
    });

    Route::get('/attendance/student/{id}', [AttendanceController::class, 'byStudent']);
    Route::get('/attendance/group/{id}', [AttendanceController::class, 'byGroup']);
    Route::get('/attendance', [AttendanceController::class, 'index'])->middleware('role:admin');
    //B
    
});




require __DIR__.'/auth.php';
