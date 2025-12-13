<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment_submissions;
use App\Models\Assignments;
use Illuminate\Http\Request;

class Assignment_submissionsControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getSubmissions($assignment_id)
    {
        $submissions = Assignment_submissions::with('student')
            ->where('assignment_id', $assignment_id)
            ->get();

        return response()->json($submissions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function updateSubmission(Request $request, $id)
    {
        $submission = Assignment_submissions::findOrFail($id);
        $submission->feedback = $request->feedback ?? $submission->feedback;
        $submission->grade = $request->grade ?? $submission->grade;
        $submission->status = $request->status ?? $submission->status;
        $submission->save();

        return response()->json(['success' => true]);
    }

    /**
     * Store a newly created resource in storage.
     */
   


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
