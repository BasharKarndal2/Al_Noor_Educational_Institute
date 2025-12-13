@extends('layout.admin.dashboard')


@section('content')

    <x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />
@include('admin.Exam.create')
@include('admin.Exam.edit')
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
        <div class="col-md-4">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-info">
                    <h3>{{ $upcomingExams->count() }}</h3>
                    <p>اختبارات قادمة</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
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
                        <table class="table table-hover text-center" id="exam_table">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th> الصف</th>
                                    <th> المعلم</th>

                                    <th>المادة</th>
                                    <th>العنوان</th>
                                    <th>الوقت</th>
                                    <th>المكان</th>
                                    <th>الملف المرفق</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingExams as $exam)
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
                                            {{-- <button class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-info-circle me-1"></i>تفاصيل
                                            </button> --}}
                                        <button class="btn btn-sm btn-warning editExamBtn"  data-id="{{ $exam->id }}" data-bs-toggle="modal" data-bs-target="#editAssignmentModal"><i class="fas fa-edit"></i></button>

                              <button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $exam->id }}"
        data-route="{{ route('exams.destroy', ':id') }}">
    <i class="fas fa-trash"></i>
</button>

                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6">لا يوجد اختبارات قادمة ✅</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- المنتهية -->
                <div class="tab-pane fade" id="completed">
                    <div class="table-responsive">
                        <table class="table table-hover text-center" id="exam_completed">
                           <thead>
                                 <tr>
                                    <th>التاريخ</th>
                                    <th> الصف</th>
                                    <th> المعلم</th>

                                    <th>المادة</th>
                                    <th>العنوان</th>
                                    <th>الوقت</th>
                                    <th>المكان</th>
                                    <th>الملفات المرفقة</th>
                                 
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($completedExams as $exam)
                                   
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
                                @empty
                                    <tr><td colspan="6">لا يوجد اختبارات مكتملة 📚</td></tr>
                                @endforelse
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
    var table = $('#exam_table').DataTable({
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
        $('select[name="exam_table_length"]').attr('id', 'show');
    });
});





$(document).ready(function() {
    var table = $('#exam_completed').DataTable({
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
        $('select[name="exam_completed_length"]').attr('id', 'show');
    });
});
</script>
@endpush