@extends('layout.admin.dashboard')

@section('content')

{{-- Alerts --}}
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

{{-- Modals --}}
@include('admin.Subjects.create')
@include('admin.Subjects.edit')
@include('admin.Subjects.alert')
@include('admin.Subjects.add_subject_to_section')

@include('admin.aleat_delet')

<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-book me-2"></i>إدارة المواد الدراسية</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
            <i class="fas fa-plus me-2"></i>إضافة مادة جديدة
        </button>
    </div>

    {{-- Search --}}
    @include('admin.Subjects.serch')

    {{-- Subjects Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة المواد الدراسية</h5>
            <button class="btn btn-sm btn-light" id="exportExcel">
                <i class="fas fa-file-excel me-1"></i>تصدير
            </button>
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
                            <th>ملاحظات</th>
                            <th width="250">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $subject->name }}</td>
                                <td>{{ $subject->number_se }}</td>
                                <td>{{ $subject->status }}</td>
                                <td>{{ $subject->note }}</td>
                                <td class="action-btns">
                                    {{-- View --}}
                                    <button class="btn btn-sm btn-outline-primary view_subject_btn"
                                        title="عرض"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewSubjectModal"
                                        data-id="{{ $subject->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    {{-- Edit --}}
                                    <button class="btn btn-sm btn-outline-warning edit-subject-btn"
                                        title="تعديل"
                                        data-id="{{ $subject->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSubjectModal">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Delete --}}
                                    <button class="btn btn-sm btn-outline-danger delete-subject-btn"
                                        title="حذف"
                                        data-id="{{ $subject->id }}"
                                        data-route="{{ route('subject.destroy', ':id') }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    {{-- Add to Section --}}
                                    <button class="btn btn-sm btn-outline-primary add-subject-to-section-btn"
                                        title="إضافة الى الشعبة"
                                        data-id="{{ $subject->id }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addsubject_to_sectionModalss">
                                        <i class="fas fa-school"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">لا توجد مواد حتى الآن</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

 
@include('admin.Subjects.show_all_data')
</div>

@endsection
