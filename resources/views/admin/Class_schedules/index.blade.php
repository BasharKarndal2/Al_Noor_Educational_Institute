

@extends('layout.admin.dashboard')


@section('content')

<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

 <style>
        body {
            padding-top: 25px;
            font-family: 'Tajawal', sans-serif;
        }

        .admin-sidebar {
            margin-top: 0;
        }
        
        @media (max-width: 991.98px) {
            body {
                padding-top: 56px;
            }
        }

        /* أنماط خاصة بجدول الدروس */
        .subject-cell {
            min-width: 150px;
        }
        .timetable-container {
            overflow-x: auto;
        }
        .period-type-1 { background-color: #d1e7dd; } /* دراسية */
        .period-type-2 { background-color: #fff3cd; } /* استراحة */
        .period-type-3 { background-color: #cff4fc; } /* نشاط */
        
        /* تحسينات للبطاقات */
        .card {
            border: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #2c3e50;
            color: white;
            border-bottom: none;
        }
        
        /* تحسينات للجداول */
        .table thead th {
            background-color: #34495e;
            color: white;
            border-bottom: none;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(52, 73, 94, 0.1);
        }
        
        /* تحسينات للأزرار */
        .btn-outline-secondary {
            border-color: #7f8c8d;
            color: #7f8c8d;
        }
        
        .btn-outline-secondary:hover {
            background-color: #7f8c8d;
            color: white;
        }
    </style>


<div class="container-fluid py-4">
                <h2 class="page-title mb-4"><i class="fas fa-calendar-alt me-2"></i>الجداول الدراسية</h2>
           
                <div class="d-flex justify-content-between mb-4">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTimetableModal">
                        <i class="fas fa-plus me-2"></i>إضافة جدول جديد
                    </button>
                    <!--<div class="d-flex">-->
                    <!--    <select class="form-select me-2" id="filterClass" style="width: 150px;">-->
                    <!--        <option value="">كل الصفوف</option>-->
                    <!--        <option>الصف الأول</option>-->
                    <!--        <option>الصف الثاني</option>-->
                    <!--        <option>الصف الثالث</option>-->
                    <!--    </select>-->
                    <!--    <select class="form-select me-2" id="filterSection" style="width: 150px;">-->
                    <!--        <option value="">كل الشعب</option>-->
                    <!--        <option>الشعبة أ</option>-->
                    <!--        <option>الشعبة ب</option>-->
                    <!--        <option>الشعبة ج</option>-->
                    <!--    </select>-->
                    <!--    <button class="btn btn-outline-secondary">-->
                    <!--        <i class="fas fa-filter me-1"></i>تصفية-->
                    <!--    </button>-->
                    <!--</div>-->
                </div>

                <!-- عرض الجداول -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-table me-2"></i>الجداول الدراسية</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="Class_schedules_table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">الصف</th>
                                        <th width="15%">الشعبة</th>
                                        <th width="15%">الفصل</th>
                                
                                        <th width="15%">إجراءات</th>
                                    </tr>
                                </thead>
                               <tbody>
@foreach($classSchedules as $index => $schedule)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $schedule->section->classroom->name ?? '-' }}</td>
        <td>{{ $schedule->section->name ?? '-' }}</td>
        <td>{{ $schedule->section->classroom->educationalStage->working_hour->name ?? '-' }}</td>
      
        <td>
            <!-- زر عرض الجدول -->
       <button class="btn btn-sm btn-primary viewSchedule"
        data-section-id="{{ $schedule->section_id }}">
    <i class="fas fa-eye"></i>
</button>
            
            <!-- زر تعديل الجدول -->
            <button class="btn btn-sm btn-warning me-1   editTimetableModalbtn"   data-id="{{ $schedule->section->id }} " data-bs-toggle="modal" data-bs-target="#editTimetableModal">
                <i class="fas fa-edit"></i>
            </button>

            <!-- زر حذف الجدول -->
             <button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $schedule->section->id }}"
        data-route="{{ route('class_schedule.destroy', ':id') }}">
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
            </div>@include('admin.Class_schedules.show')
            @include('admin.Class_schedules.edit')

@include('admin.aleat_delet')
@include('admin.Class_schedules.create1')
@endsection




@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#Class_schedules_table').DataTable({
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
        $('select[name="Class_schedules_length"]').attr('id', 'show');
    });
});
</script>
@endpush