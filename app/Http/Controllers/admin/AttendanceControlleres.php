<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //



        $today = Carbon::today()->toDateString(); // تاريخ اليوم yyyy-mm-dd

        // جلب كل سجلات الحضور اليوم
        $attendancesToday = Attendance::where('attendance_date', $today)->pluck('id');

        // جلب تفاصيل الحضور اليوم
        $details = AttendanceDetails::whereIn('attendance_id', $attendancesToday)->get();

        $totalStudents = $details->count();
        $present = $details->where('status', 'present')->count();
        $absent = $details->where('status', 'absent')->count();
        $late = $details->where('status', 'late')->count();

        // النسب
        $presentPercent = $totalStudents ? round(($present / $totalStudents) * 100) : 0;
        $absentPercent = $totalStudents ? round(($absent / $totalStudents) * 100) : 0;
        $latePercent = $totalStudents ? round(($late / $totalStudents) * 100) : 0;


        $atts =Attendance::all();
        // dd($att->attendance_date);

        // $text= $att->attendance_date;


        // // تأكد أن اللغة عربية
        // Carbon::setLocale('ar');

        // // إنشاء التاريخ المطلوب
        // $date = Carbon::create($text);

        // // عرض التاريخ بصيغة "الأحد 15 نوفمبر 2023"
        // $formattedDate = $date->translatedFormat('l j F Y');

        return view('admin.Attendance.index', compact(
            'present',
            'absent',
            'late',
            'presentPercent',
            'absentPercent',
            'latePercent',
            'totalStudents',
            'atts'
        ));

        return view('admin.Attendance.index',compact('atts', 'totalStudents',''));
    }

    /**
     * Show the form for creating a new resource.
     */
   
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

        $attendance=new  Attendance();

        $attendance->title= $title;
        $attendance->attendance_date= $request->attendance_date;
        $attendance->class_schedule_id= $request->period_number;
        $attendance->description= $notes;

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
    public function edit($id)
    {
        $evaluation = Attendance::with(['classSchedule.section.classroom.educationalStage.working_hour', 'details.student', 'classSchedule.subject', 'classSchedule.teacher'])->findOrFail($id); // جلب الطلاب مع التقييم


        return response()->json($evaluation);
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

        return redirect()->route('attendance.index')->with('danger', 'تمت حذف سجل الحضور الدراسي   بنجاح.');
    }
    public function showdata($id)
    {
        $attendance = Attendance::with(['classSchedule.section.classroom', 'details.student', 'classSchedule.subject', 'classSchedule.teacher'])->findOrFail($id);

        return response()->json($attendance);
    }
}
