@extends('layout.teacher.dashboard')


@section('conten')
    <style>

    .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* تحسين التمرير على الأجهزة المحمولة */
}

.table {
    min-width: 800px; /* الحد الأدنى لعرض الجدول لضمان الحاجة إلى التمرير */
    direction: rtl; /* دعم الاتجاه من اليمين إلى اليسار */
}

.table th, .table td {
    white-space: nowrap; /* منع التفاف النص */
    padding: 8px; /* هوامش مناسبة */
}

/* تنسيقات للشاشات الصغيرة */
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 14px; /* تقليل حجم الخط */
        padding: 6px; /* تقليل الهوامش */
    }
}

@media (max-width: 576px) {
    .table th, .table td {
        font-size: 12px; /* تقليل حجم الخط أكثر */
        padding: 4px; /* تقليل الهوامش أكثر */
    }
}
</style>

<div class="admin-content">
    <h2 class="page-title"><i class="fas fa-users"></i> طلاب  :الصف: {{ $section_name->classroom->name  }} الشعبة:{{ $section_name->name }} يدرسون المادة {{ $subjectname->name }}</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">قائمة الطلاب</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table-hover table">
                            <thead>
                                <tr>
                                    <th>صورة الطالب</th>
                                    <th>اسم الطالب</th>
                                    <th>رقم الطالب</th>
                                    <th>البريد الإلكتروني</th>
                              
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                    <tr>
                                        <td>
                                 
                                    <img src="{{ asset('storage/' . $student['student']->image_path) }}" alt="صورة الطالب" class="teacher-photo me-2 protected-data" style="width: 40px; height: 40px; border-radius: 50%;">

                                        </td>
                                        <td>{{ $student['student']->name }}</td>
                                        <td>{{ $student['student']->id }}</td>
                                    
                                        <td>{{ $student['student']->email}}</td>
                                      
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection