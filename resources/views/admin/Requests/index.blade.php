
@extends('layout.request.dashboard')
   
   

   @section('content')   
@if(session('request_success'))
<script>
Swal.fire({
    title: '🎉 تسجيل الطلب ناجح!',
    html: '<span style="color: red; font-weight: bold;">ترحب بكم إدارة نور الهدى يرجى مراجعة الإدارة لتأكيد الطلب</span><br><span style="color: black; font-weight: normal;">سيتم تحويلك الى الصفحة الرئيسية خلال لحظات...</span>',
    icon: 'success',
    timer: 10000,
    showConfirmButton: false,
    willClose: () => {
        window.location.href = '{{ route('home') }}';
    }
});
</script>
@endif

 <!-- شريط التقدم -->
    <div class="progress-container">
        <div class="progress-bar" id="progressBar"></div>
    </div>
    
    <!-- الرأس -->
    <header class="header">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="images/Noor_Alhuda_logo.png" alt="شعار مجمع نور الهدى" class="logo">
                <div>
                    <h3 class="mb-0">نظام التسجيل الإلكتروني</h3>
                    <p class="mb-0">مجمع نور الهدى التعليمي</p>
                </div>
            </div>
        </div>
    </header>
    
    <!-- مؤشر الخطوات -->
    <div class="container mt-4">
        <div class="step-indicator">
            <div class="step active" id="step1">
                <div class="step-number">1</div>
                <div class="step-label">المعلومات الشخصية</div>
            </div>
            <div class="step" id="step2">
                <div class="step-number">2</div>
                <div class="step-label">ولي الأمر</div>
            </div>
            <div class="step" id="step3">
                <div class="step-number">3</div>
                <div class="step-label">المرحلة الدراسية</div>
            </div>
            <div class="step" id="step4">
                <div class="step-number">4</div>
                <div class="step-label">اختيار المواد</div>
            </div>
            <div class="step" id="step5">
                <div class="step-number">5</div>
                <div class="step-label">اختبار المستوى</div>
            </div>
        </div>
    </div>
    
    <!-- نموذج التسجيل -->
    <div class="container py-4">
        <form id="studentRegistrationForm" method="POST" enctype="multipart/form-data"  action="{{ route('request.store') }}">
 

            @csrf
            <input type="hidden" name="full_data" id="full_data">
            <div class="form-section active" id="section1">
                <h3 class="section-title">المعلومات الشخصية والحساب للطالب</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fullName" class="form-label">الاسم الكامل</label>
                        <input type="text" name="fullname_sudent" class="form-control" id="fullName" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="birthDate" class="form-label">تاريخ الميلاد</label>
                        <input type="date" class="form-control" name="birthDate" id="birthDate" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الجنس</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                                <label class="form-check-label" for="male">ذكر</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                                <label class="form-check-label" for="female">أنثى</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">رقم التواصل</label>
                        <input type="tel" name="phon" class="form-control" id="phone" required>
                    </div>
                </div>
                   <div class="col-md-6 mb-3">
                        <label for="national_id" class="form-label">رقم الهوية</label>
                        <input type="tel" name="national_id" class="form-control" id="national_id" required>
                       
                   
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="ادخل البريد الالكتروني">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="ادخل كلمة المرور">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">العنوان</label>
                        <input type="text" name="loc" class="form-control" id="address" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="photo" class="form-label">صورة شخصية (اختيارية)</label>
                    <input type="file" name="photo_path" class="form-control" id="photo" accept="image/*">
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <div></div>
                    <button type="button" class="btn btn-primary next-section" data-next="section2">
                        التالي <i class="fas fa-arrow-left ms-2"></i>
                    </button>
                </div>
            </div>
            
            <!-- القسم 2: معلومات ولي الأمر -->
            <div class="form-section" id="section2">
                <h3 class="section-title">معلومات ولي الأمر</h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="guardianName" class="form-label">اسم ولي الأمر</label>
                        <input type="text" name="namep" class="form-control" id="guardianName" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="guardianPhone" class="form-label">رقم تواصل ولي الأمر</label>
                        <input type="tel" name="phonp" class="form-control" id="guardianPhone" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="guardianEmail" class="form-label">بريد ولي الأمر الإلكتروني</label>
                    <input type="email" name="email_pe" class="form-control" id="guardianEmail">
                </div>
                
                <div class="mb-3">
                    <label for="guardianRelation" class="form-label">صلة القرابة</label>
                    <select class="form-select" id="guardianRelation" name="relation" required>
                        <option value="" selected disabled>اختر صلة القرابة</option>
                        <option value="father">الأب</option>
                        <option value="mother">الأم</option>
                        <option value="brother">الأخ</option>
                        <option value="sister">الأخت</option>
                        <option value="uncle">العم/الخال</option>
                        <option value="other">آخر</option>
                    </select>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-primary prev-section" data-prev="section1">
                        <i class="fas fa-arrow-right me-2"></i> السابق
                    </button>
                    <button type="button" class="btn btn-primary next-section" data-next="section3">
                        التالي <i class="fas fa-arrow-left ms-2"></i>
                    </button>
                </div>
            </div>
            
            <!-- القسم 3: المعلومات الأكاديمية -->
            <div class="form-section" id="section3">
                <h3 class="section-title">المعلومات الأكاديمية</h3>
                <div class="row">

                    
                          <x-select-field label="الفوج الدراسي" id="working_hour" name="working_hour_id" required />
                        <x-select-field label="المرحلة الدراسية" id="education_stage" name="education_stage_id" required />
                        <x-select-field label="الصف الدراسية" id="classroom" name="classroom_id" required />
                       
                  
                   
                </div>
                
             
                
                <div class="mb-3">
                    <label for="academicNotes" class="form-label">ملاحظات  عن الطالب  (إن وجدت)</label>
                    <textarea name="notes" class="form-control" id="academicNotes" rows="3"></textarea>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-primary prev-section" data-prev="section2">
                        <i class="fas fa-arrow-right me-2"></i> السابق
                    </button>
                    <button type="button" class="btn btn-primary next-section" data-next="section4">
                        التالي <i class="fas fa-arrow-left ms-2"></i>
                    </button>
                </div>
            </div>
            
                    <!-- القسم 4: اختيار المواد -->
        <div class="form-section" id="section4">
            <h3 class="section-title">اختيار المواد الدراسية</h3>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>ملاحظة:</strong> يرجى اختيار المواد المراد التسجيل فيها. يمكنك اختيار مادة واحدة أو أكثر حسب الحاجة.
            </div>
            
            <div class="row" id="subjectsContainer">
                <!-- مثال لبطاقة مادة - سيتم تعبئتها ديناميكياً -->
               
            </div>
            
            <div class="card mt-4 summary-card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-file-invoice me-2"></i>ملخص الطلب</h5>
                </div>
                <div class="card-body">
                    <div id="selectedSubjectsSummary">
                        <p class="text-muted mb-0">لم يتم اختيار أي مواد بعد</p>
                    </div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">المبلغ الإجمالي:</h5>
                            <h4 class="mb-0 fw-bold text-success"><span id="totalAmount">0</span>ل.س</h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-outline-primary prev-section" data-prev="section3">
                    <i class="fas fa-arrow-right me-2"></i> السابق
                </button>
                <button type="button" class="btn btn-primary next-section" data-next="section5">
                    التالي <i class="fas fa-arrow-left ms-2"></i>
                </button>
            </div>
        </div>
            
            <!-- القسم 5: اختبار تحديد المستوى -->
            <div class="form-section" id="section5">
                <h3 class="section-title">اختبار تحديد المستوى</h3>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>تنبيه:</strong> هذا الاختبار إلزامي لتقييم مستوى الطالب في المواد المختارة. الرجاء الإجابة بدقة.
                </div>
                
                <div id="placementTestContainer">
                    <!-- سيتم تعبئة الأسئلة ديناميكياً هنا -->
                    <div class="text-center py-5" id="loadingTest">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">تحميل...</span>
                        </div>
                        <p class="mt-3">جاري تحضير الاختبار...</p>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-primary prev-section" data-prev="section4">
                        <i class="fas fa-arrow-right me-2"></i> السابق
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i> إرسال الطلب
                    </button>
                </div>
            </div>
        </form>
    </div>
        @endsection

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
$(document).ready(function() {


async function checkUnique(field, value) {
    try {
        const response = await $.ajax({
            url: '/check-unique',
            type: 'POST',
            data: {
                field: field,
                value: value,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });
        return !response.exists; // true إذا غير موجود، false إذا موجود
    } catch (error) {
        return false; // أو ممكن ترجع null أو ترمي خطأ حسب ما تريد
    }
}



   function collectSubjectData() {
        const subjectsData = [];

        $('.subject-card').each(function() {
            const card = $(this);
            const subjectId = parseInt(card.data('subject-id'));
            const subjectName = card.find('.card-header').text().trim();
            const teacherId = parseInt(card.find('.teacher-select').val());

            // جمع إجابات اختبار التحديد لكل مادة (لو موجودة)
            const answers = {};
            $(`.subject-test[data-subject="${subjectId}"] .test-question`).each(function() {
                const questionElem = $(this);
                const radioName = questionElem.find('input[type=radio]').attr('name');
                const selectedOption = questionElem.find(`input[type=radio][name="${radioName}"]:checked`).val();
                if (radioName && selectedOption) {
                    answers[radioName] = selectedOption;
                }
            });

            const isSelected = card.find('.subject-checkbox').is(':checked');
            if (isSelected) {
                subjectsData.push({
                    subject_id: subjectId,
                    subject_name: subjectName,
                    teacher_id: teacherId,
                    answers: answers
                });
            }
        });

        return subjectsData;
    }

    // حدث إرسال النموذج (يضمن تعبئة full_data قبل الإرسال)
    $('#studentRegistrationForm').on('submit', async function(event) {
        
        let section1 = {
            fullname_sudent: $('#fullName').val(),
            birthDate: $('#birthDate').val(),
            gender: $('input[name="gender"]:checked').val(),
            phon: $('#phone').val(),
            national_id: $('#national_id').val(),
            emailp: $('#email').val(),
            password: $('#password').val(),
            loc: $('#address').val()
        };

        let section2 = {
            namep: $('#guardianName').val(),
            phonp: $('#guardianPhone').val(),
            email_pe: $('#guardianEmail').val(),
            relation: $('#guardianRelation').val()
        };

        let section3 = {
            working_hour_id: $('#working_hour').val(),
            education_stage_id: $('#education_stage').val(),
            classroom_id: $('#classroom').val(),
            notes: $('#academicNotes').val()
        };

        // جمع بيانات المواد مع الإجابات
        const subjects = collectSubjectData();

        let fullData = {
            student: section1,
            parent: section2,
            academic: section3,
            subjects: subjects
        };

        $('#full_data').val(JSON.stringify(fullData));
        // هنا لا تمنع الإرسال، يسمح بإرسال النموذج

        console.log('full_data:', fullData);
    });

    // ======= دوال التحقق لكل قسم =======

    async function validateSection1() {



// $('#phone').on('blur', function() {
//     checkUnique('phone', $(this).val(), function() {});
// });

        const name = $('#fullName').val()?.trim() || '';
        const birthDate = $('#birthDate').val() || '';
        const phone = $('#phone').val()?.trim() || '';
        const nationalId = $('#national_id').val()?.trim() || '';
        const gender = $('input[name="gender"]:checked').val();

        if (name.length < 6) {
            await Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الرجاء إدخال الاسم ويجب أن يكون 6 أحرف على الأقل.',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        if (!gender) {
            await Swal.fire({
                icon: 'error',
                title: 'الجنس',
                text: 'يرجى اختيار الجنس.',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        if (!birthDate) {
            await Swal.fire({
                icon: 'error',
                title: 'تاريخ الميلاد',
                text: 'يرجى تعبئة تاريخ الميلاد.',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        const selectedDate = new Date(birthDate);
        const today = new Date();
        if (selectedDate > today) {
            await Swal.fire({
                icon: 'error',
                title: 'تاريخ الميلاد',
                text: 'تاريخ الميلاد لا يمكن أن يكون في المستقبل.',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        const phoneDigits = phone.replace(/\D/g, '');
        if (phoneDigits.length < 9) {
            await Swal.fire({
                icon: 'error',
                title: 'رقم التواصل',
                text: 'رقم التواصل يجب أن يحتوي على 9 أرقام على الأقل.', 
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        const nationalIdDigits = nationalId.replace(/\D/g, '');
        if ((nationalIdDigits.length < 9)) {
            await Swal.fire({
                icon: 'error',
                title: 'رقم الهوية',
                text: '  رقم الهوية يجب أن يحتوي عل  10 أرقام.أو أكثر',
                confirmButtonText: 'حسناً'
            });
            return false;
        }



 const email = $('#email').val()?.trim() || '';
    console.log(email)
    const emailPattern = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;

    if (!emailPattern.test(email)) {
        await Swal.fire({
            icon: 'error',
            title: 'البريد الإلكتروني',
            text: 'يجب أن يكون البريد الإلكتروني صالحاً وينتهي بـ @gmail.com',
            confirmButtonText: 'حسناً'
        });
        // ترجع false لمنع ترك الحقل لو حابب (لكن منع ترك الحقل بالـ blur مش مضمون)
        return false;
    }

    if (email) {
        const isUnique = await checkUnique('email', email);
        if (!isUnique) {
            await Swal.fire({
                icon: 'error',
                title: 'البريد الإلكتروني',
                text: 'البريد الإلكتروني مستخدم من قبل',
                confirmButtonText: 'حسناً'
            });
            return false;
        }
    }





 
const password = $('#password').val() || '';
if (password.length < 8) {
    await Swal.fire({
        icon: 'error',
        title: 'كلمة المرور',
        text: 'يجب أن تكون كلمة المرور 8 أحرف على الأقل.',
        confirmButtonText: 'حسناً'
    });
    return false;
}
const address = $('#address').val()?.trim() || '';
if (address.length < 5) {
    await Swal.fire({
        icon: 'error',
        title: 'العنوان',
        text: 'يجب تعبئة العنوان (5 أحرف على الأقل).',
        confirmButtonText: 'حسناً'
    });
    return false;
}




        return true;
    }

    async function validateSection2() {
        const guardianName = $('#guardianName').val()?.trim() || '';
        const guardianPhone = $('#guardianPhone').val()?.trim() || '';
        const guardianEmail = $('#guardianEmail').val()?.trim() || '';
        const guardianRelation = $('#guardianRelation').val() || '';

        if (guardianName.length === 0) {
            await Swal.fire({
                icon: 'error',
                title: 'اسم ولي الأمر',
                text: 'يرجى إدخال اسم ولي الأمر.',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        const phoneDigits = guardianPhone.replace(/\D/g, '');
        if (phoneDigits.length < 9) {
            await Swal.fire({
                icon: 'error',
                title: 'رقم تواصل ولي الأمر',
                text: 'رقم التواصل يجب أن يحتوي على 9 أرقام على الأقل.',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

  
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(guardianEmail)  ||guardianEmail.length===0) {
                await Swal.fire({
                    icon: 'error',
                    title: 'بريد ولي الأمر الإلكتروني',
                    text: 'يرجى إدخال بريد إلكتروني صالح.',
                    confirmButtonText: 'حسناً'
                });
                return false;
            }
        

        if (guardianRelation === '') {
            await Swal.fire({
                icon: 'error',
                title: 'صلة القرابة',
                text: 'يرجى اختيار صلة القرابة.',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        return true;
    }

  async function validateSection3() {
    const workingHour = $('#working_hour').val();
    const educationStage = $('#education_stage').val();
    const classroom = $('#classroom').val();

    if (!workingHour) {
        await Swal.fire({
            icon: 'error',
            title: 'الفوج الدراسي',
            text: 'يرجى اختيار الفوج الدراسي.',
            confirmButtonText: 'حسناً'
        });
        return false;
    }

    if (!educationStage) {
        await Swal.fire({
            icon: 'error',
            title: 'المرحلة الدراسية',
            text: 'يرجى اختيار المرحلة الدراسية.',
            confirmButtonText: 'حسناً'
        });
        return false;
    }

    if (!classroom) {
        await Swal.fire({
            icon: 'error',
            title: 'الصف الدراسي',
            text: 'يرجى اختيار الصف الدراسي.',
            confirmButtonText: 'حسناً'
        });
        return false;
    }

    return true;
}


    // يمكنك إضافة validateSection4, validateSection5 حسب الحاجة...

    // ========== التحكم بالتنقل بين الأقسام ==========

    // خريطة لأسماء دوال التحقق حسب القسم
    const validators = {
        'section1': validateSection1,
        'section2': validateSection2,
        'section3': validateSection3,
        // 'section4': validateSection4,
        // 'section5': validateSection5,
    };

    $('.next-section').click(async function() {
        const currentSection = $('.form-section.active').attr('id');
        const validateFn = validators[currentSection];
        if (validateFn) {
            const valid = await validateFn();
            if (!valid) return; // إذا فشل التحقق، لا ينتقل
        }

        const nextSection = $(this).data('next');
        if (nextSection) {
            $('.form-section').removeClass('active');
            $('#' + nextSection).addClass('active');

            updateProgressBar(nextSection);
            updateStepIndicator(nextSection);

            // تحميل بيانات خاصة حسب القسم (مثلاً المواد في section4)
            if (nextSection === 'section4') {
                const classroomId = $('#classroom').val();
                loadSubjectsByClassroom(classroomId, document.getElementById('subjectsContainer'));
            }

            if (nextSection === 'section5') {
                loadPlacementTest();
            }

            if (nextSection === 'section3') {
                loadOptionsIntoSelect('working_hour', '/educational_stage/create', '-- اختر الفوج الدراسي --');
                setupDependentSelect('working_hour', 'education_stage', '/educational_stage/get_based_on_working/:id', 'جاري تحميل المراحل...', '-- اختر المرحلة --');
                setupDependentSelect('education_stage', 'classroom', '/classroom/get_based_on_stage/:id', 'جاري تحميل الصفوف...', '-- اختر الصف --');         
            }
        }
    });

    $('.prev-section').click(function() {
        const prevSection = $(this).data('prev');
        if (prevSection) {
            $('.form-section').removeClass('active');
            $('#' + prevSection).addClass('active');

            updateProgressBar(prevSection);
            updateStepIndicator(prevSection);
        }
    });

    // ========== باقي الدوال التي لديك ==========

    function updateProgressBar(section) {
        const progress = {
            'section1': 20,
            'section2': 40,
            'section3': 60,
            'section4': 80,
            'section5': 100
        };
        $('#progressBar').css('width', progress[section] + '%');
    }

    function updateStepIndicator(currentSection) {
        const steps = {
            'section1': 'step1',
            'section2': 'step2',
            'section3': 'step3',
            'section4': 'step4',
            'section5': 'step5'
        };

        $('.step').removeClass('active completed');
        let foundCurrent = false;
        for (const [section, step] of Object.entries(steps)) {
            if (section === currentSection) {
                $('#' + step).addClass('active');
                foundCurrent = true;
            } else if (!foundCurrent) {
                $('#' + step).addClass('completed');
            }
        }
    }
       $('#email').on('blur', function() {
    checkUnique('email', $(this).val(), function() {});
});


    function loadSubjectsByClassroom(classroomId, container) {
        setTimeout(() => {
            fetch(`/request/classroom/${classroomId}/subjects`)
                .then(response => {
                    if (!response.ok) throw new Error('خطأ في الاتصال بالسيرفر');
                    return response.json();
                })
                .then(subjects => {
                    container.innerHTML = '';

                    if (!subjects || subjects.length === 0) {
                        container.innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    لا توجد مواد متاحة لهذا الصف حالياً. يرجى التواصل مع الإدارة.
                                </div>
                            </div>
                        `;
                        return;
                    }

                    subjects.forEach(subject => {
                        const teachersOptions = subject.teachers.map(teacher =>
                            `<option value="${teacher.id}">${teacher.name} - ${teacher.price} ل.س</option>`
                        ).join('');

                        container.innerHTML += `
                            <div class="col-md-4 mb-4 subject-card" data-subject-id="${subject.id}">
                                <div class="card h-100">
                                    <div class="card-header">
                                        ${subject.name}
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">${subject.note || ''}</p>
                                        <div class="mb-3">
                                            <label class="form-label">المعلم:</label>
                                            <select name='teacherid' class="form-select teacher-select">
                                                ${teachersOptions}
                                            </select>
                                        </div>
                                        <div class="form-check">
                                            <input name='chname' class="form-check-input subject-checkbox" type="checkbox" value="${subject.id}" id="${subject.id}_check">
                                            <label class="form-check-label" for="${subject.id}_check">اختيار هذه المادة</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                })
                .catch(() => {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger">
                                حدث خطأ أثناء جلب المواد. يرجى المحاولة لاحقاً.
                            </div>
                        </div>
                    `;
                });
        }, 500);
    }

    function updateSelectedSubjectsSummary() {
        const selectedSubjects = [];
        let totalAmount = 0;

        $('.subject-checkbox:checked').each(function() {
            const card = $(this).closest('.card');
            const subjectName = card.find('.card-header').text().trim();

            const teacherSelect = card.find('.teacher-select');
            const selectedOption = teacherSelect.find('option:selected').text();

            const [teacherName, priceText] = selectedOption.split(' - ');
            const price = parseFloat(priceText.replace(/[^\d.]/g, '')) || 0;

            selectedSubjects.push({
                name: subjectName,
                teacher: teacherName.trim(),
                price: price
            });

            totalAmount += price;
        });

        let summaryHtml = '';
        if (selectedSubjects.length > 0) {
            summaryHtml += '<ul class="list-group list-group-flush">';
            selectedSubjects.forEach(subject => {
                summaryHtml += `
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${subject.name}</h6>
                            <small class="text-muted">${subject.teacher}</small>
                        </div>
                        <span class="badge bg-primary rounded-pill">${subject.price} ل.س</span>
                    </li>
                `;
            });
            summaryHtml += '</ul>';
        } else {
            summaryHtml = '<p class="text-muted mb-0">لم يتم اختيار أي مواد بعد</p>';
        }

        $('#selectedSubjectsSummary').html(summaryHtml);
        $('#totalAmount').text(totalAmount.toFixed(2));
    }
    $(document).on('change', '.subject-checkbox, .teacher-select', function() {
        updateSelectedSubjectsSummary();
    });

    function loadPlacementTest() {
        const container = $('#placementTestContainer');
        const classroomId = $('#classroom').val();
        $('#loadingTest').show();
        container.empty();

        const selectedSubjects = [];
        $('.subject-checkbox:checked').each(function() {
            selectedSubjects.push($(this).val());
        });

        if (selectedSubjects.length === 0) {
            $('#loadingTest').hide();
            container.append(`
                <div class="alert alert-danger">
                    لم يتم اختيار أي مواد. الرجاء العودة واختيار مواد لإجراء الاختبار.
                </div>
            `);
            return;
        }

        const requests = selectedSubjects.map(subjectId => {
            return fetch(`/questions/classroom/${classroomId}/subject/${subjectId}`)
                .then(response => {
                    if (!response.ok) throw new Error('خطأ في تحميل الأسئلة');
                    return response.json();
                })
                .then(subjectTests => {
                    const subjectName = $(`[data-subject-id="${subjectId}"] .card-header`).text();

                    if (subjectTests.length === 0) {
                        return `
                            <div class="alert alert-info">
                                لا يوجد اختبار تحديد مستوى لمادة ${subjectName} حالياً.
                            </div>
                        `;
                    }

                    let testHtml = `
                        <div class="card mb-4 subject-test" data-subject="${subjectId}">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">اختبار مادة ${subjectName}</h5>
                            </div>
                            <div class="card-body">
                    `;

                    subjectTests.forEach((test, index) => {
                        testHtml += `
                            <div class="test-question">
                                <h5>السؤال ${index + 1}: ${test.question_text}</h5>
                                <div class="ps-3">
                        `;

                        const options = [];
                        ['option_a', 'option_b', 'option_c', 'option_d'].forEach(key => {
                            if (test[key]) {
                                options.push({ id: `${key}`, text: test[key] });
                            }
                        });

                        options.forEach(option => {
                            testHtml += `
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="${subjectId}_q${test.id}" id="${option.id}" value="${option.id}">
                                    <label class="form-check-label" for="${option.id}">${option.text}</label>
                                </div>
                            `;
                        });

                        testHtml += `
                                </div>
                            </div>
                        `;
                    });

                    testHtml += `
                            </div>
                        </div>
                    `;

                    return testHtml;
                })
                .catch(() => {
                    return `
                        <div class="alert alert-danger">
                            حدث خطأ أثناء جلب اختبار مادة ${subjectId}.
                        </div>
                    `;
                });
        });

        Promise.all(requests).then(resultsHtmlArray => {
            $('#loadingTest').hide();
            container.html(resultsHtmlArray.join(''));
        });
    }






}); // نهاية document ready
</script>
