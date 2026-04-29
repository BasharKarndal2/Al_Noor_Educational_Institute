@extends('layout.Student.dashboard')

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
 <style>
        /* أنماط عامة للوحة التحكم */
        .student-dashboard {
            background-color: #193248;
            min-height: 100vh;
            font-family: 'Tajawal', sans-serif;
            padding-top: 56px;
        }

        .navbar {
            background-color: #2c3e50;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 1rem;
        }

        .student-wrapper {
            display: flex;
        }

        .student-sidebar {
            width: 250px;
            background-color: #34495e;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            padding: 20px 0;
            position: fixed;
            height: calc(100vh - 60px);
            overflow-y: auto;
        }

        .student-sidebar .nav-link {
           padding: 10px 20px;
            color: var(--light-color);
            border-right: 3px solid transparent;
            transition: all 0.3s;
        }

        .student-sidebar .nav-link:hover,
        .student-sidebar .nav-link.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-right: 3px solid #0a2131;
        }

        .student-sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-left: 10px;
        }

        .student-content {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: calc(100vh - 56px);
        }

        .page-title {
            color: #2c3e50;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .page-title i {
            margin-left: 8px;
        }

        /* بطاقات الإحصائيات */
        .stat-card {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            height: 100%;
            color: white;
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-left: 15px;
            opacity: 0.8;
        }

        .stat-info h3 {
            font-size: 1.8rem;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .stat-info p {
            margin-bottom: 5px;
            opacity: 0.9;
        }

        /* ألوان البطاقات */
        .bg-primary {
            background-color: #3498db !important;
        }

        .bg-warning {
            background-color: #f39c12 !important;
        }

        .bg-success {
            background-color: #2ecc71 !important;
        }

        /* الكروت */
        .card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            padding: 15px 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            color: white;
        }

        .card-header i {
            margin-left: 8px;
        }

        /* عناصر الواجبات */
        .homework-item {
            display: flex;
            padding: 15px;
            margin-bottom: 15px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-right: 4px solid #ddd;
        }

        .homework-item.urgent {
            border-right-color: #e74c3c;
        }

        .homework-item.completed {
            border-right-color: #2ecc71;
        }

        .homework-icon {
            font-size: 1.8rem;
            margin-left: 15px;
            color: #7f8c8d;
            align-self: flex-start;
        }

        .homework-content {
            flex: 1;
        }

        .homework-meta span {
            margin-left: 15px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .homework-desc {
            color: #34495e;
            margin: 10px 0;
        }

        .homework-grade {
            color: #34495e;
            font-weight: 600;
            margin: 10px 0;
        }

        /* تعديلات للشريط العلوي */
        .navbar-brand {
            margin-left: auto;
        }

        .navbar .dropdown-menu {
            left: auto !important;
            right: 0 !important;
        }

        /* أنماط الـ Modal */
        .modal-header {
            color: white;
        }

        .assignment-details .detail-item {
            margin-bottom: 1rem;
        }

        .assignment-details .detail-label {
            font-weight: 600;
            color: #555;
            display: inline-block;
            width: 120px;
        }

        .assignment-details .detail-value {
            color: #333;
        }

        .assignment-details .section-title {
            font-size: 1.1rem;
            color: #3498db;
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }

        .assignment-details .section-content {
            color: #444;
            line-height: 1.6;
        }

        .attachment-item {
            display: block;
            padding: 0.5rem;
            border: 1px dashed #ddd;
            border-radius: 5px;
            color: #3498db;
            text-decoration: none;
            transition: all 0.3s;
        }

        .attachment-item:hover {
            background-color: #f8f9fa;
            border-color: #3498db;
        }

        /* responsive */
        @media (max-width: 768px) {
            .student-wrapper {
                flex-direction: column;
            }
            
            .student-sidebar {
                width: 100%;
                min-height: auto;
                position: static;
            }
            
            .student-content {
                min-height: auto;
            }
        }
    </style>

 <div class="container-fluid py-4">
                <h2 class="page-title mb-4"><i class="fas fa-book me-2"></i>الواجبات المدرسية</h2>
@php
use App\Models\Assignment_submissions;
use Carbon\Carbon;

$newAssignments = 0;
$nearDueAssignments = 0;
$completedAssignments = 0;
$homeworkList = [];

foreach($studentData as $sst) {
    foreach($sst['assignments'] as $assignment) {
        $due = Carbon::parse($assignment['due_date']);
        $now = Carbon::now();

        $submitted = Assignment_submissions::where('assignment_id', $assignment['id'])
                                        ->where('student_id', $student->id)
                                        ->exists();

        if($submitted) {
            $status = 'submitted';
            $completedAssignments++;
        } elseif($assignment['status'] == 'active' && $due->diffInDays($now) <= 3) {
            $status = 'urgent';
            $nearDueAssignments++;
        } elseif($assignment['status'] == 'active') {
            $status = 'active';
            $newAssignments++;
        } else {
            $status = 'inactive';
            $completedAssignments++;
        }

        $homeworkList[] = [
            'id'=> $assignment['id'],
            'title' => $assignment['title'],
            'subject' => $sst['subject_name'],
            'teacher' => $sst['teacher_name'],
            'due_date' => $due->format('Y-m-d H:i'),
            'status' => $status,
            'desc' => $assignment['description'] ?? '',
        ];
    }
}
@endphp
<!-- إحصائيات الواجبات -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-primary">
            <div class="stat-icon"><i class="fas fa-tasks"></i></div>
            <div class="stat-info">
                <h3>{{ $newAssignments }}</h3>
                <p>واجبات جديدة</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-warning">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h3>{{ $nearDueAssignments }}</h3>
                <p>قريبة من الموعد</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card bg-success">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <div class="stat-info">
                <h3>{{ $completedAssignments }}</h3>
                <p>واجبات مكتملة</p>
            </div>
        </div>
    </div>
</div>

<!-- قائمة الواجبات -->
<div class="homework-list">
 @foreach($homeworkList as $hw)
    <div class="homework-item 
        {{ $hw['status'] == 'submitted' ? 'completed' : ($hw['status'] == 'urgent' ? 'urgent' : '') }}">
        
        <div class="homework-icon">
            <i class="fas 
                {{ $hw['status'] == 'submitted' ? 'fa-check-circle text-success' : 
                   ($hw['status'] == 'urgent' ? 'fa-exclamation-circle text-danger' : 'fa-clock text-warning') }}">
            </i>
        </div>

        <div class="homework-content">
            <div class="d-flex justify-content-between">
                <h5>{{ $hw['title'] }}</h5>
                @if($hw['status'] == 'submitted')
                    <span class="badge bg-success">تم التسليم</span>
                @elseif($hw['status'] == 'urgent')
                    <span class="badge bg-danger">عاجل</span>
                @endif
            </div>

            <div class="homework-meta">
                <span><i class="fas fa-book me-1"></i>{{ $hw['subject'] }}</span>
                <span><i class="fas fa-user me-1"></i>{{ $hw['teacher'] }}</span>
                @if($hw['status'] != 'submitted')
                    <span><i class="fas fa-clock me-1"></i>{{ $hw['due_date'] }}</span>
                @endif
            </div>

            <p class="homework-desc">{{ $hw['desc'] }}</p>

            <button class="btn btn-sm btn-outline-primary viewAssignmentdsBtn"  
                data-id="{{ $hw['id'] }}" 
                data-bs-toggle="modal" 
                data-bs-target="#assignmentDetailsModal">
                <i class="fas fa-eye me-1"></i>عرض التفاصيل
            </button>
        </div>
    </div>
@endforeach
</div>

@include('Student.assignment.send')

@endsection