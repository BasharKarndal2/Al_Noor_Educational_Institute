@extends('layout.parent.dashboard')


@section('conten')
    
<style>

    .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* تحسين التمرير على الأجهزة المحمولة */
}

.table {
    min-width: 800px; /* الحد الأدنى لعرض الجدول لضمان الحاجة إلى التمرير */
    direction: rtl; /* دعم الاتجاه من اليمين إلى اليسار */
}

.table th, .table td {
    white-space: nowrap; /* منع التفاف النص */
    padding: 8px; /* هوامش مناسبة */
}

/* تنسيقات للشاشات الصغيرة */
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 14px; /* تقليل حجم الخط */
        padding: 6px; /* تقليل الهوامش */
    }
}

@media (max-width: 576px) {
    .table th, .table td {
        font-size: 12px; /* تقليل حجم الخط أكثر */
        padding: 4px; /* تقليل الهوامش أكثر */
    }
}
</style>
    <div class="container-fluid py-4">

   
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">
                <i class="fas fa-users me-2"></i> أبنائي
            </h2>
          
        </div>

  
        {{-- جدول الطلاب --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">قائمة الأبناء</h5>
                <div>
                
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle" id="student_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>الاسم </th>
                                <th>الرقم الأكاديمي</th>
                                <th hidden> الفوج</th>
                                <th hidden> المرحلة الدراسية</th>

                                <th>الصف</th>
                                <th>الشعبة</th>
                                <th>ولي الأمر</th>
                                <th>الحالة</th>
                                <th> البريد الإلكتروني</th>
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
                                    <td>{{ $student->name?? '-' }}</td>
                                    <td>{{ $student->id?? '-' }}</td>
                                    <td hidden>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{ $section->classroom->educationalStage->working_hour->name?? '-' }}
                                        @endforeach
                                    </td>
                                    <td hidden>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{$section->classroom->educationalStage->name?? '-' }}
                                        @endforeach
                                    </td >

                                    {{-- الصف --}}
                                    <td>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{ $section->classroom->name?? '-' }}
                                        @endforeach
                                    </td>

                                    {{-- الشعبة --}}
                                    <td>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{ $section->name?? '-' }}
                                        @endforeach
                                    </td>

                                    {{-- ولي الأمر (افتراضاً) --}}
                                    <td>
                                        {{ $student->parent->name ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-success">{{ $student->status }}</span>
                                    </td>
                                    <td>{{ $student->email }}</td>

                                  <td>
                                                    <button class="btn btn-sm btn-primary viewStudent" data-id="{{ $student->id }}" data-bs-toggle="modal" data-bs-target="#viewChildModal"><i class="fas fa-eye"></i> عرض</button>

                                  </td>
                                </tr>

                          
                            @endforeach
                        </tbody>
                    </table>
                </div>

         
            </div>
        </div>
    </div>
@include('parent.datachiled')
@endsection