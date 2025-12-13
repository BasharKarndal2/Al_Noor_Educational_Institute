@extends('admin.dashboard.index')

@section('content')

<!-- التنبيهات -->
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />
@include('admin.Working_hour.create')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-users me-2"></i>إدارة الأفواج</h2>
        <!-- زر فتح المودال الصحيح -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorking_hourModal">
            <i class="fas fa-plus me-2"></i>إضافة فوج جديد
        </button>
    </div>




    <!-- جدول عرض الأفواج -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الأفواج</h5>
            <div>
             <button id="exportExcel"
        class="btn btn-success btn-sm mb-2"
        data-table-id="workingHoursTable"
        data-filename="بيانات_الفوج">
  <i class="fas fa-file-excel"></i> تصدير Excel
</button>

<!-- زر تصدير PDF -->
<button id="generatepdf"
        class="btn btn-danger btn-sm mb-2"
        data-table-id="workingHoursTable"
        data-report-title="تقرير الأفواج"
        data-filename="تقرير_الأفواج">
  <i class="fas fa-file-pdf"></i> تصدير PDF
</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped" id="workingHoursTable">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>اسم الفوج</th>
                            <th>الحالة</th>
                            <th>الملاحظات</th>
                            <th>العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($working_hours as $working_hour)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $working_hour->name }}</td>
                            <td>{{ $working_hour->status }}</td>
                            <td>{{ $working_hour->note }}</td>
                            <td class="action-btns">
                              <button 
    class="btn btn-info btn-sm view-group-btn" 
    data-id="{{  $working_hour->id}}"
    data-name="{{ $working_hour->name }}"
    data-status="{{ $working_hour->status }}"
    data-notes="{{ $working_hour->note }}"
    data-bs-toggle="modal" 
    data-bs-target="#viewWorking_hourModal">
    <i class="fas fa-eye"></i> عرض
</button>
              <button class="btn btn-outline-warning editWorkinghourbutton" data-id="{{ $working_hour->id }}" data-bs-toggle="modal" data-bs-target="#editWorkinghourModal">
    <i class="fas fa-edit"></i>
</button>

<!-- الزر الذي يستدعي النافذة -->
<button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $working_hour->id }}"
        data-route="{{ route('working_hours.destroy', ':id') }}">
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


@include('admin.Working_hour.edit')
@include('admin.aleat_delet')
@include('admin.aleat')
@include('admin.Working_hour.showdata')
@include('admin.aleat', ['edit' => 'editWorkinghourModal','create'=>'addWorking_hourModal'])

@endsection

