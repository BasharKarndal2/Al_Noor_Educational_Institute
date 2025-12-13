<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\StudentSectionSubjectTeacher;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardsControllers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();

        $studentscount = StudentSectionSubjectTeacher::where('teacher_id',  $teacher->id)
        
            ->with('student') // تحميل بيانات الطالب
            ->get()
            ->pluck('student') // سحب الطلاب من النتائج
            ->unique('id') // منع التكرار
            ->count();

        
        // dd($studentscount);
        $countsection= $teacher->sections->count();


        // جلب اليوم الحالي
        $dayOfWeek = Carbon::now()->locale('en')->dayName;
      
        // جلب الحصص الخاصة بهذا اليوم
        $schedules = ClassSchedule::where('day_of_week', $dayOfWeek)->get();
// dd($schedules);
      
        return view('Teacher.index',compact('studentscount', 'countsection', 'dayOfWeek', 'schedules'));
    }



    public function section(){
        // المعلم الحالي


        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $sections = StudentSectionSubjectTeacher::where('teacher_id', $teacher->id)
            ->with(['section', 'subject'])
            ->get()
            ->map(function ($item) {
                $students_count = StudentSectionSubjectTeacher::where('section_id', $item->section_id)
                    ->where('subject_id', $item->subject_id)
                    ->where('teacher_id', $item->teacher_id)
                    ->distinct() // عدد الطلاب المميزين
                    ->count('student_id');

                return [
                    'section' => $item->section,
                    'subject' => $item->subject,
                    'students_count' => $students_count
                ];
            })
            ->unique(function ($item) {
                // نزيل التكرار حسب الصف + المادة
                return $item['section']->id . '-' . $item['subject']->id;
            })
            ->values();

    //    dd($sections);

        return view('Teacher.section', compact('sections'));
    }
    

  public  function getmystudent(){
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $students = StudentSectionSubjectTeacher::where('teacher_id', $teacher->id)
            ->with(['student', 'section', 'subject'])
            ->get()
            ->groupBy('student_id') // تجميع حسب الطالب
            ->map(function ($items, $studentId) {
                $student = $items->first()->student;

            // جمع كل الصفوف + المواد التي يدرس فيها الطالب عند هذا المعلم
            $sections_subjects = $items->map(function ($item) {
                return $item->section->classroom->name . ':' . $item->section->name . ' (' . $item->subject->name . ')';
            })->unique()->join(', ');

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'student_number' => $student->id ?? 'غير محدد',
                    'email' => $student->email,
                    'image' => $student->image_path ?? 'image/default.png',
                    'sections_subjects' => $sections_subjects,
                ];
            })
            ->values();

        return view('Teacher.student', compact('students'));
    }
    public function getmystudentinsectionandsubject($section_id, $subject_id)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $students = StudentSectionSubjectTeacher::where('teacher_id', $teacher->id)
            ->where('section_id', $section_id)->where('subject_id', $subject_id)  
            ->
            select('student_id') // تحديد العمود
            ->distinct()
            ->with(['student'])
            ->get();

   
        $subjectname=Subject::where('id', $subject_id)->firstOrFail();
        $section_name=Section::where('id', $section_id)->firstOrFail();

        return view('Teacher.studeninsectionandstadysubject', compact('students', 'subjectname', 'section_name'));
    }



public function getclassroom(){
      
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $teacher = Teacher::where('user_id', Auth::id())
            ->with(['sections.classroom']) // تحميل الصفوف مع الشعب
            ->firstOrFail();

        $sections = $teacher->sections;
     
       

        // dd($sections);

     

        return response()->json($sections);
    }

    public function getsubjectinsectionandteacher($id)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $subjects = SectionSubjectTeacher::where('section_id', $id)
            ->where('teacher_id', $teacher->id)
            ->with('subject') // العلاقة للـ Subject
            ->get()
            ->pluck('subject') // نجيب فقط البيانات المرتبطة بالـ subject
            ->unique('id')     // نزيل التكرار حسب الـ id
            ->values();        // لإعادة الفهرسة

        return response()->json($subjects);
    }

    public function getstudent_by_section_andsubject_and_teacher(Request $request)
    {
        $sectionId = $request->section_id;
        $teacherId = Teacher::where('user_id', Auth::id())->firstOrFail();
        $subjectId = $request->subject_id;
        $students = DB::table('student_section_subject_teacher as sst')
            ->join('students as s', 'sst.student_id', '=', 's.id')
            ->where('sst.section_id', $sectionId)
            ->where('sst.teacher_id', $teacherId->id)
            ->where('sst.subject_id', $subjectId)
            ->select('s.*')
            ->get();
        return response()->json($students);
    }



    public function Class_schedules(){


    $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

    // جلب الجدول الدراسي للمعلم مع المادة والقسم
    $schedules = ClassSchedule::with(['subject', 'section'])
        ->where('teacher_id', $teacher->id)
        ->orderByRaw("FIELD(day_of_week, 'sunday','monday','tuesday','wednesday','thursday','friday','saturday')")
        ->orderBy('period_number')
        ->get();

    return view('Teacher.sechuled', compact('schedules'));
  
    }
}