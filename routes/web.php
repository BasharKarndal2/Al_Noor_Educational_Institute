<?php

use App\Http\Controllers\admin\announcementsControlleres;
use App\Http\Controllers\admin\Assignment_submissionsControlleres;
use App\Http\Controllers\admin\AssignmentsControlleres;
use App\Http\Controllers\admin\AttendanceControlleres;
use App\Http\Controllers\admin\CheckdataControlleres;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\Working_hours_Controllers;
use App\Http\Controllers\admin\Educational_StagesControllers;
use App\Http\Controllers\admin\ClassroomsColntrollers;
use App\Http\Controllers\admin\ClassScheduleControlleres;
use App\Http\Controllers\admin\DashboardControllers;
use App\Http\Controllers\admin\EvaluationControlleres;
use App\Http\Controllers\admin\examControlleres;
use App\Http\Controllers\admin\mainpageControlleres;
use App\Http\Controllers\admin\PearantControlleres;
use App\Http\Controllers\admin\QuestinsControlleres;
use App\Http\Controllers\admin\ReaquestControolers;
use App\Http\Controllers\admin\SectionsColntrollers;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\Student_Controlleres;
use App\Http\Controllers\admin\Subjects_Colntrollers;
use App\Http\Controllers\admin\TeachersControllers;
use App\Http\Controllers\PearantController;
use App\Http\Controllers\Teacher\DashboardsControllers;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     Auth::logout();
//     request()->session()->invalidate();
//     request()->session()->regenerateToken();
   
//     return view('Main_page.index');
// })->name('home');

Route::get('/', [mainpageControlleres::class, 'index'])->name('home');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });






/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/


Route::resource('/dashboard', DashboardControllers::class)->name('index', 'admin.dashboard')->middleware('checkrole:admin');
// Route::get('/dashboard', [DashboardController::class, 'index'])
//     ->middleware('checkrole:admin,teacher');
/*
|--------------------------------------------------------------------------
| Working Hours
|--------------------------------------------------------------------------
*/
Route::resource('/working_hours', Working_hours_Controllers::class)->except(['show,edit'])->middleware('checkrole:admin');
Route::get('/working_hours/{id}/edit', [Working_hours_Controllers::class, 'edit'])->name('working_hours.edit')->middleware('checkrole:admin');

/*
|--------------------------------------------------------------------------
| Educational Stages
|--------------------------------------------------------------------------
*/
Route::resource('/educational_stage', Educational_StagesControllers::class)->except(['show','create','edit'])->middleware('checkrole:admin');
Route::get('/educational_stage/{id}/edit', [Educational_StagesControllers::class, 'edit'])->middleware('checkrole:admin'); 

/*
|--------------------------------------------------------------------------
| Classrooms
|--------------------------------------------------------------------------
*/
Route::resource('/classroom', ClassroomsColntrollers::class)->except(['show' ,'create', 'edit'])->middleware('checkrole:admin');
Route::get('/classroom/{id}/edit', [ClassroomsColntrollers::class, 'edit'])->middleware('checkrole:admin');


/*
|--------------------------------------------------------------------------
| Sections
|--------------------------------------------------------------------------
*/
Route::resource('/section', SectionsColntrollers::class)->except([ 'edit'])->middleware('checkrole:admin');
Route::get('/Section/{id}/edit', [SectionsColntrollers::class, 'edit'])->middleware('checkrole:admin');
Route::get('/section/filter', [SectionsColntrollers::class, 'filter'])->middleware('checkrole:admin');
Route::get('/section/get_based_on_classroom/{id}', [SectionsColntrollers::class, 'get_sction_based_on_classroom'])->middleware('checkrole:admin');
Route::get('/sectfsdfion/get_not_dsadin_subject', [SectionsColntrollers::class, 'get_sction_based_on_classroom_not_in_subject'])->name('get_sections_not_in_subject')->middleware('checkrole:admin');
Route::delete('/section/{section}/subject/{subject}/delete', [SectionsColntrollers::class, 'removeSubject'])->middleware('checkrole:admin');
Route::get('/section/{id}/show_data_teacher', [SectionsColntrollers::class, 'get_all_data_teacher'])->name('section.showteacherinsection')->middleware('checkrole:admin');

Route::post('/section/add-subjects/{id}', [SectionsColntrollers::class, 'addsubjects_to_section'])->name('section.addsubjects_to_section')->middleware('checkrole:admin');
Route::get('/section/{id}/show_data', [SectionsColntrollers::class, 'get_all_data'])->name('section.show_data')->middleware('checkrole:admin');

Route::get('/section/{section}/teachers', [SectionsColntrollers::class, 'getSectionTeachers'])->middleware('checkrole:admin');
Route::get('/section/{section}/teacher/{teacher}/subjects', [SectionsColntrollers::class, 'getTeacherSubjects'])->middleware('checkrole:admin');
Route::get('/section/{section}/subject/{subject}/available-teachers', [SectionsColntrollers::class, 'getAvailableTeachersForSubject'])->middleware('checkrole:admin');
Route::post('/section/replace-teacher', [SectionsColntrollers::class, 'replaceTeacher'])->middleware('checkrole:admin');



/*


|--------------------------------------------------------------------------
| Subjects
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'checkrole:admin'])->group(function () {

    // Resource routes بدون show و edit
    Route::resource('/subject', Subjects_Colntrollers::class)->except(['show', 'edit']);

    // راوتات منفصلة
    Route::get('/subject/show/{id}', [Subjects_Colntrollers::class, 'show'])->name('subject.show');
    Route::get('/subject/{id}/edit', [Subjects_Colntrollers::class, 'edit']);
    Route::post('/subject/add_to_section/{id}', [Subjects_Colntrollers::class, 'addsubject_to_section'])->name('subject.addsubject_to_section');
    Route::get('/subject/get_not_in_section', [Subjects_Colntrollers::class, 'getSubjectsNotInSection']);
    Route::get('/subject/get_not_in_teacher/{id}', [Subjects_Colntrollers::class, 'getNotAssignedToTeacher']);
    Route::get('/subject/get_subjects_in_teacher_and_section_notjoin', [Subjects_Colntrollers::class, 'get_subjects_in_teacher_and_section_notjoin'])->name('subject.get_subjects_in_teacher_and_section_notjoin');
    Route::get('/subject/{id}/show_all_data', [Subjects_Colntrollers::class, 'show_all_data'])->name('subject.show_all_data');
    Route::get('/subject/get_in_section/{id}', [Subjects_Colntrollers::class, 'getSubjectsInSection']);
});
/*
|--------------------------------------------------------------------------
Teachers
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'checkrole:admin'])->group(function () {

    Route::resource('/teaher', TeachersControllers::class)->except(['show', 'edit']);
Route::get('/teachers/{id}', [TeachersControllers::class, 'show'])->name('teachersdata.show');

Route::get('/teaher/{id}/edit', [TeachersControllers::class, 'edit']);
Route::get('/teaher/specializations', [TeachersControllers::class, 'getSpecializations'])->name('teachers.specializations');
Route::post('/teacher/add_to_subject/{id}', [TeachersControllers::class, 'addsubjects_to_teacher'])->name('teacher.addsubject_to_teacher');
Route::post('/teacher/add_to_section/{id}', [TeachersControllers::class, 'addteacher_to_section'])->name('teacher.addteacher_to_section');
Route::get('/teacher/get_not_in_section/', [SectionsColntrollers::class, 'get_section_not_in_teacher_and_subject'])->name('get_section_not_in_teacher_and_subject');
Route::get('/teachers-by-subject-section', [TeachersControllers::class, 'getTeachersBySubjectAndSection']);
Route::get('/teacher/{id}/subjects', [TeachersControllers::class, 'getSubjects']);
Route::get('/teacher/{teacher}/subject/{subject}/check-before-delete', [TeachersControllers::class, 'checkBeforeDelete']);
Route::delete('/teacher/{teacher}/subject/{subject}/delete', [TeachersControllers::class, 'deleteSubject']);

});




/*
|--------------------------------------------------------------------------
Students
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:admin'])->group(function () {

    Route::resource('/student', Student_Controlleres::class)->except(['show', 'edit']);
Route::get('/classroom/{id}/subjects', [Student_Controlleres::class, 'getSubjectsForClassroom']);
Route::get('/subject/{subject}/classroom/{classroom}/teachers', [Student_Controlleres::class, 'getTeachersForSubject'])
    ->name('subject.teachers');
Route::get('/student/{id}/edit', [Student_Controlleres::class, 'edit']);
Route::get('student/get_subject_inclassroom/{id}', [Student_Controlleres::class, 'get_subject_in_classroom'])->name('student.get_subject_in_classroom');
Route::post('/student/addSubjects/{id}', [Student_Controlleres::class, 'addSubjectsToStudent'])->name('student.addSubjects');
Route::get('/student/getstudent_by_section_andsubject_and_teacher',[Student_Controlleres::class, 'getstudent_by_section_andsubject_and_teacher']);
Route::get('/check-unique_student',[CheckdataControlleres::class, 'checkUnique']);

Route::get('/student/getstudent/{id}', [PearantControlleres::class, 'showchild'])->name('showstudentper');
Route::post('/students/{student}/link-parent', [PearantControlleres::class, 'storeLink'])
    ->name('students.link.store');
});
/*
|--------------------------------------------------------------------------
class Schedule
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:admin'])->group(function () {

    Route::resource('/class_schedule', ClassScheduleControlleres::class)->except(['show', 'edit']);
Route::get('/class-schedules/dayperiods', [ClassScheduleControlleres::class, 'getDayPeriods'])
    ->name('class-schedules.dayperiods');

Route::get('/check-section-schedule/{section}', [ClassScheduleControlleres::class, 'checkSectionSchedule']);

Route::get('/schedule/{sectionId}/edit', [ClassScheduleControlleres::class, 'getSchedule']);

Route::get('/schedule/show/{id}', [ClassScheduleControlleres::class, 'show']);
});

/*
|--------------------------------------------------------------------------
Reaquest
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'checkrole:admin'])->group(function () {
Route::get('/request/show_alldata', [ReaquestControolers::class, 'show_data'])->name('request.show_all_data');
Route::get('/request/accept/{id}', [ReaquestControolers::class, 'accept'])->name('request.accept');
Route::get('/request/accept_teacher/{id}', [ReaquestControolers::class, 'accept_teacher'])->name('request.accept_teacher');

    /*
|--------------------------------------------------------------------------
Questions
|--------------------------------------------------------------------------
*/

    Route::resource('/questins', QuestinsControlleres::class)->except(['show', 'create', 'edit']);



    /*
|--------------------------------------------------------------------------
Evaluation
|--------------------------------------------------------------------------
*/
    Route::resource('/evaluation', EvaluationControlleres::class)->except(['show', 'create', 'edit']);
    // جلب بيانات تقييم محدد (للتعديل)
    Route::get('/admin/evaluations/{id}/edit', [EvaluationControlleres::class, 'edit'])->name('admin.evaluations.edit');

    // تحديث التقييم
    Route::get('/evaluations/showdata/{id}', [EvaluationControlleres::class, 'showdata'])->name('evaluations.showdata');

    /*
|--------------------------------------------------------------------------
Attendance
|--------------------------------------------------------------------------
*/

    Route::resource('/attendance', AttendanceControlleres::class)->except(['show', 'create', 'edit']);
    Route::get('/attendance/showdata/{id}', [AttendanceControlleres::class, 'showdata'])->name('evaluations.showdata');
    Route::get('/admin/attendance/{id}/edit', [AttendanceControlleres::class, 'edit'])->name('admin.attendance.edit');

    /*
|--------------------------------------------------------------------------
Attendance
|--------------------------------------------------------------------------
*/

    Route::resource('/pearant', PearantControlleres::class)->except(['show', 'create', 'edit', 'update']);
    Route::post('/check-unique_parent', [CheckdataControlleres::class, 'check_unique_parent']);
    Route::get('/pearant/{id}', [PearantControlleres::class, 'show'])->name('pearant.show');
    Route::post('/pearant/update', [PearantControlleres::class, 'update'])->name('pearant.update');


    /*
|--------------------------------------------------------------------------
Assignments
|--------------------------------------------------------------------------
*/

    Route::resource('/assignments', AssignmentsControlleres::class)->except(['show', 'create', 'edit']);
    Route::get('/admin/assignment/{id}/edit', [AssignmentsControlleres::class, 'edit'])->name('admin.assignment.edit');
    Route::get('/assignments/{id}', [AssignmentsControlleres::class, 'show']);
    Route::get('/assignment/{id}/submitted-students', [Assignment_submissionsControlleres::class, 'getSubmissions'])
        ->name('assignment.submitted.students');
    Route::post('/submission/{id}/update', [Assignment_submissionsControlleres::class, 'updateSubmission'])
        ->name('submission.update');

    /*
|--------------------------------------------------------------------------
Exams
|--------------------------------------------------------------------------
*/
    Route::get('/admin/exams/{id}/edit', [examControlleres::class, 'edit']);

    Route::resource('/exams', examControlleres::class)->except(['show', 'create', 'edit']);

    /*
|--------------------------------------------------------------------------
announcements
|--------------------------------------------------------------------------
*/
    Route::resource('/announcements', announcementsControlleres::class)->except(['show', 'create', 'edit']);
});



/*
|--------------------------------------------------------------------------
setting
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'checkrole:admin'])->group(function () {
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/admin/settings/update', [SettingController::class, 'update'])->name('settings.update');
});



/*
|--------------------------------------------------------------------------
all
|--------------------------------------------------------------------------
*/
Route::get('/questions/classroom/{classroomId}/subject/{subjectId}', [QuestinsControlleres::class, 'indexByClassroomAndSubject']);
Route::get('/classroom/get_based_on_stage/{id}', [ClassroomsColntrollers::class, 'get_classroom_based_on_education_stage']);
Route::post('/check-unique', [CheckdataControlleres::class, 'checkUnique'])->name('check.unique');
Route::get('/request/register/teacher', [ReaquestControolers::class, 'register'])->name('teacher.register');
Route::post('/check-unique_teacher', [CheckdataControlleres::class, 'checkUnique_teacher'])->name('check.unique_teacher');
Route::post('/request/register/teacher/stor', [ReaquestControolers::class, 'stor_teacher'])->name('request_teacher.stor');
Route::get('/educational_stage/get_based_on_working/{id}', [Educational_StagesControllers::class, 'get_education_stage_based_on_Working']);
Route::get('/educational_stage/create', [Educational_StagesControllers::class, 'create']); // تحقق من وجود هذه الدالة فعلاً
Route::resource('/request', ReaquestControolers::class)->except(['show', 'create', 'edit']);
Route::get('/request/classroom/{id}/subjects', [ReaquestControolers::class, 'getSubjectsandTeachersForClassroom']);
require __DIR__.'/auth.php';
require __DIR__ . '/teacher.php';
require __DIR__ . '/student.php';
require __DIR__ . '/parent.php';