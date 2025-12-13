@extends('layout.Student.dashboard')

@section('conten')
<style>
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        min-width: 800px;
        direction: rtl;
    }

    .table th, .table td {
        white-space: nowrap;
        padding: 8px;
    }

    .stat-card {
        border-radius: 10px;
        padding: 20px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .stat-card .stat-icon {
        font-size: 2rem;
        margin-right: 10px;
    }

    .stat-card .stat-info h3 {
        margin: 0;
        font-size: 1.5rem;
    }

    .stat-card .stat-info p {
        margin: 0;
    }

    /* تنسيقات للشاشات الصغيرة */
    @media (max-width: 768px) {
        .table th, .table td { font-size: 14px; padding: 6px; }
        .stat-card { padding: 15px; }
        .stat-card .stat-icon { font-size: 1.5rem; }
    }

    @media (max-width: 576px) {
        .table th, .table td { font-size: 12px; padding: 4px; }
        .stat-card { padding: 10px; }
        .stat-card .stat-icon { font-size: 1.2rem; }
    }
</style>

<div class="container-fluid py-4">
    <h2 class="page-title mb-4"><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h2>
    
    <!-- بطاقات الإحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card bg-primary">
                <div class="stat-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <h3>{{ $percentage }}%</h3>
                    <p>نسبة الحضور</p>
                    <a href="{{ route('sudent.attstudent') }}" class="text-white">عرض التفاصيل <i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-info">
                    <h3>{{ $assignmentsCount }}</h3>
                    <p>واجبات جديدة</p>
                    <a href="{{ route('assignmentsStudent.index') }}" class="text-white">عرض الواجبات <i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card bg-warning text-dark">
                <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                <div class="stat-info">
                    <h3>{{ $upcoming->count() }}</h3>
                    <p>اختبارات قادمة</p>
                    <a href="{{ route('sudent.exam') }}" class="text-dark">عرض الاختبارات <i class="fas fa-arrow-left"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="stat-card bg-info">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <div class="stat-info">
                    <h3>{{ $average }}</h3>
                    <p>المعدل العام</p>
                </div>
            </div>
        </div>
    </div>

    <!-- الجدول الدراسي اليوم -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>جدول اليوم</h5>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الحصة</th>
                                <th>المادة</th>
                                <th>المعلم</th>
                                <th>الوقت</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td>الحصة: {{ $schedule->period_number }}</td>
                                    <td>{{ $schedule->subject->name }}</td>
                                    <td>{{ $schedule->teacher->full_name }}</td>
                                    <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
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

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-bell me-2"></i>آخر الإشعارات</h5>
                </div>
                <div class="card-body">
                    <div class="notifications-list">
                        <!-- مثال للإشعارات -->
                        <div class="notification-item unread">
                            <div class="notification-icon text-danger"><i class="fas fa-exclamation-circle"></i></div>
                            <div class="notification-content">
                                <h6>واجب جديد في الرياضيات</h6>
                                <p>الموعد النهائي: غداً الساعة 10 مساءً</p>
                                <small>منذ ساعتين</small>
                            </div>
                        </div>

                        <div class="notification-item">
                            <div class="notification-icon text-success"><i class="fas fa-check-circle"></i></div>
                            <div class="notification-content">
                                <h6>تم رفع درجات الاختبار</h6>
                                <p>اختبار اللغة العربية - الأسبوع الماضي</p>
                                <small>منذ يوم</small>
                            </div>
                        </div>

                        <div class="notification-item">
                            <div class="notification-icon text-primary"><i class="fas fa-info-circle"></i></div>
                            <div class="notification-content">
                                <h6>اجتماع أولياء الأمور</h6>
                                <p>يوم السبت القادم الساعة 11 صباحاً</p>
                                <small>منذ 3 أيام</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
