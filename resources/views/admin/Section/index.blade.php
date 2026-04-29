@extends('layout.admin.dashboard')



@section('content')
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

@include('admin.Section.create')
@include('admin.Section.edite')
   <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="page-title mb-0"><i class="fas fa-school me-2"></i>إدارة الشعب</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSectionModal" id="addSectionBtn">
                        <i class="fas fa-plus me-2"></i>إضافة شعبة جديدة
                    </button>
                </div>

          @include('admin.Section.serch')

                
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">قائمة الشعب</h5>
                        <div>
                                 <button id="exportExcel"
        class="btn btn-success btn-sm mb-2"
        data-table-id="Section_Tabel"
        data-filename="بيانات الصفوف  الدراسية">
  <i class="fas fa-file-excel"></i> تصدير Excel
</button>
<!-- زر تصدير PDF -->
<button id="generatepdf"
        class="btn btn-danger btn-sm mb-2"
        data-table-id="Section_Tabel"
        data-report-title="تقرير عن بيانات الصفوف الدراسية"
        data-filename="تقرير الصفوف الدراسية ">
  <i class="fas fa-file-pdf"></i> تصدير PDF
</button> 
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="Section_Tabel" class="table table-hover table-striped w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="50">#</th>
                                        <th>اسم الشعبة</th>
                                        <th>الفوج</th>
                                        <th>المرحلة</th>
                                  
                                          <th>الصف</th>
                                            <th>عدد الطلاب</th>
                                        <th>الحالة</th>
                                        <th width="120">إجراءات</th>
                                    </tr>
                                </thead>
                                <tbody id="section-body">
                                     @foreach ($sections as $section)
                                    <tr>
                                         <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="class-icon me-2">
                                                    <i class="fas fa-chalkboard"></i>
                                                </div>
                                                <span>{{ $section->name }} </span>
                                            </div>
                                        </td>
                                        <td>{{  $section->classroom->educationalStage->working_hour->name }}</td>
                                        <td>{{ $section->classroom->educationalStage->name }}</td>
                                     
                                         
                                        <td>{{ $section->classroom->name }}</td>
                                        <td>{{ $section->students->pluck('id')->unique()->count() }} / {{ $section->maxvalue }}</td>
                                         <td>{{ $section->status }}</td>
                                    
                                        <td class="action-btns">
                                            <button class="btn btn-sm btn-outline-primary view-class-btn" title="عرض" data-bs-toggle="modal" data-bs-target="#viewClassModal" id="viewClassModalbuttons" data-id="{{ $section->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            </button>
            
                                            <button class="btn btn-sm btn-outline-warning  editSectionModal" title="تعديل"  data-id="{{ $section->id }}" data-bs-toggle="modal" data-bs-target="#editSectionModal" id="editeditSectionbtn">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                         data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal"
                                     data-id="{{ $section->id }}"
                                                data-route="{{ route('section.destroy', ':id') }}">
                                        <i class="fas fa-trash"></i>
    </button>

    <button class="btn btn-sm btn-outline-primary addsubject_to_sectionModal" 
        title="اضافة مواد الى شعبة "  
      data-id="{{ $section->id }}"
        data-bs-toggle="modal" 
        data-bs-target="#addsubject_to_sectionModal"
        id="addsubject_to_sectionBtn">
    <i class="fas fa-book "></i> 
</button>


<button class="btn btn-sm btn-outline-danger manage-subjects-btn" 
        data-bs-toggle="modal" 
        data-bs-target="#manageSubjectsModal"
        data-id="{{ $section->id }}">
    <i class="fas fa-cogs"></i>
</button>
<button type="button" class="btn btn-sm btn-outline-danger manage-subjects-btn" data-bs-toggle="modal" data-bs-target="#replaceTeacherModal" data-id="{{ $section->id }}">
    <i class="fas fa-exchange-alt"></i> 
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

@include('admin.Section.reblace')
@include('admin.Section.remove_subject_from')
@include('admin.Section.add_subject_to_section')
@include('admin.Section.show_data')
@include('admin.aleat_delet')
@include('admin.Section.alert')


            @endsection
@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#Section_Tabel').DataTable({
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
        $('select[name="Section_Tabel_length"]').attr('id', 'show');
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


    $('#filterclassroom').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = '';
        table.column(4).search(val).draw(); 
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



