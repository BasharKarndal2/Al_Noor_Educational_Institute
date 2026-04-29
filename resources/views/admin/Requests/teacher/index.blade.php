@extends('layout.request.dashboard')


@section('content')



@if(session('request_success'))
<script>
    Swal.fire({
        title: '🎉 تسجيل الطلب ناجح!',
        html: '<b>في حال تم قبول الطلب سوف يتم التواصل معك</b><br>سيتم تحويلك  الى  الصفحة الرئيسية خلال لحظات...',
        icon: 'success',
        timer: 7000,
        showConfirmButton: false,
        willClose: () => {
            window.location.href = '{{ route('home') }}';
        }
    });
</script>
@endif
    <div id="teacherRegistrationFormContainer">
    <div class="container py-4">
        <form id="teacherRegistrationForm" enctype="multipart/form-data"  method="POST" action="{{ route('request_teacher.stor') }}">
            
            @csrf
            <div class="form-section active">
                <h3 class="section-title">نموذج تسجيل المعلم</h3>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>ملاحظة:</strong> يرجى تعبئة جميع الحقول المطلوبة بدقة لتسهيل عملية المراجعة.
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="teacherFullName" class="form-label">الاسم الكامل</label>
                        <input type="text" class="form-control" id="teacherFullName" name='full_name' required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="teacherId" class="form-label">رقم الهوية</label>
                        <input type="text"  name="identity_number" class="form-control" id="teacherId" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="teacherBirthDate" class="form-label">تاريخ الميلاد</label>
                        <input type="date" name="birth_date" class="form-control" id="teacherBirthDate" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الجنس</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="teacherGender" id="teacherMale" value="male" required>
                                <label class="form-check-label" for="teacherMale">ذكر</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="teacherGender" id="teacherFemale" value="female">
                                <label class="form-check-label" for="teacherFemale">أنثى</label>
                            </div>
                        </div>
                    </div>
                </div>
               
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="teacherEducation" class="form-label">الشهادة العلمية</label>
                        <select class="form-select" name="education" id="teacherEducation" required>
                            <option value="" selected disabled>اختر الشهادة</option>
                            <option value="diploma">دبلوم</option>
                            <option value="bachelor">بكالوريوس</option>
                            <option value="master">ماجستير</option>
                            <option value="phd">دكتوراه</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="teacherSpecialization" class="form-label">التخصص</label>
                        <input type="text" class="form-control" name="specialization" id="teacherSpecialization" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="teacherExperience" class="form-label">سنوات الخبرة</label>
                        <input type="number" class="form-control" name="experience_years" id="teacherExperience" min="0" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="teacherPreviousWork" class="form-label">الأعمال السابقة</label>
                        <input type="text" name="previous_work"  class="form-control" id="teacherPreviousWork" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="teacherSalarySYP" class="form-label">الراتب المتوقع (ليرة سورية)</label>
                        <div class="input-group">
                            <input type="number" name="salary_syp" class="form-control" id="teacherSalarySYP" min="0" required>
                            <span class="input-group-text">ل.س</span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="teacherSalaryUSD" class="form-label">الراتب المتوقع (دولار)</label>
                        <div class="input-group">
                            <input type="number" name="salary_usd" class="form-control" id="teacherSalaryUSD" min="0" required>
                            <span class="input-group-text">$</span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    
                    <div class="col-md-6 mb-3">
                        <label for="teacherEmail" class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" id="teacherEmail" required>
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="teacherPassword" class="form-label">كلمة المرور </label>
                        <input type="password" name="password" class="form-control" id="teacherPassword" required>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="teacherPhone" class="form-label">رقم الهاتف</label>
                        <input type="tel" name="phone" class="form-control" id="teacherPhone" required>
                    </div>
                    <div class="col-md-6 mb-3">
                    <label for="teacherAddress"  class="form-label">العنوان الحالي</label>
                    <input type="text" name="address" class="form-control" id="teacherAddress" required>
                    </div>
                    
                </div>
                
                <div class="mb-3">
                    <label for="teache rPhoto" class="form-label">صورة شخصية (اختيارية)</label>
                    <input type="file" name="photo_path" class="form-control" id="teacherPhoto" accept="image/*">
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="teacherAgreement" required>
                    <label class="form-check-label" for="teacherAgreement">أوافق على شروط وسياسات المعهد</label>
                </div>
                
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i> إرسال الطلب
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>



async function checkUnique(field, value) {
    try {
        const response = await $.ajax({
            url: '/check-unique_teacher',
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


$(document).ready(function() {

    $('#teacherRegistrationForm').on('submit', async function(e) {
        e.preventDefault();

          
        const name = $('#teacherFullName').val().trim();
        const idNumber = $('#teacherId').val().trim();
        const birthDate = $('#teacherBirthDate').val();
        const gender = $('input[name="teacherGender"]:checked').val();
        const education = $('#teacherEducation').val();
        const specialization = $('#teacherSpecialization').val().trim();
        const previousWork = $('#teacherPreviousWork').val().trim();
        const salarySYP = parseFloat($('#teacherSalarySYP').val());
        const salaryUSD = parseFloat($('#teacherSalaryUSD').val());
        const email = $('#teacherEmail').val().trim();
        const password = $('#teacherPassword').val();
        const phone = $('#teacherPhone').val().trim();

        // تحقق الاسم
        if(name.length < 6) {
            Swal.fire('خطأ', 'الاسم الكامل يجب أن يكون 6 أحرف على الأقل', 'error');
            return;
        }

        // تحقق رقم الهوية
        if(idNumber.length <= 7 || idNumber.length >= 15) {
            Swal.fire('خطأ', 'رقم الهوية يجب أن يكون أكثر من 7 وأقل من 15 رقم', 'error');
            return;
        }

        // تحقق تاريخ الميلاد (لا يكون في المستقبل)
        if(!birthDate) {
            Swal.fire('خطأ', 'يرجى تحديد تاريخ الميلاد', 'error');
            return;
        }
        const today = new Date().setHours(0,0,0,0);
        const birth = new Date(birthDate).setHours(0,0,0,0);
        if(birth > today) {
            Swal.fire('خطأ', 'تاريخ الميلاد لا يمكن أن يكون في المستقبل', 'error');
            return;
        }

        // تحقق اختيار الجنس
        if(!gender) {
            Swal.fire('خطأ', 'يرجى اختيار الجنس', 'error');
            return;
        }

        // تحقق الشهادة العلمية
        if(!education) {
            Swal.fire('خطأ', 'يرجى اختيار الشهادة العلمية', 'error');
            return;
        }

        // تحقق تخصص لا يقل عن 5 أحرف
        if(specialization.length < 5) {
            Swal.fire('خطأ', 'التخصص يجب أن يكون أكثر من 4 أحرف', 'error');
            return;
        }

        // تحقق الأعمال السابقة (ليست فارغة)
        if(previousWork.length === 0) {
            Swal.fire('خطأ', 'الأعمال السابقة لا يمكن أن تكون فارغة', 'error');
            return;
        }

        // تحقق الراتب المتوقع لا يقل عن صفر
        if(isNaN(salarySYP) || salarySYP < 0) {
            Swal.fire('خطأ', 'الراتب المتوقع بالليرة السورية يجب أن يكون صفر أو أكثر', 'error');
            return;
        }
        if(isNaN(salaryUSD) || salaryUSD < 0) {
            Swal.fire('خطأ', 'الراتب المتوقع بالدولار يجب أن يكون صفر أو أكثر', 'error');
            return;
        }

        if (email.length === 0) {
             Swal.fire({
                icon: 'error',
                title: 'البريد الإلكتروني',
                text: 'البريد الإلكتروني لا يمكن أن يكون فارغاً',
                confirmButtonText: 'حسناً'
            });
            return false;
        }

        // تحقق البريد الإلكتروني (إن وُجد)
        if(email.length > 0) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!emailPattern.test(email)) {
                Swal.fire('خطأ', 'البريد الإلكتروني غير صحيح', 'error');
                return;
            }
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


if (password.length == 0) {
     Swal.fire({
            icon: 'error',
            title: 'كلمة المرور',
            text: 'كلمة المرور لا يمكن أن تكون فارغة',
            confirmButtonText: 'حسناً'
        });
        return false;
}
        
        // تحقق كلمة المرور (إن وُجد)
        if(password.length > 0 && password.length <= 8  ) {
            Swal.fire('خطأ', 'كلمة المرور يجب أن تكون أكثر من 8 أحرف', 'error');
            return;
        }


        // تحقق رقم الهاتف (أكثر من 9 أرقام)
        const phoneDigits = phone.replace(/\D/g, '');
        if(phoneDigits.length <= 9) {
            Swal.fire('خطأ', 'رقم الهاتف يجب أن يكون أكثر من 9 أرقام', 'error');
            return;
        }


        Swal.fire({
            title: 'جاري تسجيل الطلب',
            html: 'الرجاء الانتظار...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            this.submit();
        }, 500);
    });

        // كل الشروط تم تحققها - نرسل الفورم
        
    });
</script>
@endpush