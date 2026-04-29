<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignments;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssignmentsteacherControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        $assignments = Assignments::where('teacher_id', $teacher->id)-> with(['subject', 'section', 'teacher'])->get();
    

        // dd($assignments);
        return view('Teacher.Assignments.index', compact('assignments'));
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

        //    dd($request->all());
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

        // تجهيز مصفوفة البيانات
        $data = $request->only([
            'title',

            'section_id',
            'subject_id',
        

            'due_date',
            'status',
            'description'
        ]);

        // التعامل مع الملف المرفوع باستخدام store على disk 'public'
        if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
            $data['file_path'] = $request->file('file_path')->store('assignments', 'public');
        }
        $data['teacher_id']=$teacher->id;

        // إنشاء الواجب
        $assignment = Assignments::create($data);

        return redirect()->route('assignmentsteacher.index')->with('success', 'تم إضافة الواجب بنجاح ✅');
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
    public function update(Request $request,  $id)
    {
        $assignment = Assignments::findOrFail($id);

        $data = $request->only([
            'title',

            'section_id',
            'subject_id',


            'due_date',
            'status',
            'description'
        ]);

        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $data['teacher_id'] = $teacher->id;

        // إذا تم رفع ملف جديد
        if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
            // حذف الملف القديم إذا كان موجودًا
            if ($assignment->file_path && Storage::disk('public')->exists($assignment->file_path)) {
                Storage::disk('public')->delete($assignment->file_path);
            }

            // حفظ الملف الجديد باسم مبني على عنوان الواجب
            $fileName = Str::slug($request->title) . '.' . $request->file('file_path')->getClientOriginalExtension();
            $data['file_path'] = $request->file('file_path')->storeAs('assignments', $fileName, 'public');
        } else {
            unset($data['file_path']); // نحتفظ بالملف القديم إذا لم يتم رفع جديد
        }

        $assignment->update($data);

        return redirect()->route('assignmentsteacher.index')->with('success', 'تم تعديل الواجب بنجاح ');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $assignment = Assignments::findOrFail($id);

        // إذا كان هناك ملف مرفق، نحذفه أولًا
        if ($assignment->file_path && Storage::disk('public')->exists($assignment->file_path)) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        // حذف السجل من قاعدة البيانات
        $assignment->delete();

        return redirect()->route('assignmentsteacher.index')
            ->with('danger', 'تم حذف الواجب بنجاح ✅');
    }
}
