<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Student_Controlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $students =Student::with('sectionSubjectTeachers.subject', 'sectionSubjectTeachers.teacher', 'sectionSubjectTeachers.section')->get();
        
    // dd($students);
        // $students=Student::all();
     
        return view('admin.Student.index', compact('students'));
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
            $request->all(),
            [
                'name'           => 'required|string|min:3',
                'national_id'    => 'required|string|unique:students,national_id,',
                'date_of_birth'  => 'required|date',
                'gender'         => 'required|in:male,female',
                'phone'          => 'required|string|unique:students,phone,',
                'email'          => 'required|email|unique:students,email,',
                'password'       => 'nullable|string|min:8',
                'status'         => 'required|in:active,inactive,on_leave',
                'address'        => 'nullable|string',
                'image_path'     => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'notes'          => 'nullable|string',
                'subject_ids'    => 'required|array|min:1',
                'teachers_for_subject' => 'required|array',
                'classroom_id'   => 'required|exists:classrooms,id',
            ],
            [
                'name.required'          => 'يرجى إدخال اسم الطالب.',
                'name.min'               => 'اسم الطالب يجب أن يكون على الأقل 3 أحرف.',

                'national_id.required'   => 'يرجى إدخال رقم هوية الطالب.',
                'national_id.unique'     => 'رقم الهوية مستخدم مسبقًا.',

                'date_of_birth.required' => 'يرجى إدخال تاريخ الميلاد.',
                'date_of_birth.date'     => 'صيغة تاريخ الميلاد غير صحيحة.',

                'gender.required'        => 'يرجى اختيار الجنس.',
                'gender.in'              => 'القيمة المدخلة للجنس غير صالحة.',

                'phone.required'         => 'يرجى إدخال رقم الهاتف.',
                'phone.unique'           => 'رقم الهاتف مستخدم مسبقًا.',

                'email.required'         => 'يرجى إدخال البريد الإلكتروني.',
                'email.email'            => 'صيغة البريد الإلكتروني غير صحيحة.',
                'email.unique'           => 'البريد الإلكتروني مستخدم مسبقًا.',

                'password.min'           => 'كلمة المرور يجب أن تكون على الأقل 8 أحرف.',

                'status.required'        => 'يرجى اختيار الحالة.',
                'status.in'              => 'الحالة المدخلة غير صالحة.',

                'classroom_id.required'  => 'يرجى اختيار الصف الدراسي.',
                'classroom_id.exists'    => 'الصف الدراسي المحدد غير موجود.',

                'subject_ids.required'   => 'يرجى اختيار مادة واحدة على الأقل.',
                'subject_ids.array'      => 'صيغة المواد غير صحيحة.',
                'subject_ids.min'        => 'يجب اختيار مادة واحدة على الأقل.',

                'teachers_for_subject.required' => 'يرجى تحديد المعلمين للمواد.',

                'image_path.image'       => 'الملف يجب أن يكون صورة.',
                'image_path.mimes'       => 'الصورة يجب أن تكون بصيغة: jpg, jpeg, png.',
                'image_path.max'         => 'حجم الصورة لا يجب أن يتجاوز 2 ميغابايت.',
            ]
        );

    

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'create',
                    'id_model' => 'addStudentModal',
                ]);
        }
        $data = $validator->validated();

        // جمع أزواج (subject_id, teacher_id) المختارة
        $pairs = [];
        foreach ($data['subject_ids'] as $subjectId) {
            $teacherId = $data['teachers_for_subject'][$subjectId] ?? null;
            if (!$teacherId) {
                return back()->withErrors(['teachers_for_subject' => "يرجى اختيار مدرس للمادة $subjectId"])->withInput();
            }
            $pairs[] = ['subject_id' => $subjectId, 'teacher_id' => $teacherId];
        }

        $classroomId = $data['classroom_id'];

        // جلب كل الشعب ضمن الصف مع علاقات sectionSubjectTeachers
        $allSections = Section::where('classroom_id', $classroomId)->with('sectionSubjectTeachers')->get();

        $uncoveredPairs = $pairs;
        $selectedSections = [];

        // خوارزمية Greedy لاختيار أقل عدد شعب لتغطية كل الأزواج
        while (!empty($uncoveredPairs)) {
            $bestSection = null;
            $bestCoverage = [];

            foreach ($allSections as $section) {
                // الأزواج التي تغطيها هذه الشعبة من الأزواج غير المغطاة بعد
                $coverage = [];

                foreach ($section->sectionSubjectTeachers as $sst) {
                    foreach ($uncoveredPairs as $index => $pair) {
                        if ($sst->subject_id == $pair['subject_id'] && $sst->teacher_id == $pair['teacher_id']) {
                            $coverage[$index] = $pair;
                        }
                    }
                }

                if (count($coverage) > count($bestCoverage)) {
                    $bestCoverage = $coverage;
                    $bestSection = $section;
                }
            }

            if (!$bestSection) {
                return back()->withErrors(['section' => 'لا توجد شعب كافية لتغطية جميع المواد والمعلمين المختارين.'])->withInput();
            }

            $selectedSections[$bestSection->id] = $bestSection;

            // إزالة الأزواج المغطاة من الأزواج غير المغطاة
            foreach ($bestCoverage as $index => $pair) {
                unset($uncoveredPairs[$index]);
            }
        }

        // تحقق من السعة لكل شعبة مختارة
        foreach ($selectedSections as $section) {
            $studentCount = $section->students()->pluck('students.id')->unique()->count();
            if ($studentCount >= $section->maxvalue) {
                return back()->withErrors(['section' => "الشعبة {$studentCount} ممتلئة، يرجى توسيعها أو إضافة  شعبة جديدة."])->withInput();
            }
        }

        // رفع الصورة إن وجدت
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            if ($request->file('image_path')->isValid()) {
                $imagePath = $request->file('image_path')->store('image', 'public');
                // اختياري للتأكد من النتيجة
            } else {
                $imagePath = 'image/default.png';
            }
        } else {
            $imagePath = 'image/default.png';
        }
        // إنشاء المستخدم
        $studentRole = Role::where('name', 'student')->first();
        if (!$studentRole) {
            return back()->with('error', 'لم يتم العثور على دور الطالب.');
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role_id = $studentRole->id;
        $user->save();

        // إنشاء الطالب بدون ربط شعبة في جدول الطلاب (لأن الربط دقيق في الوسيط)
        $student = Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'],
            'address' => $data['address'] ?? null,
            'gender' => $data['gender'],
            'national_id' => $data['national_id'],
            'image_path' => $imagePath,
            'notes' => $data['notes'] ?? null,
            'status' => $data['status'],
            'user_id' => $user->id,
            'classroom_id' => $classroomId,
            // لا تحدد section_id هنا
        ]);

        // ربط الطالب بالمواد والمعلمين والشعب المختارة
        foreach ($selectedSections as $section) {
            foreach ($pairs as $pair) {
                // تحقق إذا الشعبة تغطي الزوج (subject+teacher)
                $exists = SectionSubjectTeacher::where('section_id', $section->id)
                    ->where('subject_id', $pair['subject_id'])
                    ->where('teacher_id', $pair['teacher_id'])
                    ->exists();

                if ($exists) {
                    DB::table('student_section_subject_teacher')->insert([
                        'student_id' => $student->id,
                        'section_id' => $section->id,
                        'subject_id' => $pair['subject_id'],
                        'teacher_id' => $pair['teacher_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }



        
$duplicates = DB::table('student_section_subject_teacher')
    ->select('student_id', 'subject_id', 'teacher_id', DB::raw('COUNT(*) as count'))
    ->groupBy('student_id', 'subject_id', 'teacher_id')
    ->having('count', '>', 1)
    ->get();

foreach ($duplicates as $dup) {
    // جلب جميع السجلات المتكررة لهذا الطالب + المادة + المدرس
    $records = DB::table('student_section_subject_teacher')
        ->where('student_id', $dup->student_id)
        ->where('subject_id', $dup->subject_id)
        ->where('teacher_id', $dup->teacher_id)
        ->orderBy('id') // ترتيب حسب ID
        ->get();

    // ابقي على أول سجل واحذف الباقي
    $keepId = $records->first()->id;
    $idsToDelete = $records->pluck('id')->filter(fn($id) => $id != $keepId);

    if ($idsToDelete->isNotEmpty()) {
        DB::table('student_section_subject_teacher')->whereIn('id', $idsToDelete)->delete();
    }
}

        return redirect()->route('student.index')->with('success', 'تم إضافة الطالب وربطه بالشعب والمواد والمعلمين بنجاح.');
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
        $student= Student::where('id', $id)->first();
        if (!$student) {
            return redirect()->route('student.index')->with('error', 'الطالب غير موجود.');
        }   
        return response()->json(
            $student
               
           
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'          => 'required|string|min:3',
                'national_id'   => 'required|string|unique:students,national_id,' . $id,
                'date_of_birth' => 'required|date',
                'gender'        => 'required|in:male,female',
                'phone'         => 'required|string|unique:students,phone,' . $id,
                'email'         => 'required|email|unique:students,email,' . $id,
                'status'        => 'required|in:active,inactive,on_leave',
                'address'       => 'nullable|string',
                'image_path'    => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
                'password'      => 'nullable|string|min:6',
                'note'          => 'nullable|string',
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with([
                    'form_type' => 'edit',
                    'id_model'  => 'editStudentModal',
                    'id'        => $id,
                    'url'       => 'student.update',
                ]);
        }

        $student = Student::findOrFail($id);
        $user = User::findOrFail($student->user_id);

        $data = $validator->validated();

        // تحديث بيانات المستخدم
        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        // معالجة الصورة
        if ($request->hasFile('image_path')) {
            if ($student->image_path && Storage::disk('public')->exists($student->image_path) && $student->image_path != 'image/default.png') {
                Storage::disk('public')->delete($student->image_path);
            }
            $newImagePath = $request->file('image_path')->store('image', 'public');
            $data['image_path'] = $newImagePath;
        } else {
            // إذا لم يتم رفع صورة جديدة، نحتفظ بالقديمة
            $data['image_path'] = $student->image_path;
        }

        // تحديث بيانات الطالب
        $student->update($data);

        return redirect()->route('student.index')->with('info', 'تم تعديل البيانات بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // إيجاد السجل
        $student = Student::findOrFail($id);
        $user = User::findOrFail($student->user_id);

        // حذف الصورة من التخزين إذا كانت موجودة وليست الصورة الافتراضية
        $defaultImage = 'image/default.png'; // ضع هنا اسم الصورة الافتراضية الفعلي
        if ($student->image_path && $student->image_path !== $defaultImage && Storage::disk('public')->exists($student->image_path)) {
            Storage::disk('public')->delete($student->image_path);
        }

        // حذف السجل من قاعدة البيانات
        $student->delete();
        $user->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('student.index')->with('success', 'تم حذف الطالب بنجاح.');
    }
    public function getSubjectsForClassroom($id)
    {
        $subjects = DB::table('section_subject_teacher')
            ->join('sections', 'sections.id', '=', 'section_subject_teacher.section_id')
            ->join('subjects', 'subjects.id', '=', 'section_subject_teacher.subject_id')
            ->where('sections.classroom_id', $id)
            ->select('subjects.id', 'subjects.name')
            ->distinct()
            ->get();

        return response()->json($subjects);
    }
    function get_subject_in_classroom($id)
    {
        // جلب الطالب مع المواد والشعب
        $student = Student::where('id', $id)->with('sectionSubjectTeachers.section')->first();

        if (!$student) {
            return response()->json([], 404); // لو الطالب مش موجود رجع فارغ مع كود 404
        }

        // جلب الصفوف (الشعب) التي يدرس فيها الطالب
        $sections = $student->sectionSubjectTeachers
            ->pluck('section_id')
            ->unique()
            ->values()
            ->map(function ($sectionId) {
                return Section::find($sectionId);
            });

        if ($sections->isEmpty()) {
            return response()->json([], 200);
        }

        // افترضنا الصف الأول من الشعب فقط
        $classroomId = $sections[0]->classroom_id;

        // جلب كل المواد المرتبطة بهذا الصف (الشعبة)
        $allSubjects = DB::table('section_subject_teacher')
            ->join('sections', 'sections.id', '=', 'section_subject_teacher.section_id')
            ->join('subjects', 'subjects.id', '=', 'section_subject_teacher.subject_id')
            ->where('sections.classroom_id', $classroomId)
            ->select('subjects.id', 'subjects.name')
            ->distinct()
            ->get();

        // جلب المواد المسجلة للطالب
        $studentSubjectIds = $student->sectionSubjectTeachers
            ->pluck('subject_id')
            ->unique();

        // تصفية المواد: إرجاع المواد التي **ليست** ضمن مواد الطالب
        $subjectsNotAssigned = $allSubjects->filter(function ($subject) use ($studentSubjectIds) {
            return !$studentSubjectIds->contains($subject->id);
        })->values();

        return response()->json(['subject'=> $subjectsNotAssigned, 'classroom_id' => $classroomId]);
    }

    public function getTeachersForSubject($subjectId, $classroomId)
    {
        $data = DB::table('section_subject_teacher')
            ->join('sections', 'sections.id', '=', 'section_subject_teacher.section_id')
            ->join('subjects', 'subjects.id', '=', 'section_subject_teacher.subject_id')
            ->join('teachers', 'teachers.id', '=', 'section_subject_teacher.teacher_id')
            ->join('teacher_subject', function ($join) {
                $join->on('teacher_subject.teacher_id', '=', 'section_subject_teacher.teacher_id')
                    ->on('teacher_subject.subject_id', '=', 'section_subject_teacher.subject_id');
            })
            ->where('sections.classroom_id', $classroomId)
            ->where('section_subject_teacher.subject_id', $subjectId) // 🔹 الشرط الجديد
            ->select(
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                'subjects.note',
                'teachers.id as teacher_id',
                'teachers.full_name as teacher_name',
                'teacher_subject.price'
            )
            ->get();

        // تنسيق البيانات
        $subject = null;
        $teachers = [];

        foreach ($data as $row) {
            if (!$subject) {
                $subject = [
                    'id' => $row->subject_id,
                    'name' => $row->subject_name,
                    'note' => $row->note ?? '',
                    'teachers' => []
                ];
            }

            // تجنب التكرار
            if (!in_array($row->teacher_id, array_column($teachers, 'id'))) {
                $teachers[] = [
                    'id' => $row->teacher_id,
                    'name' => $row->teacher_name,
                    'price' => (float) $row->price
                ];
            }
        }

        if ($subject) {
            $subject['teachers'] = $teachers;
        }

        return response()->json($subject);
    }




    public function addSubjectsToStudent(int $studentId, Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'subject_ids' => 'required|array|min:1',
                'teachers_for_subject' => 'required|array',
                'classroom_id' => 'required|exists:classrooms,id',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // بناء أزواج (subject_id, teacher_id)
        $pairs = [];
        foreach ($data['subject_ids'] as $subjectId) {
            $teacherId = $data['teachers_for_subject'][$subjectId] ?? null;
            if (!$teacherId) {
                return back()->withErrors(['teachers_for_subject' => "يرجى اختيار مدرس للمادة $subjectId"])->withInput();
            }
            $pairs[] = ['subject_id' => $subjectId, 'teacher_id' => $teacherId];
        }

        $classroomId = $data['classroom_id'];

        // جلب الشعب ضمن الصف مع العلاقات
        $allSections = Section::where('classroom_id', $classroomId)->with('sectionSubjectTeachers')->get();

        $uncoveredPairs = $pairs;
        $selectedSections = [];

        // خوارزمية اختيار أقل عدد شعب
        while (!empty($uncoveredPairs)) {
            $bestSection = null;
            $bestCoverage = [];

            foreach ($allSections as $section) {
                $coverage = [];

                foreach ($section->sectionSubjectTeachers as $sst) {
                    foreach ($uncoveredPairs as $index => $pair) {
                        if ($sst->subject_id == $pair['subject_id'] && $sst->teacher_id == $pair['teacher_id']) {
                            $coverage[$index] = $pair;
                        }
                    }
                }

                if (count($coverage) > count($bestCoverage)) {
                    $bestCoverage = $coverage;
                    $bestSection = $section;
                }
            }

            if (!$bestSection) {
                return back()->withErrors(['section' => 'لا توجد شعب كافية لتغطية جميع المواد والمعلمين المختارين.'])->withInput();
            }

            $selectedSections[$bestSection->id] = $bestSection;

            foreach ($bestCoverage as $index => $pair) {
                unset($uncoveredPairs[$index]);
            }
        }

        // تحقق السعة لكل شعبة
        foreach ($selectedSections as $section) {
            if ($section->students()->count() >= $section->maxvalue) {
                return back()->withErrors(['section' => "الشعبة {$section->name} ممتلئة، يرجى توسيعها أو إضافة شعبة جديدة."])->withInput();
            }
        }

        // 1. جلب الروابط القديمة للطالب
        $oldLinks = DB::table('student_section_subject_teacher')
            ->where('student_id', $studentId)
            ->get(['section_id', 'subject_id', 'teacher_id'])
            ->toArray();

        $oldLinksStrings = array_map(function ($item) {
            return $item->section_id . '-' . $item->subject_id . '-' . $item->teacher_id;
        }, $oldLinks);

        // 2. إضافة الروابط الجديدة فقط إذا غير موجودة مسبقًا
        foreach ($selectedSections as $section) {
            foreach ($pairs as $pair) {
                $key = $section->id . '-' . $pair['subject_id'] . '-' . $pair['teacher_id'];

                if (in_array($key, $oldLinksStrings)) {
                    // موجود مسبقًا، تجاهل الإضافة
                    continue;
                }

                $exists = SectionSubjectTeacher::where('section_id', $section->id)
                    ->where('subject_id', $pair['subject_id'])
                    ->where('teacher_id', $pair['teacher_id'])
                    ->exists();

                if ($exists) {
                    DB::table('student_section_subject_teacher')->insert([
                        'student_id' => $studentId,
                        'section_id' => $section->id,
                        'subject_id' => $pair['subject_id'],
                        'teacher_id' => $pair['teacher_id'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // إزالة التكرارات إن وجدت (اختياري)
        $duplicates = DB::table('student_section_subject_teacher')
            ->select('student_id', 'subject_id', 'teacher_id', DB::raw('COUNT(*) as count'))
            ->groupBy('student_id', 'subject_id', 'teacher_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $dup) {
            $records = DB::table('student_section_subject_teacher')
                ->where('student_id', $dup->student_id)
                ->where('subject_id', $dup->subject_id)
                ->where('teacher_id', $dup->teacher_id)
                ->orderBy('id')
                ->get();

            $keepId = $records->first()->id;
            $idsToDelete = $records->pluck('id')->filter(fn($id) => $id != $keepId);

            if ($idsToDelete->isNotEmpty()) {
                DB::table('student_section_subject_teacher')->whereIn('id', $idsToDelete)->delete();
            }
        }

        return redirect()->back()->with('success', 'تم ربط الطالب بالشعب والمواد والمعلمين بنجاح.');
    }



    public function getstudent_by_section_andsubject_and_teacher(Request $request){
           $sectionId=$request->section_id;
        $teacherId=$request->teacher_id;
        $subjectId= $request->subject_id;
        $students = DB::table('student_section_subject_teacher as sst')
            ->join('students as s', 'sst.student_id', '=', 's.id')
            ->where('sst.section_id', $sectionId)
            ->where('sst.teacher_id', $teacherId)
            ->where('sst.subject_id', $subjectId)
            ->select('s.*')
            ->get();
        return response()->json($students);
    }
}
