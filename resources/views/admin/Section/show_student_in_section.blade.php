@extends('layout.admin.dashboard')

@section('content')
   <style>
        
        .class-icon {
            width: 40px;
            height: 40px;
            background-color: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #0d6efd;
            border: 2px solid #dee2e6;
        }
        .action-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin: 0 2px;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .card-header {
            font-weight: 600;
        }
        .form-label.required:after {
            content: " *";
            color: #dc3545;
        }
          /* أنماط جدول الطلاب */
        #studentsSection {
            border-top: 1px solid #eee;
            padding-top: 20px;
            margin-top: 20px;
        }

        #studentsSection h5 {
            color: #0d6efd;
            font-weight: 600;
        }

        #studentsTableBody img {
            border: 2px solid #dee2e6;
        }

        /* أنماط زر العودة */
        .back-btn {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #0d6efd;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: 500;
        }

        .back-btn:hover {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
        }

        .back-btn i {
            transition: transform 0.3s ease;
        }

        .back-btn:hover i {
            transform: translateX(5px);
        }
    </style>

<div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="page-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        <span id="classTitle">طلاب الصف {{ $section->classroom->name }} الشعبة: {{ $section->name }} </span>
                    </h2>
                    <button class="btn back-btn me-2" onclick="window.location.href='{{ route('section.index') }}'">
                        <i class="fas fa-arrow-right me-2"></i> العودة إلى الشعب
                    </button>
                </div>

                <!-- جدول الطلاب -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">قائمة طلاب الصف</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>الصورة</th>
                                        <th>اسم الطالب</th>
                                        <th>الرقم الجامعي</th>
                                       
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsTable">
                                  
                                    @foreach ($Students as $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $student->image_path) }}" alt="صورة الطالب" class="teacher-photo me-2 protected-data" style="width: 40px; height: 40px; border-radius: 50%;">
                                            </td>
                                            <td>{{ $student->name }}</td>
                                            <td>{{ $student->id }}</td>
                                            {{-- <td>{{ $student->section ? $student->section->name : '-' }}</td> --}}
                                            <td><span class="badge bg-success">{{ $student->status }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    
@endsection



