@extends('layout.Student.dashboard')

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
<div class="container-fluid py-4">
    <h2 class="page-title mb-4"><i class="fas fa-clipboard-list me-2"></i>الحضور والغياب</h2>

    <!-- إحصائيات الحضور -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-primary text-white">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $percentage }}%</h3>
                <p>نسبة الحضور</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-success text-white">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $present }}</h3>
                <p>حصة حضرتها</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-danger text-white">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $absent }}</h3>
                <p>حصة غبت عنها</p>
            </div>
        </div>
    </div>
</div>
    <!-- جدول الحضور -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>سجل الحضور</h5>
        </div>
        <div class="card-body">
            @php
                $days = [
                   'saturday' => 'السبت',
                            'sunday' => 'الأحد',
                            'monday' => 'الإثنين',
                            'tuesday' => 'الثلاثاء',
                            'wednesday' => 'الأربعاء',
                            'thursday' => 'الخميس',
                            'friday' => 'الجمعة',
                ];
            @endphp

            <div class="table-responsive">
                <table class="table table-hover text-center align-middle">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>اليوم</th>
                            <th>المادة</th>
                            <th>الحصة</th>
                            <th>الحالة</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($att as $a)
                        <tr>
                            <td>{{ $a->attendance->attendance_date ?? '-' }}</td>
                            <td>{{ $days[$a->attendance->classSchedule->day_of_week ?? ''] ?? '-' }}</td>
                            <td>{{ $a->attendance->classSchedule->subject->name ?? '-' }}</td>
                            <td>{{ $a->attendance->classSchedule->period_number ?? '-' }}</td>
                            <td>
                                @if($a->status == 'present')
                                    <span class="badge bg-success">حاضر</span>
                                @elseif($a->status == 'absent')
                                    <span class="badge bg-danger">غائب</span>
                                @else
                                    <span class="badge bg-secondary">اذن</span>
                                @endif
                            </td>
                            <td>{{ $a->notes ?? 'لا يوجد' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
