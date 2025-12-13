<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Educational_Stage;
use App\Models\Working_hour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Educational_StagesControllers extends Controller
{
  public function index()
  {

    $educationals = Educational_Stage::all();
    return view('admin.Educational_Stage.index', compact('educationals'));
  }

  public function store(Request $request)
  {

    $validator = Validator::make(
      $request->all(),
      [
        'name' => 'required|min:3',
        'note' => 'required',
        'status' => 'required|in:active,inactive',
        'working_hour_id' => 'required'
      ],
      [
        'name.required' => 'يرجى إدخال اسم الفوج.',
        'name.min' => 'الاسم يجب أن يحتوي على 3 أحرف على الأقل.',
        'note.required' => 'يرجى إدخال الملاحظات.',
        'status.required' => 'يرجى اختيار الحالة.',
        'status.in' => 'قيمة الحالة غير صحيحة، يجب أن تكون نشط أو غير نشط.',
        'working_hour_id.required' => 'يجب أن تختار الفوج '
      ]
    );



    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withErrors($validator)
        ->withInput()
        ->with([
          'form_type' => 'create',
          'id_model' => 'addEducational_StageModal',
        ]); // تمرير نوع العملية
    }
    $data = $request->all();
    Educational_Stage::create($data);
    return redirect()->route('educational_stage.index')->with('success', 'تمت إضافة المرحلة الدراسية بنجاح.');
  }
  public function create()
  {

    $working_hour = Working_hour::all();

    return response()->json($working_hour);
  }

  public function createss()
  {

    $working_hour = Working_hour::all();

    return response()->json($working_hour);
  }





  public function edit($id)
  {
    $stage = Educational_Stage::findOrFail($id);

    return response()->json($stage);
  }


  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|min:3',
      'note' => 'required',
      'status' => 'required|in:active,inactive',
      'working_hour_id'=> ' required'
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
          'url' => 'educational_stage.update',
          'working_id' => $request->working_hour_id
        ]); // تمرير نوع العملية
    }

    $stage = Educational_Stage::findOrFail($id);
    $stage->update($validator->validated());

    return redirect()->route('educational_stage.index')->with('info', 'تم تحديث بيانات المرحلة الدراسية بنجاح');
  }




  public function destroy(string $id)
  {

    $Ed = Educational_Stage::find($id);
    $Ed->delete();

    return redirect()->route('educational_stage.index')->with('danger', 'تمت حذف المرحلة الدراسية بنجاح بنجاح.');
  }


  public function get_education_stage_based_on_Working($id)
  {
    $Ed = Educational_Stage::where('working_hour_id', $id)->get();

    return response()->json($Ed);
  }
}
