@extends('layout.admin.dashboard')

@section('content')

<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />
@include('admin.Subjects.create')
@include('admin.Subjects.edit')
@include('admin.Subjects.alert')

  @include('admin.Subjects.add_subject_to_section')
     <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="page-title mb-0"><i class="fas fa-book me-2"></i>إدارة المواد الدراسية</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal" id="addSubjectBtn">
                        <i class="fas fa-plus me-2"></i>إضافة مادة جديدة
                    </button>
                 
                </div>
                   @include('admin.Subjects.serch')
  <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">قائمة المواد الدراسية</h5>
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
                            <table id="subjectsTable" class="table table-hover table-striped w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>المادة</th>
                                        <th>عدد الحصص</th>
                                        <th>الحالة</th>
                                        <th>  ملاحظات</th>
                                        <th width="200">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                   @foreach ($subjects as $subject)
                                    <tr>
                                         <td>{{ $loop->iteration }}</td>
                                        <td>
                                           {{  $subject->name }}
                                        </td>
                                
                                        <td>{{ $subject->number_se }}</td>
                                         <td>{{ $subject->status }}</td>
                                     <td>{{ $subject->note }}</td>
                                        <td class="action-btns">
                                            <button class="btn btn-sm btn-outline-primary view_subject_btn" title="عرض" data-bs-toggle="modal" data-bs-target="#viewSubjectModal" data-id={{ $subject->id }}>
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            </button>
        
                                            <button class="btn btn-sm btn-outline-warning  editSubjectModal" title="تعديل"  data-id="{{ $subject->id }}" data-bs-toggle="modal" data-bs-target="#editSubjectModal"  id="editsubjectbtn">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                         data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal"
                                     data-id="{{ $subject->id }}"
                                                data-route="{{ route('subject.destroy', ':id') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

<button class="btn btn-sm btn-outline-primary addsubject_to_sectionModalss" 
        title="إضافة الى الشعبة"  
      data-id="{{ $subject->id }}"
        data-bs-toggle="modal" 
        data-bs-target="#addsubject_to_sectionModalss"
  
        id="addsubject_to_sectionBtns">
    <i class="fas fa-school"></i> 
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

@include('admin.Subjects.show_all_data')

 

    <!-- Modal for viewing class details -->
    <div class="modal fade" id="viewClassModal" tabindex="-1" aria-labelledby="viewClassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewClassModalLabel"><i class="fas fa-school me-2"></i>بيانات المادة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body
              
@include('admin.aleat_delet')

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#subjectsTable').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
        },
        dom: 'lrtip'  // إخفاء مربع البحث الأصلي
    });

    // ربط مربع البحث الخارجي بالبحث في الجدول
    $('#customSearch').on('keyup', function() {
        table.search(this.value).draw();
    });
});
</script>
@endpush