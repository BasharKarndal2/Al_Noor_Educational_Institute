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
@include('Teacher.Exam.edit')
@include('Teacher.Exam.create')

<div class="container-fluid py-4 text-end">
   <h2 class="page-title">إدارة الإختبارات</h2>

            <!-- زر إضافة واجب جديد -->
            <div class="mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExamModal">
                    إضافة إختبار جديد
                </button>
            </div>

    
    <!-- إحصائيات -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon me-2"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-info">
                    <h3>{{ $upcomingExams->count() }}</h3>
                    <p>اختبارات قادمة</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon me-2"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3>{{ $completedExams->count() }}</h3>
                    <p>اختبارات مكتملة</p>
                </div>
            </div>
        </div>
    
    <!-- جدول -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>جدول الاختبارات</h5>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="examsTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#upcoming">القادمة</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#completed">المنتهية</button>
                </li>
            </ul>

            <div class="tab-content p-3 border border-top-0 rounded-bottom">
                <!-- القادمة -->
                <div class="tab-pane fade show active" id="upcoming">
                    <div class="table-responsive">
                        <table class="table table-hover text-center"  id="upcomingTable">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th> الصف</th>
                                    <th> المعلم</th>

                                    <th>المادة</th>
                                    <th>النوع</th>
                                    <th>الوقت</th>
                                    <th>المكان</th>
                                    <th>الملف</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingExams as $exam)
                                    <tr>
                                        <td>{{ $exam->exam_date }}</td>
                                        <td>{{ $exam->section->name }}:{{ $exam->section->classroom->name }} </td>
                                        <td>{{ $exam->teacher->full_name }} </td>

                                        <td>{{ $exam->subject->name ?? '-' }}</td>
                                        <td>{{ $exam->title ?? 'اختبار' }}</td>
                                        <td>{{ $exam->start_time }} - {{ $exam->end_time }}</td>
                                        
                                        
                                        <td>{{ $exam->loc ?? '-' }}</td>


                                        <td>
    @if($exam->exam_file)
        <a href="{{ asset('storage/' . $exam->exam_file) }}"
           class="btn btn-sm btn-info"
           download="{{ $exam->title }}.{{ pathinfo($exam->exam_file, PATHINFO_EXTENSION) }}">
            <i class="fas fa-download"></i> تحميل الملف
        </a>
    @else
        <span class="text-muted">لا يوجد ملف</span>
    @endif
</td>
                                        <td>
                                           
                                        <button class="btn btn-sm btn-warning editExamteacherBtn"  data-id="{{ $exam->id }}" data-bs-toggle="modal" data-bs-target="#editExamModal"><i class="fas fa-edit"></i></button>

                              <button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $exam->id }}"
        data-route="{{ route('examsteacher.destroy', ':id') }}">
    <i class="fas fa-trash"></i>
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
                        <table class="table table-hover text-center" id="completedTable">
                           <thead>
                                 <tr>
                                    <th>التاريخ</th>
                                    <th> الصف</th>
                                    <th> المعلم</th>

                                    <th>المادة</th>
                                    <th>النوع</th>
                                    <th>الوقت</th>
                                    <th>المكان</th>
                                    <th>الملفات المرفقة</th>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($completedExams as $exam)
                                   
                                    <tr>
                                        <td>{{ $exam->exam_date }}</td>
                                        <td>{{ $exam->section->name }}:{{ $exam->section->classroom->name }} </td>
                                        <td>{{ $exam->teacher->full_name }} </td>
                                        <td>{{ $exam->subject->name ?? '-' }}</td>
                                        <td>{{ $exam->title ?? 'اختبار' }}</td>
                                        <td>{{ $exam->start_time }} - {{ $exam->end_time }}</td>
                                        <td>{{ $exam->loc ?? '-' }}</td>
                                                  <td>
    @if($exam->exam_file)
        <a href="{{ asset('storage/' . $exam->exam_file) }}"
           class="btn btn-sm btn-info"
           download="{{ $exam->title }}.{{ pathinfo($exam->exam_file, PATHINFO_EXTENSION) }}">
            <i class="fas fa-download"></i> تحميل الملف
        </a>
    @else
        <span class="text-muted">لا يوجد ملف</span>
    @endif
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
@include('admin.aleat_delet')
@endsection


@push('scripts')
<script>
$(document).ready(function() {
    // جدول الاختبارات القادمة
    $('#upcomingTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
            url: '{{ asset('js/datatables/ar.json') }}'
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]]
    });

    // جدول الاختبارات المنتهية
    $('#completedTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
            url: '{{ asset('js/datatables/ar.json') }}'
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]]
    });
})
</script>
@endpush