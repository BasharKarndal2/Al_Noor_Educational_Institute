<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="{{ asset('images/Noor_Alhuda_logo.png') }}">
    <title>لوحة التحكم - ولي الأمر | مجمع نور الهدى</title>
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
      <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables JS -->

    <link rel="stylesheet" href="{{ asset('css/student.css') }}">

    <!--<link rel="stylesheet" href="{{ asset('css/parent.css') }}">-->
</head>
<body class="student-dashboard">
    <!-- شريط التنقل العلوي -->

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <!-- الشعار + عنوان لوحة ولي الأمر -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('parent.dashboard') }}">
            <img src="{{ asset('images/Noor_Alhuda_logo.png') }}" 
                 alt="شعار المجمع" height="40" class="ms-2">
            <span class="">لوحة ولي الأمر</span>
        </a>

        <!-- زر القائمة للشاشات الصغيرة -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarContent" aria-controls="navbarContent" 
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- محتوى الـ Navbar -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="ms-auto d-flex align-items-center">
                <!-- الإشعارات -->
                <div class="dropdown me-3">
                    <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="badge bg-danger">0</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <!--<li><h6 class="dropdown-header">الإشعارات الجديدة</h6></li>-->
                        <!--<li><a class="dropdown-item" href="#">واجب جديد لابنك</a></li>-->
                        <!--<li><a class="dropdown-item" href="#">تقرير الحضور الأسبوعي</a></li>-->
                        <li><hr class="dropdown-divider"></li>
                        <!--<li><a class="dropdown-item text-center" href="#">عرض الكل</a></li>-->
                    </ul>
                </div>
                <!-- القائمة المنسدلة للملف الشخصي -->
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        @if ($authUser)
                            <span id="adminName">{{ $authUser->name }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <!--<li><a class="dropdown-item" href="profile.html"><i class="fas fa-user me-2"></i>الملف الشخصي</a></li>-->
                        <!--<li><a class="dropdown-item" href="settings.html"><i class="fas fa-cog me-2"></i>الإعدادات</a></li>-->
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
    </div>
</nav>
    
    <!-- القائمة الجانبية -->
    <div class="student-wrapper">
        <aside class="student-sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('parent.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="{{ route('parent.get_chiled') }}">
                        <i class="fas fa-users me-2"></i>أبنائي
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('parent.schedule') }}">
                        <i class="fas fa-calendar-alt me-2"></i>الجدول الدراسي
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('chiled.getatt') }}">
                        <i class="fas fa-clipboard-list me-2"></i>الحضور والغياب
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('chiled.evaluation') }}">
                        <i class="fas fa-chart-line me-2"></i>الدرجات والتقييم
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('chiled.chiledgetassing') }}">
                        <i class="fas fa-book me-2"></i>الواجبات المدرسية
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('chiled.exam') }}">
                        <i class="fas fa-file-alt me-2"></i>الاختبارات
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link" href="materials.html">
                        <i class="fas fa-download me-2"></i>المواد التعليمية
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="messages.html">
                        <i class="fas fa-envelope me-2"></i>الرسائل
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.html">
                        <i class="fas fa-user me-2"></i>الملف الشخصي
                    </a>
                </li> --}}
            </ul>
        </aside>

        <!-- المحتوى الرئيسي -->
        <main class="student-content">
        @yield('conten')
        </main>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        @stack('stack')
        @stack('styels')
</body>
</html>