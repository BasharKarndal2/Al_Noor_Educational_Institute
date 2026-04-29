@extends('layout.admin.dashboard')


@section('content')
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-users me-2"></i>إدارة الصفوف الدراسية</h2>
        <!-- زر فتح المودال الصحيح -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClassroomModal" id="addClassroombuttun">
            <i class="fas fa-plus me-2"></i>إضافة صف جديد
        </button>
    </div>
@include('admin.Classroom.create')
@include('admin.Classroom.edit')
 @include('admin.Classroom.filter')
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الصفوف</h5>
            <div>
        <button id="exportExcel"
        class="btn btn-success btn-sm mb-2"
        data-table-id="ClassroomTable"
        data-filename="بيانات الصفوف  الدراسية">
  <i class="fas fa-file-excel"></i> تصدير Excel
</button>
<!-- زر تصدير PDF -->
<button id="generatepdf"
        class="btn btn-danger btn-sm mb-2"
        data-table-id="ClassroomTable"
        data-report-title="تقرير عن بيانات الصفوف الدراسية"
        data-filename="تقرير الصفوف الدراسية ">
  <i class="fas fa-file-pdf"></i> تصدير PDF
</button>   

</div>
        </div>
        <div class="card-body text-center">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="ClassroomTable">
                    <thead>
                        <tr >
                            <th width="50">#</th>
                            <th>اسم الصف</th>
                             <th> فوج الصف</th>
                                <th> مرحلة الصف </th>
                            <th>الحالة</th>
                            <th>عدد الطلاب</th>
                            <th>الملاحظات</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classrooms as $classroom)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $classroom->name }}</td>
                       <td>{{ $classroom->educationalStage->working_hour->name ?? 'غير محدد' }}</td>

                           <td>{{ $classroom->educationalStage->name ?? 'غير محدد' }}</td>
           <td>
    @if($classroom->status === 'active')
        <span class="badge bg-success">نشط</span>
    @else
        <span class="badge bg-danger">غير نشط</span>
    @endif
</td>
   <td>
            {{ $classroom->sections->sum(fn($section) => $section->students->count()) }}
        </td>
                            <td>{{ $classroom->note }}</td>
                            <td class="action-btns">
                              <button 
    class="btn btn-info btn-sm view-group-btn" 
    data-id="{{  $classroom->id}}"

    data-bs-toggle="modal" 
    data-bs-target="#viewClassroomModal">
    <i class="fas fa-eye"></i> عرض
</button>
              <button class="btn btn-outline-warning editClassroomModal" data-id="{{ $classroom->id }}" data-bs-toggle="modal" data-bs-target="#editClassroomModal" id="editClassroombuttun">
    <i class="fas fa-edit"></i>
</button>

<!-- الزر الذي يستدعي النافذة -->
<button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $classroom->id }}"
        data-route="{{ route('classroom.destroy', ':id') }}">
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
</div>


@include('admin.aleat_delet')
@include('admin.Classroom.alert')
@include('admin.Classroom.showdata')



@endsection
@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#ClassroomTable').DataTable({
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
        $('select[name="ClassroomTable_length"]').attr('id', 'show');
    });

    // بحث ديناميكي
    $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
    });

    // فلترة الفوج حسب النص الظاهر
    $('#filterWorkingHour').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = ''; 
        table.column(2).search(val).draw(); 
    });

    // فلترة المرحلة حسب النص الظاهر
    $('#filterStage').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = '';
        table.column(3).search(val).draw(); 
    });

    // إعادة تعيين الفلاتر
    $('#resetFilters').on('click', function() {
        $('#filterWorkingHour').val('الكل');
        $('#filterStage').val('الكل');
        table.search('').columns().search('').draw();
    });
});
</script>
@endpush