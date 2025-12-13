<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment_submissions;
use App\Models\Assignments;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentsstudentControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // جلب الطالب مع العلاقات
        $student = Student::where('user_id', Auth::id())
            ->with(['sectionsSubjectsTeachers.section', 'sectionsSubjectsTeachers.subject', 'sectionsSubjectsTeachers.teacher'])
            ->firstOrFail();

        // مصفوفة منظمة لكل علاقة
        $studentData = collect($student->sectionsSubjectsTeachers)->map(function ($sst) {
            // جلب الواجبات المطابقة للشعبة + المادة + المعلم مع ترتيب تنازلي حسب تاريخ التسليم
            $assignments = Assignments::where('section_id', $sst->section_id)
                ->where('subject_id', $sst->subject_id)
                ->where('teacher_id', $sst->teacher_id)
                ->orderBy('due_date', 'desc') // ترتيب عكسي
                ->get(['id', 'title', 'due_date', 'status', 'file_path', 'description'])
                ->toArray();

            return [
                'section_id'   => $sst->section->id ?? null,
                'section_name' => $sst->section->name ?? null,
                'subject_id'   => $sst->subject->id ?? null,
                'subject_name' => $sst->subject->name ?? null,
                'teacher_id'   => $sst->teacher->id ?? null,
                'teacher_name' => $sst->teacher->full_name ?? null,
                'assignments'  => $assignments
            ];
        })->toArray();

        // إعادة البيانات إلى العرض
        return view('Student.assignment.index', compact('studentData', 'student'));
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
    public function createdata(Request $request ,$id)
    {

        $student = Auth::user()->student;

        if ($request->hasFile('submitted_file') && $request->file('submitted_file')->isValid()) {
           $file_path= $request->file('submitted_file')->store('Assignment_submission', 'public');
        }

            $assigsum=Assignment_submissions::create([
                'student_id'=>$student->id,
            'assignment_id'=>$id,
            'submitted_file'=> $file_path,
            'submitted_text'=>$request->submitted_text
            ]);

        return redirect()->route('assignmentsStudent.index')->with('success', '  تم تسليم الواجب بنجاح سوف يتم تقييمه ');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Auth::user()->student;

        $assignment = Assignments::with(['subject', 'teacher'])->findOrFail($id);

        $submitted = Assignment_submissions::where('assignment_id', $id)
            ->where('student_id', $student->id)
            ->exists();

        return response()->json([
            'id' => $assignment->id,
            'title' => $assignment->title,
            'description' => $assignment->description,
            'subject_name' => $assignment->subject->name,
            'teacher_name' => $assignment->teacher->full_name,
            'created_at' => $assignment->created_at->format('Y-m-d'),
            'due_date' => $assignment->due_date,
            'file_path' => $assignment->file_path,
        
            'submitted' => $submitted,
        ]);
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
        //
    }
}
