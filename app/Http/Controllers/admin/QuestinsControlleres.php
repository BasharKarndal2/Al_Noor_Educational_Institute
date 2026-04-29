<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Section;
use App\Models\Section_Subject;
use Illuminate\Http\Request;

class QuestinsControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $questions = Question::with([
            'sectionSubject.subject',
            'sectionSubject.section.classroom'
        ])->get();

        // إزالة التكرارات حسب نص السؤال
        $questions = $questions->unique('question_text');
        // $questions = Question::all();
        return view('admin.Question.index', compact('questions'));
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
        // dd($request->all());

        // التحقق من البيانات إذا لزم الأمر
        $validated = $request->validate([
            'working_hour_id' => 'required|integer',
            'education_stage_id' => 'required|integer',
            'classroom_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'questions' => 'required|array',
        ]);

        $classroomId = $request->classroom_id;
        $subjectId = $request->subject_id;

        // 1. الحصول على جميع الشعب التابعة للصف المحدد
        $sections = Section::where('classroom_id', $classroomId)->get();

        foreach ($sections as $section) {
            // 2. التحقق من أن المادة مرتبطة بهذه الشعبة
            $sectionSubject = Section_Subject::where('section_id', $section->id)
                ->where('subject_id', $subjectId)
                ->first();

            if ($sectionSubject) {
                $sectionSubjectId = $sectionSubject->id;

                // 3. تخزين الأسئلة لهذه الشعبة/المادة
                foreach ($request->questions as $question) {
                    // تحقق من أن كل القيم موجودة
                    if (
                        isset($question['name']) &&
                        isset($question['option_a']) &&
                        isset($question['option_b']) &&
                        isset($question['option_c']) &&
                        isset($question['option_d']) &&
                        isset($question['correct_option'])
                    ) {
                        Question::create([
                            'section_subject_id' => $sectionSubjectId,
                            'question_text' => $question['name'],
                            'option_a' => $question['option_a'],
                            'option_b' => $question['option_b'],
                            'option_c' => $question['option_c'],
                            'option_d' => $question['option_d'],
                            'correct_option' => $question['correct_option'],
                        ]);
                    }
                }
            }
        }

        // 4. إعادة التوجيه أو عرض رسالة نجاح
        return redirect()->back()->with('success', 'تم إضافة الأسئلة بنجاح.');
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
        // حذف السؤال
        $question = Question::findOrFail($id);

        // احذف كل الأسئلة التي لها نفس نص السؤال
        Question::where('question_text', $question->question_text)->delete();

        return redirect()->back()->with('success', 'تم حذف جميع الأسئلة التي لها نفس النص.');

        // إعادة التوجيه أو عرض رسالة نجاح
        return redirect()->back()->with('success', 'تم حذف السؤال بنجاح.');
    }


    public function indexByClassroomAndSubject($classroomId, $subjectId)
    {
        $questions = Question::with([
            'sectionSubject.subject',
            'sectionSubject.section.classroom'
        ])
            ->whereHas('sectionSubject.section.classroom', function ($query) use ($classroomId) {
                $query->where('id', $classroomId);
            })
            ->whereHas('sectionSubject.subject', function ($query) use ($subjectId) {
                $query->where('id', $subjectId);
            })
            ->get();

        // إزالة التكرارات حسب نص السؤال
        $questions = $questions->unique('question_text');

        return $questions;
    }
}
