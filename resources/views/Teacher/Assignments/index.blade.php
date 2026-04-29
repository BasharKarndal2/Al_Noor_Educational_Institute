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

@include('Teacher.Assignments.create')
@include('Teacher.Assignments.edit')
        <!-- محتوى الصفحة -->
        <div class="admin-content">
            <h2 class="page-title">إدارة الواجبات</h2>

            <!-- زر إضافة واجب جديد -->
            <div class="mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                    إضافة واجب جديد
                </button>
            </div>

            <!-- جدول الواجبات -->
            <div class="card mb-4">
                <div class="card-header">
                    قائمة الواجبات والاختبارات
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="Assignments_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    
                                    <th>الصف</th>
                                    <th>المادة</th>
                                    <th>تاريخ التسليم</th>
                                    <th>المعلم</th>
                                    <th>الحالة</th>
                                    <th>ملف المرفقات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ( $assignments as  $assignment )
                                       <tr>
                                    <td>{{   $assignment->id }}</td>
                                    <td>{{  $assignment->title }}</td>
                                 
                                    <td>   {{ $assignment->section->name }}:{{ $assignment->section->classroom->name }}</td>
                                    <td>{{  $assignment->subject->name }}</td>
                                    <td>{{ $assignment->due_date }}</td>
                                    <td>أ.  {{ $assignment->teacher->full_name }}</td>
                                  <td>
    @if($assignment->status === 'active')
        <span class="badge bg-success">نشط</span>
    @else
        <span class="badge bg-danger">غير نشط</span>
    @endif
</td><td>
    @if($assignment->file_path)
        <a href="{{ asset('storage/' . $assignment->file_path) }}"
           class="btn btn-sm btn-info"
           download="{{ $assignment->title }}.{{ pathinfo($assignment->file_path, PATHINFO_EXTENSION) }}">
            <i class="fas fa-download"></i> تحميل الملف
        </a>
    @else
        <span class="text-muted">لا يوجد ملف</span>
    @endif
</td>
                                    <td>
<!--                                        <button class="btn btn-sm btn-primary viewAssignmentBtn" data-id="{{ $assignment->id }}">-->
<!--    <i class="fas fa-eye"></i>-->
<!--</button>-->
                                        <button class="btn btn-sm btn-warning editAssignmentBtn"  data-id="{{ $assignment->id }}" data-bs-toggle="modal" data-bs-target="#editAssignmentModal"><i class="fas fa-edit"></i></button>
                              <button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $assignment->id }}"
        data-route="{{ route('assignmentsteacher.destroy', ':id') }}">
    <i class="fas fa-trash"></i>
</button>

<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewSubmissionsModal" 
onclick="loadSubmissions({{ $assignment->id }})">
عرض الطلاب
</button>
                                    </td>
                                </tr>
                                @empty
                                     <tr>
                                    <td>لا يوجد واجبات بعد</td>
                                    
                                </tr>
                                @endforelse
                               
                                
                            </tbody>
                        </table>
                    </div>
                    <!-- الصفحات -->
                   
                </div>
            </div>

@include('Teacher.Assignments.show')
@include('admin.aleat_delet')
@include('Teacher.Assignments.showsubmitinstudent')

@endsection


@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready(function() {
    var table = $('#Assignments_table').DataTable({
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
        $('select[name="requeststudent_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush

