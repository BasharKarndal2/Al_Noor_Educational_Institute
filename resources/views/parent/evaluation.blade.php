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
    <h2 class="page-title mb-4"><i class="fas fa-clipboard-list me-2"></i>التقييمات</h2>

    <!-- اختيار الابن -->
    <div class="row mb-4">
        <div class="col-md-4">
            <select class="form-select" id="childSelect">
                <option value="">اختر الابن...</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="reloadChildren" class="btn btn-secondary w-100">
                <i class="fas fa-sync-alt"></i> إعادة تعيين
            </button>
        </div>
    </div>

    <!-- إحصائيات التقييمات -->
    <div class="row g-4 mb-6 mb-4">
        <div class="col-md-6">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-info">
                    <h3 id="totalEvaluations">0</h3>
                    <p>إجمالي التقييمات</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3 id="averageGrade">0</h3>
                    <p>المعدل العام ( للتقييمات )</p>
                </div>
            </div>
        </div>
        
    </div>

    <!-- جدول التقييمات -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">سجل التقييمات</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover text-center align-middle" id="EvaluationsTable">
                    <thead>
                        <tr>
                            <th>عنوان التقييم</th>
                            <th>التاريخ</th>
                            <th>المعلم</th>
                            <th>المادة</th>
                            <th>النوع</th>
                            <th>التكرار</th>
                            <th>الدرجة</th>
                            <th>الملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- سيتم تحميل البيانات عبر Ajax -->
                    </tbody>
                </table>
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
    var table = $('#EvaluationsTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {  url: '{{ asset('js/datatables/ar.json') }}'},
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
        columns: [
            { data: 'title' },
            { data: 'date' },
            { data: 'teacher' },
            { data: 'subject' },
            { data: 'type' },
            { data: 'frequency' },
            { data: 'grade' },
            { data: 'feedback' }
        ]
    });

    // تحميل الأبناء
    function loadChildren() {
        $.get("{{ route('get.childrenselect') }}", function(data) {
            $('#childSelect').empty().append('<option value="">اختر الابن...</option>');
            $.each(data, function(index, child) {
                $('#childSelect').append('<option value="'+ child.id +'">'+ child.name +'</option>');
            });
        });
    }
    loadChildren();

    // تحميل التقييمات للابن المحدد
    function loadEvaluations(childId) {
        $.get("{{ route('parent.evaluation.data') }}", { child_id: childId }, function(res) {
            table.clear().rows.add(res.records).draw();

            // تحديث الإحصائيات
            $('#totalEvaluations').text(res.records.length);
            var avg = 0;
            if(res.records.length > 0) {
                avg = res.records.reduce((sum, r) => sum + (parseFloat(r.grade) || 0), 0) / res.records.length;
            }
            $('#averageGrade').text(avg.toFixed(2));

            var pending = res.records.filter(r => !r.grade).length;
            $('#pendingEvaluations').text(pending);
        });
    }

    // عند اختيار الابن
    $('#childSelect').on('change', function() {
        var childId = $(this).val();
        if(childId) loadEvaluations(childId);
        else {
            table.clear().draw();
            $('#totalEvaluations').text('0');
            $('#averageGrade').text('0');
            $('#pendingEvaluations').text('0');
        }
    });

    // إعادة تعيين
    $('#reloadChildren').on('click', function() {
        $('#childSelect').val('');
        table.clear().draw();
        $('#totalEvaluations').text('0');
        $('#averageGrade').text('0');
        $('#pendingEvaluations').text('0');
    });
});
</script>
@endpush
