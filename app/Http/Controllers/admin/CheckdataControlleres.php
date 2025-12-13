<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pearant;
use App\Models\Student;
use App\Models\Student_Requeast;
use App\Models\Teacher;
use App\Models\Teachers_Request;
use App\Models\User;
use Illuminate\Http\Request;

class CheckdataControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function checkUnique(Request $request)
    {
        // $request->validate([
        //     'field' => 'required|string|in:email,phone',
        //     'value' => 'required|string'
        // ]);

        $exists = false;

        if ($request->field == 'phone') {
            // تحقق في موديل Student_Requeast
            $existsInStudent = Student_Requeast::where('phone', $request->value)->exists();

            // تحقق في موديل آخر، مثلاً Guardian
            $existsInGuardian = Student::where('phone', $request->value)->exists();

            $exists = $existsInStudent || $existsInGuardian ;
        }

        if ($request->field == 'email') {
            // تحقق في موديل Student_Requeast
            $existsInStudent = Student_Requeast::where($request->field, $request->value)->exists();

            // تحقق في موديل آخر، مثلاً User
            $existsInUser = User::where($request->field, $request->value)->exists();
            

            $exists = $existsInStudent || $existsInUser;
        }

        return response()->json(['exists' => $exists]);
    }


    public function checkUnique_teacher(Request $request)
    {
        $request->validate([
            'field' => 'required|string|in:email,phone',
            'value' => 'required|string'
        ]);

        $exists = false;

        if ($request->field == 'phone') {
            // تحقق في موديل Student_Requeast
            $existsInteacher = Teachers_Request::where('phone', $request->value)->exists();

            // تحقق في موديل آخر، مثلاً Guardian
            $existsInGuardian = Teacher::where('phone', $request->value)->exists();

            $exists = $existsInteacher || $existsInGuardian;
        }

        if ($request->field == 'email') {
            // تحقق في موديل Student_Requeast
            $existsIneacher = Teachers_Request::where($request->field, $request->value)->exists();

            // تحقق في موديل آخر، مثلاً User
            $existsInUser = User::where($request->field, $request->value)->exists();


            $exists = $existsIneacher || $existsInUser;
        }

        return response()->json(['exists' => $exists]);
    }


    public function check_unique_parent(Request $request)
    {
        $request->validate([
            'field' => 'required|string|in:email,phone,national_id',
            'value' => 'required|string'
        ]);

        $exists = false;

        if ($request->field === 'national_id') {
            $exists = Pearant::where('national_id', $request->value)->exists();
        }

        if ($request->field === 'phone') {
            $exists = Pearant::where('phone', $request->value)->exists();
        }

        if ($request->field === 'email') {
            $existsInPearant = Pearant::where('email', $request->value)->exists();
            $existsInUser = User::where('email', $request->value)->exists();
            $exists = $existsInPearant || $existsInUser;
        }

        return response()->json(['exists' => $exists]);
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
