<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Section;
use App\Models\setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class mainpageControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        $announcements=Announcement::all();



        $studentsCount   = Student::count();
        $teachersCount   = Teacher::count();
        $classesCount    = Section::count();
        $subjectsCount   = Subject::count();
        $teachers = Teacher::inRandomOrder()->limit(3)->get();
        $setting = Setting::first();
        return view('Main_page.index',compact('announcements',
            'studentsCount',
            'teachersCount',
            'classesCount',
            'subjectsCount',
            'setting','teachers'
    
    
    ));
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
