<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Teacher;
use GuzzleHttp\Promise\Each;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class examteacherControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $today = now()->toDateString();
        $currentTime = now()->toTimeString();

        // الاختبارات القادمة
        $upcomingExams = Exam::where('teacher_id', $teacher->id)
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('exam_date', '>', $today)
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->where('exam_date', $today)
                            ->where('start_time', '>', $currentTime);
                    });
            })
            ->orderBy('exam_date')
            ->orderBy('start_time')
            ->get();

        // الاختبارات المنتهية
        $completedExams = Exam::where('teacher_id', $teacher->id)
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('exam_date', '<', $today)
                    ->orWhere(function ($q) use ($today, $currentTime) {
                        $q->where('exam_date', $today)
                            ->where('end_time', '<', $currentTime);
                    });
            })
            ->orderByDesc('exam_date')
            ->orderByDesc('end_time')
            ->get();

        return view('Teacher.Exam.index', compact('upcomingExams', 'completedExams'));
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
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // تجهيز مصفوفة البيانات
        $data = $request->only([
            'title',
            'section_id',
            'subject_id',
        
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
        $data['teacher_id'] = $teacher->id;
        // إنشاء الاختبار
        $exam = Exam::create($data);

        return redirect()->route('examsteacher.index')->with('success', 'تم إضافة الاختبار بنجاح ✅');
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
    public function update(Request $request, $id)
    {
        // جلب الاختبار الحالي
        $exam = Exam::findOrFail($id);

        // تجهيز مصفوفة البيانات للتحديث
        $data = $request->only([
            'title',
            'section_id',
            'subject_id',
         
            'exam_date',
            'start_time',
            'end_time',
            'loc',
            'description'
        ]);
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // التعامل مع الملف المرفوع
        if ($request->hasFile('exam_file') && $request->file('exam_file')->isValid()) {
            // حذف الملف القديم إذا كان موجوداً
            if ($exam->exam_file && Storage::disk('public')->exists($exam->exam_file)) {
                Storage::disk('public')->delete($exam->exam_file);
            }

            // تخزين الملف الجديد
            $data['exam_file'] = $request->file('exam_file')->store('exams', 'public');
        }
        $data['teacher_id'] = $teacher->id;
        // تحديث بيانات الاختبار
        $exam->update($data);

        return redirect()->route('examsteacher.index')->with('success', 'تم تعديل الاختبار بنجاح ✅');
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

        return redirect()->route('examsteacher.index')
            ->with('danger', 'تم حذف الإختبار  بنجاح ✅');
    }
}
