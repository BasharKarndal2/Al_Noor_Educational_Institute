@extends('layout.admin.dashboard')

@section('content')

    {{-- Alerts --}}
    <x-alert type="success" />
    <x-alert type="danger" />
    <x-alert type="info" />

    @include('admin.aleat_delet')
    @include('admin.Student.create')
    @include('admin.Student.edit')

    {{-- عرض خطأ --}}
    @if ($errors->has('section'))
        <div class="alert alert-danger">
            {{ $errors->first('section') }}
        </div>
    @endif

    <div class="container-fluid py-4">

        {{-- العنوان + زر الإضافة --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">
                <i class="fas fa-users me-2"></i> إدارة الطلاب
            </h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                <i class="fas fa-plus me-2"></i> إضافة طالب جديد
            </button>
        </div>

        @include('admin.Student.filter')
        {{-- جدول الطلاب --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">قائمة الطلاب</h5>
                <div>
                    <button id="exportExcel"
        class="btn btn-success btn-sm mb-2"
        data-table-id="student_table"
        data-filename="بيانات الصفوف  الدراسية">
  <i class="fas fa-file-excel"></i> تصدير Excel
</button>
<!-- زر تصدير PDF -->
<button id="generatepdf"
        class="btn btn-danger btn-sm mb-2"
        data-table-id="student_table"
        data-report-title="تقرير عن بيانات الصفوف الدراسية"
        data-filename="تقرير الصفوف الدراسية ">
  <i class="fas fa-file-pdf"></i> تصدير PDF
</button>  
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle" id="student_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>اسم الطالب</th>
                                <th>الرقم الأكاديمي</th>
                                <th hidden> الفوج</th>
                                <th hidden> المرحلة الدراسية</th>

                                <th>الصف</th>
                                <th>الشعبة</th>
                                <th>ولي الأمر</th>
                                <th>الحالة</th>
                                <th> البريد الإلكتروني</th>
                                <th>إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $student->image_path) }}"
                                             alt="صورة الطالب"
                                             class="teacher-photo me-2 protected-data"
                                             style="width: 40px; height: 40px; border-radius: 50%;">
                                    </td>
                                    <td>{{ $student->name?? '-' }}</td>
                                    <td>{{ $student->id?? '-' }}</td>
                                    <td hidden>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{ $section->classroom->educationalStage->working_hour->name?? '-' }}
                                        @endforeach
                                    </td>
                                    <td hidden>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{$section->classroom->educationalStage->name?? '-' }}
                                        @endforeach
                                    </td >

                                    {{-- الصف --}}
                                    <td>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{ $section->classroom->name?? '-' }}
                                        @endforeach
                                    </td>

                                    {{-- الشعبة --}}
                                    <td>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{ $section->name?? '-' }}
                                        @endforeach
                                    </td>

                                    {{-- ولي الأمر (افتراضاً) --}}
                                    <td>
                                        {{ $student->parent->name ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-success">{{ $student->status }}</span>
                                    </td>
                                    <td>{{ $student->email }}</td>

                                    {{-- الإجراءات --}}
                                    <td class="action-btns">
                                        <!--<button class="btn btn-sm btn-outline-primary"-->
                                        <!--        title="عرض"-->
                                        <!--         data-id="{{ $student->id }}"-->
                                        <!--        data-bs-toggle="modal"-->
                                        <!--        data-bs-target="#viewStudentModal">-->
                                        <!--    <i class="fas fa-eye"></i>-->
                                        <!--</button>-->

                                        <button class="btn btn-sm btn-outline-warning"
                                                title="تعديل"
                                                data-id="{{ $student->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editStudentModal">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal"
                                                data-id="{{ $student->id }}"
                                                data-route="{{ route('student.destroy', ':id') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-secondary"
                                                title="إضافة مادة"
                                                data-id="{{ $student->id }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addSubjectToStudentModal">
                                            <i class="fas fa-book"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- الصف الإضافي للمواد
                                <tr class="bg-light">
                                    <td colspan="10">
                                        <strong>المواد التي يدرسها الطالب:</strong>
                                        <ul class="mb-0">
                                            @foreach ($student->sectionSubjectTeachers as $sst)
                                                <li>
                                                    المادة: {{ $sst->subject->name ?? '-' }} -
                                                    الشعبة: {{ $sst->section->name ?? '-' }} -
                                                    المعلم: {{ $sst->teacher->full_name ?? '-' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr> --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- مودالات إضافية --}}
            
                @include('admin.Student.add_subject_to_student')
                @include('admin.Student.alert')
            </div>
        </div>
    </div>

@endsection
@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#student_table').DataTable({
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
$('select[name="student_table_length"]').attr('id', 'show');
    });

    // بحث ديناميكي
    $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
    });

    // فلترة الفوج حسب النص الظاهر
    $('#filterWorkingHour').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = ''; 
        table.column(4).search(val).draw(); 
    });

    // فلترة المرحلة حسب النص الظاهر
    $('#filterStage').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = '';
        table.column(5).search(val).draw(); 
    });


    $('#filterclassroom').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = '';
        table.column(6).search(val).draw(); 
    });
    // إعادة تعيين الفلاتر
    $('#resetFilters').on('click', function() {
        $('#filterWorkingHour').val('الكل');
        $('#filterStage').val('الكل');
         $('#filterclassroom').val('الكل');
        table.search('').columns().search('').draw();
    });
});
</script>
@endpush
