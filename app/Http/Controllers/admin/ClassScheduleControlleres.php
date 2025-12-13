<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use Illuminate\Http\Request;

class ClassScheduleControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classSchedules = ClassSchedule::with(['section'])->get();

        // إزالة التكرار حسب الشعبة
        $classSchedules = $classSchedules->unique('section_id');

        return view('admin.Class_schedules.index', compact('classSchedules'));
      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method can be used to show a form for creating a new class schedule
        // You can return a view here, e.g., return view('admin.class_schedule.create');

        
      
    }
    public function show($id){



        $schedule = ClassSchedule::where('section_id', $id)
            ->with(['subject', 'teacher'])
            ->orderByRaw("FIELD(day_of_week,'sunday','monday','tuesday','wednesday','thursday','friday','saturday')")
            ->orderBy('period_number')
            ->get()
            ->groupBy('day_of_week');

        return response()->json($schedule);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // تحقق من البيانات المطلوبة
        // $request->validate([
        //     'working_hour_id' => 'required|exists:working_hours,id',
        //     'education_stage_id' => 'required|exists:education_stages,id',
        //     'classroom_id' => 'required|exists:classrooms,id',
        //     'section_id' => 'required|exists:sections,id',
        //     'periods' => 'required|array',
        // ]);

        $sectionId = $request->section_id;
   

        // يمكنك إنشاء جدول رئيسي لكل قسم أو اعتماد جدول واحد مشترك
        // سأفترض وجود جدول periods يحتوي على: section_id, day_of_week, period_number, start_time, end_time, subject_id, teacher_id, color, is_break

        foreach ($request->periods as $day => $periods) {
            foreach ($periods as $index => $period) {


                ClassSchedule  ::create([
                    'section_id' => $sectionId,
                    'day_of_week' => $period['day_of_week'],
                    'period_number' => $period['period_number'],
                    'start_time' => $period['start_time'],
                    'end_time' => $period['end_time'],
                    'subject_id' => $period['subject_id'] ?? null,
                    'teacher_id' => $period['teacher_id'] ?? null,
                    'color' => $period['color'] ?? '#ffffff',
                    'is_break' => $period['is_break'] ?? 0,
                ]);
            }
        }

        return redirect()->back()->with('success', 'تم حفظ الجدول بنجاح!');
    }


    public function update(Request $request, $sectionId)
{
    // تحقق من البيانات المطلوبة
    // $request->validate([
    //     'working_hour_id' => 'required|exists:working_hours,id',
    //     'education_stage_id' => 'required|exists:education_stages,id',
    //     'classroom_id' => 'required|exists:classrooms,id',
    //     'section_id' => 'required|exists:sections,id',
    //     'periods' => 'required|array',
    // ]);

    // حذف الحصص القديمة للقسم
    ClassSchedule::where('section_id', $sectionId)->delete();

    // إدخال الحصص الجديدة
    foreach ($request->periods as $day => $periods) {
        foreach ($periods as $index => $period) {
            ClassSchedule::create([
                'section_id' => $sectionId,
                'day_of_week' => $period['day_of_week'],
                'period_number' => $period['period_number'],
                'start_time' => $period['start_time'],
                'end_time' => $period['end_time'],
                'subject_id' => $period['subject_id'] ?? null,
                'teacher_id' => $period['teacher_id'] ?? null,
                'color' => $period['color'] ?? '#ffffff',
                'is_break' => $period['is_break'] ?? 0,
            ]);
        }
    }

    return redirect()->back()->with('success', 'تم تعديل الجدول بنجاح!');
}
    


    public function checkSectionSchedule($sectionId)
    {
        $exists = ClassSchedule::where('section_id', $sectionId)->exists();
        return response()->json(['exists' => $exists]);
    }


    public function destroy(string $sectionId)
    {
        // تحقق أولًا إذا وجد جدول للشعبة
        $schedule = ClassSchedule::where('section_id', $sectionId)->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'لا يوجد جدول لهذه الشعبة.');
        }

        // حذف جميع الحصص الخاصة بهذه الشعبة
        ClassSchedule::where('section_id', $sectionId)->delete();

        return redirect()->back()->with('success', 'تم حذف جدول الشعبة بنجاح.');
    }


    public function getSchedule($sectionId)
    {


        $section = ClassSchedule::where('section_id', $sectionId)
            ->with('section.classroom.educationalStage.working_hour')
            ->first();

        $schedule = ClassSchedule::where('section_id', $sectionId)
            ->with(['subject', 'teacher'])
            ->orderByRaw("FIELD(day_of_week,'sunday','monday','tuesday','wednesday','thursday','friday','saturday')")
            ->orderBy('period_number')
            ->get()
            ->groupBy('day_of_week');

        return response()->json(["schedule"=>$schedule, 'sections'=>$section]);
    }




  public function getDayPeriods(Request $request)
{
    $request->validate([
        'day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
        'section_id'  => 'required|exists:sections,id',
        'teacher_id'  => 'required|exists:teachers,id',
    ]);

    $schedules = ClassSchedule::with('subject')
        ->where('day_of_week', $request->day_of_week)
        ->where('section_id', $request->section_id)
        ->where('teacher_id', $request->teacher_id)
        ->where('is_break', false)
        ->when($request->subject_id, function($q) use ($request) {
            $q->whereHas('subject', fn($q2) => $q2->where('id', $request->subject_id));
        })
        ->orderBy('period_number')
        ->get();

    if ($schedules->isEmpty()) {
        return response()->json([['id' => null, 'label' => 'لا يوجد حصص']]);
    }

    $options = $schedules->map(fn($s) => [
        'id' => $s->id,
        'label' => "الحصة {$s->period_number} | {$s->start_time} - {$s->end_time}"
    ]);

    return response()->json($options);
}
}
