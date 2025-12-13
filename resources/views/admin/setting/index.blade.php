@extends('layout.admin.dashboard')

@section('content')


<x-alert type="success" />
       <div class="admin-content text-start">
            <h2 class="page-title">الإعدادات</h2>
            <div class="card">
                <div class="card-body">
                 
      <form action="{{ route('settings.update') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="location" class="form-label">العنوان</label>
        <input type="text" class="form-control" id="location" name="location" value="{{ $setting->location ?? '' }}">
    </div>

    <div class="mb-3">
        <label for="phone2" class="form-label">رقم الهاتف الاساسي</label>
        <input type="text" class="form-control" id="whatsapp1" name="whatsapp" value="{{ $setting->whatsapp ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="whatsapp" class="form-label"> رقم الهاتف الثانوي </label>
        <input type="text" class="form-control" id="whatsapp2" name="phone2" value="{{ $setting->phone2 ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="telegram" class="form-label">تلجرام</label>
        <input type="text" class="form-control" id="telegram" name="telegram" value="{{ $setting->telegram ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">البريد الإلكتروني</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ $setting->email ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="facebook" class="form-label">صفحة فيسبوك</label>
        <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $setting->facebook ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="instagram" class="form-label">صفحة انستغرام</label>
        <input type="text" class="form-control" id="instagram" name="instagram" value="{{ $setting->instagram ?? '' }}">
    </div>
    <div class="mb-3">
        <label for="working_hours" class="form-label">ساعات العمل</label>
        <textarea class="form-control" id="working_hours" name="working_hours" rows="4">{{ $setting->working_hours ?? '' }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
</form>

            </div>
        </div>
    </div>

@endsection