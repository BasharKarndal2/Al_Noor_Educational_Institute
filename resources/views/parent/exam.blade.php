@extends('layout.parent.dashboard')

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
    <h2 class="page-title mb-4"><i class="fas fa-file-alt me-2"></i>الاختبارات</h2>

    <!-- اختيار الابن -->
    <div class="row mb-4 align-items-end">
        <div class="col-md-4">
            <label for="childSelect" class="form-label">اختر الطالب:</label>
            <select class="form-select" id="childSelect">
                <option value="">اختر الطالب...</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="reloadChildren" class="btn btn-secondary w-100">
                <i class="fas fa-sync-alt"></i> إعادة تعيين
            </button>
        </div>
    </div>

    <!-- إحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-info">
                    <h3 id="upcomingCount">0</h3>
                    <p>اختبارات قادمة</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3 id="completedCount">0</h3>
                    <p>اختبارات مكتملة</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-info text-white">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>قيد التطوير</h3>
                    <p>المعدل العام</p>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول الاختبارات -->
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
                        <table class="table table-hover text-center" id="upcomingTable">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>المادة</th>
                                    <th>المعلم</th>
                                    <th>الوقت</th>
                                    <th>القاعة</th>
                                    <th>تفاصيل</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- المنتهية -->
                <div class="tab-pane fade" id="completed">
                    <div class="table-responsive">
                        <table class="table table-hover text-center" id="completedTable">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>المادة</th>
                                    <th>المعلم</th>
                                    <th>تفاصيل</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('parent.about_note_exam')
@endsection

@push('stack')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
   var table = $('#upcomingTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {  url: '{{ asset('js/datatables/ar.json') }}'},
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
       
    });

       var table = $('#completedTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {  url: '{{ asset('js/datatables/ar.json') }}'},
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
       
    });

    function loadChildren() {
        $.get("{{ route('get.childrenselect') }}", function(data) {
            $('#childSelect').empty().append('<option value="">اختر الطالب...</option>');
            $.each(data, function(index, child) {
                $('#childSelect').append('<option value="'+ child.id +'">'+ child.name +'</option>');
            });
        });
    }
    loadChildren();

    function clearTables() {
        $('#upcomingCount').text('0');
        $('#completedCount').text('0');
        $('#upcomingTable tbody').empty();
        $('#completedTable tbody').empty();
    }

    function loadExams(childId) {
        // أول شي نفرغ البيانات السابقة
        clearTables();

        $.get("{{ route('parent.exam.data') }}", { child_id: childId }, function(res) {
            // تحديث الإحصائيات
            $('#upcomingCount').text(res.upcoming.length);
            $('#completedCount').text(res.completed.length);

            // ملئ الجداول
            $.each(res.upcoming, function(i, exam) {
                $('#upcomingTable tbody').append('<tr>'+
                    '<td>'+exam.exam_date+'</td>'+
                    '<td>'+exam.subject.name+'</td>'+
                    '<td>'+exam.teacher.full_name+'</td>'+
                    '<td>'+exam.start_time+' - '+exam.end_time+'</td>'+
                    '<td>'+exam.loc+'</td>'+
                  '<td><button class="btn btn-sm btn-outline-primary viewExamBtn" data-id="'+exam.id+'">'+
    '<i class="fas fa-info-circle me-1"></i> تفاصيل</button></td>'+
                '</tr>');
            });

            $.each(res.completed, function(i, exam) {
                $('#completedTable tbody').append('<tr>'+
                    '<td>'+exam.exam_date+'</td>'+
                    '<td>'+exam.subject.name+'</td>'+
                    '<td>'+exam.teacher.full_name+'</td>'+
                '<td><button class="btn btn-sm btn-outline-primary viewExamBtn" data-id="'+exam.id+'">'+
    '<i class="fas fa-info-circle me-1"></i> تفاصيل</button></td>'+
        '</tr>');
            });
        });
    }

    $('#childSelect').on('change', function() {
        var childId = $(this).val();
        if(childId) {
            loadExams(childId);
        } else {
            clearTables();
        }
    });

    $('#reloadChildren').on('click', function() {
        $('#childSelect').val('');
        clearTables();
    });

});
</script>
@endpush





