<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة التحكم - المعلم | مجمع نور الهدى</title>
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- CSS الخاص بالمعلم -->
    <link rel="stylesheet" href="{{ asset('css/teacher.css') }}">
 
       <!-- JavaScript Libraries (الترتيب مهم جدًا) -->
    <!-- 1. jQuery أولًا -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- 2. Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- 3. ملفات JS الخاصة بالمعلم -->
      <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
</head>

<body class="admin-dashboard">





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




    

    @if(session('login_success'))
        <script>
            Swal.fire({
                title: '🎉 تسجيل الدخول ناجح!',
                html: '<b>مرحبًا بك في مجمع نور الهدى</b><br>سيتم تحويلك خلال لحظات...',
                icon: 'success',
                timer: 5000,
                showConfirmButton: false
            });
        </script>
    @endif

    <!-- شريط التنقل العلوي -->
    <nav class="navbar navbar-expand-lg navbar-dark admin-navbar fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="dashboard.html">
                <img src="{{ asset('images/Noor_Alhuda_logo.png') }}" alt="شعار المجمع" height="40">
                <span class="d-none d-sm-inline">لوحة المعلم</span>
            </a>
            
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                                  @if ($authUser)
                        
                        <img src="{{ asset('storage/' .$authUser->teacher->image_path) }}" alt="" class="teacher-photo me-2 protected-data" style="width: 40px; height: 40px; border-radius: 50%;">
                        {{-- <i class="fas fa-user-circle me-1"> </i> --}}
                        
                            <span id="adminName">{{ $authUser->name }} أ.</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-start">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>الملف الشخصي</a></li>
                      
                        <li><hr class="dropdown-divider"></li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                            </button>
                        </form>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- القائمة الجانبية -->
    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('teacher.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teacher.section') }}">
                        <i class="fas fa-school me-2"></i>صفوفي الدراسية
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teacher.student') }}">
                        <i class="fas fa-users me-2"></i>طلابي
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teacher.att.index') }}">
                        <i class="fas fa-clipboard-list me-2"></i>الحضور والغياب
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teacher.evaluations.index') }}">
                        <i class="fas fa-check-circle me-2"></i>تقييم الطلاب
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('teacher.Class_schedules') }}">
                        <i class="fas fa-calendar-alt me-2"></i>جدولي الدراسي
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('assignmentsteacher.index')}}">
                        <i class="fas fa-tasks me-2"></i>الواجبات 
                    </a>
                </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('examsteacher.index') }}">
                        <i class="fas fa-tasks me-2"></i>الاختبارات                   </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fas fa-envelope me-2"></i>الرسائل
                    </a>
                </li> --}}
            </ul>
        </aside>

        <!-- المحتوى الرئيسي -->
        <main class="admin-content">
            @yield('conten')
        </main>
    </div>

 
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/admin/js/index.js') }}"></script>
    <script src="{{ asset('assets/admin/js/classroom.js') }}"></script>
    <script src="{{ asset('assets/admin/js/eductional_stage.js') }}"></script>
    <script src="{{ asset('fonts/fonts.js') }}"></script>

   
        @stack('scripts')
</body>
</html>
