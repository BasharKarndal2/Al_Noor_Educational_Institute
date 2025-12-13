@extends('layout.admin.dashboard')

@section('content')

<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />
@include('admin.Teacher.create')
@include('admin.Teacher.edit')

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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="page-title mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>إدارة المعلمين</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal" id="addTeacherBtn">
                        <i class="fas fa-plus me-2"></i>إضافة معلم جديد
                    </button>
                </div>

@include('admin.Teacher.filter')
                <!-- جدول المعلمين -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">قائمة المعلمين</h5>
                        <div>
                            <button class="btn btn-sm btn-light me-2" id="exportExcel">
                                <i class="fas fa-file-excel me-1"></i> تصدير
                            </button>
                            <button class="btn btn-sm btn-light" id="printBtn">
                                <i class="fas fa-print me-1"></i> طباعة
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="teachersTable" class="table table-hover table-striped w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>صورة</th>
                                        <th>المعلم</th>
                                        <th>رقم الهوية</th>
                                        <th>التخصص</th>
                                        <th>سنوات الخبرة</th>
                                        <th>الحالة</th>
                                        <th>تاريخ التعيين</th>
                                        <th width="120">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>

                                              @foreach ($teachers as $teacher)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                        <img src="{{ asset('storage/' . $teacher->image_path) }}" alt="صورة المعلم" class="teacher-photo me-2 protected-data" style="width: 40px; height: 40px; border-radius: 50%;">
                                        </td>
                                        <td class="protected-data">{{ $teacher->full_name }}</td>
                                        <td >{{ $teacher->national_id }}</td>
                                        <td>{{ $teacher->specialization }}</td>
                                        <td>{{ $teacher->experience }}</td>
                                        <td><span class="badge bg-success">{{ $teacher->status }}</span></td>
                                        <td>{{ $teacher->hire_date }}</td>
                                        <td class="action-btns">
                                           <button class="btn btn-info btn-sm view_teacher_btn" data-id="{{ $teacher->id }}">
    <i class="fas fa-eye"></i> 
</button>
                                       
                                            <button class="btn btn-sm btn-outline-warning  editTeacherModal" title="تعديل"  data-id="{{ $teacher->id }}" data-bs-toggle="modal" data-bs-target="#editTeacherModal" id="editteacherbtn">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                             <button class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal"
                                                data-id="{{ $teacher->id }}"
                                                data-route="{{ route('teaher.destroy', ':id') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>


                                     <button class="btn btn-sm btn-outline-primary addsubject_to_sectionModal" 
        title="اضافة مواد الى معلم "  
      data-id="{{ $teacher->id }}"
        data-bs-toggle="modal" 
        data-bs-target="#addsubject_to_teacherModal"
        id="addsubject_to_teacherBtn">
    <i class="fas fa-book "></i> 
</button>

<button class="btn btn-sm btn-outline-primary addsubject_to_sectionModalss" 
        title="إضافة الى الشعبة"  
      data-id="{{ $teacher->id }}"
        data-bs-toggle="modal" 
        data-bs-target="#addteacher_to_sectionModalss"
  
        id="addsubject_to_sectionBtns">
    <i class="fas fa-school"></i> 
</button>

<button class="btn btn-sm btn-outline-danger manage-subjects-btn" 
        data-bs-toggle="modal" 
        data-bs-target="#manageTeacherSubjectsModal"
        data-id="{{ $teacher->id }}">
    <i class="fas fa-cogs"></i>
</button>
                                        </td>
                                    </tr>
                                          @endforeach
                                    <!-- يمكن إضافة المزيد من الصفوف هنا -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @include('admin.Teacher.removesubject')
        @include('admin.Teacher.showdata')
            @include('admin.aleat_delet')
@include('admin.Teacher.add_teacher_to_section')
@include('admin.Teacher.add_subject_to_teacher')

@include('admin.Teacher.alert')
@endsection

@push('scripts')
<script>
       $(document).ready(function() {
            var table = $('#teachersTable').DataTable({
                language: {
                     url: '{{ asset('js/datatables/ar.json') }}'
                },
                responsive: true,
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                pageLength: 10,
                order: [[0, 'asc']]
            });

            // البحث في الجدول
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                table.search($('#searchInput').val()).draw();
            });

            // تصفية حسب التخصص
            $('#specializationFilter').on('change', function() {
                   table.column(4).search('^' + this.value + '$', true, false).draw();
            });

            // تصفية حسب الحالة
           $('#statusFilter').on('change', function() {
    table.column(6).search('^' + this.value + '$', true, false).draw();
 // ← العمود 6 = الحالة
});

            // إعادة تعيين الفلاتر
            $('#resetFilters').on('click', function() {
                $('#searchInput').val('');
                $('#specializationFilter').val('');
                $('#statusFilter').val('');
                table.search('').columns().search('').draw();
            });});
        </script>
@endpush