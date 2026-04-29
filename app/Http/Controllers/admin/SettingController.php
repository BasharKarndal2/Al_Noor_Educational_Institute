<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // جلب أول سطر من الإعدادات (عادة يكون واحد فقط)
        $setting = setting::first();

        return view('admin.setting.index', compact('setting'));
    }

    /**
     * تحديث الإعدادات
     */
    public function update(Request $request)
    {
        // $request->validate([
        //     'email' => 'nullable|email',
        //     'phone1' => 'nullable|string|max:20',
          
        //     'whatsapp' => 'nullable|string|max:20',
        // ]);

        $setting = Setting::first();

        if (!$setting) {
            $setting = setting::create($request->all());
        } else {
            $setting->update($request->all());
        }

        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح ✅');
    }
}

