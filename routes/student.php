<?php

use App\Http\Controllers\Student\AssignmentsstudentControlleres;
use App\Http\Controllers\Student\DashboardControlleres;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkrole:student'])->group(
    function () {

        Route::resource('/student/dashboard', DashboardControlleres::class)->name('index', 'student.dashboard');
Route::get('/student/schedule', [DashboardControlleres::class, 'weeklySchedule'])->name('student.schedule');
Route::resource('assignmentsStudent', AssignmentsstudentControlleres::class);

Route::get('/student/assignments/{id}', [AssignmentsstudentControlleres::class, 'show']);
Route::put('/student/assignmentssent/{id}', [AssignmentsstudentControlleres::class, 'createdata'])
    ->name('student.Assignment_submissions');
Route::get('/student/att/', [DashboardControlleres::class, 'get_att'])->name('sudent.attstudent');
Route::get('/student/evaluaton', [DashboardControlleres::class, 'get_evaluaton'])->name('sudent.evaluatonstudent');
Route::get('/student/exam', [DashboardControlleres::class, 'get_exam'])->name('sudent.exam');
Route::get('/student/exams/{id}', [DashboardControlleres::class, 'showexam']);
    }
);