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
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-calendar-week me-2"></i>الجدول الأسبوعي</h5>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-4">
                <select class="form-select" id="chiled">
                    <option value="">اختر الطالب...</option>
                </select>
            </div>

            <div class="col-md-2">
                <button id="reloadChildren" class="btn btn-secondary">
                    <i class="fas fa-sync-alt"></i> إعادة تعيين
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover text-center align-middle" id="ClassSchdule_tabel">
                <thead>
                    <tr>
                        <th>اسم الابن</th>
                        <th>اليوم</th>
                        <th>الفترة</th>
                        <th>الوقت</th>
                        <th>المادة</th>
                        <th>المعلم</th>
                        <th>القسم</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        use App\Helpers\ColorHelper;
                        $daysArabic = [
                            'saturday' => 'السبت',
                            'sunday' => 'الأحد',
                            'monday' => 'الإثنين',
                            'tuesday' => 'الثلاثاء',
                            'wednesday' => 'الأربعاء',
                            'thursday' => 'الخميس',
                            'friday' => 'الجمعة',
                        ];
                    @endphp

                    @foreach($schedules as $schedule)
                        @php
                            $bgColor = $schedule->color ?? '#423434';
                            $textColor = ColorHelper::getContrastColor($bgColor);
                        @endphp
                        <tr data-bg-color="{{ $bgColor }}" data-text-color="{{ $textColor }}">
                            <td>{{ $schedule->student_name ?? '-' }}</td>
                            <td>{{ $daysArabic[strtolower($schedule->day_of_week)] ?? $schedule->day_of_week }}</td>
                            <td>{{ $schedule->period_number }}</td>
                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                            <td>{{ $schedule->subject->name ?? 'غير محدد' }}</td>
                            <td>{{ $schedule->teacher->full_name ?? 'غير محدد' }}</td>
                            <td>{{ $schedule->section->name ?? 'غير محدد' }} : {{ $schedule->section->classroom->name ?? 'غير محدد' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('stack')
<!-- jQuery أولاً -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#ClassSchdule_tabel').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        language: {  url: '{{ asset('js/datatables/ar.json') }}' },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
        rowCallback: function(row, data, index) {
            var bgColor = $(row).data('bg-color') || '#ffffff';
            var textColor = $(row).data('text-color') || '#000000';
            $('td', row).css({'background-color': bgColor, 'color': textColor});
        }
    });

    // تحميل الأبناء في select
    function loadChildren() {
        $.ajax({
            url: "{{ route('get.childrenselect') }}",
            type: "GET",
            success: function(data) {
                $('#chiled').empty().append('<option value="الكل">الكل</option>');
                $.each(data, function(index, child) {
                    $('#chiled').append('<option value="'+ child.name +'">'+ child.name +'</option>');
                });
            },
            error: function() {
                alert("حصل خطأ أثناء جلب الأبناء");
            }
        });
    }
    loadChildren();

    // فلترة حسب اسم الابن
    $('#chiled').on('change', function() {
        var val = $(this).val();
        if(val === 'الكل') val = '';
        table.column(0).search(val).draw();
    });

    // إعادة تعيين الفلاتر
    $('#reloadChildren').on('click', function() {
        $('#chiled').val('الكل');
        table.search('').columns().search('').draw();
    });
});
</script>
@endpush
