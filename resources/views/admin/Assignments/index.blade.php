@extends('layout.admin.dashboard')


@section('content')
    <x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />
@include('admin.Assignments.create')
@include('admin.Assignments.edit')
        <!-- محتوى الصفحة -->
        <div class="admin-content">
            <h2 class="page-title">إدارة الواجبات</h2>

            <!-- زر إضافة واجب جديد -->
            <div class="mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                    إضافة واجب جديد
                </button>
            </div>

            <!-- جدول الواجبات -->
            <div class="card mb-4">
                <div class="card-header">
                    قائمة الواجبات والاختبارات
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="Assignments_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    
                                    <th>الصف</th>
                                    <th>المادة</th>
                                    <th>تاريخ التسليم</th>
                                    <th>المعلم</th>
                                    <th>الحالة</th>
                                    <th>ملف المرفقات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ( $assignments as  $assignment )
                                       <tr>
                                    <td>{{   $assignment->id }}</td>
                                    <td>{{  $assignment->title }}</td>
                                 
                                    <td>   {{ $assignment->section->name }}:{{ $assignment->section->classroom->name }}</td>
                                    <td>{{  $assignment->subject->name }}</td>
                                    <td>{{ $assignment->due_date }}</td>
                                    <td>أ.  {{ $assignment->teacher->full_name }}</td>
                                  <td>
    @if($assignment->status === 'active')
        <span class="badge bg-success">نشط</span>
    @else
        <span class="badge bg-danger">غير نشط</span>
    @endif
</td>

<td>
    @if($assignment->file_path)
        <a href="{{ asset('storage/' . $assignment->file_path) }}"
           class="btn btn-sm btn-info"
           download="{{ $assignment->title }}.{{ pathinfo($assignment->file_path, PATHINFO_EXTENSION) }}">
            <i class="fas fa-download"></i> تحميل الملف
        </a>
    @else
        <span class="text-muted">لا يوجد ملف</span>
    @endif
</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary viewAssignmentBtn" data-id="{{ $assignment->id }}">
    <i class="fas fa-eye"></i>
</button>
                                        <button class="btn btn-sm btn-warning editAssignmentBtn"  data-id="{{ $assignment->id }}" data-bs-toggle="modal" data-bs-target="#editAssignmentModal"><i class="fas fa-edit"></i></button>
                              <button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $assignment->id }}"
        data-route="{{ route('assignments.destroy', ':id') }}">
    <i class="fas fa-trash"></i>
</button>

<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewSubmissionsModal" 
onclick="loadSubmissions({{ $assignment->id }})">
عرض الطلاب
</button>
                                    </td>
                                </tr>
                                @empty
                                     <tr>
                                    <td>لا يوجد واجبات بعد</td>
                                    
                                </tr>
                                @endforelse
                               
                                
                            </tbody>
                        </table>
                    </div>
                    <!-- الصفحات -->
                  
                </div>
            </div>

@include('admin.Assignments.show')
@include('admin.aleat_delet')
@include('admin.Assignments.showsubmitinstudent')
@endsection



@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#Assignments_table').DataTable({
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
        $('select[name="Assignments_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush