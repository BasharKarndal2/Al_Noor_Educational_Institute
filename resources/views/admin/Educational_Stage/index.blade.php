@extends('layout.admin.dashboard')


@section('content')
    
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />
@include('admin.Educational_Stage.create')

@include('admin.Educational_Stage.edit')
@include('admin.Educational_Stage.alert')
@include('admin.aleat_delet')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-users me-2"></i>إدارة المراحل الدراسية</h2>
        <!-- زر فتح المودال الصحيح -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEducational_StageModal" id="addEducational_Stagebuttun">
            <i class="fas fa-plus me-2"></i>إضافة مرحلة جديد
        </button>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الأفواج</h5>
            <div>
        <button id="exportExcel"
        class="btn btn-success btn-sm mb-2"
        data-table-id="Education_StageTable"
        data-filename="بيانات المراحل الدراسية">
  <i class="fas fa-file-excel"></i> تصدير Excel
</button>

<!-- زر تصدير PDF -->
<button id="generatepdf"
        class="btn btn-danger btn-sm mb-2"
        data-table-id="Education_StageTable"
        data-report-title="تقرير عن بيانات المراحل الدراسية"
        data-filename="تقرير مراحل الدراسية ">
  <i class="fas fa-file-pdf"></i> تصدير PDF
</button>   </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="Education_StageTable">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>اسم المرحلة</th>
                             <th> فوج المرحلة</th>
                            <th>الحالة</th>
                            <th>الملاحظات</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($educationals as $educational)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $educational->name }}</td>
                            <td>{{ $educational->working_hour->name }}</td>
                            <td>{{ $educational->status }}</td>
                            <td>{{ $educational->note }}</td>
                            <td class="action-btns">
                              <button 
    class="btn btn-info btn-sm view-group-btn" 
    data-id="{{  $educational->id}}"
    data-name="{{ $educational->name }}"
    data-status="{{ $educational->status }}"
    data-notes="{{ $educational->note }}"
 
    data-stage="{{ $educational->working_hour->name }}"
    data-bs-toggle="modal" 
    data-bs-target="#viewEduModal">
    <i class="fas fa-eye"></i> عرض
</button>
              <button class="btn btn-outline-warning editEducationalStageModal" data-id="{{ $educational->id }}" data-bs-toggle="modal" data-bs-target="#editEducationalStageModal" id="editEducationalStagebuttun">
    <i class="fas fa-edit"></i>
</button>

<!-- الزر الذي يستدعي النافذة -->
<button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $educational->id }}"
        data-route="{{ route('educational_stage.destroy', ':id') }}">
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

@include('admin.Educational_Stage.showdata')




@endsection