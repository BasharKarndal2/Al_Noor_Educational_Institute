<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام التسجيل - مجمع نور الهدى التعليمي</title>
      <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome للأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Bootstrap RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Tajawal -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
     <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
     @stack('scripts')
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        :root {
            --primary-color: #2c5f8a;
            --secondary-color: #4b8b9d;
            --accent-color: #f5a623;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .logo {
            height: 60px;
            margin-left: 15px;
        }
        
        .form-section {
            display: none;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            border-top: 4px solid var(--primary-color);
        }
        
        .form-section.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .subject-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }
        .footer a{
            text-decoration: none;
        }
        .subject-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .subject-card.selected {
            border: 2px solid var(--primary-color);
            background-color: rgba(44, 95, 138, 0.05);
        }
        
        .subject-card .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            padding: 12px 15px;
        }
        
        .teacher-option {
            display: flex;
            justify-content: space-between;
        }
        
        .price-tag {
            background-color: var(--accent-color);
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .progress-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: #e0e0e0;
            z-index: 1000;
        }
        
        .progress-bar {
            height: 100%;
            background: var(--primary-color);
            width: 20%;
            transition: width 0.3s ease;
        }
        
        .section-title {
            color: var(--primary-color);
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 25px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50px;
            height: 3px;
            background: var(--accent-color);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #234a6d;
            border-color: #234a6d;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        .test-question {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-color);
        }
        
        .test-question h5 {
            color: var(--primary-color);
        }
        
        .summary-card {
            border-left: 4px solid var(--success-color);
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--primary-color);
        }
        
        .nav-pills .nav-link {
            color: var(--primary-color);
        }
        
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        
        .step {
            text-align: center;
            flex: 1;
            position: relative;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            background-color: #e0e0e0;
            color: #666;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
        }
        
        .step.active .step-number {
            background-color: var(--primary-color);
            color: white;
        }
        
        .step.completed .step-number {
            background-color: var(--success-color);
            color: white;
        }
        
        .step-label {
            font-size: 0.9rem;
            color: #666;
        }
        
        .step.active .step-label {
            color: var(--primary-color);
            font-weight: bold;
        }
        
        .step.completed .step-label {
            color: var(--success-color);
        }
        
        .step:not(:last-child):after {
            content: '';
            position: absolute;
            top: 20px;
            right: 50%;
            left: -50%;
            height: 2px;
            background: #e0e0e0;
            z-index: -1;
        }
        
        .step.completed:not(:last-child):after {
            background: var(--success-color);
        }
        
        @media (max-width: 768px) {
            .step-label {
                font-size: 0.7rem;
            }
        }
       

                /* تعريف المتغيرات للألوان والانتقالات */
        :root {
            --primary-color: #007bff;
            --secondary-color: #343a40;
            --accent-color: #28a745;
            --white: #ffffff;
            --transition: all 0.3s ease;
        }
          
        /* Footer */
        .footer {
            background-color: var(--secondary-color);
            color: var(--white);
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            background: linear-gradient(to right, var(--primary-color), var(--accent-color));
        }

        .footer-about img {
            margin-bottom: 15px;
        }

        .footer-about p {
            color: rgba(255, 255, 255, 0.7);
        }

        .social-links {
            margin-top: 20px;
        }

        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--white);
            border-radius: 50%;
            margin-right: 10px;
            transition: var(--transition);
        }

        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }

        .footer-links h3 {
            color: var(--white);
            font-size: 1.3rem;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }


        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: var(--primary-color);
            padding-right: 5px;
        }

        .footer-contact ul {
            list-style: none;
            padding: 0;
        }

        .footer-contact li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .footer-contact i {
            margin-left: 10px;
            color: var(--primary-color);
            font-size: 18px;
            margin-top: 3px;
        }

        .footer-contact p {
            color: rgba(255, 255, 255, 0.7);
            margin: 0;
        }

        .footer-divider {
            border-color: rgba(255, 255, 255, 0.1);
            margin: 30px 0;
        }
    </style>
</head>
<body>



@yield('content')


      <!-- التذييل -->
 <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-2 mb-lg-0">
                    <div class="footer-about">
                        <img src="images/Noor_Alhuda_logo.png" alt="شعار مجمع نور الهدى" height="120" width="120">
                        <p class="">مجمع نور الهدى التعليمي يقدم تعليماً حديثاً يجمع بين الأصالة والمعاصرة، ويسهم في بناء شخصية الطالب بناءً متكاملاً.</p>
                        <div class="social-links">
                         <a href="https://wa.me/+963951742878" class="social-icon"><i class="fab fa-whatsapp"></i></a>                   
                        <a href="https://www.facebook.com/share/1Et35C8YKg/" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://t.me/+963951742878" class="social-icon"><i class="fab fa-telegram-plane"></i></a>
                        <a href="mailto:I.nooralhuda2025@gmail.com" class="social-icon"><i class="fas fa-envelope"></i></a>             
                        <a href="https://www.instagram.com/i.nooralhuda2025" class="social-icon"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h3>روابط سريعة</h3>
                        <ul>
                            <li><a href="#home">الرئيسية</a></li>
                            <li><a href="#about">عن المجمع</a></li>
                            <li><a href="#departments">الأقسام</a></li>
                            <li><a href="#courses">الدورات</a></li>
                            <li><a href="#teachers">المعلمون</a></li>
                            <li><a href="#contact">التواصل</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h3>الدورات</h3>
                        <ul>
                            <li><a href="#courses">برمجة الأطفال</a></li>
                            <li><a href="#courses">قيادة الحاسب الآلي</a></li>
                            <li><a href="#courses">الإنجليزية المحادثة</a></li>
                            <li><a href="#courses">التصميم الجرافيكي</a></li>
                            <li><a href="#courses">تطوير الويب للمبتدئين</a></li>
                            <li><a href="#courses">التسويق الرقمي</a></li>
                            <li><a href="#toggle-courses">عرض جميع الدورات</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="footer-contact">
                        <h3>معلومات التواصل</h3>
                        <ul>
                            <li><i class="fas fa-map-marker-alt">

                            </i> حلب مخيم النيرب مفرق الشنكل _ مقابل منزل آل زيدان</li>

                            <li><i class="fas fa-phone-alt"></i> 878 742 951 963+</li>
                            <li><i class="fas fa-envelope"></i> I.nooralhuda2025@gmail.com</li>
                            <li><i class="fas fa-clock"></i> الأحد - الخميس: 7:30ص - 3:30م</li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0"> جميع الحقوق محفوظة © مجمع نور الهدى 2025</p>
                   
                </div>   
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">تصميم وتطوير <a href="#" class="text-white">فريق المجمع</a></p>
                </div>
            </div>
        </div>
    </footer>

   <script src="{{ asset('assets/admin/js/index.js') }}"></script>
<script src="{{ asset('assets/admin/js/classroom.js') }}"></script>
<script src="{{ asset('assets/admin/js/eductional_stage.js') }}"></script>
<script src="{{ asset('fonts/fonts.js') }}"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>
</html>