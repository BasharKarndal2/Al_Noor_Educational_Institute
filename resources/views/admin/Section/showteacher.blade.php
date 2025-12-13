@extends('layout.admin.dashboard')

@section('content')

<style>
    .badge-subject {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }
    .teacher-photo {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #dee2e6;
    }
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
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0">
            <i class="fas fa-chalkboard-teacher me-2"></i>
            معلمو الشعبة {{ $section->name }}
        </h2>
        <a href="{{ route('section.index') }}" class="btn back-btn">
            <i class="fas fa-arrow-right me-2"></i> العودة إلى الشُعب
        </a>
    </div>

    <div class="row" id="teachersContainer">
        @foreach($teachers as $teacherData)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex align-items-center">
                        <!-- صورة المعلم -->
                        <div class="me-3 text-center">
                            <img src="{{ asset('storage/' . $teacherData['teacher']->image_path) }}" 
                                 alt="صورة {{ $teacherData['teacher']->full_name }}" 
                                 class="teacher-photo">
                        </div>
                        <!-- بيانات المعلم -->
                        <div>
                            <h4>{{ $teacherData['teacher']->full_name }}</h4>
                            <p><strong>المواد:</strong>
                                @foreach($teacherData['subjects'] as $subject)
                                    <span class="badge badge-subject bg-primary">{{ $subject->name }}</span>
                                @endforeach
                            </p>
                            <p><strong>التواصل:</strong>
                                <a href="mailto:{{ $teacherData['teacher']->email }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-envelope me-1"></i> إيميل
                                </a>
                                <a href="https://wa.me/{{ $teacherData['teacher']->phone }}" class="btn btn-outline-success btn-sm">
                                    <i class="fab fa-whatsapp me-1"></i> واتساب
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@endsection
