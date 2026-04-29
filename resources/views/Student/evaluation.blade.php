@extends('layout.Student.dashboard')

@section('conten')

<style>
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.table {
    min-width: 800px;
    direction: rtl;
}
.table th, .table td {
    white-space: nowrap;
    padding: 8px;
}
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 14px;
        padding: 6px;
    }
}
@media (max-width: 576px) {
    .table th, .table td {
        font-size: 12px;
        padding: 4px;
    }
}
</style>

<h2 class="page-title">التقييمات</h2>

@php
    $types = [
        'quiz' => ['label' => 'اختبار قصير', 'class' => 'bg-info'],
        'exam' => ['label' => 'امتحان', 'class' => 'bg-danger'],
        'assignment' => ['label' => 'واجب', 'class' => 'bg-primary'],
        'project' => ['label' => 'مشروع', 'class' => 'bg-warning text-dark'],
        'activity' => ['label' => 'نشاط', 'class' => 'bg-success'],
        'participation' => ['label' => 'مشاركة', 'class' => 'bg-secondary'],
    ];

    $frequencies = [
        'daily' => ['label' => 'يومي', 'class' => 'bg-success'],
        'weekly' => ['label' => 'أسبوعي', 'class' => 'bg-primary'],
        'monthly' => ['label' => 'شهري', 'class' => 'bg-warning text-dark'],
    ];
@endphp

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        تفاصيل التقييمات
    </div>
    <div class="card-body table-responsive">
        <table class="table table-hover text-center align-middle" id="EvaluationsTable">
            <thead>
                <tr>
                    <th>عنوان التقييم</th>
                    <th>التاريخ</th>
                    <th>المعلم</th>
                    <th>المادة</th>
                    <th>النوع</th>
                    <th>التكرار</th>
                    <th>الدرجة</th>
                    <th>الملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($eval as $e)
                    <tr>
                        <td>{{ $e->evaluation->title ?? '-' }}</td>
                        <td>{{ $e->evaluation->evaluation_date ?? '-' }}</td>
                        <td>{{ $e->evaluation->teacher->full_name ?? '-' }}</td>
                        <td>{{ $e->evaluation->subject->name ?? '-' }}</td>
                        <td>
                            @php
                                $type = $types[$e->evaluation->type] ?? ['label' => $e->evaluation->type, 'class' => 'bg-secondary'];
                            @endphp
                            <span class="badge {{ $type['class'] }}">{{ $type['label'] }}</span>
                        </td>
                        <td>
                            @php
                                $freq = $frequencies[$e->evaluation->frequency] ?? ['label' => $e->evaluation->frequency, 'class' => 'bg-secondary'];
                            @endphp
                            <span class="badge {{ $freq['class'] }}">{{ $freq['label'] }}</span>
                        </td>
                        <td>{{ $e->grade ?? '-' }}%</td>
                        <td>{{ $e->feedback ?? 'لا يوجد' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('stack')
<!-- jQuery & DataTables -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#EvaluationsTable').DataTable({
        responsive: true,
        pageLength: 10,
        language: { url: '{{ asset('js/datatables/ar.json') }}' },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]],
    });
});
</script>
@endpush
