@extends('layout.admin.dashboard')



@section('content')
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-users me-2"></i>إدارة الصفوف الدراسية</h2>
        <!-- زر فتح المودال الصحيح -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal" id="addQuestionbuttun">
            <i class="fas fa-plus me-2"></i>إضافة اسئلة جديد
        </button>
    </div>
@include('admin.Question.create')

<table class="table" id="Question_table">
    <thead>
        <tr>
            <th>السؤال</th>
            <th>المادة</th>
            <th>الصف</th>
            <th>الإجرائيات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($questions as $question)
            <tr>
                <td>{{ $question->question_text }}</td>
                <td>{{ $question->sectionSubject->subject->name ?? 'غير متوفر' }}</td>
                <td>{{ $question->sectionSubject->section->classroom->name ?? 'غير متوفر' }}</td>
                <td>    <button class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                    data-bs-target="#confirmDeleteModal"
                                                data-id="{{ $question->id }}"
                                                data-route="{{ route('questins.destroy', ':id') }}">
                                        <i class="fas fa-trash"></i>
                                    </button></td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('admin.aleat_delet')


@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#Question_table').DataTable({
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
        $('select[name="Question_table_length"]').attr('id', 'show');
    });
});



</script>
@endpush

