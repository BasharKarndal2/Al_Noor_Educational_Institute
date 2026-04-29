@extends('layout.admin.dashboard')

@section('content')

@if(session('login_success'))
    <script>
        Swal.fire({
            title: '🎉 تسجيل الدخول ناجح!',
            html: '<b>مرحبًا بك في مجمع نور الهدى</b><br>سيتم تحويلك خلال لحظات...',
            icon: 'success',
            timer: 2500,
            showConfirmButton: false
        });
    </script>
@endif

     <div class="container-fluid py-4">
                    <h2 class="page-title mb-4"><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h2>
                    
                    <!-- بطاقات الإحصائيات -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card bg-primary text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-info">
                 <h3>{{ $studentsCount }}</h3>
                                    <p>عدد الطلاب</p>
                                    <a href="{{ route('student.index') }}" class="text-white">عرض الكل <i class="fas fa-arrow-left"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card bg-success text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>{{ $teachersCount }}</h3>
         
            
                                       <p>عدد المعلمين</p>
                                    <a href="{{ route('teaher.index') }}" class="text-white">عرض الكل <i class="fas fa-arrow-left"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card bg-warning text-dark">
                                <div class="stat-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>{{ $classesCount }}</h3>
                                    <p>عدد الصفوف</p>
                                    <a href="{{ route('classroom.index') }}" class="text-dark">عرض الكل <i class="fas fa-arrow-left"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card bg-info text-white">
                                <div class="stat-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="stat-info">
                                    <h3>{{ $subjectsCount }}</h3>
                                    <p>عدد المواد</p>
                                    <a href="{{ route('subject.index') }}" class="text-white">عرض الكل <i class="fas fa-arrow-left"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الصفوف الأخيرة -->
                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>أحدث الطلاب المسجلين</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>اسم الطالب</th>
                                                    <th>الصف</th>
                                                    <th>تاريخ التسجيل</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($latestStudents as $student )
                                                    
                                               
                                                <tr>
                                                    <td>{{ $student->id }}</td>
                                                    <td>{{ $student->name }} </td>
                                              
                                                    <td>
                                        @foreach ($student->sectionSubjectTeachers->pluck('section')->unique('id') as $section)
                                            {{ $section->name?? '-' }}    {{ $section->classroom->name?? '-' }}
                                        @endforeach
                                    </td>  <td>{{ $student->created_at }} </td>
                                                </tr>
                                                 @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-lg-6">-->
                        <!--    <div class="card shadow-sm">-->
                        <!--        <div class="card-header bg-success text-white">-->
                        <!--            <h5 class="mb-0"><i class="fas fa-bell me-2"></i>آخر الإشعارات</h5>-->
                        <!--        </div>-->
                        <!--        <div class="card-body">-->
                        <!--            <div class="notifications-list">-->
                        <!--                <div class="notification-item unread">-->
                        <!--                    <div class="notification-icon text-danger">-->
                        <!--                        <i class="fas fa-exclamation-circle"></i>-->
                        <!--                    </div>-->
                        <!--                    <div class="notification-content">-->
                        <!--                        <h6>طلب جديد لتغيير مادة</h6>-->
                        <!--                        <p>من الطالب أحمد محمد - الصف العاشر أ</p>-->
                        <!--                        <small>منذ ساعتين</small>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--                <div class="notification-item">-->
                        <!--                    <div class="notification-icon text-success">-->
                        <!--                        <i class="fas fa-check-circle"></i>-->
                        <!--                    </div>-->
                        <!--                    <div class="notification-content">-->
                        <!--                        <h6>تمت الموافقة على طلب إجازة</h6>-->
                        <!--                        <p>للمعلم يوسف خالد</p>-->
                        <!--                        <small>منذ يوم</small>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--                <div class="notification-item">-->
                        <!--                    <div class="notification-icon text-primary">-->
                        <!--                        <i class="fas fa-info-circle"></i>-->
                        <!--                    </div>-->
                        <!--                    <div class="notification-content">-->
                        <!--                        <h6>اجتماع هيئة التدريس</h6>-->
                        <!--                        <p>يوم الخميس القادم الساعة 10 صباحاً</p>-->
                        <!--                        <small>منذ 3 أيام</small>-->
                        <!--                    </div>-->
                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                </div>
@endsection