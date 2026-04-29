<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="{{ asset('images/Noor_Alhuda_logo.png') }}">
    <title>مجمع نور الهدى التعليمي - تعليم حديث</title>
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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
</head>
<body>


   
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/Noor_Alhuda_logo.png') }}" alt="شعار مجمع نور الهدى" height="50">
                <span class="brand-text">Noor Al Huda Integrated Educational Complex</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#home">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">عن المجمع</a></li>
                    <li class="nav-item"><a class="nav-link" href="#departments">الأقسام</a></li>
                    <li class="nav-item"><a class="nav-link" href="#courses">الدورات</a></li>
                    <li class="nav-item"><a class="nav-link" href="#teachers">المعلمون</a></li>
                    <li class="nav-item"><a class="nav-link" href="#facilities">المرافق</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">التواصل</a></li>
                </ul>
                <a href="{{ route('login') }}" class="btn btn-outline-light ms-3">تسجيل الدخول</a>
            </div>
        </div>
    </nav>

    
<!-- Advertisement Slider Section -->
<section class="advertisement-slider m-=">
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <!-- Slide 1 -->
            @forelse ( $announcements as  $announcement)
                  <div class="swiper-slide">
                <img src="{{ asset('storage/' .$announcement->image_path) }}" alt="إعلان 1" class="swiper-image">
                <div class="swiper-caption">
                    <h3> {{  $announcement->titel }}</h3>
                    <p>  {{  $announcement->discridtion  }}   </p>
                    <!-- <a href="#courses" class="btn btn-primary">سجل الآن</a> -->
                </div>
            </div>
            @empty
                  <div class="swiper-slide">
                <img src="{{ asset('images/course6.jpg') }}" alt="إعلان 1" class="swiper-image">
                <div class="swiper-caption">
                    <h3>إعلان عن دورة جديدة</h3>
                    <p>سجل الآن في دورتنا الجديدة في التصمميم الجرافيكي </p>
                    <!-- <a href="#courses" class="btn btn-primary">سجل الآن</a> -->
                </div>
            </div>
            @endforelse
          
            
         
        </div>
        
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        
        <!-- Navigation Buttons -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</section>

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->


    <!-- Hero Section -->
    <header id="home" class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-md-12">
                    <h1 id="h1style" class="animate__animated animate__fadeInDown">مجمع نور الهدى التعليمي</h1>
                    <p class="lead animate__animated animate__fadeInUp animate__delay-1s">نور العلم يضيء المستقبل - تعليم حديث يركز على تنمية المهارات</p>
                    <a href="#about" class="btn btn-primary btn-lg mt-3 animate__animated animate__fadeInUp animate__delay-2s">تعرف علينا</a>
                    
                    <!-- قسم الدخول حسب الصفة المضاف هنا -->
                    <div  id="hederpage" class="login-sections animate__animated animate__fadeInUp animate__delay-2s mt-3">
                        <h4 class="text-white">سجل الآن حسب صفتك:</h4>
                        <div class="d-flex flex-wrap justify-content-center gap-4 mt-3">
                           
                            <a href="{{ route('teacher.register') }}" class="btn btn-outline-light">
                                <i class="fas fa-chalkboard-teacher me-2"></i>تسجيل معلم جديد 
                            </a>
                            <a href="{{ route('request.index') }}" class="btn btn-outline-light">
                                <i class="fas fa-user-graduate me-2"></i> تسجيل طالب جديد
                            </a>
                           
                             <a href="#" class="btn btn-outline-light">
                                <i class="fas fa-user-friends me-2"></i> تسجل متدرب جديد
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-wave">
            <svg viewBox="0 0 500 150" preserveAspectRatio="none">
                <path d="M0.00,49.98 C149.99,150.00 349.20,-49.98 500.00,49.98 L500.00,150.00 L0.00,150.00 Z"></path>
            </svg>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2 class="text-center">عن مجمع نور الهدى</h2>
                <div class="divider"></div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-content">
                        <h3>رسالتنا</h3>
                        <p>تقديم تعليم متميز يجمع بين الأصالة والمعاصرة، ويسهم في بناء شخصية الطالب بناءً متكاملاً من الناحية العلمية والأخلاقية.</p>
                        
                        <h3 class="mt-4">رؤيتنا</h3>
                        <p>الريادة في تقديم تعليم حديث يواكب متطلبات العصر، مع الحفاظ على القيم الإسلامية والهوية العربية.</p>
                        
                        <div class="stats mt-5">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="stat-box">
                                        <div class="counter" data-target="15">0</div>
                                        <p>عاماً من الخبرة</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-box">
                                        <div class="counter" data-target="{{ $teachersCount }}">0</div>
                                        <p>معلماً متميزاً</p>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-box">
                                        <div class="counter" data-target="{{ $studentsCount ?? 0 }}">0</div>
                                        <p>طالب وطالبة</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-img">
                        <img src="{{ asset('images/about.jpg') }}" alt="عن المجمع" class="img-fluid rounded shadow">
                        <div class="img-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section py-5 bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="text-center">مميزات مجمع نور الهدى</h2>
                <div class="divider"></div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h3>تعليم تقني حديث</h3>
                        <p>فصول مجهزة بأحدث التقنيات التعليمية لمواكبة التطور التكنولوجي.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-quran"></i>
                        </div>
                        <h3>تحفيظ القرآن الكريم</h3>
                        <p>برامج متخصصة لتحفيظ القرآن الكريم مع أحكام التجويد والتفسير.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bus"></i>
                        </div>
                        <h3>خدمة المواصلات</h3>
                        <p>أسطول من الحافلات المكيفة لتوصيل الطلاب من وإلى المجمع بأمان.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h3>مختبرات علمية</h3>
                        <p>مختبرات مجهزة بأحدث الأجهزة لتجارب العلوم والفيزياء والكيمياء.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3>مكتبة ثرية</h3>
                        <p>مكتبة تحتوي على آلاف الكتب والمراجع في مختلف المجالات.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h3>نشاطات لا صفية</h3>
                        <p>برامج رياضية وفنية وثقافية لتنمية مواهب الطلاب.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Departments Section -->
    <section id="departments" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2 class="text-center">الأقسام الدراسية</h2>
                <div class="divider"></div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="department-card">
                        <div class="dept-img">
                            <img src="{{ asset('images/dept0.jpg') }}" alt="مرحلة الروضة" class="img-fluid">
                        </div>
                        <div class="dept-content">
                            <h3>مرحلة الروضة</h3>
                            <p>التركيز على تنمية المهارات الأساسية مثل التواصل، اللعب، المهارات الاجتماعية، وتهيئة الطفل للمرحلة الابتدائية.</p>
                            <a href="#hederpage" class="btn btn-outline-primary">أبدأ الآن</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="department-card">
                        <div class="dept-img">
                            <img src="{{ asset('images/dept1.jpg') }}" alt="المرحلة الابتدائية" class="img-fluid">
                        </div>
                        <div class="dept-content">
                            <h3>المرحلة الابتدائية</h3>
                            <p>تعليم المواد الأساسية مثل القراءة، الكتابة، الرياضيات، العلوم، الدراسات الإسلامية، واللغة الإنجليزية، مع التركيز على بناء الأسس الأكاديمية.</p>
                            <a href="#hederpage" class="btn btn-outline-primary">أبدأ الآن</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="department-card">
                        <div class="dept-img">
                            <img src="{{ asset('images/dept2.jpg') }}" alt=" المرحلة الإعدادية (المتوسطة)" class="img-fluid">
                        </div>
                        <div class="dept-content">
                            <h3>المرحلة الإعدادية (المتوسطة) </h3>
                            <P>تعزيز المهارات الأكاديمية والفكرية عبر دراسة العربية، الرياضيات، العلوم، الإسلامية، الإنجليزية، والاجتماعيات لتهيئة الطالب للثانوية.</P>
                            <a href="#hederpage" class="btn btn-outline-primary"> أبدأ الآن </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="department-card">
                        <div class="dept-img">
                            <img src="{{ asset('images/dept3.jpg') }}" alt="المرحلة الثانوية" class="img-fluid">
                        </div>
                        <div class="dept-content">
                            <h3>المرحلة الثانوية</h3>
                            <p>إعداد الطالب للتعليم العالي (الجامعة) أو سوق العمل من خلال تطوير المهارات الأكاديمية والعملية.</p>
                            <a href="#hederpage" class="btn btn-outline-primary"> أبدأ الآن </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Section -->
    <section id="courses" class="py-5 bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="text-center">الدورات التدريبية المتاحة</h2>
                <div class="divider"></div>
            </div>
            
            <!-- Featured Courses (First 3) -->
            <div class="row" id="featured-courses">
                <!-- سيتم عرض أول 3 دورات هنا -->
                <!-- Course 1 -->
                <div class="col-md-4 mb-4">
                    <div class="course-card">
                        <div class="course-badge">جديد</div>
                        <img src="{{ asset('images/course1.jpg') }}" alt="برمجة الأطفال" class="img-fluid">
                        <div class="course-content">
                            <h3>برمجة الأطفال</h3>
                            <p class="course-desc">تعلم أساسيات البرمجة بلغة سكراتش وبايثون للأطفال من 8-12 سنة.</p>
                            <div class="course-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>المدة: 20 ساعة (8 أسابيع)</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>السعر: $150 | 1,500,000 ل.س</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span>المدرب: أ. أحمد علي</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span>ملاحظات: تتضمن شهادة معتمدة</span>
                                </div>
                            </div>
                            <button class="btn btn-primary register-btn" data-course="برمجة الأطفال">تسجيل في الدورة</button>
                        </div>
                    </div>
                </div>
                
                <!-- Course 2 -->
                <div class="col-md-4 mb-4">
                    <div class="course-card">
                        <img src="{{ asset('images/course4.jpg') }}" alt="قيادة الحاسوب الآلي" class="img-fluid">
                        <div class="course-content">
                            <h3>قيادة الحاسوب الآلي</h3>
                            <p class="course-desc">تعلم قيادة الحاسب الآلي والبرامج المكتبية - الرخصة الدولية</p>
                            <div class="course-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>المدة: 30 ساعة (12 أسبوعاً)</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>السعر: $100 | 1,000,000 ل.س</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span>المدرب: أ. محمد حسن</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span>ملاحظات: تتضمن امتحان الرخصة</span>
                                </div>
                            </div>
                            <button class="btn btn-primary register-btn" data-course="قيادة الحاسوب الآلي">تسجيل في الدورة</button>
                        </div>
                    </div>
                </div>
                
                <!-- Course 3 -->
                <div class="col-md-4 mb-4">
                    <div class="course-card">
                        <div class="course-badge">شائع</div>
                        <img src="{{ asset('images/course3.jpg') }}" alt="الإنجليزية المحادثة" class="img-fluid">
                        <div class="course-content">
                            <h3>الإنجليزية المحادثة</h3>
                            <p class="course-desc">تحسين مهارات المحادثة باللغة الإنجليزية للمستويات المتوسطة.</p>
                            <div class="course-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>المدة: 25 ساعة (10 أسابيع)</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>السعر: $120 | 1,200,000 ل.س</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span>المدرب: أ. سارة أحمد</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span>ملاحظات: مواد تعليمية مجانية</span>
                                </div>
                            </div>
                            <button class="btn btn-primary register-btn" data-course="الإنجليزية المحادثة">تسجيل في الدورة</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- All Courses (Hidden by default) -->
            <div class="row" id="all-courses" style="display: none;">
                <!-- سيتم عرض جميع الدورات هنا -->
                <!-- يمكن إضافة المزيد من الدورات بنفس الهيكل -->
                
                <!-- Course 4 -->
                <div class="col-md-4 mb-4">
                    <div class="course-card">
                        <img src="{{ asset('images/course5.png') }}" alt="التصميم الجرافيكي" class="img-fluid">
                        <div class="course-content">
                            <h3>التصميم الجرافيكي</h3>
                            <p class="course-desc">تعلم أساسيات التصميم باستخدام برامج الفوتوشوب والإليستريتور.</p>
                            <div class="course-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>المدة: 35 ساعة (14 أسبوعاً)</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>السعر: $180 | 1,800,000 ل.س</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span>المدرب: أ. ياسمين خالد</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span>ملاحظات: تتطلب حاسوب شخصي</span>
                                </div>
                            </div>
                            <button class="btn btn-primary register-btn" data-course="التصميم الجرافيكي">تسجيل في الدورة</button>
                        </div>
                    </div>
                </div>
                
                <!-- Course 5 -->
                <div class="col-md-4 mb-4">
                    <div class="course-card">
                        <div class="course-badge">مكثف</div>
                        <img src="{{ asset('images/course6.jpg') }}" alt="تطوير الويب" class="img-fluid">
                        <div class="course-content">
                            <h3>تطوير الويب للمبتدئين</h3>
                            <p class="course-desc">تعلم HTML, CSS, JavaScript لإنشاء مواقع ويب تفاعلية.</p>
                            <div class="course-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>المدة: 40 ساعة (10 أسابيع)</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>السعر: $200 | 2,000,000 ل.س</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span>المدرب: أ. خالد وليد</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span>ملاحظات: مشروع عملي في نهاية الدورة</span>
                                </div>
                            </div>
                            <button class="btn btn-primary register-btn" data-course="تطوير الويب للمبتدئين">تسجيل في الدورة</button>
                        </div>
                    </div>
                </div>
                
                <!-- Course 6 -->
                <div class="col-md-4 mb-4">
                    <div class="course-card">
                        <img src="{{ asset('images/course7.jpg') }}" alt="التسويق الرقمي" class="img-fluid">
                        <div class="course-content">
                            <h3>التسويق الرقمي</h3>
                            <p class="course-desc">أساسيات التسويق الإلكتروني وإدارة الحملات الإعلانية.</p>
                            <div class="course-meta">
                                <div class="meta-item">
                                    <i class="far fa-clock"></i>
                                    <span>المدة: 30 ساعة (12 أسبوعاً)</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>السعر: $170 | 1,700,000 ل.س</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-user-tie"></i>
                                    <span>المدرب: أ. لينا محمد</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span>ملاحظات: شهادة معتمدة دولياً</span>
                                </div>
                            </div>
                            <button class="btn btn-primary register-btn" data-course="التسويق الرقمي">تسجيل في الدورة</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <button id="toggle-courses" class="btn btn-outline-primary">عرض جميع الدورات</button>
            </div>
        </div>
    </section>


 
<!-- نموذج التسجيل -->
<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تسجيل في الدورة: <span id="courseTitle">اسم الدورة</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="registrationForm">
                    <input type="hidden" id="selectedCourse" name="course_name">
                    <div class="form-group">
                        <label for="fullname">الاسم الكامل</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="age">العمر</label>
                        <input type="number" class="form-control" id="age" name="age" min="10" required>
                    </div>
                    <div class="form-group">
                        <label for="education">المستوى التعليمي</label>
                        <select class="form-control" id="education" name="education" required>
                            <option value="">اختر المستوى التعليمي</option>
                            <option value="school">طالب مدرسة</option>
                            <option value="university">طالب جامعة</option>
                            <option value="institute">معهد</option>
                            <option value="diploma">دبلوم</option>
                            <option value="bachelor">جامعي</option>
                            <option value="master">ماجستير</option>
                            <option value="phd">دكتوراه</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">ملاحظات إضافية</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">إرسال طلب التسجيل</button>
                </form>
            </div>
        </div>
    </div>
</div>





    <!-- Teachers Section -->
    <section id="teachers" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2 class="text-center">كادرنا التعليمي</h2>
                <div class="divider"></div>
            </div>
            <div class="row">
                <div class="col-12">
                   @forelse ($teachers as $teacher )
                       
                  
                        <div class="teacher-card">
                            <div class="teacher-img">
                                <img src="{{ asset('storage/' . $teacher->image_path) }}" alt="المعلم يوسف خالد" class="img-fluid">
                                <div class="teacher-social">
                                  
                                </div>
                            </div>

                            <div class="teacher-info">
                                <h3>أ.  {{ $teacher->full_name??'-' }}</h3>
                                @forelse ($teacher->subjects as $sub )
                                     <p>مدرسة  {{ $sub->name??'-' }}</p>
                                @empty
                                   <p> لا يوجد مواد</p> 
                                @endforelse
                                
                                <p class="teacher-bio">{{ $teacher->notes??'-' }}</p>
                            </div>
                        </div>
 @empty
              <div class="teacher-card">
                            <div class="teacher-img">
                                <img src="{{ asset('images/6NX4E6c4aQLQFh6nOOJtXD6tqs4AHmqypO6sP6T0.png') }}" alt="المعلمة مريم وليد" class="img-fluid">
                               
                            </div>
                            <div class="teacher-info">
                                <h3>أ. مريم وليد</h3>
                                <p>مدرسة اللغة الإنجليزية</p>
                                <p class="teacher-bio">خريجة جامعة كامبريدج، خبرة في تدريس الإنجليزية كلغة ثانية.</p>
                            </div>
                        </div>           
                   @endforelse

                      
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Section -->
    <section id="facilities" class="py-5 bg-light">
        <div class="container">
            <div class="section-header">
                <h2 class="text-center">مرافقنا</h2>
                <div class="divider"></div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="facility-card">
                        <div class="facility-img">
                            <img src="{{ asset('images/classroom.jpg') }}" alt="الفصول الدراسية" class="img-fluid">
                            <div class="">
                                <a href="{{ asset('images/classroom.jpg') }}" data-lightbox="facilities" data-title="الفصول الدراسية"><i class="fas fa-search-plus"></i></a>
                            </div>
                        </div>
                        <div class="facility-content">
                            <h3>الفصول الدراسية</h3>
                            <p>فصول مجهزة بأحدث الوسائل التعليمية، شاشات ذكية، تكييف مركزي، وإضاءة مناسبة.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="facility-card">
                        <div class="facility-img">
                            <img src="{{ asset('images/lab.jpg') }}" alt="المختبرات علمية" class="img-fluid">
                            <div class="">
                                <a href="{{ asset('images/lab.jpg') }}" data-lightbox="facilities" data-title="المختبرات العلمية"><i class="fas fa-search-plus"></i></a>
                            </div>
                        </div>
                        <div class="facility-content">
                            <h3>المختبرات العلمية</h3>
                            <p>مختبرات مجهزة بأحدث الأجهزة والتقنيات لتجارب العلوم والفيزياء والكيمياء.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="facility-card">
                        <div class="facility-img">
                            <img src="{{ asset('images/library.jpg') }}" alt="المكتبة" class="img-fluid">
                            <div class="">
                                <a href="{{ asset('images/library.jpg') }}" data-lightbox="facilities" data-title="المكتبة"><i class="fas fa-search-plus"></i></a>
                            </div>
                        </div>
                        <div class="facility-content">
                            <h3>المكتبة</h3>
                            <p>مكتبة ضخمة تحتوي على آلاف الكتب والمراجع في مختلف المجالات العلمية والأدبية.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--<div class="text-center mt-4">-->
                <!--<a href="#" class="btn btn-outline-primary">عرض جميع المرافق</a>-->
            <!--</div>-->
        </div>
    </section>

    <!-- Testimonials Section
    <section class="testimonials-section py-5">
        <div class="container">
            <div class="section-header">
                <h2 class="text-center">آراء أولياء الأمور</h2>
                <div class="divider"></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="testimonial-slider owl-carousel">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
                                <p>مجمع نور الهدى غير مستوى ابني في اللغة العربية بشكل ملحوظ، المعلمون متميزون وطريقة التدريس حديثة وممتعة للأطفال.</p>
                            </div>
                            <div class="testimonial-author">
                                <img src="images/parent1.jpg" alt="وليد أحمد">
                                <h4>وليد أحمد</h4>
                                <p>ولي أمر طالب بالصف الثالث</p>
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
                                <p>ابنتي تطورت كثيراً في اللغة الإنجليزية بعد التحاقها بالمجمع كما أن الاهتمام بالجانب الأخلاقي والديني شيء رائع.</p>
                            </div>
                            <div class="testimonial-author">
                                <img src="images/parent2.jpg" alt="سمر خالد">
                                <h4>سمر خالد</h4>
                                <p>والدة طالبة بالصف الخامس</p>
                            </div>
                        </div>
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="quote-icon"><i class="fas fa-quote-right"></i></div>
                                <p>المرافق ممتازة والاهتمام بالنظافة والسلامة يطمئن الأهالي، كما أن نظام المواصلات منظم وآمن.</p>
                            </div>
                            <div class="testimonial-author">
                                <img src="images/parent3.jpg" alt="محمد ناصر">
                                <h4>محمد ناصر</h4>
                                <p>ولي أمر طالبين بالمجمع</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="section-header">
                <h2 class="text-center">تواصل معنا</h2>
                <div class="divider"></div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="contact-info">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-text">
                                <h3>العنوان</h3>
                               
                            <p>{{ $setting->location??" -" }}</p>

                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-text">
                                <h3>الهاتف</h3>
                                <p> {{ $setting->whatsapp??" -" }}+</p>
                                <p>{{ $setting->phone2??" -" }}+</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-text">
                                <h3>البريد الإلكتروني</h3>
                                <p>{{ $setting->email??" -" }}</p>
                                
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="contact-text">
                                <h3>ساعات الدوام</h3>
                              <p>{{ $setting->working_hours??" -" }}</p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="contact-form">
                        <form id="contactForm">
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="name" placeholder="اسمك الكريم" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="email" class="form-control" id="email" placeholder="البريد الإلكتروني" required>
                            </div>
                            <div class="form-group mb-3">
                                <input type="tel" class="form-control" id="phone" placeholder="رقم الهاتف">
                            </div>
                            <div class="form-group mb-3">
                                <input type="text" class="form-control" id="subject" placeholder="موضوع الرسالة" required>
                            </div>
                            <div class="form-group mb-3">
                                <textarea class="form-control" id="message" rows="5" placeholder="نص الرسالة" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">إرسال الرسالة</button>
                        </form>
                    </div>
                </div>
            </div>
           <div class="row mt-4">
    <div class="col-12">
        <div class="map-container">
           <iframe src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3220.8108178721955!2d37.22246668472774!3d36.17115798008292!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMzbCsDEwJzE2LjIiTiAzN8KwMTMnMTMuMCJF!5e0!3m2!1sar!2s!4v1754217167673!5m2!1sar!2s" 
            width="100%" 
            height="600"
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>

        </div>
    </section>

    <!-- Newsletter Section -->
    <!-- <section class="newsletter-section py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h3>اشترك في نشرتنا البريدية</h3>
                    <p class="mb-0">ابق على اطلاع بآخر أخبار المجمع والفعاليات والأنشطة.</p>
                </div>
                <div class="col-md-6">
                    <form class="newsletter-form">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="بريدك الإلكتروني" required>
                            <button class="btn btn-dark" type="submit">اشتراك</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section> -->

    <!-- Footer -->
       <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="footer-about">
                        <img src="images/Noor_Alhuda_logo.png" alt="شعار مجمع نور الهدى" height="60" width="60">
                        <p class="mt-3">مجمع نور الهدى التعليمي يقدم تعليماً حديثاً يجمع بين الأصالة والمعاصرة، ويسهم في بناء شخصية الطالب بناءً متكاملاً.</p>
                        <div class="social-links">
                        <a href="https://wa.me/+{{ $setting->whatsapp??" -" }}" class="social-icon"><i class="fab fa-whatsapp"></i></a>                   
                        <a href="{{ $setting->facebook??" -" }}" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://t.me/+{{ $setting->telegram ??" -"}}" class="social-icon"><i class="fab fa-telegram-plane"></i></a>
                        <a href="mailto:{{ $setting->email??" -" }}" class="social-icon"><i class="fas fa-envelope"></i></a>             
                        <a href="{{ $setting->instagram??" -" }}" class="social-icon"><i class="fab fa-instagram"></i></a>
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

                            </i>{{ $setting->location??" -" }}</li>

                            <li><i class="fas fa-phone-alt"></i>{{ $setting->whatsapp??" -" }}+</li>
                            <li><i class="fas fa-phone-alt"></i>{{ $setting->phone2??" -" }}+</li>
                            <li><i class="fas fa-envelope"></i>{{ $setting->email??" -" }}</li>
                            <li><i class="fas fa-clock"></i>{{ $setting->working_hours??" -" }} 
</li>
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

    <!-- Back to Top Button -->
    <a href="#" class="back-to-top"><i class="fas fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>


