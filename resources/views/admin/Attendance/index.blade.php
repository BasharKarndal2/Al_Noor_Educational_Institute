@extends('layout.admin.dashboard')


@section('content')
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

 <div class="admin-content text-start" id="bbb">
            <h2 class="page-title">نظام الحضور والغياب</h2>
            <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">تسجيل حضور جديد</button>
                <button class="btn btn-success" onclick="saveChanges()">حفظ التغييرات</button>
            </div>
{{-- @include('admin.Attendance.filters') --}}

 <div class="row mb-3">
                <div class="col-md-4">
                    <div class="stat-card bg-success text-white">
                        <span class="stat-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="stat-info">
                          <h3>{{ $presentPercent }}%</h3>
<p>{{ $present }} من {{ $totalStudents }} طالب</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card bg-danger text-white">
                        <span class="stat-icon"><i class="fas fa-times-circle"></i></span>
                        <div class="stat-info">
                           
<h3>{{ $absentPercent }}%</h3>
<p>{{ $absent }} من {{ $totalStudents }} طالب</p>

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card bg-warning text-white">
                        <span class="stat-icon"><i class="fas fa-clock"></i></span>
                        <div class="stat-info">
                          <h3>{{ $latePercent }}%</h3>
<p>{{ $late }} من {{ $totalStudents }} طالب</p>
                        </div>
                    </div>
                </div>
            </div>
         
 </div>
  <div class="table-responsive">
      
                <div class="d-flex justify-content-between mb-3">
                    <button class="btn btn-primary" onclick="printRecord()">طباعة</button>
                    <button class="btn btn-warning" onclick="sendNotifications()">إرسال تنبيهات</button>
                </div>
                <table class="table table-bordered" id="Attendance_table">
                    <thead>
                        <tr>
                            
                            <th>رقم السجل</th>
                            <th>الصف</th>
                            <th>الحصة</th>
                            <th> المعلم</th>
                            <th>المادة</th>
                            <th>التاريخ</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($atts as $att )
                             <tr>
                            <td>{{ $att->id   }}</td>
                            <td>{{ $att->classSchedule->section->classroom->name }}    {{ $att->classSchedule->section->name }}</td>
                            <td> الحصة:{{  $att->classSchedule->period_number }}</td>
                            <td>{{ $att->classSchedule->teacher->full_name }}</td>
                            <td>{{ $att->classSchedule->subject->name }}</td>
                            <td>{{ $att->attendance_date }}</td>
                            <td>
                                <button 
    class="btn btn-info btn-sm viewattendanceBtn" 
    data-id="{{  $att->id}}"

    data-bs-toggle="modal" 
    data-bs-target="#viewattendanceModal">
    <i class="fas fa-eye"></i> عرض
                            </button>
                                <button class="btn btn-sm btn-warning editattBtn" data-bs-toggle="modal" data-bs-target="#editAttendanceModal"   data-id="{{  $att->id}}">تعديل</button>
                               <button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $att->id }}"
        data-route="{{ route('attendance.destroy', ':id') }}">
    <i class="fas fa-trash"></i>
</button>
          
                            </td>
                        </tr>
                      
                        @endforeach
                       
                          
                       
                    </tbody>
                </table>
             
            </div>
            
@include('admin.aleat_delet')
@include('admin.Attendance.viewatt')
@include('admin.Attendance.create')
@include('admin.Attendance.edit')
@endsection





@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#Attendance_table').DataTable({
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
        $('select[name="Attendance_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush