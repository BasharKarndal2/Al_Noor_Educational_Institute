<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardControllers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        // الإحصائيات
        $studentsCount   = Student::count();
        $teachersCount   = Teacher::count();
        $classesCount    = Section::count();
        $subjectsCount   = Subject::count();

        // آخر الطلاب المسجلين
        $latestStudents = Student::with('sectionSubjectTeachers.subject', 'sectionSubjectTeachers.teacher', 'sectionSubjectTeachers.section')
            ->latest()
            ->take(5)
            ->get();
        return view('admin.dashboard.index', compact('user' , 'studentsCount',
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
