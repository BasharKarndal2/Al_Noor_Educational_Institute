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
    <h2 class="page-title mb-4"><i class="fas fa-clipboard-list me-2"></i>الحضور والغياب</h2>

    <!-- اختيار الابن -->
    <div class="row mb-4">
        <div class="col-md-4">
            <select class="form-select" id="chiled">
                <option value="">اختر الابن...</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="reloadChildren" class="btn btn-secondary">
                <i class="fas fa-sync-alt"></i> إعادة تعيين
            </button>
        </div>
    </div>

    <!-- إحصائيات الحضور -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3 id="percentage">0%</h3>
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
                    <h3 id="present">0</h3>
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
                    <h3 id="absent">0</h3>
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
                <table class="table table-hover text-center align-middle" id="AttendanceTable">
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
    var table = $('#AttendanceTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: {  url: '{{ asset('js/datatables/ar.json') }}'},
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
        columns: [
            { data: 'date' },
            { data: 'day' },
            { data: 'subject' },
            { data: 'period' },
            { data: 'status' },
            { data: 'notes' }
        ]
    });

    // تحميل الأبناء في select
    function loadChildren() {
        $.ajax({
            url: "{{ route('get.childrenselect') }}",
            type: "GET",
            success: function(data) {
                $('#chiled').empty().append('<option value="">اختر الابن...</option>');
                $.each(data, function(index, child) {
                    $('#chiled').append('<option value="'+ child.id +'">'+ child.name +'</option>');
                });
            },
            error: function() {
                alert("حصل خطأ أثناء جلب الأبناء");
            }
        });
    }
    loadChildren();

    function loadAttendance(childId) {
        $.ajax({
            url: "{{ route('parent.attendance.data') }}", // ضع هنا route لإحضار بيانات حضور الابن
            type: "GET",
            data: { child_id: childId },
            success: function(res) {
                // تحديث الإحصائيات
                $('#percentage').text(res.percentage + '%');
                $('#present').text(res.present);
                $('#absent').text(res.absent);

                // تحديث الجدول
                table.clear().rows.add(res.records).draw();
            },
            error: function() {
                alert('حدث خطأ أثناء جلب بيانات الحضور');
            }
        });
    }

    // عند اختيار الابن
    $('#chiled').on('change', function() {
        var childId = $(this).val();
        if(childId) loadAttendance(childId);
        else {
            $('#percentage').text('0%');
            $('#present').text('0');
            $('#absent').text('0');
            table.clear().draw();
        }
    });

    // إعادة التعيين
    $('#reloadChildren').on('click', function() {
        $('#chiled').val('');
        $('#percentage').text('0%');
        $('#present').text('0');
        $('#absent').text('0');
        table.clear().draw();
    });
});
</script>
@endpush
