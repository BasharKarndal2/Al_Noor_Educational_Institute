@extends('layout.admin.dashboard')

@section('content')
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-users me-2"></i>إدارة الأسئلة</h2>
        <!-- زر فتح المودال الصحيح -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal" id="addQuestionbuttun">
            <i class="fas fa-plus me-2"></i>إضافة سؤال جديد
        </button>
    </div>

    @include('admin.Question.create')

    <div class="card shadow-sm text-start">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-table me-2"></i>قائمة الأسئلة</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-start" id="Question_table">
                    <thead class="table-dark">
                        <tr  class=' text-start'>
                            <th  class="text-center">السؤال</th>
                            <th class="text-center">المادة</th>
                            <th class="text-center">الصف</th>
                            <th class="text-center">الإجرائيات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($questions as $question)
                            <tr  class='text-start'>
                                <td class="text-center">{{ $question->question_text }}</td>
                                <td class="text-center">{{ $question->sectionSubject->subject->name ?? 'غير متوفر' }}</td>
                                <td class="text-center">{{ $question->sectionSubject->section->classroom->name ?? 'غير متوفر' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal"
                                            data-id="{{ $question->id }}"
                                            data-route="{{ route('questins.destroy', ':id') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">لا يوجد أسئلة حالياً 📚</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.aleat_delet')
</div>
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
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]]
    });

    table.on('init', function() {
        $('select[name="Question_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush
