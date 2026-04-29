<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pearant;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PearantControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pearants=Pearant::all();
        return view('admin.Pearant.index',compact('pearants'));
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
        // التحقق من البيانات
        // $request->validate([
        //     'name' => 'required|string|min:6',
        //     'national_id' => 'required|string|unique:pearants,national_id|min:8|max:14',
        //     'date_of_birth' => 'required|date|before:today',
        //     'gender' => 'required|in:male,female',
        //     'relation' => 'required|string',
        //     'phone' => 'required|string|min:10',
        //     'email' => 'required|email|unique:pearants,email',
        //     'password' => 'required|string|min:8',
        //     'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        //     'address' => 'nullable|string',
        //     'status' => 'required|in:active,inactive'
        // ]);




        $adminRole = Role::where('name', 'parent')->first();
        if (!$adminRole) {
            return redirect()->back()->with([
                'error' => 'لم يتم العثور على دور المسؤول (admin).',
            ]);
        }

        // رفع الصورة إن وجدت
        $imagePath = null;


        if ($request->hasFile('image_path') && $request->file('image_path')->isValid()) {
            // رفع الصورة الجديدة
            $imagePath = $request->file('image_path')->store('image', 'public');
        } else {
            // استخدام صورة افتراضية
            $imagePath = 'image/default.png'; // تأكد أن هذه الصورة موجودة في public/images
        }
        $user = new User();
        $user->name = $request->name;
        $user->email =$request-> email;
        $user->password = Hash::make($request->password);
        $user->role_id = $adminRole->id;
        $user->save();

        // إنشاء ولي الأمر
        $pearant = Pearant::create([
            'name' => $request->name,
            'national_id' => $request->national_id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'relation' => $request->relation,
            'phone' => $request->phone,
            'email' => $request->email,
           
            'image_path' => $imagePath,
            'address' => $request->address,
            'status' => $request->status,
            'user_id'=>$user->id
        ]);


        return redirect()->back()->with('success', 'تم إضافة ولي الأمر بنجاح!');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pearant = Pearant::findOrFail($id);
        return response()->json($pearant);
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
    public function update(Request $request)
    {
        $pearant = Pearant::findOrFail($request->id);
        $user = $pearant->user;

        // التحقق من البيانات (يمكنك تفعيلها)
        // $request->validate([...]);

        // تحديث بيانات المستخدم
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // إذا تم رفع صورة جديدة
        if ($request->hasFile('image_path') && $request->file('image_path')->isValid()) {

            // حذف الصورة القديمة إذا لم تكن الصورة الافتراضية
            if ($pearant->image_path && $pearant->image_path !== 'image/default.png') {
                Storage::disk('public')->delete($pearant->image_path);
            }

            // رفع الصورة الجديدة
            $imagePath = $request->file('image_path')->store('image', 'public');
        } else {
            // الإبقاء على الصورة القديمة
            $imagePath = $pearant->image_path ?? 'image/default.png';
        }

        // تحديث بيانات ولي الأمر
        $pearant->update([
            'name' => $request->name,
            'national_id' => $request->national_id,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'relation' => $request->relation,
            'phone' => $request->phone,
            'email' => $request->email,
            'image_path' => $imagePath,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('info', 'تم تحديث بيانات ولي الأمر بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // البحث عن الأب
        $parent = Pearant::findOrFail($id);
        $user = User::findOrFail($parent->user_id);

        // التحقق إذا عنده أبناء
        if ($parent->students()->count() > 0) {
            // تحديث الأبناء بحيث parent_id = null
            $parent->students()->update(['parent_id' => null]);
        }

        // حذف الصورة من التخزين إذا موجودة وليست الافتراضية
        $defaultImage = 'image/default.png'; // ضع اسم الصورة الافتراضية الفعلي
        if ($parent->image_path && $parent->image_path !== $defaultImage && Storage::disk('public')->exists($parent->image_path)) {
            Storage::disk('public')->delete($parent->image_path);
        }

        // حذف السجل وحساب المستخدم المرتبط
        $parent->delete();
        $user->delete();

        return redirect()->back()->with('danger', 'تم حذف بيانات ولي الأمر وحسابه بنجاح!');
    }
    public function showchild($id){

        $parent = Pearant::findOrFail($id);
        $students = Student::with('sectionSubjectTeachers.subject', 'sectionSubjectTeachers.teacher', 'sectionSubjectTeachers.section')->get();

        return view('admin.Pearant.join', compact('students', 'parent'));
   
    }

    // تخزين الربط بين الطالب والأب
    public function storeLink(Request $request, Student $student)
    {
        // تحقق أن parent_id موجود
        // $request->validate([
        //     'parent_id' => 'required|exists:parentts,id',
        // ]);

        $student->parent_id = $request->parent_id;
        $student->save();
        return redirect()->route('pearant.index')->with('success', "تم ربط الطالب {$student->name} مع الأب بنجاح.");

     
    }

 
}
