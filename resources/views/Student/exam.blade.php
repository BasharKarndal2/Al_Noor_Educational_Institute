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
        font-size: 14px;
        padding: 6px;
    }
}

@media (max-width: 576px) {
    .table th, .table td {
        font-size: 12px;
        padding: 4px;
    }
}
</style>

@include('Student.about_note_exam')

<div class="container-fluid py-4">
    <h2 class="page-title mb-4"><i class="fas fa-file-alt me-2"></i>الاختبارات</h2>

    <!-- إحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-info">
                    <h3>{{ $upcoming->count() }}</h3>
                    <p>اختبارات قادمة</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3>{{ $completed->count() }}</h3>
                    <p>اختبارات مكتملة</p>
                </div>
            </div>
        </div>
        <!--<div class="col-md-4">-->
        <!--    <div class="stat-card bg-info text-white">-->
        <!--        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>-->
        <!--        <div class="stat-info">-->
        <!--            <h3>قيد التطوير</h3>-->
        <!--            <p>المعدل العام</p>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    </div>

    <!-- جدول -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>جدول الاختبارات</h5>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="examsTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button">القادمة</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">المنتهية</button>
                </li>
            </ul>

            <div class="tab-content p-3 border border-top-0 rounded-bottom">
                <!-- القادمة -->
                <div class="tab-pane fade show active" id="upcoming">
                    <div class="table-responsive">
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>المادة</th>
                                    <th>المعلم</th>
                                    <th>الوقت</th>
                                    <th>القاعة</th>
                                    <th>التفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($upcoming as $exam)
                                    <tr>
                                        <td>{{ $exam->exam_date }}</td>
                                        <td>{{ $exam->subject->name ?? '-' }}</td>
                                        <td>{{ $exam->teacher->full_name ?? '-' }}</td>
                                        <td>{{ $exam->start_time }} - {{ $exam->end_time }}</td>
                                        <td>{{ $exam->loc ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary viewExamBtn" data-id="{{ $exam->id }}">
                                                <i class="fas fa-info-circle me-1"></i>تفاصيل
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- المنتهية -->
                <div class="tab-pane fade" id="completed">
                    <div class="table-responsive">
                        <table class="table table-hover text-center">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>المادة</th>
                                    <th>المعلم</th>
                                    <th>التفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($completed as $exam)
                                    @php
                                        $score = $results[$exam->id]->score ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $exam->exam_date }}</td>
                                        <td>{{ $exam->subject->name ?? '-' }}</td>
                                        <td>{{ $exam->teacher->full_name ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary viewExamBtn" data-id="{{ $exam->id }}">
                                                <i class="fas fa-info-circle me-1"></i>تفاصيل
                                            </button>
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
@endsection

@push('stack')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    // لجداول القادمة والمنتهية
    $('#upcoming table, #completed table').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            url: '{{ asset("js/datatables/ar.json") }}'
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]]
    });
});
</script>
@endpush