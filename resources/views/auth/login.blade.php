<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | مجمع نور الهدى</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Noor_Alhuda_logo.png') }}">
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Tajawal -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c5f8a;
            --secondary-color: #4b8b9d;
            --accent-color: #f5a623;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fa;
            display: flex;
            min-height: 100vh;
            align-items: center;
            background-image: url('../images/login-bg.jpg');
            background-size: cover;
            background-position: center;
        }
        
        .login-container {
            max-width: 1000px;
            margin: auto;
            box-shadow: 0 0 30px rgba(0,0,0,0.2);
            border-radius: 15px;
            overflow: hidden;
            background-color: rgba(255,255,255,0.95);
        }
        
        .login-left {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 1;
        }
        
        .login-left-content {
            position: relative;
            z-index: 2;
        }
        
        .login-right {
            padding: 40px;
        }
        
        .login-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .login-logo img {
            margin: -20px;
            height: 210PX;
            width: 180px;
            margin-bottom: 8px;
        }
        
        .login-title {
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--primary-color);
            text-align: center;
        }
        
        .form-control {
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(44, 95, 138, 0.25);
        }
        
        .btn-login {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            transition: all 0.3s;
        }
        
        .btn-login:hover {
            background-color: #234a6d;
            transform: translateY(-2px);
        }
        
        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .login-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .input-group-text {
            background-color: #f8f9fa;
        }
        
        .toggle-password {
            cursor: pointer;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #666;
        }
        
        .divider::before, .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }
        
        .divider-text {
            padding: 0 10px;
        }
        
        /* تنسيقات مربعات الميزات */
        .features-list {
            margin-top: 30px;
        }
        
        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.1);
            padding: 5PX 5px;
            text-align: center;
            border-radius: 30PX;
            background: white;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(44, 95, 138, 0.1);
            border-radius: 50%;
            font-size: 1.5rem;
        }
        
        .feature-icon.admin { color: var(--primary-color); }
        .feature-icon.teacher { color: #28a745; }
        .feature-icon.student { color: #17a2b8; }
        .feature-icon.parent { color: #ffc107; }
        .feature-icon.trainee { color: #dc3545; }
        
        .feature-title {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark-color);
        }
        
        .feature-desc {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0;
        }
        
       
       
        @media (max-width: 991.98px) {
            .login-left {
                display: none;
            }
            
            .login-container {
                max-width: 500px;
            }
            
            
        }

        @media (max-width: 767.98px) {
            .main-container {
                margin: 10px;
            }
            
            .login-container {
                padding: 20px;
            }
            
            .feature-card {
                padding: 15px 10px !important;
            }
            
            .feature-icon {
                width: 50px;
                height: 50px;
                font-size: 1.2rem;
            }
            
            .feature-title {
                font-size: 0.95rem;
            }
            
            .features-grid {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            }
        }

        @media (max-width: 479.98px) {
            .login-container h2 {
                font-size: 1.3rem;
            }
            
            .form-group input {
                padding: 8px;
                font-size: 0.9rem;
            }
            
            button {
                padding: 8px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>

@if ($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'خطأ',
        text: 'اسم المستخدم أو كلمة المرور غير صحيحة',
        confirmButtonText: 'حسناً',
        timer: 4000,
        timerProgressBar: true,
    });
</script>
@endif
    <div class="container login-container">
        <div class="row no-gutters">
            <div class="col-lg-6 d-none d-lg-block login-left">
                <div class="login-left-content">
                    <div class="login-logo">
                        <img src="{{ asset('images/Noor_Alhuda_logo.png') }}" alt="شعار مجمع نور الهدى">
                    </div>
                    <h3 class="text-center mb-4">نظام إدارة المجمع التعليمي</h3>
                    <p class="text-center mb-5">منصة متكاملة لجميع مستخدمي المجمع</p>
                    
                    <div class="features-list">
                        <div class="row">
                            <!-- الإدارة -->
                            <div class="col-4 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon admin">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <h5 class="feature-title">الإدارة</h5>
                                    <p class="feature-desc">إدارة شاملة للمجمع والمرافق</p>
                                </div>
                            </div>
                            
                            <!-- المعلمون -->
                            <div class="col-4 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon teacher">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <h5 class="feature-title">المعلمون</h5>
                                    <p class="feature-desc">إدارة الدروس والتقييمات</p>
                                </div>
                            </div>
                            
                            <!-- الطلاب -->
                            <div class="col-4 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon student">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <h5 class="feature-title">الطلاب</h5>
                                    <p class="feature-desc">متابعة المسيرة التعليمية</p>
                                </div>
                            </div>
                            
                            <!-- أولياء الأمور -->
                            <div class="col-6 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon parent">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <h5 class="feature-title">أولياء الأمور</h5>
                                    <p class="feature-desc">متابعة تقارير الأبناء</p>
                                </div>
                            </div>
                            
                            <!-- المتدربون -->
                            <div class="col-6 mb-4">
                                <div class="feature-card">
                                    <div class="feature-icon trainee">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <h5 class="feature-title">المتدربون</h5>
                                    <p class="feature-desc">متابعة الدورات التدريبية</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 login-right">
                <div class="login-logo d-lg-none">
                     <img src="../images/Noor_Alhuda_logo.png" data-mobile-src="images/Noor_Alhuda__5_login.png" alt="Logo" class="logo">                </div>
                <h2 class="login-title">تسجيل الدخول</h2>
                
                <form id="unifiedLoginForm" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="username" class="form-label">اسم المستخدم / البريد الإلكتروني</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input name="email"   type="text" class="form-control" id="username" placeholder="أدخل اسم المستخدم أو البريد الإلكتروني"  autofocus required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" id="password" placeholder="أدخل كلمة المرور" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
               
                    
                    <button type="submit" class="btn btn-login mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i> تسجيل الدخول
                    </button>
                    
                    <div class="divider">
                        <span class="divider-text">أو</span>
                    </div>
                    
                    <a href="{{ route('home') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-home me-2"></i> العودة للصفحة الرئيسية
                    </a>
                </form>
                <div class="login-footer text-center mt-3">
                    ليس لديك حساب؟ 
                    <a href="{{ route('home') }}">سجل الآن</a> 
                    | جميع الحقوق محفوظة &copy; مجمع نور الهدى 2025
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- SweetAlert لعرض الرسائل -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
       $(document).ready(function() {
    // تبديل عرض كلمة المرور
    $('.toggle-password').click(function() {
        const passwordInput = $('#password');
        const icon = $(this).find('i');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });});
    
  
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('unifiedLoginForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();  // منع الإرسال الفوري

            Swal.fire({
                title: 'جاري تسجيل الدخول',
                html: 'الرجاء الانتظار...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // بعد عرض التنبيه، أرسل الفورم فعلياً
            // استخدام setTimeout لتأكيد ظهور التحميل
            setTimeout(() => {
                form.submit();
            }, 500);  // تأخير 500 مللي ثانية (يمكن تعديله حسب الحاجة)
        });
    });
</script>
</body>
</html>