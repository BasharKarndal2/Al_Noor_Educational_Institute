<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Subjects_Colntrollers extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects=Subject::all();
        return view('admin.Subjects.index',compact('subjects'));
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
        $validator = Validator::make(
            $request->except(['working_hour_id', 'education_stage_id']),
            [
                'name' => 'required|min:3',
                'note' => 'required',
                'status' => 'required|in:active,inactive',
                'number_se' => 'required|integer|between:1,10',
                

            ],
            [
                'name.required' => 'يرجى إدخال اسم الفوج.',
                'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل.',
                'note.required' => 'يرجى إدخال الملاحظات.',
                'status.required' => 'يرجى اختيار الحالة.',
                'status.in' => 'قيمة الحالة غير صحيحة، يجب أن تكون نشط أو غير نشط.',
                'number_se.between' => 'عدد الحصص يجب أن يكون بين 1 و 10.',
            ]
        );


        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'create',
                   
                  
                
                ]); // تمرير نوع العملية
        }
        $data = $request->all();
        Subject::create($data);
        return redirect()->route('subject.index')->with('success', 'تمت إضافة الشعبة  الدراسية بنجاح.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = Subject::find($id); // أو where(...)->first()
        $teachers = $subject->teachers;
    //   dd($teachers);
        return view('admin.Subjects.show_teacher', compact( 'teachers' , 'subject'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subject = Subject::
            where('id', $id)
            ->firstOrFail();

        return response()->json($subject);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:3',
                'note' => 'required',
                'status' => 'required|in:active,inactive',
                'number_se' => 'required|integer|between:1,10',


            ],
            [
                'name.required' => 'يرجى إدخال اسم الفوج.',
                'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل.',
                'note.required' => 'يرجى إدخال الملاحظات.',
                'status.required' => 'يرجى اختيار الحالة.',
                'status.in' => 'قيمة الحالة غير صحيحة، يجب أن تكون نشط أو غير نشط.',
                'number_se.between' => 'عدد الحصص يجب أن يكون بين 1 و 10.',
            ]
        );
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'edit',
                    'id' => $id,
                    'url' => 'subject.update',
                    'id_model' => 'editSubjectModal',
                ]); // تمرير نوع العملية
        }




        // dd($validator->validated());

        $subject = Subject::findOrFail($id);
        $subject->update($validator->validated());
        return redirect()->route('subject.index')->with('info', 'تمت تعديل بيانات   المادة بنجاح.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $subject = Subject::find($id);
        $subject->delete();

        return redirect()->route('subject.index')->with('danger', 'تمت حذف المادة بنجاح.');
    }


    public function addsubject_to_section( Request $request ,$id){
        $sectionId = $request->input('section_id');
        // $id هو معرف المادة
        $subject = Subject::findOrFail($id);

      
        $section = Section::findOrFail($sectionId);
      
        if($subject &&  $section){
            $subject->sections()->syncWithoutDetaching([$sectionId]);

        }

        return redirect()->route('subject.index')->with('success', 'تمت إضافة المادة إلى الشعبة بنجاح.');
    }
    public function addsubjects_to_section(Request $request, $id)
    {
        $sectionId = $id;  // $id هو معرف الشعبة
        $subjectIds = $request->input('subject_ids'); // استقبل أكثر من مادة ك array

        $section = Section::findOrFail($sectionId);

        if ($section && is_array($subjectIds)) {
            // ربط المواد بالشعبة، بدون حذف الموجودين (syncWithoutDetaching)
            $section->subjects()->syncWithoutDetaching($subjectIds);
        }

        return redirect()->route('subject.index')->with('success', 'تمت إضافة المواد إلى الشعبة بنجاح.');
    }

    public function getSubjectsNotInSection(Request $request)
    {
        $sectionId = $request->query('section_id');

        // جلب المواد التي ليست مرتبطة بهذه الشعبة
        $subjects = Subject::whereDoesntHave('sections', function ($query) use ($sectionId) {
            $query->where('sections.id', $sectionId);
        })->get(['id', 'name']);

        return response()->json($subjects);
    }
    public function getNotAssignedToTeacher($id)
    {
        $teacherId = $id;

        // جلب المواد المرتبطة بالمعلم
        $assignedSubjectIds = Subject::whereHas('teachers', function ($query) use ($teacherId) {
            $query->where('teacher_subject.teacher_id', $teacherId); // التعديل هنا
        })->pluck('id');

        // جلب المواد التي ليست مرتبطة بالمعلم
        $subjects = Subject::whereNotIn('id', $assignedSubjectIds)->get(['id', 'name']);

        return response()->json($subjects);
    }

public function  get_subjects_in_teacher_and_section_notjoin(Request $request)
    {
        
        $teacherId = $request->teacher_id_or_subject;
        $sectionId = $request->section_id_or_classroom_id;
// dd($teacherId, $sectionId);
        // المواد التي يدرّسها المعلم
        $teacherSubjectIds = DB::table('teacher_subject')
            ->where('teacher_id', $teacherId)
            ->pluck('subject_id');

        // المواد الموجودة في هذه الشعبة
        $sectionSubjectIds = DB::table('section_subject')
            ->where('section_id', $sectionId)
            ->pluck('subject_id');

        // المواد التي درّسها المعلم داخل الشعبة فعليًا
        $alreadyAssignedSubjectIds = DB::table('section_subject_teacher')
            ->where('teacher_id', $teacherId)
            ->where('section_id', $sectionId)
            ->pluck('subject_id');

        // المواد المشتركة بين الشعبة والمعلم والتي لم تُربط بعد
        $availableSubjects = Subject::whereIn('id', $teacherSubjectIds)
            ->whereIn('id', $sectionSubjectIds)
            ->whereNotIn('id', $alreadyAssignedSubjectIds)
            ->get();

        return response()->json($availableSubjects);
    }


    public function show_all_data($id)
    {
        $subject = Subject::with(['sections', 'sections.classroom.educationalStage', 'sections.classroom.educationalStage.working_hour', 'teachers'])
            ->where('id', $id)
            ->firstOrFail();

    
        return response()->json([
            'subject' => $subject,
        
        ]);
    }


    public function getSubjectsInSection($id)
    {
        // جلب المواد المرتبطة بالشعبة
        $subjects = Subject::whereHas('sections', function ($query) use ($id) {
            $query->where('sections.id', $id);
        })->get(['id', 'name']);

        return response()->json($subjects);
    }

}
