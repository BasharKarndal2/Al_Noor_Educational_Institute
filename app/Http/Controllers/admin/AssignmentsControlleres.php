<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Assignments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class AssignmentsControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

        $assignments = Assignments::with(['subject', 'section', 'teacher'])->get();

// dd($assignments);
        return view('admin.Assignments.index' ,compact('assignments'));
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

        // تجهيز مصفوفة البيانات
        $data = $request->only([
            'title',
            
            'section_id',
            'subject_id',
            'teacher_id',
    
            'due_date',
            'status',
            'description'
        ]);

        // التعامل مع الملف المرفوع باستخدام store على disk 'public'
        if ($request->hasFile('file_path') && $request->file('file_path')->isValid()) {
            $data['file_path'] = $request->file('file_path')->store('assignments', 'public');
        }

        // إنشاء الواجب
        $assignment = Assignments::create($data);

        return redirect()->route('assignments.index')->with('success', 'تم إضافة الواجب بنجاح ✅');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)


    {

        
        $Assignment =Assignments::with(['subject', 'teacher', 'section.classroom.educationalStage.working_hour'])->findOrFail($id); // جلب الطلاب مع التقييم


        return response()->json($Assignment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $assignment = Assignments::findOrFail($id);

        // $data = $request->validate([
        //     'title' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'subject_id' => 'required|exists:subjects,id',
        //     'section_id' => 'required|exists:sections,id',
        //     'teacher_id' => 'required|exists:teachers,id',
        //     'due_date' => 'nullable|date',
        //     'status' => 'required|in:active,inactive',
        //     'file_path' => 'nullable|file|mimes:pdf,doc,docx,zip',
        // ]);

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
            $data['file_path']= $assignment->file_path;  // نحتفظ بالملف القديم إذا لم يتم رفع جديد
        }

        $assignment->update($data);

        return redirect()->route('assignments.index')->with('info', 'تم تعديل الواجب بنجاح ');
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

        return redirect()->route('assignments.index')
            ->with('danger', 'تم حذف الواجب بنجاح ✅');
    }
}
