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
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'نجاح',
            text: "{{ session('success') }}",
            confirmButtonText: 'موافق',
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: "{{ session('error') }}",
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#d33'
        });
    </script>
@endif

 <!-- محتوى الصفحة -->
        <div class="admin-content">
            <h2 class="page-title">إدارة الحضور والغياب</h2>

            <!-- زر إضافة واجب جديد -->
            <div class="mb-4">
                              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">تسجيل حضور جديد</button>

                 
                </button>
            </div>



       <!-- جدول الواجبات -->
            <div class="card mb-4">
                <div class="card-header">
                    قائمة  الحضور والغياب
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="att_table" >
                          <thead>
                        <tr>
                            
                            <th>رقم السجل</th>
                            <th>الصف</th>
                            <th>الحصة</th>
                            <th> المعلم</th>
                            <th>المادة</th>
                            <th>التاريخ</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                             <tbody>

                        @foreach ($atts as $att )
                             <tr>
                            <td>{{ $att->id   }}</td>
                            <td>{{ $att->classSchedule->section->classroom->name }}    {{ $att->classSchedule->section->name }}</td>
                            <td> الحصة:{{  $att->classSchedule->period_number }}</td>
                            <td>{{ $att->classSchedule->teacher->full_name }}</td>
                            <td>{{ $att->classSchedule->subject->name }}</td>
                            <td>{{ $att->attendance_date }}</td>
                            <td>
                                <button 
    class="btn btn-info btn-sm viewattendanceBtn" 
    data-id="{{  $att->id}}"

    data-bs-toggle="modal" 
    data-bs-target="#viewattendanceModal">
    <i class="fas fa-eye"></i> عرض
                            </button>
                                <button class="btn btn-sm btn-warning editattBtn" data-bs-toggle="modal" data-bs-target="#editAttendanceModal"   data-id="{{  $att->id}}">تعديل</button>
                               <button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $att->id }}"
        data-route="{{ route('teacheratt.destroy', ':id') }}">
    <i class="fas fa-trash"></i>
</button>
          
                            </td>
                        </tr>
                      
                        @endforeach
                       
                          
                       
                    </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
@include('admin.aleat_delet')
@include('Teacher.Attendance.viewatt')
@include('Teacher.Attendance.create')
@include('Teacher.Attendance.edit')
@endsection



@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#att_table').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
     url: '{{ asset('js/datatables/ar.json') }}'
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]] // إضافة خيار الكل
    });

    // تغيير id للـ select تبع عدد الصفوف
    table.on('init', function() {
        $('select[name="att_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush