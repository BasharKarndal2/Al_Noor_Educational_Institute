<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TeachersControllers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers=Teacher::all();
        return view('admin.Teacher.index',[
            'teachers' => $teachers,
        ]);
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


        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'full_name'      => 'required|string|min:3',
                'national_id'     => 'required|string|unique:teachers,national_id',
                'birth_date'     => 'required|date',
                'gender'         => 'required|in:male,female',
                'marital'        => 'required|in:married,single',
                'specialization' => 'required|string',
                'experience'     => 'required|integer|min:0',
                'phone'          => 'required|string|unique:teachers,phone',
                'email'          => 'required|email|unique:teachers,email',
                'password'       => 'required|min:8',
                'status'         => 'required|in:active,inactive,on_leave',
                'hire_date'      => 'required|date',
                'address'        => 'nullable|string',
               
             
            ],
            [
                'full_name.required' => 'يرجى إدخال الاسم الكامل.',
                'national_id.required' => 'يرجى إدخال رقم هوية المعلم.',
                'national_id.unique' => 'رقم الهوية مستخدم مسبقًا.',
                'birth_date.required' => 'يرجى إدخال تاريخ الميلاد.',
                'gender.required' => 'يرجى اختيار الجنس.',
                'marital.required' => 'يرجى تحديد الحالة الاجتماعية.',
                'specialization.required' => 'يرجى إدخال التخصص.',
                'experience.required' => 'يرجى إدخال عدد سنوات الخبرة.',
                'phone.required' => 'يرجى إدخال رقم الهاتف.',
                'phone.unique' => 'رقم الهاتف مستخدم مسبقًا.',
                'email.required' => 'يرجى إدخال البريد الإلكتروني.',
                'email.unique' => 'البريد الإلكتروني مستخدم مسبقًا.',
                'password.required' => 'يرجى إدخال كلمة المرور.',
                'status.required' => 'يرجى اختيار الحالة.',
                'hire_date.required' => 'يرجى إدخال تاريخ التعيين.',
                'image_path.image' => 'الملف يجب أن يكون صورة.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'create',
                    'id_model' => 'addTeacherModal',
                ]);
        }

        $data = $validator->validated(); // ✅ هنا يتم استخراج البيانات المصادق عليها

        // إنشاء المستخدم المرتبط
        $adminRole = Role::where('name', 'teacher')->first();
        if (!$adminRole) {
            return redirect()->back()->with([
                'error' => 'لم يتم العثور على دور المسؤول (admin).',
            ]);
        }

        // رفع الصورة إن وجدت
        $imagePath = null;


        if ($request->hasFile('image_path')) {
            if ($request->file('image_path')->isValid()) {
                $imagePath = $request->file('image_path')->store('image', 'public');
                // dd($imagePath); // اختياري للتأكد من النتيجة
            } else {
                $imagePath = 'image/default.png';
            }
        } else {
            $imagePath = 'image/default.png';
        }
        $user = new User();
        $user->name = $data['full_name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role_id = $adminRole->id; 
        $user->save();

        // إنشاء المعلم
       $teacher= Teacher::create([
            'full_name'     => $data['full_name'],
            'national_id'    => $data['national_id'],
            'birth_date'    => $data['birth_date'],
            'gender'        => $data['gender'],
            'marital'       => $data['marital'],
            'specialization' => $data['specialization'],
            'experience'    => $data['experience'],
            'phone'         => $data['phone'],
            'email'         => $data['email'],
            'status'        => $data['status'],
            'hire_date'     => $data['hire_date'],
            'address'       => $data['address'] ?? null,
            'image_path'    => $imagePath,
            'user_id'       => $user->id,
         
        ]);
        $teacher->notes=$request->not;
        $teacher->save();
        return redirect()
            ->route('teaher.index')
            ->with([
                'success' => 'تم إضافة المعلم بنجاح.',
            ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $teacher = Teacher::with(['sections.classroom', 'subjects'])->findOrFail($id);

        return response()->json([
            'id'             => $teacher->id,
            'full_name'      => $teacher->full_name,
            'status'         => $teacher->status,
            'national_id'    => $teacher->national_id,
            'specialization' => $teacher->specialization,
            'birth_date'     => \Carbon\Carbon::parse($teacher->birth_date)->format('d/m/Y'),
            'experience'     => $teacher->experience,
            'phone'          => $teacher->phone,
            'email'          => $teacher->email,
            'hire_date'      => \Carbon\Carbon::parse($teacher->hire_date)->format('d/m/Y'),
            'gender'         => $teacher->gender,
            'address'        => $teacher->address,
            'photo'          => $teacher->image_path ,

            // مدموج الصف + الشعبة
            'classes'        => $teacher->sections->map(function ($section) {
                return $section->classroom->name . ' ' .'(' . $section->name.')';
            }),

            'subjects'       => $teacher->subjects->pluck('name'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // إيجاد السجل
        // جلب المعلم بناءً على المعرف
        $teacher = Teacher::findOrFail($id);

        // جلب المستخدم المرتبط بالمعلم
        $user = User::findOrFail($teacher->user_id);

        // إعادة البيانات بصيغة JSON
        return response()->json(
            $teacher
               
           
        );}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'full_name'      => 'required|string|min:3',
                'national_id'     => 'required|string|unique:teachers,national_id,' . $id,
                'birth_date'     => 'required|date',
                'gender'         => 'required|in:male,female',
                'marital'        => 'required|in:married,single',
                'specialization' => 'required|string',
                'experience'     => 'required|integer|min:0',
                'phone'          => 'required|string|unique:teachers,phone,' . $id,
                'email'          => 'required|email|unique:teachers,email,' . $id,
                'status'         => 'required|in:active,inactive,on_leave',
                'hire_date'      => 'required|date',
                'address'        => 'nullable|string',
               
                'password'       => 'nullable|string|min:6',
               
            ],
            [
                'full_name.required' => 'يرجى إدخال الاسم الكامل.',
                'national_id.required' => 'يرجى إدخال رقم هوية المعلم.',
                'national_id.unique' => 'رقم الهوية مستخدم مسبقًا.',
                'birth_date.required' => 'يرجى إدخال تاريخ الميلاد.',
                'gender.required' => 'يرجى اختيار الجنس.',
                'marital.required' => 'يرجى تحديد الحالة الاجتماعية.',
                'specialization.required' => 'يرجى إدخال التخصص.',
                'experience.required' => 'يرجى إدخال عدد سنوات الخبرة.',
                'phone.required' => 'يرجى إدخال رقم الهاتف.',
                'phone.unique' => 'رقم الهاتف مستخدم مسبقًا.',
                'email.required' => 'يرجى إدخال البريد الإلكتروني.',
                'email.unique' => 'البريد الإلكتروني مستخدم مسبقًا.',
                'password.required' => 'يرجى إدخال كلمة المرور.',
                'status.required' => 'يرجى اختيار الحالة.',
                'hire_date.required' => 'يرجى إدخال تاريخ التعيين.',
                'image_path.image' => 'الملف يجب أن يكون صورة.',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'edit',
                    'id_model' => 'editTeacherModal',
                    'id' => $id, // إضافة معرف المعلم إلى الجلسة
                    'url' => 'teaher.update', // اسم المسار لتحديث المعلم
                ]);
        }

        $teacher = Teacher::findOrFail($id);
        $user = User::findOrFail($teacher->user_id);

        $data = $validator->validated();

        // ✅ تحديث بيانات المستخدم
        $user->name = $data['full_name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // ✅ التحقق من تغيير الصورة
        if ($request->hasFile('image_path')) {

            if($teacher->image_path == 'image/default.png'){
                // تخزين الصورة الجديدة
                $newImagePath = $request->file('image_path')->store('image', 'public');
                $data['image_path'] = $newImagePath;

            }
            
            else{

                if ($teacher->image_path && Storage::disk('public')->exists($teacher->image_path)) {
                    Storage::disk('public')->delete($teacher->image_path);
                }
                $newImagePath = $request->file('image_path')->store('image', 'public');
                $data['image_path'] = $newImagePath;
            }
          
           

           
        } 
        else {
            // إذا لم يتم رفع صورة جديدة، لا نغير الحقل
            unset($data['image_path']);
        }
        $data['notes']=$request->note;
    

        $teacher->update($data);
        $teacher->notes= $request->note;
        $teacher->save();
        return redirect()->back()->with('success', 'تم تعديل بيانات المعلم بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // إيجاد السجل
        $teacher = Teacher::findOrFail($id);
        $user = User::findOrFail($teacher->user_id);

        // حذف الصورة من التخزين إذا كانت موجودة وليست الصورة الافتراضية
        $defaultImage = 'image/default.png'; // ضع هنا اسم الصورة الافتراضية الفعلي
        if ($teacher->image_path && $teacher->image_path !== $defaultImage && Storage::disk('public')->exists($teacher->image_path)) {
            Storage::disk('public')->delete($teacher->image_path);
        }

        // حذف السجل من قاعدة البيانات
        $teacher->delete();
        $user->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('teaher.index')->with('success', 'تم حذف المعلم بنجاح.');
    }


public  function getSpecializations()
{
    $specializations = Teacher::select('specialization')->distinct()->pluck('specialization');
    return response()->json($specializations);
}

    public function addsubjects_to_teacher(Request $request, $id)
    {
        $teacherID = $id;
        $subjectIds = $request->input('subject_ids'); // [1, 2, 3]
        $subjectPrices = $request->input('subject_prices'); // [1 => 100, 2 => 150, 3 => 200]

        $teacher = Teacher::findOrFail($teacherID);

        if ($teacher && is_array($subjectIds)) {
            $attachData = [];

            foreach ($subjectIds as $subjectId) {
                $price = $subjectPrices[$subjectId] ?? null;
                if ($price !== null) {
                    $attachData[$subjectId] = ['price' => $price];
                }
                else {
                    $attachData[$subjectId] = ['price' => 0]; // إذا لم يكن هناك سعر، نضع 0
                }
            }

            // ربط مع الأسعار بدون حذف السابقين
            $teacher->subjects()->syncWithoutDetaching($attachData);
        }

        return redirect()->route('teaher.index')->with('success', 'تمت إضافة المواد إلى المعلم بنجاح.');
    }
//  إضافة معلم إلى شعبة
    public function addteacher_to_section(Request $request, $id)
    {
        $teacherId = $id; // معرف المعلم
        $sectionId = $request->input('section_id'); // معرف الشعبة
        $subjectId = $request->input('subjct_id'); // معرف المادة
        $teacher = Teacher::findOrFail($teacherId);
      
        // ربط المعلم بالشعبة بدون حذف السابقين
        $teacher->sections()->syncWithoutDetaching([$sectionId]);

        SectionSubjectTeacher::create([
            'teacher_id' => $teacherId,
            'section_id' => $sectionId,
            'subject_id' => $subjectId,
        ]);

        return redirect()->route('teaher.index')->with('success', 'تمت إضافة المعلم إلى الشعبة بنجاح.');
    }

//  هذه الدالة لجلب المعلمين بناءً على المادة والشعبة

    public function getTeachersBySubjectAndSection(Request $request)
    {
        $subjectId = $request->subject_id;
        $sectionId = $request->section_id;

        // جلب IDs المعلمين المرتبطين بالمادة والشعبة
        $teacherIds = DB::table('section_subject_teacher')
            ->where('subject_id', $subjectId)
            ->where('section_id', $sectionId)
            ->pluck('teacher_id');

        // جلب بيانات المعلمين بناءً على IDs
        $teachers = Teacher::whereIn('id', $teacherIds)->get();

        return response()->json($teachers);
    }


    public function getSubjects($id)
    {
        $teacher = Teacher::with('subjects')->findOrFail($id);
        return response()->json($teacher->subjects);
    }
    public function checkBeforeDelete($teacherId, $subjectId)
    {
        $isTeaching = DB::table('section_subject_teacher')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $subjectId)
            ->exists();

        if ($isTeaching) {
            return response()->json([
                'can_delete' => false,
                'message' => 'لا يمكن إزالة هذه المادة حاليا لأن المعلم يدرّسها في إحدى الشعب'
            ]);
        }

        return response()->json(['can_delete' => true]);
    }

 
    public function deleteSubject($teacherId, $subjectId)
    {
        // إزالة العلاقة فقط
        DB::table('teacher_subject')
            ->where('teacher_id', $teacherId)
            ->where('subject_id', $subjectId)
            ->delete();

        return response()->json(['success' => true, 'message' => 'تمت إزالة المادة من المعلم']);
    }
}