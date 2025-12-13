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
            <h2 class="page-title"><i class="fas fa-chalkboard"></i> صفوفي الدراسية</h2>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary">قائمة الصفوف</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>اسم الصف</th>
                                            <th>عدد الطلاب</th>
                                            <th>المادة</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
@foreach($sections as $item)
    <tr>
        <td>{{ $item['section']->name }}</td>
        <td>{{ $item['students_count'] }}</td>
        <td>{{ $item['subject']->name }}</td>
        <td>
            <a href="{{ route('teacher.getstudent', ['sectionID' => $item['section']->id, 'subjectID' => $item['subject']->id]) }}" 
               class="btn btn-sm btn-primary">
                <i class="fas fa-eye"></i> عرض
            </a>
           
        </td>
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
    </div>

@endsection