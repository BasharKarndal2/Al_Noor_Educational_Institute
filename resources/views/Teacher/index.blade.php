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
     <div class="container-fluid py-4">
                <h2 class="page-title"><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h2>
                
                <!-- بطاقات الإحصائيات -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6 col-lg-6">
                        <div class="stat-card bg-primary">
                            <div class="stat-icon me-2">
                                <i class="fas fa-school"></i>
                            </div>
                            <div class="stat-info">
                                <h3>{{ $countsection }}</h3>
                                <p>عدد الصفوف</p>
                                <a href="{{ route('teacher.section') }}">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <div class="stat-card bg-secondary">
                            <div class="stat-icon me-2">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-info">
                                <h3>{{ $studentscount }}</h3>
                                <p>عدد الطلاب</p>
                                <a href="{{ route('teacher.student') }}">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>
                            </div>
                        </div>
                    </div>




                    <!--<div class="col-md-6 col-lg-4">-->
                    <!--    <div class="stat-card bg-accent">-->
                    <!--        <div class="stat-icon">-->
                    <!--            <i class="fas fa-tasks"></i>-->
                    <!--        </div>-->
                    <!--        <div class="stat-info">-->
                    <!--            <h3>قيد التطوير</h3>-->
                    <!--            <p>واجبات معلقة</p>-->
                    <!--            <a href="#">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <!--<div class="col-md-6 col-lg-3">-->
                    <!--    <div class="stat-card bg-dark">-->
                    <!--        <div class="stat-icon">-->
                    <!--            <i class="fas fa-envelope"></i>-->
                    <!--        </div>-->
                    <!--        <div class="stat-info">-->
                    <!--            <h3>قيد التطوير</h3>-->
                    <!--            <p>رسائل جديدة</p>-->
                    <!--            <a href="#">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>

                <div class="row g-4">
        
                      <div class="col-lg-12">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>جدولي اليومي</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>الحصة</th>
                                                <th>المادة</th>
                                                <th>الصف</th>
                                                <th>الوقت</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($schedules as $schedule)
                    <tr>
                        <td>  الحصة :{{   $schedule->period_number }}</td>
                        <td>{{ $schedule->subject->name }}</td>
                        <td>{{ $schedule->section->classroom->name }}   {{  $schedule->section->name }}</td>
                                                                   <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">لا يوجد حصص لهذا اليوم</td>
                    </tr>
                @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--<div class="col-lg-6">-->
                    <!--    <div class="card shadow-sm">-->
                    <!--        <div class="card-header bg-secondary text-white">-->
                    <!--            <h5 class="mb-0"><i class="fas fa-bell me-2"></i>آخر الإشعارات</h5>-->
                    <!--        </div>-->
                    <!--        <div class="card-body">-->
                    <!--            <div class="notifications-list">-->
                    <!--                <div class="notification-item unread">-->
                    <!--                    <div class="notification-icon text-danger">-->
                    <!--                        <i class="fas fa-exclamation-circle"></i>-->
                    <!--                    </div>-->
                    <!--                    <div class="notification-content">-->
                    <!--                        <h6>تذكير بتسجيل الحضور</h6>-->
                    <!--                        <p>حصة الرياضيات للصف العاشر أ</p>-->
                    <!--                        <small>منذ 30 دقيقة</small>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--                <div class="notification-item">-->
                    <!--                    <div class="notification-icon text-primary">-->
                    <!--                        <i class="fas fa-info-circle"></i>-->
                    <!--                    </div>-->
                    <!--                    <div class="notification-content">-->
                    <!--                        <h6>اجتماع المادة</h6>-->
                    <!--                        <p>يوم الأربعاء القادم الساعة 11 صباحاً</p>-->
                    <!--                        <small>منذ يومين</small>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--                <div class="notification-item">-->
                    <!--                    <div class="notification-icon text-success">-->
                    <!--                        <i class="fas fa-check-circle"></i>-->
                    <!--                    </div>-->
                    <!--                    <div class="notification-content">-->
                    <!--                        <h6>تم تسليم الواجب</h6>-->
                    <!--                        <p>من الطالب محمد علي - الصف الحادي عشر علمي</p>-->
                    <!--                        <small>منذ 3 أيام</small>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>
            </div> 
@endsection
