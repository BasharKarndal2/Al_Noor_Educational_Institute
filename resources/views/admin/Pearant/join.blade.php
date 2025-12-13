@extends('layout.admin.dashboard')

@section('content')
<div class="container-fluid py-4">

    {{-- العنوان --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">
            <i class="fas fa-users me-2"></i> ربط الطالب بالأب: {{ $parent->name }}
        </h2>
    </div>

    @include('admin.Student.filter')

    {{-- جدول الطلاب --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">قائمة الطلاب</h5>
            <div>
                {{-- يمكن إضافة أزرار تصدير PDF أو Excel هنا --}}
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="student_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>اسم الطالب</th>
                            <th>الرقم الأكاديمي</th>
                            <th hidden>الفوج</th>
                            <th hidden>المرحلة الدراسية</th>
                            <th>الصف</th>
                            <th>الشعبة</th>
                            <th>ولي الأمر</th>
                            <th>الحالة</th>
                            <th>البريد الإلكتروني</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $student->image_path) }}"
                                     alt="صورة الطالب"
                                     class="teacher-photo me-2 protected-data"
                                     style="width: 40px; height: 40px; border-radius: 50%;">
                            </td>
                            <td>{{ $student->name ?? '-' }}</td>
                            <td>{{ $student->id ?? '-' }}</td>

                            {{-- الفوج --}}
                            <td hidden>
                                @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                    {{ $section->classroom->educationalStage->working_hour->name ?? '-' }}
                                @endforeach
                            </td>

                            {{-- المرحلة الدراسية --}}
                            <td hidden>
                                @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                    {{ $section->classroom->educationalStage->name ?? '-' }}
                                @endforeach
                            </td>

                            {{-- الصف --}}
                            <td>
                                @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                    {{ $section->classroom->name ?? '-' }}
                                @endforeach
                            </td>

                            {{-- الشعبة --}}
                            <td>
                                @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                    {{ $section->name ?? '-' }}
                                @endforeach
                            </td>

                            {{-- ولي الأمر --}}
                            <td>{{ $student->parent->name ?? '-' }}</td>

                            {{-- الحالة --}}
                            <td>
                                <span class="badge bg-success">{{ $student->status }}</span>
                            </td>

                            {{-- البريد الإلكتروني --}}
                            <td>{{ $student->email ?? '-' }}</td>

                            {{-- الإجراءات --}}
                            <td class="action-btns">
                              

                                {{-- زر ربط الطالب بالأب --}}
                                <form method="POST" action="{{ route('students.link.store', $student->id) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $parent->id }}">
                                    <button type="submit" class="btn btn-sm btn-success mt-1">
                                        <i class="fas fa-link me-1"></i> ربط بالأب
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#student_table').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]]
    });

    // تغيير id للـ select تبع عدد الصفوف
    table.on('init', function() {
        $('select[name="student_table_length"]').attr('id', 'show');
    });

    // بحث ديناميكي
    $('#searchInput').on('keyup', function () {
        table.search(this.value).draw();
    });

    // فلترة الفوج حسب النص الظاهر
    $('#filterWorkingHour').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = ''; 
        table.column(4).search(val).draw(); 
    });

    // فلترة المرحلة حسب النص الظاهر
    $('#filterStage').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = '';
        table.column(5).search(val).draw(); 
    });

    // فلترة الصف
    $('#filterclassroom').on('change', function() {
        var val = $(this).find('option:selected').text();
        if(val === 'الكل') val = '';
        table.column(6).search(val).draw(); 
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
