    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/Noor_Alhuda_logo.png') }}">

        <title>لوحة التحكم - المدير | مجمع نور الهدى</title>
        <!-- Bootstrap RTL -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Google Fonts - Tajawal -->
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- ملفات CSS المحلية -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('alert.css') }}"> --}}
<!-- jQuery أولاً -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap و Font Awesome -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- jsPDF و AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<!-- XLSX (تصدير Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<!-- ملفات JavaScript الخاصة بالمشروع -->
<script src="{{ asset('assets/admin/js/index.js') }}"></script>
<script src="{{ asset('assets/admin/js/classroom.js') }}"></script>
<script src="{{ asset('assets/admin/js/eductional_stage.js') }}"></script>
<script src="{{ asset('fonts/fonts.js') }}"></script>

 
    </head >
    <body class="admin-dashboard text-start">
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
        <nav class="navbar navbar-expand-lg navbar-dark  fixed-top">
            <div class="container-fluid ">
                <a class="navbar-brand" href="dashboard.html">
                    <img src="{{ asset('images/Noor_Alhuda_logo.png') }}" alt="شعار المجمع" height="40" >
                    <span class="brand-text">لوحة المدير</span>
                </a>
                <div class="d-flex align-items-center">
                    <div class="dropdown me-3">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger">3</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">الإشعارات الجديدة</h6></li>
                            <li><a class="dropdown-item" href="#">طلب جديد من ولي الأمر</a></li>
                            <li><a class="dropdown-item" href="#">تقرير حضور جديد</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">عرض الكل</a></li>
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            @if ($authUser)

                              <span id="adminName">  المدير{{ $authUser->name }} أ.</span>
                      
      
                          @endif
                            <span id="adminName"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>الملف الشخصي</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>الإعدادات</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="dropdown-item">
            <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
        </button>
    </form>
</li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- القائمة الجانبية -->
        <div class="admin-wrapper">
            <aside class="admin-sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item ">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
                        </a>
                    </li>

                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('working_hours.index') }}">
                            <i class="fas fa-users me-2"></i>إدارة الأفواج اليومية                 </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('educational_stage.index') }}">
                            <i class="fas fa-users me-2"></i>إدارة المراحل الدراسية                 </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('classroom.index') }}">
                            <i class="fas fa-users me-2"></i>إدارة الصفوف الدراسية                 </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.index') }}">
                            <i class="fas fa-users me-2"></i>إدارة الطلاب
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teaher.index') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>إدارة المعلمين
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pearant.index') }}">
                            <i class="fas fa-chalkboard-teacher me-2"></i>إدارة ولي الأمر
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('section.index') }}">
                            <i class="fas fa-school me-2"></i>إدارة الشعب
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('subject.index') }}">
                            <i class="fas fa-book me-2"></i>إدارة المواد
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('class_schedule.index') }}">
                            <i class="fas fa-calendar-alt me-2"></i>الجداول الدراسية
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('questins.index') }}">
                            <i class="fas fa-calendar-alt me-2"></i>الاسئلة 
                        </a>
                    </li>
                       <li class="nav-item">
                        <a class="nav-link" href="{{ route('request.show_all_data') }}">
                            <i class="fas fa-chart-bar me-2"></i>إدارة الطلبات
                        </a>
                    </li>

                        <li class="nav-item">
                        <a class="nav-link" href="{{ route('evaluation.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>إدارة التقييمات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('attendance.index') }}">
                            <i class="fas fa-clipboard-list me-2"></i>الحضور والغياب
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('assignments.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>إدراة الواجبات
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('exams.index') }}">
                            <i class="fas fa-chart-bar me-2"></i>إدراة الإختبارات
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('announcements.index') }}">
                            <i class="fas fa-cog me-2"></i>إدارة الإعلانات
                        </a>
                    </li>
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('settings.index') }}">
                            <i class="fas fa-cog me-2"></i>الإعدادات 
                        </a>
                    </li>
                </ul>
            </aside>
     <div id="notification-area"></div>
            <!-- المحتوى الرئيسي -->
            <main class="admin-content">
       
               @yield('content')
            </main>
        </div>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- JavaScript Libraries -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- JS الخاص بالمدير -->
      <script src="{{ asset('js/admin.js') }}"></script>
          <script src="{{ asset('assets/js/script.js') }}">  </script>
            @stack('scripts')
           
    </body>
    </html>