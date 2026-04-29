@extends('layout.teacher.dashboard')
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
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'نجاح',
            text: "{{ session('success') }}",
            confirmButtonText: 'موافق',
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif

@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: "{{ session('error') }}",
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#d33'
        });
    </script>
@endif

@include('Teacher.Evaluation.create')

                 <h2 class="page-title"><i class="bi bi-clipboard-data"></i> تقييم الطلاب</h2>
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#enter-grades">إدخال الدرجات</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#view-grades">عرض التقييمات</a>
                </li>
            </ul>
            <div class="tab-content mt-3">
                <!-- Enter Grades Tab -->
                <div class="tab-pane fade show active" id="enter-grades">
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAssessmentteacherModal"><i class="bi bi-plus"></i> إضافة تقييم جديد</button>
                    <div class="card">
                        <div class="card-header bg-primary">  @if($last_Evaluation)
    {{ $last_Evaluation->title }}
@else
    لا يوجد تقييمات
@endif</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>اسم الطالب</th>
                                            <th>رقم الطالب</th>
                                            <th>الدرجة</th>
                                            <th>ملاحظات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gradesTable">
                                        @foreach ($lasetresults as $lasetresult)
                                             <tr>
                                            <td>{{  $lasetresult->student->name }}</td>
                                            <td>{{ $lasetresult->student->id }}</td>
                                            <td>  {{ $lasetresult->grade}}</td>
                                             <td>  {{ $lasetresult->feedback}}</td>
                                        </tr>
                                        @endforeach
                                        
                                       
                                        
                                    </tbody>
                                </table>
                            </div>
                           
                        </div>
                    </div>
                </div>
                <!-- View Grades Tab -->
                <div class="tab-pane fade" id="view-grades">
                    <div class="card">
                        <div class="card-header bg-primary">عرض التقييمات</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="evalution_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>عنوان التقييم</th>
                                            <th> الصف</th>
                                            <th>نوع التقييم</th>
                                            <th> تكرار </th>
                                            <th> المادة</th>
                                        
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="assessmentsTable">
                                      

                                             @foreach ($Evaluations as $Evaluation )
<tr>
                                             <td> {{ $Evaluation->id }}</td>
                                             <td>{{ $Evaluation->title }}</td>
                                                  <td>{{ $Evaluation->section->classroom->name }}</td>
                                                   <td>{{ $Evaluation->type }}</td>
                                                    <td>{{ $Evaluation->frequency }}</td>
                                                     <td>{{ $Evaluation->subject->name }}</td>
<td>




                                                        <button 
    class="btn btn-info btn-sm viewAssessmentBtn" 
    data-id="{{  $Evaluation->id}}"
    data-bs-toggle="modal" 
    data-bs-target="#viewAssessmentteacherModal">
    <i class="fas fa-eye"></i> عرض
</button>
              <button class="btn btn-outline-warning editEvaluationBtn" data-id="{{ $Evaluation->id }}" data-bs-toggle="modal" data-bs-target="#editEvaluationModal" id="editEvaluationBtn">
    <i class="fas fa-edit"></i>
</button>

<!-- الزر الذي يستدعي النافذة -->
<button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $Evaluation->id }}"
        data-route="{{ route('teacherevaluation.destroy', ':id') }}">
    <i class="fas fa-trash"></i>
</button>
    
</td>
   
                                             @endforeach
                                      
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
       
@include('admin.aleat_delet')
@include('Teacher.Evaluation.show')

@include('Teacher.Evaluation.edit')

    
@endsection


@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#evalution_table').DataTable({
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
        $('select[name="evalution_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush