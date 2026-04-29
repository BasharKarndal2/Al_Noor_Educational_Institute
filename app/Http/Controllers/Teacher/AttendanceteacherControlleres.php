<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetails;
use App\Models\ClassSchedule;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceteacherControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // // جلب بيانات المدرس الحالي
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // جلب سجلات الحضور المرتبطة بالمدرس عبر الـ class_schedule
        $atts= Attendance::whereHas('classSchedule', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })
            ->with(['classSchedule.section', 'classSchedule.subject']) // جلب القسم والمادة المرتبطين بالحصة
            ->orderBy('attendance_date', 'desc')
            ->get();

        

      
        return view('Teacher.Attendance.index', compact('atts'));
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

        $title = $request->title ?? "لا يوجد عنوان";
        $notes = $request->notes ?? "لا يوجد ";

        // dd($title, $notes);
        // $attendance = Attendance::create([
        //     'title' => $title,
        //     'class_schedule_id' => $request->period_number , // تأكد من اسم الحقل الصحيح
        //     'attendance_date' => $request->attendance_date,
        //     'description' =>  $notes
        // ]);

        $attendance = new  Attendance();

        $attendance->title = $title;
        $attendance->attendance_date = $request->attendance_date;
        $attendance->class_schedule_id = $request->period_number;
        $attendance->description = $notes;
        $attendance->save();

        // حفظ درجات الطلاب
        foreach ($request->students as $studentId => $studentData) {
            AttendanceDetails::create([
                'attendance_id' => $attendance->id,
                'student_id' => $studentId,
                'status' => $studentData['status'] ?? 'present',
                'notes' => $studentData['notes'] ?? 'لا يوجد',
            ]);
        }

        return redirect()->back()->with('success', 'تم حفظ سجل الحضور  بنجاح');
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
        // جلب سجل الحضور الموجود
        $attendance = Attendance::findOrFail($id);

        // تحديث بيانات الحضور
        $attendance->title = $request->title ?? "لا يوجد عنوان";
        $attendance->attendance_date = $request->attendance_date;
        $attendance->class_schedule_id = $request->period_number;
        $attendance->description = $request->notes ?? "لا يوجد";
        $attendance->save();

        // تعديل أو إضافة تفاصيل الطلاب
        foreach ($request->students as $studentId => $studentData) {
            AttendanceDetails::updateOrCreate(
                [
                    'attendance_id' => $attendance->id,
                    'student_id' => $studentId
                ],
                [
                    'status' => $studentData['status'] ?? 'present',
                    'notes' => $studentData['notes'] ?? 'لا يوجد'
                ]
            );
        }

        return redirect()->back()->with('success', 'تم تعديل سجل الحضور بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $att = Attendance::find($id);
        $att->delete();

        return redirect()->route('teacher.att.index')->with('success', 'تمت حذف سجل الحضور الدراسي   بنجاح.');
    }
    public function getPeriods(Request $request)
    {
        $request->validate([
            'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'section_id'  => 'required|exists:sections,id',
            'subject_id'  => 'required|exists:subjects,id',
        ]);

        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // جلب الحصص فقط التي لها مادة فعلية وتطابق المادة المطلوبة
        $schedules = ClassSchedule::with('subject')
            ->where('day_of_week', $request->day_of_week)
            ->where('section_id', $request->section_id)
            ->where('teacher_id', $teacher->id)
            ->where('is_break', false) // استبعاد الاستراحة
            ->whereHas('subject', function ($query) use ($request) {
                $query->where('id', $request->subject_id);
            })
            ->orderBy('period_number')
            ->get();

        // إذا لا توجد حصص → رسالة
        if ($schedules->isEmpty()) {
            return response()->json([
                [
                    'id' => null,
                    'label' => 'لا يوجد حصص'
                ]
            ]);
        }

        // إنشاء قائمة الخيارات
        $options = $schedules->map(function ($s) {
            return [
                'id' => $s->id,
                'label' => "الحصة {$s->period_number} | {$s->start_time} - {$s->end_time}"
            ];
        });

        return response()->json($options);
    }
}

