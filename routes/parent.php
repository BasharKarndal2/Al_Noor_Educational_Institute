<?php

use App\Http\Controllers\parent\DashboardparentControlleres;

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'checkrole:parent'])->group(
    function () {

        Route::resource('/parent/dashboard', DashboardparentControlleres::class)->name('index', 'parent.dashboard');
Route::get('/parent/get_chiled', [DashboardparentControlleres::class, 'get_chiled'])->name('parent.get_chiled');
Route::get('/chiled/{id}', [DashboardparentControlleres::class, 'getStudent'])->name('chiled.get');
Route::get('/parent/schedule', [DashboardparentControlleres::class, 'weeklySchedule'])->name('parent.schedule');
// web.php
Route::get('/parent/get_childrenselect', [DashboardparentControlleres::class, 'getChildren'])->name('get.childrenselect');
Route::get('/attendance/data', [DashboardparentControlleres::class, 'getAttendanceData'])->name('parent.attendance.data');
Route::get('/att/chiled', [DashboardparentControlleres::class, 'chiledgetatt'])->name('chiled.getatt');
Route::get('/parent/evaluations/data', [DashboardparentControlleres::class, 'getEvaluationData'])
    ->name('parent.evaluation.data');

Route::get('/parent/evaluations/chiled', [DashboardparentControlleres::class, 'chiledgetevaluation'])->name('chiled.evaluation');
Route::get('/parent/exam/chiled', [DashboardparentControlleres::class, 'chiledgetexam'])->name('chiled.exam');
Route::get('/parent/exams-data', [DashboardparentControlleres::class, 'getExamData'])->name('parent.exam.data');
Route::get('/chiled/exams/{id}', [DashboardparentControlleres::class, 'showexam']);
Route::get('/parent/homework/data', [DashboardparentControlleres::class, 'getHomeworkByChild'])->name('parent.homework.data');
Route::get('/parent/chiledgetassing/chiled', [DashboardparentControlleres::class, 'chiledgetassing'])->name('chiled.chiledgetassing');
Route::get('/parent/assignments/{id}', [DashboardparentControlleres::class, 'showdata']);
    }
);