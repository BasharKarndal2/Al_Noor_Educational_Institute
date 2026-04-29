@extends('layout.admin.dashboard')

@section('content')
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

@include('admin.Evaluation.create')
  <div class="admin-content">
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
                    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addAssessmentModal"><i class="bi bi-plus"></i> إضافة تقييم جديد</button>
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
                                <table class="table table-bordered" id="Evaluation_table">
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
    data-bs-target="#viewAssessmentModal">
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
        data-route="{{ route('evaluation.destroy', ':id') }}">
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
            </div>
        </div>
@include('admin.aleat_delet')
@include('admin.Evaluation.show')

@include('admin.Evaluation.edit')

    
@endsection



@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#Evaluation_table').DataTable({
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
        $('select[name="Evaluation_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush