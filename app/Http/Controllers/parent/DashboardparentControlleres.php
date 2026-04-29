<?php

namespace App\Http\Controllers\parent;

use App\Http\Controllers\Controller;
use App\Models\Assignment_submissions;
use App\Models\Assignments;
use App\Models\AttendanceDetails;
use App\Models\ClassSchedule;
use App\Models\EvaluationResult;
use App\Models\Exam;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardparentControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parent = Auth::user()->perent;
        $children = Student::where('parent_id', $parent->id)->get();

        $weights = [
            'exam'          => 0.50, // الاختبار النهائي
            'quiz'          => 0.10, // الاختبار القصير
            'assignment'    => 0.10, // الواجب
            'participation' => 0.10, // المشاركة الصفية
            'activity'      => 0.05, // النشاط
            'project'       => 0.05, // المشروع
        ];

        $childrenData = $children->map(function ($child) use ($weights) {
            // ===== حساب الحضور =====
            $att = AttendanceDetails::join('attendances', 'attendance_details.attendance_id', '=', 'attendances.id')
                ->where('attendance_details.student_id', $child->id)
                ->select('attendance_details.*')
                ->with('attendance.classSchedule.subject')
                ->get();

            $total = $att->count();
            $present = $att->where('status', 'present')->count();
            $absent = $att->where('status', 'absent')->count();
            $attendancePercentage = $total > 0 ? ($present / $total) * 100 : null;

            // ===== حساب المعدل الموزون =====
            $evals = EvaluationResult::with('evaluation')
                ->where('student_id', $child->id)
                ->get();

            $weightedSum = 0;
            $totalWeight = 0;

            foreach ($weights as $type => $weight) {
                $avg = $evals->filter(fn($e) => $e->evaluation->type === $type)->avg('grade');
                if ($avg !== null) { // فقط إذا يوجد تقييم لهذا النوع
                    $weightedSum += $avg * $weight;
                    $totalWeight += $weight;
                }
            }

            // ===== إضافة الحضور =====
            if ($attendancePercentage !== null) {
                $weightedSum += $attendancePercentage * $weights['participation']; // وزن 10% للحضور
                $totalWeight += $weights['participation'];
            }

            $finalAverage = $totalWeight > 0 ? round($weightedSum / $totalWeight, 2) : 0;

            return [
                'name'       => $child->name,
                'class'      => $child->class_name ?? '-',
                'present'    => $present,
                'absent'     => $absent,
                'attendance' => $attendancePercentage ? round($attendancePercentage, 2) : 0,
                'average'    => $finalAverage,
            ];
        });

        return view('parent.dashboard', compact('childrenData', 'children'));
    }





    public function showdata($id)
    {
        $assignment = Assignments::with(['section.classroom', 'subject', 'teacher'])->findOrFail($id);

        return response()->json([
            'title' => $assignment->title,
            'type' => 'واجب', // أو استخدم عمود type إذا موجود
            'section_name' => $assignment->section->name . ' : ' . $assignment->section->classroom->name,
            'subject_name' => $assignment->subject->name,
            'teacher_name' => $assignment->teacher->full_name,
            'due_date' => $assignment->due_date,
            'file_path' => $assignment->file_path,
            'instructions' => explode("\n", $assignment->description), // تحويل النص إلى قائمة
        ]);
    }

    public function get_chiled()
    {

        $parent = Auth::user()->perent;

     
        $students= Student::where('parent_id', $parent->id)->with('sectionSubjectTeachers.subject', 'sectionSubjectTeachers.teacher', 'sectionSubjectTeachers.section')->get();
    //   dd($student);

        return view('parent.chiled',compact('students'));
    }


    public function getStudent($id)
    {
        $student = Student::with([
            'sectionSubjectTeachers.subject',
            'sectionSubjectTeachers.teacher',
            'sectionSubjectTeachers.section.classroom'
        ])->findOrFail($id);

        // حساب الحضور
        $att = AttendanceDetails::join('attendances', 'attendance_details.attendance_id', '=', 'attendances.id')
            ->where('attendance_details.student_id', $id)
            ->orderBy('attendances.attendance_date', 'desc')
            ->select('attendance_details.*')
            ->with('attendance.classSchedule.subject')
            ->get();

        $total = $att->count();
        $present = $att->where('status', 'present')->count();
        $percentage = $total > 0 ? round(($present / $total) * 100) : 0;

        // استخراج المواد مع المدرس ورقم WhatsApp
        $subjects = $student->sectionSubjectTeachers->map(function ($sst) {
            return [
                'subject_name' => $sst->subject->name ?? '',
                'teacher_name' => $sst->teacher->full_name ?? '',
                'teacher_whatsapp' => $sst->teacher->phone ?? '', // حقل رقم واتساب في جدول المدرسين
                'icon' => '<i class="fas fa-book me-2"></i>', // أيقونة المادة
            ];
        })->unique('subject_name'); // لتجنب التكرار

        // الصف والشعبة
        $class = optional($student->sectionSubjectTeachers->first()->section)->name ?? '';
        $classroom = optional($student->sectionSubjectTeachers->first()->section->classroom)->name ?? '';
        $classFull = $class . '.' . $classroom;

        return response()->json([
            'id' => $student->id,
            'name' => $student->name,
            'status' => $student->status,
            'class' => $classFull,
            'last_activity' => $student->last_activity ?? 'لا يوجد',
            'percentage' => $percentage,
            'subjects' => $subjects
        ]);
    }



    public function weeklySchedule()
    {
        // جلب الأب الحالي مع أبنائه

        $parent = Auth::user()->perent;
// dd($parent);
        if (!$parent) {
            abort(404, 'Parent not found');
        }

        // جلب جميع الأبناء
        $students = $parent->students;

        // مصفوفة لتخزين الجداول لكل ابن
        $allSchedules = collect();

        foreach ($students as $student) {
            // جلب الحصص المرتبطة بكل ابن
            $schedules = ClassSchedule::whereHas('section.students', function ($q) use ($student) {
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

            // إضافة اسم الابن مع كل جدول
            $schedules->each(function ($schedule) use ($student) {
                $schedule->student_name = $student->name;
            });

            // دمج الجداول في المصفوفة النهائية
            $allSchedules = $allSchedules->concat($schedules);
        }

        return view('parent.ClassScheduleget', ['schedules' => $allSchedules]);
    }


    public function getChildren()
    {
        $parent = Auth::user()->perent;
        // لو عندك جدول students أو children حسب التسمية
        $children = Student::where('parent_id', $parent->id)->get();

        return response()->json($children);
    }




    // 2️⃣ جلب بيانات الحضور للابن (Ajax)
    public function getAttendanceData(Request $request)
    {
        $childId = $request->child_id;

        $att = AttendanceDetails::join('attendances', 'attendance_details.attendance_id', '=', 'attendances.id')
            ->where('attendance_details.student_id', $childId)
            ->orderBy('attendances.attendance_date', 'desc') // الآن صحيح
            ->select('attendance_details.*') // نأخذ فقط أعمدة attendance_details
            ->with('attendance.classSchedule.subject')
            ->get();

        $total = $att->count();
        $present = $att->where('status', 'present')->count();
        $absent = $att->where('status', 'absent')->count();
        $percentage = $total > 0 ? round(($present / $total) * 100) : 0;

        // تجهيز البيانات للجدول
        $records = $att->map(function ($a) {
            return [
                'date' => $a->attendance->attendance_date ?? '-',
                'day' => $a->attendance && $a->attendance->classSchedule ? $a->attendance->classSchedule->day_of_week : '-',
                'subject' => $a->attendance && $a->attendance->classSchedule && $a->attendance->classSchedule->subject
                    ? $a->attendance->classSchedule->subject->name
                    : '-',
                'period' => $a->attendance && $a->attendance->classSchedule ? $a->attendance->classSchedule->period_number : '-',
                'status' => $a->status == 'present'
                    ? '<span class="badge bg-success">حاضر</span>'
                    : ($a->status == 'absent'
                        ? '<span class="badge bg-danger">غائب</span>'
                        : '<span class="badge bg-secondary">اذن</span>'),
                'notes' => $a->notes ?? 'لا يوجد',
            ];
        });

        return response()->json([
            'percentage' => $percentage,
            'present' => $present,
            'absent' => $absent,
            'records' => $records,
        ]);
    }



    public function chiledgetatt()
    {

        return view('parent.att');
    }




    public function getEvaluationData(Request $request)
    {
        $childId = $request->child_id;

        $eval = EvaluationResult::with('evaluation.subject', 'evaluation.teacher')
            ->where('student_id', $childId)
            ->get();

        $records = $eval->map(function ($e) {
            return [
                'title' => $e->evaluation->title ?? '-',
                'date' => $e->evaluation->evaluation_date ?? '-',
                'teacher' => $e->evaluation->teacher->full_name ?? '-',
                'subject' => $e->evaluation->subject->name ?? '-',
                'type' => $e->evaluation->type ?? '-',
                'frequency' => $e->evaluation->frequency ?? '-',
                'grade' => $e->grade ?? '-',
                'feedback' => $e->feedback ?? 'لا يوجد',
            ];
        });

        return response()->json(['records' => $records]);
    }




    public function chiledgetevaluation()
    {
        return view('parent.evaluation');
    }


    public function chiledgetexam()
    {
        return view('parent.exam');
    }



    public function chiledgetassing()
    {
        return view('parent.assignment');
    }


    public function getExamData(Request $request)
    {
        $childId = $request->child_id;

        if (!$childId) {
            return response()->json([
                'upcoming' => [],
                'completed' => [],
                'upcoming_count' => 0,
                'completed_count' => 0,
            ]);
        }

        $student = Student::find($childId);
        if (!$student) {
            return response()->json([
                'upcoming' => [],
                'completed' => [],
                'upcoming_count' => 0,
                'completed_count' => 0,
            ]);
        }

        // ✅ هنا لازم تحدد طريقة ربط المواد مع الطالب
        $subjects = $student->subjects()
            ->wherePivot('student_id', $student->id) // لو علاقة pivot
            ->pluck('subject_id');
        $section = $student->sections()
            ->wherePivot('student_id', $student->id) // لو علاقة pivot
            ->pluck('section_id');
        $teacher = $student->teachers()
            ->wherePivot('student_id', $student->id) // لو علاقة pivot
            ->pluck('teacher_id');
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

        return response()->json([
            'upcoming' => $upcoming,
            'completed' => $completed,
            'upcoming_count' => $upcoming->count(),
            'completed_count' => $completed->count(),
        ]);
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



    public function getHomeworkByChild(Request $request)
    {
        $childId = $request->child_id;

        if (!$childId) {
            return response()->json([
                'new' => 0,
                'nearDue' => 0,
                'completed' => 0,
                'list' => []
            ]);
        }

        // جلب الطالب مع العلاقات
        $student = Student::with([
            'sectionsSubjectsTeachers.section',
            'sectionsSubjectsTeachers.subject',
            'sectionsSubjectsTeachers.teacher'
        ])
            ->findOrFail($childId);

        $newAssignments = 0;
        $nearDueAssignments = 0;
        $completedAssignments = 0;
        $homeworkList = [];

        $now = \Carbon\Carbon::now();

        foreach ($student->sectionsSubjectsTeachers as $sst) {
            $assignments = Assignments::where('section_id', $sst->section_id)
                ->where('subject_id', $sst->subject_id)
                ->where('teacher_id', $sst->teacher_id)
                ->orderBy('due_date', 'desc')
                ->get(['id', 'title', 'due_date', 'status', 'description']);

            foreach ($assignments as $assignment) {
                // تحقق إذا تم التسليم
                $submitted = Assignment_submissions::where('assignment_id', $assignment->id)
                    ->where('student_id', $student->id)
                    ->exists();

                if ($submitted) {
                    $status = 'submitted';
                    $completedAssignments++;
                } elseif ($assignment->status == 'active' && \Carbon\Carbon::parse($assignment->due_date)->diffInDays($now) <= 3) {
                    $status = 'urgent';
                    $nearDueAssignments++;
                } elseif ($assignment->status == 'active') {
                    $status = 'active';
                    $newAssignments++;
                } else {
                    $status = 'inactive';
                    $completedAssignments++;
                }

                $homeworkList[] = [
                    'id' => $assignment->id,
                    'title' => $assignment->title,
                    'subject' => $sst->subject->name ?? '',
                    'teacher' => $sst->teacher->full_name ?? '',
                    'due_date' => \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d H:i'),
                    'status' => $status,
                    'desc' => $assignment->description ?? ''
                ];
            }
        }

        return response()->json([
            'new' => $newAssignments,
            'nearDue' => $nearDueAssignments,
            'completed' => $completedAssignments,
            'list' => $homeworkList
        ]);
    }
}

