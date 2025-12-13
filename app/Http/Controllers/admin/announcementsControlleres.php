<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class announcementsControlleres extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::latest()->get();
            // dd($announcements);
        return view('admin.announcements.index', compact('announcements'));

       
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

      
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'titel' => 'required|string|min:5',
            'discridtion' => 'required|string|min:5',
        
        ]);


        // رفع الصورة
        $imagePath = null;
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('announcements', 'public');
       
        }

        // حفظ البيانات
        $announcement = Announcement::create([
            'titel' => $validated['titel'],
            'discridtion' => $validated['discridtion'],
            'image_path' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة الإعلان بنجاح!');
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
    public function update(Request $request, $id)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'titel' => 'required|string|min:5',
            'discridtion' => 'required|string|min:5',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // جلب الإعلان المطلوب تعديله
        $announcement = Announcement::findOrFail($id);

        // تحديث البيانات
        $announcement->titel = $validated['titel'];
        $announcement->discridtion = $validated['discridtion'];

        // إذا رفع صورة جديدة
        if ($request->hasFile('image_path')) {
            // حذف الصورة القديمة (اختياري)
            if ($announcement->image_path && Storage::disk('public')->exists($announcement->image_path)) {
                Storage::disk('public')->delete($announcement->image_path);
            }

            // حفظ الصورة الجديدة
            $path = $request->file('image_path')->store('announcements', 'public');
            $announcement->image_path = $path;
        }

        $announcement->save();

        return redirect()->back()->with('success', 'تم تحديث الإعلان بنجاح!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // إيجاد السجل
        $Announcement = Announcement::findOrFail($id);
       
        // حذف الصورة من التخزين (إذا موجودة)
        if ($Announcement->image_path && Storage::disk('public')->exists($Announcement->image_path)) {
            Storage::disk('public')->delete($Announcement->image_path);
        }

        // حذف السجل من قاعدة البيانات
        $Announcement->delete();
   
        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('announcements.index')->with('success', 'تم حذف الإعلان  بنجاح.');
    }
}
