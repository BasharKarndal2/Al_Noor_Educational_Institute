<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pearant;
use App\Models\Role;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\Student;
use App\Models\Student_Requeast;
use App\Models\Teacher;
use App\Models\Teachers_Request;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ReaquestControolers extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.Requests.index');
    }


    public function store(Request $request)
    {

        // dd($request->all());
        $fullData = json_decode($request->input('full_data'), true);

        $adminRole = Role::where('name', 'parent')->first();
        if (!$adminRole) {
            return redirect()->back()->with([
                'error' => 'لم يتم العثور على دور المسؤول (admin).',
            ]);
        }

        $user = new User();
        $user->name =  $fullData['parent']['namep'];
        $user->email = $fullData['parent']['email_pe'];
        $user->password = Hash::make('fsdjfksjdflkjhd');
        $user->role_id = $adminRole->id;
        $user->save();
        // حفظ ولي الأمر
        $parent = Pearant::create([
            'name' => $fullData['parent']['namep'],
            'phone' => $fullData['parent']['phonp'],
            'email' => $fullData['parent']['email_pe'],
            'relation' => $fullData['parent']['relation'],
            'address' => $fullData['student']['loc'] ?? null,
            'national_id'=> $fullData['student']['national_id'],
            'user_id'=> $user->id
        ]);

        $imagePath = null;

        if ($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
            // رفع الصورة الجديدة
            $imagePath = $request->file('image_path')->store('image', 'public');
        } else {
            // استخدام صورة افتراضية
            $imagePath = 'image/default.png'; // تأكد أن هذه الصورة موجودة في public/images
        }
        // حفظ الطالب وربطه بولي الأمر
        $student = Student_Requeast::create([
            'name' => $fullData['student']['fullname_sudent'],
            'email' => $fullData['student']['emailp'],
            'phone' => $fullData['student']['phon'],
            'date_of_birth' => $fullData['student']['birthDate'],
            'address' => $fullData['student']['loc'],
            'gender' => $fullData['student']['gender'],
            'national_id' => $fullData['student']['national_id'],
            'notes' => $fullData['academic']['notes'] ?? null,
            'password' => bcrypt($fullData['student']['password']),
            'classroom_id' => $fullData['academic']['classroom_id'],
            'parent_id' => $parent->id,
            'image_path'=>  $imagePath
        ]);

        // حفظ المواد والروابط مع المعلم
        foreach ($fullData['subjects'] as $subject) {
            // لازم تجيب سعر المادة من جدول teacher_subject
            $price = DB::table('teacher_subject')
                ->where('teacher_id', $subject['teacher_id'])
                ->where('subject_id', $subject['subject_id'])
                ->value('price');

            DB::table('student_subject')->insert([
                'student_id' => $student->id,
                'subject_id' => $subject['subject_id'],
                'teacher_id' => $subject['teacher_id'],
                'price' => $price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // حفظ الاجابات لكل مادة
            foreach ($subject['answers'] as $question_key => $selected_option) {
                // question_key مثال: "1_q19" --> نحتاج رقم السؤال (19) فقط
                preg_match('/q(\d+)/', $question_key, $matches);
                $question_id = $matches[1] ?? null;
                if (!$question_id) continue;

                // جلب الإجابة الصحيحة للسؤال من جدول الأسئلة
                $correct_option = DB::table('questions')->where('id', $question_id)->value('correct_option');

                DB::table('student_answers')->insert([
                    'student__requast_id' => $student->id,
                    'question_id' => $question_id,
                    'selected_option' => substr($selected_option, -1), // مثلا "option_b" --> "b"
                    'is_correct' => (substr($selected_option, -1) == $correct_option),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('request.index')->with('request_success', 'تم التسجيل بنجاح');
    }


    public function show_data()
    {
        $teacherres = Teachers_Request::all();
        // جلب الطلاب مع المواد والإجابات مع الأسئلة
        $students = Student_Requeast::with(['subjects', 'studentAnswers.question.sectionSubject.subject'])->get();

        // جلب كل المعلمين مرة واحدة لتجنب N+1 problem
        $teacherIds = $students->flatMap(function ($student) {
            return $student->subjects->pluck('pivot.teacher_id');
        })->unique();

        $teachers = Teacher::whereIn('id', $teacherIds)->get()->keyBy('id');

        foreach ($students as $student) {
            // بناء JSON للمواد مع المعلمين والأسعار
            $student->subjects_json = $student->subjects->map(function ($subject) use ($teachers) {
                $teacher = $teachers->get($subject->pivot->teacher_id);
                return [
                    'name' => $subject->name,
                    'teacher' => $teacher ? $teacher->full_name : 'لا يوجد معلم',
                    'price' => $subject->pivot->price,
                ];
            })->toJson();

            // بناء JSON للإجابات بحسب المادة لكل طالب
            $answersBySubject = [];

            foreach ($student->studentAnswers as $answer) {
                $question = $answer->question;
                if (!$question) continue;
                $student->total_price = $student->subjects->sum(function ($subject) {
                    return $subject->pivot->price;
                });
                // جلب اسم المادة
                $subjectName = $question->sectionSubject->subject->name ?? 'غير معروف';

                if (!isset($answersBySubject[$subjectName])) {
                    $answersBySubject[$subjectName] = [];
                }

                $answersBySubject[$subjectName][] = [
                    'subject' => $subjectName,
                    'question' => $question->question_text,
                    'answer' => $question->{'option_' . $answer->selected_option} ?? '',
                    'correct' => (bool) $answer->is_correct,
                ];
            }

            $student->test_results_json = json_encode($answersBySubject, JSON_UNESCAPED_UNICODE);
        }

        // عرض البيانات للمعاينة
        // dd($students);
        $pending_student = Student_Requeast::where("request_status", 'pending');
        $pending_teachers=Teachers_Request::where("request_status", 'pending');
        return view('admin.Requests.show', compact('students', 'pending_student' , 'teacherres', 'pending_teachers'));
    }

    public function register()
    {



        return view('admin.Requests.teacher.index');
    }

    public function accept($id)
    {
        // جلب الطلب مع العلاقات
        $requestStudent = Student_Requeast::with(['subjects', 'classroom', 'teachers'])
            ->findOrFail($id);
        if ($requestStudent->request_status == 'accepted') {

            return redirect()->route('request.show_all_data')->with('danger', 'هذا الطلب تم قبوله من قبل');
        }

        // تجهيز البيانات
        $data = [
            'name' => $requestStudent->name,
            'national_id' => $requestStudent->national_id,
            'date_of_birth' => $requestStudent->date_of_birth,
            'gender' => $requestStudent->gender,
            'phone' => $requestStudent->phone,
            'email' => $requestStudent->email,
            'password' => $requestStudent->password, // إذا كان مشفر مسبقاً
            'status' => $requestStudent->status,
            'address' => $requestStudent->address,
            'image_path' => $requestStudent->image_path,
            'notes' => $requestStudent->notes,
            'classroom_id' => $requestStudent->classroom_id,
            'image_path'=> $requestStudent->image_path,
            'parent_id'=> $requestStudent->parent_id
        ];

        // جمع أزواج subject_id و teacher_id
        $pairs = [];
        foreach ($requestStudent->subjects as $subject) {
            $pairs[] = [
                'subject_id' => $subject->id,
                'teacher_id' => $subject->pivot->teacher_id
            ];
        }

        // جلب كل الشعب للصف
        $allSections = Section::where('classroom_id', $data['classroom_id'])
            ->with('sectionSubjectTeachers')
            ->get();

        $uncoveredPairs = $pairs;
        $selectedSections = [];

        // اختيار أقل عدد شعب لتغطية المواد والمعلمين
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
                return back()->withErrors(['section' => 'لا توجد شعب كافية لتغطية جميع المواد والمعلمين المختارين.']);
            }

            $selectedSections[$bestSection->id] = $bestSection;
            foreach ($bestCoverage as $index => $pair) {
                unset($uncoveredPairs[$index]);
            }
        }

        // تحقق من السعة
        foreach ($selectedSections as $section) {
            $studentCount = $section->students()->pluck('students.id')->unique()->count();
            if ($studentCount >= $section->maxvalue) {
                return back()->withErrors(['section' => "الشعبة {$section->name} ممتلئة."]);
            }
        }

        // إنشاء مستخدم
        $studentRole = Role::where('name', 'student')->first();
        if (!$studentRole) {
            return back()->with('error', 'لم يتم العثور على دور الطالب.');
        }

    $pernt=Pearant::where('id', $data['parent_id'])->first();
   
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role_id = $studentRole->id;
        $user->save();
//  dd($data['parent_id']);
        // إنشاء الطالب
        $student = Student::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'date_of_birth' => $data['date_of_birth'],
            'address' => $data['address'],
            'gender' => $data['gender'],
            'national_id' => $data['national_id'],
            'image_path' => $data['image_path'],
            'notes' => $data['notes'],
            'status' => $data['status'],
            'user_id' => $user->id,
           
            'image_path'=>$data['image_path'],
            'parent_id'=> $pernt->id,
        ]);


   

        // ربط الطالب بالشعب
        foreach ($selectedSections as $section) {
            foreach ($pairs as $pair) {
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

        // تحديث حالة الطلب
        $requestStudent->request_status = 'accepted';
        $requestStudent->save();

        return redirect()->route('request.show_all_data')->with('success', 'تم قبول الطالب وإضافته بنجاح.');
    }


    /**
     * Get subjects and teachers for a specific classroom.
     */
    public function getSubjectsandTeachersForClassroom($id)
    {



        $data = DB::table('section_subject_teacher')
            ->join('sections', 'sections.id', '=', 'section_subject_teacher.section_id')
            ->join('subjects', 'subjects.id', '=', 'section_subject_teacher.subject_id')
            ->join('teachers', 'teachers.id', '=', 'section_subject_teacher.teacher_id')
            ->join('teacher_subject', function ($join) {
                $join->on('teacher_subject.teacher_id', '=', 'section_subject_teacher.teacher_id')
                    ->on('teacher_subject.subject_id', '=', 'section_subject_teacher.subject_id');
            })
            ->where('sections.classroom_id', $id)
            ->select(
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                'subjects.note',
                'teachers.id as teacher_id',
                'teachers.full_name as teacher_name',
                'teacher_subject.price'
            )
            ->get();

        // تجميع البيانات بالشكل المطلوب
        $subjects = [];
        foreach ($data as $row) {
            if (!isset($subjects[$row->subject_id])) {
                $subjects[$row->subject_id] = [
                    'id' => $row->subject_id,
                    'name' => $row->subject_name,
                    'note' => $row->note ?? '',
                    'teachers' => []
                ];
            }

            // التأكد أن المعلم غير مكرر في نفس المادة
            $teacherExists = false;
            foreach ($subjects[$row->subject_id]['teachers'] as $t) {
                if ($t['id'] == $row->teacher_id) {
                    $teacherExists = true;
                    break;
                }
            }

            if (!$teacherExists) {
                $subjects[$row->subject_id]['teachers'][] = [
                    'id' => $row->teacher_id,
                    'name' => $row->teacher_name,
                    'price' => (float) $row->price
                ];
            }
        }

        return response()->json(array_values($subjects));
    }

    public function stor_teacher(Request $request)
    {




        $validated = $request->validate([
            'full_name' => 'required'

        ]);

        // معالجة رفع الصورة
        $imagePath = null;

        if ($request->hasFile('photo_path')) {
            if ($request->file('photo_path')->isValid()) {
                $imagePath = $request->file('photo_path')->store('image', 'public');
                // dd($imagePath); // اختياري للتأكد من النتيجة
            } else {
                $imagePath = 'image/default.png';
            }
        } else {
            $imagePath = 'image/default.png';
        }

        // تخزين البيانات
        $teacher = Teachers_Request::create([
            'full_name' => $validated['full_name'],
            'identity_number' => $request->identity_number,
            'birth_date' => $request->birth_date,
            'teacherGender' => $request->teacherGender,

            'education' => $request->education,
            'specialization' => $request->specialization,
            'experience_years' => $request->experience_years,
            'previous_work' => $request->previous_work,
            'salary_syp' => $request->salary_syp,
            'salary_usd' => $request->salary_usd,
            'email' => $request->email,
            'password' => isset($request->password) ? Hash::make($request->password) : null,
            'phone' => $request->phone,
            'address' => $request->address,
            'photo_path' => $imagePath,
        ]);
        $teacher->save();

        return redirect()->route('teacher.register')->with('request_success', 'تم إرسال طلب التسجيل بنجاح!');
    }



    public function accept_teacher($id){

        $requestTeacher = Teachers_Request::findOrFail($id);



        if($requestTeacher->request_status== 'accepted'){

            return redirect()->route('request.show_all_data')->with('danger', 'هذا الطلب تم قبوله من قبل');
        }

       

        $data = [
            'full_name'       => $requestTeacher->full_name,
            'national_id'     => $requestTeacher->identity_number, // تعديل الاسم ليتطابق مع الجدول
            'birth_date'      => $requestTeacher->birth_date,
            'gender'          => $requestTeacher->gender,
            'phone'           => $requestTeacher->phone,
            'email'           => $requestTeacher->email,
            'password'        => $requestTeacher->password, // إذا كان مشفر مسبقاً
            'status'          => $requestTeacher->status,
            'address'         => $requestTeacher->address,
            'previous_work'   => $requestTeacher->previous_work,
            'specialization'  => $requestTeacher->specialization,
            'experience'=> $requestTeacher->experience_years,
            'marital'=> $requestTeacher->marital_status,
            'image_path'      => $requestTeacher->photo_path,
            'hire_date'       => now(), // تاريخ التعيين الحالي
            // تأكد من إدخاله إذا كان لديك علاقة مع جدول users
        ];

      
        $adminRole = Role::where('name', 'teacher')->first();
        if (!$adminRole) {
            return redirect()->back()->with([
                'error' => 'لم يتم العثور على دور المسؤول (admin).',
            ]);
        }

        $user = new User();
        $user->name = $data['full_name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->role_id = $adminRole->id;
        $user->save();




        // إنشاء المعلم
        Teacher::create([
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
            'image_path'    => $data['image_path'],
            'user_id'       => $user->id,
            'notes'         =>  null,
        ]);

        // تجهيز البيانات

        $requestTeacher->request_status = 'accepted';
        $requestTeacher->save();
        return redirect()->route('request.show_all_data')->with('success', 'تم قبول الطالب وإضافته بنجاح.');
    }

}
