<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\EvaluationResult;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationsteacherControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // آخر تقييم خاص بالمدرس الحالي
        $last_Evaluation = Evaluation::where('teacher_id', $teacher->id)
            ->latest()
            ->first();

        $lasetresults = collect();
        if ($last_Evaluation) {
            $lasetresults = EvaluationResult::where('evaluation_id', $last_Evaluation->id)->get();
        }

        // جميع التقييمات الخاصة بالمدرس الحالي
        $Evaluations = Evaluation::where('teacher_id', $teacher->id)->get();

        return view('Teacher.Evaluation.index', compact('Evaluations', 'lasetresults', 'last_Evaluation'));
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
        // التحقق من المدخلات
    //    dd($request->all());
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
      
        // إنشاء التقييم
        $evaluation = Evaluation::create([
            'title' => $request->title,

            'section_id' => $request->section_id,
            'subject_id' => $request->subjct_id,
            'teacher_id' => $teacher->id,
            'evaluation_date' => $request->evaluation_date,
            'frequency' => $request->frequency,
            'type' => $request->type,
            'description' => $request->note,
        ]);

        // حفظ درجات الطلاب
        foreach ($request->students as $studentId => $studentData) {
            EvaluationResult::create([
                'evaluation_id' => $evaluation->id,
                'student_id' => $studentId,
                'grade' => $studentData['grade'] ?? 0,
                'feedback' => $studentData['notes'] ?? 'لا يوجد',
            ]);
        }

        return redirect()->route('teacher.evaluations.index')->with('success', 'تم حفظ التقييم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function showdata($id)
    {
        $evaluation = Evaluation::with(['results.student', 'section.classroom.educationalStage.working_hour', 'subject'])->findOrFail($id);

        return response()->json($evaluation);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $evaluation = Evaluation::with(['subject', 'teacher', 'section', 'results.student', 'section.classroom.educationalStage.working_hour'])->findOrFail($id); // جلب الطلاب مع التقييم


        return response()->json($evaluation);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    { 
        // $request->validate([
        //     'title' => 'required|string|max:255',
        //     'working_hour_id' => 'required|integer',
        //     'education_stage_id' => 'required|integer',
        //     'classroom_id' => 'required|integer',
        //     'section_id' => 'required|integer',
        //     'subject_id' => 'required|integer',
        //     'teacher_id' => 'required|integer',
        //     'evaluation_date' => 'required|date',
        //     'frequency' => 'required|string',
        //     'type' => 'required|string',
        //     'note' => 'nullable|string',
        // ]);
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $evaluation = Evaluation::findOrFail($id);
        $evaluation->update(

            [
                'title' => $request->title,

                'section_id' => $request->section_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $teacher->id,
                'evaluation_date' => $request->evaluation_date,
                'frequency' => $request->frequency,
                'type' => $request->type,
                'description' => $request->note,
            ]


        );



        // تحديث الطلاب
        foreach ($request->students as $studentId => $studentData) {
            EvaluationResult::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'student_id' => $studentId,
                ],
                [
                    'grade' => $studentData['grade'] ?? 0,
                    'feedback' => $studentData['notes'] ?? 'لا يوجد',
                ]
            );
        }
        return redirect()->back()->with('success', 'تم تعديل التقييم بنجاح');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->delete();

        return redirect()->back()->with('success', 'تم حذف التقييم بنجاح');
    }
}
