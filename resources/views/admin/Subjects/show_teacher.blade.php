@extends('layout.admin.dashboard')


@section('content')

    <style>
        .subject-icon {
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
        .badge-subject {
            font-size: 0.85rem;
            padding: 0.35em 0.65em;
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
            <i class="fas fa-chalkboard-teacher me-2"></i>
            معلمو مادة {{ $subject->name }}
          </h2>
          <!-- تم تحويل زر العودة إلى رابط ثابت -->
          <a href="{{ route('subject.index') }}" class="btn back-btn">
            <i class="fas fa-arrow-right me-2"></i> العودة إلى المواد
          </a>
        </div>

        <!-- بطاقات المعلمين (محتوى ثابت) -->
        <div class="row" id="teachersContainer">

          @foreach ($teachers as $teacher) 
             <div class="col-md-6 mb-4">
            <div class="card teacher-card h-100">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4 text-center">
                                        <img src="{{ asset('storage/' . $teacher->image_path) }}" alt="صورة المعلم" class="teacher-photo me-2 protected-data" style="width: 100px; height:100px; border-radius: 50%;">
                  
                  </div>
                  <div class="col-md-8 text-center">
                    <h4> {{ $teacher->full_name }}</h4>
                    <p><strong>التخصص:</strong> {{ $teacher->specialization }}</p>
                    <p><strong>الخبرة:</strong> {{ $teacher->experience }} سنوات</p>
                    <p><strong>الصفوف:</strong>
                       @foreach ( $teacher->sections as $section)
                        <span class="badge badge-subject bg-secondary">  {{ $section->classroom->name }}   {{ $section->name  }}   </span>
                
                    @endforeach </p>
                    <a href="mailto:{{ $teacher->email }}" class="btn btn-outline-primary btn-sm mt-2">
                      <i class="fas fa-envelope me-1"></i> التواصل
                    </a>

                       <a href="https://wa.me/{{ $teacher->phone }} " class="btn btn-outline-primary btn-sm mt-2">
                      <i class="fab fa-whatsapp me-1"></i> واتساب
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          @endforeach
         
         
        </div>
      </div>
    </main>
  </div>
@endsection