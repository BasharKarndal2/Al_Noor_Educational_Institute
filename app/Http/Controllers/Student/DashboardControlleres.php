<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignments;
use App\Models\Attendance;
use App\Models\AttendanceDetails;
use App\Models\ClassSchedule;
use App\Models\EvaluationResult;
use App\Models\Exam;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $student = Auth::user()->student;

        // عدد الواجبات الكلية اللي ما انتهى موعدها
        $assignmentsCount = Assignments::whereIn('section_id', $student->sectionsSubjectsTeachers->pluck('section_id'))
            ->whereIn('subject_id', $student->sectionsSubjectsTeachers->pluck('subject_id'))
            ->whereIn('teacher_id', $student->sectionsSubjectsTeachers->pluck('teacher_id'))
            ->where('due_date', '>=', now())
            ->count();

        // حضور وغياب
        $att = AttendanceDetails::join('attendances', 'attendance_details.attendance_id', '=', 'attendances.id')
            ->where('attendance_details.student_id', $student->id)
            ->orderBy('attendances.attendance_date', 'desc')
            ->select('attendance_details.*')
            ->with('attendance.classSchedule.subject')
            ->get();

        $total = $att->count();
        $present = $att->where('status', 'present')->count();
        $percentage = $total > 0 ? round(($present / $total) * 100) : 0;

        // ===== حساب المعدل العام =====
        $weights = [
            'exam'          => 0.50,
            'quiz'          => 0.10,
            'assignment'    => 0.10,
            'participation' => 0.10,
            'activity'      => 0.05,
            'project'       => 0.05,
        ];

        $evals = EvaluationResult::with('evaluation')
            ->where('student_id', $student->id)
            ->get();

        $weightedSum = 0;
        $totalWeight = 0;

        foreach ($weights as $type => $weight) {
            $avg = $evals->filter(fn($e) => $e->evaluation->type === $type)->avg('grade');
            if ($avg !== null) {
                $weightedSum += $avg * $weight;
                $totalWeight += $weight;
            }
        }

        // إضافة نسبة الحضور كجزء من المشاركة (weight = 10%)
        if ($percentage > 0) {
            $weightedSum += $percentage * $weights['participation'];
            $totalWeight += $weights['participation'];
        }

        $finalAverage = $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : 0;

        // جلب الاختبارات القادمة
        $subjects = $student->subjects()->pluck('subject_id');
        $section = $student->sections()->pluck('section_id');
        $teacher = $student->teachers()->pluck('teacher_id');

        $today = now()->toDateString();
        $upcoming = Exam::whereIn('subject_id', $subjects)
            ->whereIn('section_id', $section)
            ->whereIn('teacher_id', $teacher)
            ->where('exam_date', '>=', $today)
            ->with('subject', 'teacher')
            ->orderBy('exam_date', 'asc')
            ->get();

        $dayOfWeek = Carbon::now()->locale('en')->dayName;
        $todaySchedules = ClassSchedule::whereHas('section.students', function ($q) use ($student) {
            $q->where('students.id', $student->id);
        })
            ->where('day_of_week', $dayOfWeek)
            ->with(['subject', 'teacher', 'section.classroom'])
            ->orderBy('period_number')
            ->get();

        return view('Student.index', [
            'schedules' => $todaySchedules,
            'upcoming' => $upcoming,
            'percentage' => $percentage,
            'assignmentsCount' => $assignmentsCount,
            'average' => $finalAverage // أرسل المعدل للعرض في الصفحة
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    public function weeklySchedule()
    {
        $student = Auth::user()->student;

        // جلب الحصص المرتبطة بالطالب مباشرة
        $allSchedules = \App\Models\ClassSchedule::whereHas('section.students', function ($q) use ($student) {
            $q->where('students.id', $student->id);
        })
            ->with([
                'subject',
                'teacher',
                'section.classroom'
            ])
            ->orderBy('day_of_week')
            ->orderBy('period_number')
            ->get();

        return view('Student.ClassScheduleget', ['schedules' => $allSchedules]);
    }

    public function get_att()
    {
        $student = Auth::user()->student;

        $att = AttendanceDetails::join('attendances', 'attendance_details.attendance_id', '=', 'attendances.id')
            ->where('attendance_details.student_id', $student->id)
            ->orderBy('attendances.attendance_date', 'desc') // ترتيب تصاعدي حسب التاريخ
            ->select('attendance_details.*') // نأخذ فقط أعمدة attendance_details
            ->with('attendance.classSchedule.subject') // نضيف العلاقات المطلوبة
            ->get();




        $total = $att->count(); // إجمالي الحصص
        $present = $att->where('status', 'present')->count(); // عدد الحصص الحاضرة
        $absent = $att->where('status', 'absent')->count(); // عدد الحصص الغائبة

        $percentage = $total > 0 ? round(($present / $total) * 100) : 0; // نسبة الحضور %

        return view('Student.att', compact('att', 'total', 'present', 'absent', 'percentage'));
    }



    public function get_evaluaton(){

        $student = Auth::user()->student;
        $eval=EvaluationResult::where('student_id',  $student->id)->with('evaluation.subject', 'evaluation.teacher')->get();
        return view('Student.evaluation', compact('eval'));
    }

    public function get_exam()
    {
        $student = Auth::user()->student;

        // جميع المواد والشُعب التي يدرسها الطالب
        $subjects = $student->subjects()->pluck('subject_id');

        $section = $student->sections()->pluck('section_id');
        $teacher = $student->teachers() ->pluck('teacher_id');
        // جلب الاختبارات الخاصة بالطالب فقط
        $today = now()->toDateString();

        $upcoming = Exam::whereIn('subject_id', $subjects)->whereIn('section_id', $section)->whereIn('teacher_id', $teacher)
            ->where('exam_date', '>=', $today)
            ->with('subject', 'teacher')
            ->orderBy('exam_date', 'asc')
            ->get();

        $completed = Exam::whereIn('subject_id', $subjects)->whereIn('section_id', $section)->whereIn('teacher_id', $teacher)
            ->where('exam_date', '<', $today)
            ->with('subject', 'teacher')
            ->orderBy('exam_date', 'desc')
            ->get();

        // إذا عندك جدول درجات (exam_results) ممكن تجيب الدرجات للامتحانات المنتهية:
      
        return view('Student.exam', compact('upcoming', 'completed'));
    }

    public function showexam($id)
    {
        $exam = Exam::with('subject', 'teacher')
            ->findOrFail($id);

        return response()->json([
            'title' => $exam->title,
            'exam_date' => $exam->exam_date,
            'start_time' => $exam->start_time,
            'end_time' => $exam->end_time,
            'loc' => $exam->loc,
            'description' => $exam->description,
            'exam_file' => $exam->exam_file,
            'subject_name' => $exam->subject->name ?? '',
            'teacher_name' => $exam->teacher->full_name ?? '',
        ]);
    }
}
