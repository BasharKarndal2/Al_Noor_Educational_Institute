<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Educational_Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassroomsColntrollers extends Controller
{
    /**
     * Display a listing of the resource.
     */

 
    public function index()
    {
        $classrooms = Classroom::with('educationalStage.working_hour', 'sections.students')
            ->get();

        return view('admin.Classroom.index', compact('classrooms'));
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
            $request->except(['working_hour_id']),
            [
                'name' => 'required|min:3',
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
                    'id_model' => 'addClassroomModal',
                ]); // تمرير نوع العملية
        }
        $data = $request->all();
        Classroom::create($data);
        return redirect()->route('classroom.index')->with('success', 'تمت إضافة الصف  الدراسي بنجاح.');
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
        $classroom = Classroom::with(['educationalStage', 'educationalStage.working_hour' , 'sections.students'])
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($classroom);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = Validator::make(
            $request->except(['working_hour_id']),
            [
                'name' => 'required|min:3',
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
                        'url' => 'classroom.update',
                    'id_model' => 'editClassroomModal',
                ]); // تمرير نوع العملية
        }
        $classroom = Classroom::findOrFail($id);

        // dd($classroom->education_stage_id, $request->education_stage_id);
        $classroom->name = $request->name ;
        $classroom->note = $request->note;
        $classroom->status = $request->status;
        $classroom->education_stage_id = $request->education_stage_id;
        $classroom->save();
        // $classroom->update($validator->validated());
        return redirect()->route('classroom.index')->with('info', 'تمت تعديل بيانات الصف  الدراسي بنجاح.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $classroom = Classroom::find($id);
        $classroom->delete();

        return redirect()->route('classroom.index')->with('danger', 'تمت حذف الصف الدراسي   بنجاح.');
    }


    public function loaddatafrometabel_baceon_value_and_target_data($id){


        $Edu =Educational_Stage::where('id', $id)->firstOrFail();

        return response()->json($Edu);

    }
    public function get_classroom_based_on_education_stage($id){
        $classroom = Classroom::where('education_stage_id', $id)->get();
    
        return response()->json($classroom);
    }
}
