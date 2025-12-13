<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Working_hour;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class Working_hours_Controllers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   $working_hours=Working_hour::all();
        $studentsCount   = Student::count();
        $teachersCount   = Teacher::count();
        $classesCount    = Section::count();
        $subjectsCount   = Subject::count();

        // آخر الطلاب المسجلين
        $latestStudents = Student::with('sectionSubjectTeachers.subject', 'sectionSubjectTeachers.teacher', 'sectionSubjectTeachers.section')
            ->latest()
            ->take(5)
            ->get();
        return view('admin.Working_hour.index',compact('working_hours',
            'studentsCount',
            'teachersCount',
            'classesCount',
            'subjectsCount',
            'latestStudents',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {           
        return view('admin.Working_hour.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'note' => 'required',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'يرجى إدخال اسم الفوج.',
            'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل.',
            'note.required' => 'يرجى إدخال الملاحظات.',
            'status.required' => 'يرجى اختيار الحالة.',
            'status.in' => 'قيمة الحالة غير صحيحة، يجب أن تكون نشط أو غير نشط.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'create',
                 
                    
                ]); // تمرير نوع العملية
        }
        $data= $request->all();
        Working_hour::create($data);
        return redirect()->route('working_hours.index')->with('success', 'تمت إضافة الفوج بنجاح.');
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
    public function edit( $id)
    {

        $working_hour = Working_hour::findOrFail($id);
        // dd($working_hour);
        return response()->json($working_hour);
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'note' => 'required',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'يرجى إدخال اسم الفوج.',
            'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل.',
            'note.required' => 'يرجى إدخال الملاحظات.',
            'status.required' => 'يرجى اختيار الحالة.',
            'status.in' => 'قيمة الحالة غير صحيحة، يجب أن تكون نشط أو غير نشط.',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'edit',
                    'id' => $id,
                'url' => 'working_hours.update',
                ]); // تمرير نوع العملية
        }

        $working_hour = Working_hour::findOrFail($id);
        $working_hour->update($validator->validated());

        return redirect()->route('working_hours.index')->with('info', 'تم تحديث بيانات الفوج بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $Working_hour = Working_hour::find($id);
        $Working_hour->delete();

        return redirect()->route('working_hours.index')->with('danger', 'تمت حذف الفوج بنجاح.');
    }
}
