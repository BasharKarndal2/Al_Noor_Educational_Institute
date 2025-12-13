<?php

use App\Http\Controllers\admin\Assignment_submissionsControlleres;
use App\Http\Controllers\admin\AssignmentsControlleres;
use App\Http\Controllers\admin\AttendanceControlleres;
use App\Http\Controllers\admin\EvaluationControlleres;
use App\Http\Controllers\admin\examControlleres;
use App\Http\Controllers\Teacher\AssignmentsteacherControlleres;
use App\Http\Controllers\Teacher\AttendanceteacherControlleres;
use App\Http\Controllers\Teacher\EvaluationsControlleres;
use App\Http\Controllers\Teacher\DashboardsControllers;
use App\Http\Controllers\Teacher\EvaluationsteacherControlleres;
use App\Http\Controllers\Teacher\examteacherControlleres;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth', 'checkrole:teacher'])->group(function () {

    Route::resource('/teacher/dashboard', DashboardsControllers::class)->name('index', 'teacher.dashboard');
Route::get('teacher/section',[DashboardsControllers::class,'section'])->name('teacher.section');
Route::get('teacher/student', [DashboardsControllers::class, 'getmystudent'])->name('teacher.student');
Route::get('/teacher/getstudentinsection/{sectionID}/{subjectID}', [DashboardsControllers::class, 'getmystudentinsectionandsubject'])
    ->name('teacher.getstudent');
    Route::get('/attendance/showdata/{id}', [AttendanceControlleres::class, 'showdata'])->name('evaluations.showdata');
    Route::get('/admin/attendance/{id}/edit', [AttendanceControlleres::class, 'edit'])->name('admin.attendance.edit');
Route::get('/teachergetsubject/insection/{id}', [DashboardsControllers::class, 'getsubjectinsectionandteacher'])
    ->name('teacher.getstu');
Route::get('/teacher/Class_schedules',[DashboardsControllers::class, 'Class_schedules'])->name('teacher.Class_schedules');
Route::get('/teacher/getclassroom', [DashboardsControllers::class, 'getclassroom'])->name('teacher.getclassroom');
Route::get('/teacher/getstudent_by_section_andsubject_and_teacher', [DashboardsControllers::class, 'getstudent_by_section_andsubject_and_teacher']);
Route::resource('/teacherevaluation', EvaluationsteacherControlleres::class)->except(['show', 'create', 'edit'])->name('index', 'teacher.evaluations.index');
// جلب بيانات تقييم محدد (للتعديل)
// Route::get('/admin/evaluations/{id}/edit', [EvaluationsteacherControlleres::class, 'edit'])->name('Teacher.evaluations.edit');

// تحديث التقييم
Route::get('/evaluationsteacher/showdata/{id}', [EvaluationsteacherControlleres::class, 'showdata'])->name('Teacher.evaluations.showdata');

    Route::get('/admin/evaluations/{id}/edit', [EvaluationControlleres::class, 'edit'])->name('admin.evaluations.edit');

    Route::resource('/teacheratt', AttendanceteacherControlleres::class)->except(['show', 'create', 'edit'])->name('index', 'teacher.att.index');
Route::get('/class-schedules/periodsteacher', [AttendanceteacherControlleres::class, 'getPeriods'])->name('class-schedules.periodsteacher');

    Route::get('/admin/exams/{id}/edit', [examControlleres::class, 'edit']);

    Route::resource('/assignmentsteacher', AssignmentsteacherControlleres::class)->except(['show', 'create', 'edit']);
Route::resource('/examsteacher', examteacherControlleres::class)->except(['show', 'create', 'edit']);
Route::get('/assignment_teacher/{id}/submitted-students', [Assignment_submissionsControlleres::class, 'getSubmissions'])
    ->name('assignment_teacher.submitted.students');
    Route::get('/admin/assignment/{id}/edit', [AssignmentsControlleres::class, 'edit'])->name('admin.assignment.edit');


    Route::post('/submission_teacher/{id}/update', [Assignment_submissionsControlleres::class, 'updateSubmission'])
    ->name('submission_teacher.update');




});