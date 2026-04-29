<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class examControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $today = now();

        // الاختبارات القادمة
        $upcomingExams = Exam::where(function ($q) use ($today) {
            $q->where('exam_date', '>', $today->toDateString())
                ->orWhere(function ($q2) use ($today) {
                    $q2->where('exam_date', $today->toDateString())
                        ->where('start_time', '>', $today->toTimeString());
                });
        })->orderBy('exam_date')->orderBy('start_time')->get();

        // الاختبارات المنتهية
        $completedExams = Exam::where(function ($q) use ($today) {
            $q->where('exam_date', '<', $today->toDateString())
                ->orWhere(function ($q2) use ($today) {
                    $q2->where('exam_date', $today->toDateString())
                        ->where('end_time', '<', $today->toTimeString());
                });
        })->orderByDesc('exam_date')->orderByDesc('end_time')->get();

        return view('admin.Exam.index', compact('upcomingExams', 'completedExams'));
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
        // تحقق من صحة البيانات (يمكن إضافة FormRequest لاحقاً)
        // dd($request);

        // تجهيز مصفوفة البيانات
        $data = $request->only([
            'title',
            'section_id',
            'subject_id',
            'teacher_id',
            'exam_date',
            'start_time',
            'end_time',
            'loc',
            'description'
        ]);

        // التعامل مع الملف المرفوع باستخدام store على disk 'public'
        if ($request->hasFile('exam_file') && $request->file('exam_file')->isValid()) {
            $data['exam_file'] = $request->file('exam_file')->store('exams', 'public');
        }

        // إنشاء الاختبار
        $exam = Exam::create($data);

        return redirect()->route('exams.index')->with('success', 'تم إضافة الاختبار بنجاح ✅');
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


        $exam = Exam::with(['subject', 'teacher', 'section.classroom.educationalStage.working_hour'])->findOrFail($id); // جلب الطلاب مع التقييم


        return response()->json($exam);
    
}

    /**
     * Update the specified resource in storage.
     */
   
        public function update(Request $request, $id)
        {
            // جلب الاختبار الحالي
            $exam = Exam::findOrFail($id);

            // تجهيز مصفوفة البيانات للتحديث
            $data = $request->only([
                'title',
                'section_id',
                'subject_id',
                'teacher_id',
                'exam_date',
                'start_time',
                'end_time',
                'loc',
                'description'
            ]);

            // التعامل مع الملف المرفوع
            if ($request->hasFile('exam_file') && $request->file('exam_file')->isValid()) {
                // حذف الملف القديم إذا كان موجوداً
                if ($exam->exam_file && Storage::disk('public')->exists($exam->exam_file)) {
                    Storage::disk('public')->delete($exam->exam_file);
                }
                // تخزين الملف الجديد
                $data['exam_file'] = $request->file('exam_file')->store('exams', 'public');
            }

            // تحديث بيانات الاختبار
            $exam->update($data);

            return redirect()->route('exams.index')->with('success', 'تم تعديل الاختبار بنجاح ✅');
        }
   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);

        // إذا كان هناك ملف مرفق، نحذفه أولًا
        if ($exam->exam_file && Storage::disk('public')->exists($exam->exam_file)) {
            Storage::disk('public')->delete($exam->exam_file);
        }

        // حذف السجل من قاعدة البيانات
        $exam->delete();

        return redirect()->route('exams.index')
            ->with('danger', 'تم حذف الإختبار  بنجاح ✅');
    }
}
