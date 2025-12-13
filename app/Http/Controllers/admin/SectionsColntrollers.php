<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Assignments;
use App\Models\Attendance;
use App\Models\ClassSchedule;
use App\Models\Evaluation;
use App\Models\Exam;
use App\Models\Section;
use App\Models\Section_Subject;
use App\Models\SectionSubjectTeacher;
use App\Models\Student;
use App\Models\StudentSectionSubjectTeacher;
use App\Models\Subject;
use App\Models\Working_hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SectionsColntrollers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   $sections= Section::all();
        // جلب كل الشعب مع المعلمين المرتبطين بها والمواد
        $sections = Section::with([
            'subjectTeachers.teacher',
            'subjectTeachers.subject',
            'students'
        ])->get();

          
           
    //    dd($sections);
        $working_hours=Working_hour::all();
       return view('admin.Section.index',compact('sections', 'working_hours'));
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
                'name' => 'required|min:1',
                'note' => 'required',
                'status' => 'required|in:active,inactive',

            ],
            [
                'name.required' => 'يرجى إدخال اسم الفوج.',
                'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل.',
                'note.required' => 'يرجى إدخال الملاحظات.',
                'status.required' => 'يرجى اختيار الحالة.',
                'status.in' => 'قيمة الحالة غير صحيحة، يجب أن تكون نشط أو غير نشط.',

            ]
        );



        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'create',
                    'id_model' => 'addSectionModal',
                    
                ]); // تمرير نوع العملية
        }
        $data = $request->all();
        Section::create($data);
        return redirect()->route('section.index')->with('success', 'تمت إضافة الشعبة  الدراسية بنجاح.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $section = Section::where('id', $id)->firstOrFail();
        $Students = $section->students->unique('id')->values();
        // إحضار الطلاب المرتبطين بالشعبة

       
        return view('admin.Section.show_student_in_section', compact('section', 'Students'));
    
    }


    public function get_all_data_teacher($sectionId)
    {
        $section = Section::with(['sectionSubjectTeachers.teacher', 'sectionSubjectTeachers.subject'])
            ->findOrFail($sectionId);

        // نجمع المعلمين مع المواد لكل معلم
        $teachers = $section->sectionSubjectTeachers->groupBy('teacher_id')->map(function ($items) {
            return [
                'teacher' => $items->first()->teacher,
                'subjects' => $items->pluck('subject')->filter()->unique('id')->values()
            ];
        });

        return view('admin.Section.showteacher', compact('section', 'teachers'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $section = Section::with(['classroom','classroom.educationalStage', 'classroom.educationalStage.working_hour'])
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($section);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->except(['working_hour_id', 'education_stage_id']),
            [
                'name' => 'required|min:1',
                'note' => 'required',
                'status' => 'required|in:active,inactive',
          

            ],
            [
                'name.required' => 'يرجى إدخال اسم الفوج.',
                'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل.',
                'note.required' => 'يرجى إدخال الملاحظات.',
                'status.required' => 'يرجى اختيار الحالة.',
                'status.in' => 'قيمة الحالة غير صحيحة، يجب أن تكون نشط أو غير نشط.',

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
                'url' => 'section.update',
                    'id_model' => 'editSectionModal',
                ]); // تمرير نوع العملية
        }
        $section = Section::findOrFail($id);
        $section->name = $request->name;
        $section->note = $request->note;
        $section->status = $request->status;
        $section->classroom_id = $request->classroom_id;
        $section->maxvalue=$request->maxvalue;
        $section->save();
        return redirect()->route('section.index')->with('info', 'تمت تعديل بيانات الشعبة  الدراسي بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $section = Section::find($id);
        $section->delete();

        return redirect()->route('section.index')->with('danger', 'تمت حذف الشعبة بنجاح.');
    }




    public function filter(Request $request)
    {
        $query = Section::with('classroom.educationalStage.working_hour');

        if ($request->working_hour_id) {
            $query->whereHas('classroom.educationalStage', function ($q) use ($request) {
                $q->where('working_hour_id', $request->working_hour_id);
            });
        }

        if ($request->filled('education_stage_id')) {
            $query->whereHas('classroom.educationalStage', function ($q) use ($request) {
                $q->where('id', $request->education_stage_id);
            });
        }

        $sections = $query->get();

        return response()->json($sections);
    }


    public function get_sction_based_on_classroom($id)
    {
        $classroom = Section::where('classroom_id', $id)->get();

        return response()->json($classroom);
    }

    public function get_sction_based_on_classroom_not_in_subject(Request $request){
        $sectionId = $request->query('section_id_or_classroom_id');
        $teacherOrSubject = $request->query('teacher_id_or_subject');


        // جلب الشعب المرتبطة بالصف
        $sections = Section::where('classroom_id', $sectionId)
            ->whereDoesntHave('subjects', function ($query) use ($teacherOrSubject) {
                $query->where('subjects.id', $teacherOrSubject);
            })
            ->get();
        // $sections=   [
        //     ['id' => 1, 'name' => 'القسم 1'],
        //     ['id' => 2, 'name' => 'القسم 2'],
        // ];
        // dd($sections);
        return response()->json($sections);

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

        return redirect()->route('section.index')->with('success', 'تمت إضافة المواد إلى الشعبة بنجاح.');
    }



    


    public function get_all_data($id)
    {
        $section = Section::with([
            'classroom.educationalStage.working_hour',
            'subjectTeachers.subject',
            'subjectTeachers.teacher',
            'students' // إحضار الطلاب
        ])->where('id', $id)->firstOrFail();

        // إزالة التكرار حسب student_id
        $uniqueStudentCount = $section->students->unique('id')->count();

        // يمكنك إرفاقه يدويًا:
        $section->students_count = $uniqueStudentCount;

        return response()->json($section);
        }
    public function removeSubject($sectionId, $subjectId)
    {
        DB::transaction(function () use ($sectionId, $subjectId) {
            Section_Subject::where('subject_id', $subjectId)
                ->where('section_id', $sectionId)
                ->delete();

            StudentSectionSubjectTeacher::where('section_id', $sectionId)
                ->where('subject_id', $subjectId)
                ->delete();

            SectionSubjectTeacher::where('section_id', $sectionId)
                ->where('subject_id', $subjectId)
                ->delete();

            ClassSchedule::where('section_id', $sectionId)
                ->where('subject_id', $subjectId)
                ->delete();

            Evaluation::where('section_id', $sectionId)
                ->where('subject_id', $subjectId)
                ->delete();

            Exam::where('section_id', $sectionId)
                ->where('subject_id', $subjectId)
                ->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المادة من الشعبة وجميع العلاقات المرتبطة'
        ]);
    }



    public function getSectionTeachers($sectionId)
    {
        $section = Section::findOrFail($sectionId);
        return response()->json($section->teachers);
    }

    public function getTeacherSubjects($sectionId, $teacherId)
    {
        $subjects = SectionSubjectTeacher::where('section_id', $sectionId)
            ->where('teacher_id', $teacherId)
            ->with('subject')
            ->get()
            ->pluck('subject');
        return response()->json($subjects);
    }

    public function getAvailableTeachersForSubject($sectionId, $subjectId)
    {
        $subject = Subject::where('id', $subjectId)->first();
        $teachers = $subject ? $subject->teachers : collect();
           
        return response()->json($teachers);
    }





    public function replaceTeacher(Request $request)
    {
        $current = SectionSubjectTeacher::where('section_id', $request->section_id)
            ->where('teacher_id', $request->current_teacher_id)
            ->where('subject_id', $request->subject_id)
            ->first();

        if (!$current) {
            return response()->json(['success' => false, 'message' => 'المعلم الحالي غير موجود']);
        }

        // تحديث المعلم الجديد في المادة
        $current->teacher_id = $request->new_teacher_id;
        $current->save();

        // تحديث جميع الجداول المرتبطة
        $tablesToUpdate = [
            StudentSectionSubjectTeacher::class,
            Assignments::class,
            ClassSchedule::class,
            Evaluation::class,
            Exam::class
        ];

        foreach ($tablesToUpdate as $table) {
            $table::where('section_id', $request->section_id)
                ->where('teacher_id', $request->current_teacher_id)
                ->where('subject_id', $request->subject_id)
                ->update(['teacher_id' => $request->new_teacher_id]);
        }

        // التحقق من عدد المواد التي يدرسها المعلم القديم في الشعبة بعد التغيير
        $oldTeacherSubjectsCount = SectionSubjectTeacher::where('section_id', $request->section_id)
            ->where('teacher_id', $request->current_teacher_id)
            ->count();

        $newTeacherExists = SectionSubjectTeacher::where('section_id', $request->section_id)
            ->where('teacher_id', $request->new_teacher_id)
            ->exists();

        if ($oldTeacherSubjectsCount === 0) {
            // المعلم القديم لم يعد يدرس أي مادة في الشعبة => نحذفه من teacher_section
            DB::table('teacher_section')
                ->where('section_id', $request->section_id)
                ->where('teacher_id', $request->current_teacher_id)
                ->delete();
        }
        else{

            DB::table('teacher_section')->updateOrInsert(
                [
                    'section_id' => $request->section_id,
                    'teacher_id' => $request->new_teacher_id
                ]
            );
        }

        if (!$newTeacherExists) {
            // المعلم الجديد لم يكن موجوداً في الشعبة => نضيفه
            DB::table('teacher_section')->updateOrInsert(
                [
                    'section_id' => $request->section_id,
                    'teacher_id' => $request->new_teacher_id
                ]
            );
        }

        return response()->json(['success' => true, 'message' => 'تم استبدال المعلم بالمعلم الجديد بنجاح']);
    }
}
