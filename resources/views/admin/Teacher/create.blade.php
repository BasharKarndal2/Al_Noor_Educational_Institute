<!-- مودال إضافة معلم جديد -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- رأس المودال -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addTeacherModalLabel">
                    <i class="fas fa-user-plus me-2"></i>إضافة معلم جديد
                </h5>
                
            </div>

            <!-- جسم المودال -->
            <div class="modal-body text-right">
                <form id="addTeacherForm" method="POST" action="{{ route('teaher.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="row g-3">

                        <!-- الاسم الثلاثي -->
                        <div class="col-md-6">
                            <label for="full_name" class="form-label required">الاسم الثلاثي</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                            <div class="invalid-feedback">يرجى إدخال الاسم الثلاثي</div>
                        </div>

                        <!-- رقم الهوية -->
                        <div class="col-md-6">
                            <label for="national_id" class="form-label required">رقم الهوية</label>
                            <input type="text" class="form-control" id="national_id" name="national_id" value="{{ old('national_id') }}" required>
                            <div class="invalid-feedback">يرجى إدخال رقم الهوية</div>
                        </div>

                        <!-- تاريخ الميلاد -->
                        <div class="col-md-6">
                            <label for="birthDate" class="form-label required">تاريخ الميلاد</label>
                            <input type="date" class="form-control" id="birthDate" name="birth_date" value="{{ old('birth_date') }}" required>
                            <div class="invalid-feedback">يرجى إدخال تاريخ الميلاد</div>
                        </div>

                        <!-- الجنس -->
                        <div class="col-md-6">
                            <label for="gender" class="form-label required">الجنس</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">اختر...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                            <div class="invalid-feedback">يرجى اختيار الجنس</div>
                        </div>

                        <!-- الحالة الاجتماعية -->
                        <div class="col-md-6">
                            <label for="marital" class="form-label required">الحالة الإجتماعية</label>
                            <select class="form-select" id="marital" name="marital" required>
                                <option value="">اختر...</option>
                                <option value="single" {{ old('marital') == 'single' ? 'selected' : '' }}>أعزب</option>
                                <option value="married" {{ old('marital') == 'married' ? 'selected' : '' }}>متزوج</option>
                            </select>
                            <div class="invalid-feedback">يرجى اختيار الحالة</div>
                        </div>

                        <!-- التخصص -->
                        <div class="col-md-6">
                            <label for="specialization" class="form-label required">التخصص</label>
                            <input type="text" class="form-control" id="specialization" name="specialization" value="{{ old('specialization') }}" required>
                            <div class="invalid-feedback">يرجى إدخال التخصص</div>
                        </div>

                        <!-- سنوات الخبرة -->
                        <div class="col-md-6">
                            <label for="experience" class="form-label">سنوات الخبرة</label>
                            <input type="number" class="form-control" id="experience" name="experience" min="0" value="{{ old('experience') }}">
                        </div>

                        <!-- رقم الهاتف -->
                        <div class="col-md-6">
                            <label for="phone" class="form-label required">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                            <div class="invalid-feedback">يرجى إدخال رقم الهاتف</div>
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-6">
                            <label for="status" class="form-label required">الحالة</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                            </select>
                        </div>

                        <!-- تاريخ التعيين -->
                        <div class="col-md-6">
                            <label for="hireDate" class="form-label required">تاريخ التعيين</label>
                            <input type="date" class="form-control" id="hireDate" name="hire_date" value="{{ old('hire_date') }}" required>
                            <div class="invalid-feedback">يرجى إدخال تاريخ التعيين</div>
                        </div>

                        <!-- العنوان -->
                        <div class="col-12">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea class="form-control" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="col-md-6">
                            <label for="email" class="form-label required">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            <div class="invalid-feedback">يرجى إدخال البريد الإلكتروني</div>
                        </div>

                        <!-- كلمة المرور -->
                        <div class="col-md-6">
                            <label for="password" class="form-label required">كلمة المرور</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="password" name="password" required minlength="8">
                                <div class="invalid-feedback">كلمة المرور يجب أن تكون 8 أحرف على الأقل</div>
                            </div>
                        </div>

                        <!-- صورة المعلم -->
                        <div class="col-md-12">
                            <label for="teacherPhoto" class="form-label">صورة المعلم</label>
                            <input type="file" class="form-control" id="teacherPhoto" name="image_path" accept="image/*">
                            <small class="text-muted">الصور المسموح بها: JPG, PNG بحد أقصى 2MB</small>
                        </div>

                        <!-- ملاحظات اضافية -->
                        <div class="col-12">
                            <label for="notes" class="form-label">ملاحظات اضافية</label>
                            <textarea class="form-control" id="notes" name="not" rows="2">{{ old('not') }}</textarea>
                        </div>

                    </div>
                </form>
            </div>

            <!-- تذييل المودال -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="addTeacherForm" class="btn btn-primary">حفظ المعلم</button>
            </div>
        </div>
    </div>
</div>
<script>
      document.querySelectorAll('.password-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });






   // دالة لفحص التكرار
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
            return !response.exists;
        } catch (error) {
            return false;
        }
    }

    $(document).ready(function() {
        $('#addTeacherForm').on('submit', async function(e) {
            e.preventDefault();

            const name = $('#full_name').val().trim();
            const idNumber = $('#national_id').val().trim();
            const birthDate = $('#birthDate').val();
            const gender = $('#gender').val();
            const marital = $('#marital').val();
            const specialization = $('#specialization').val().trim();
            const experience = $('#experience').val();
            const phone = $('#phone').val().trim();
            const status = $('#status').val();
            const hireDate = $('#hireDate').val();
            const address = $('#address').val().trim();
            const email = $('#email').val().trim();
            const password = $('#password').val();
            const notes = $('#notes').val().trim();

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

            // تحقق تاريخ الميلاد
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

            // تحقق الحالة الاجتماعية
            if(!marital) {
                Swal.fire('خطأ', 'يرجى اختيار الحالة الاجتماعية', 'error');
                return;
            }

            // تحقق التخصص
            if(specialization.length < 5) {
                Swal.fire('خطأ', 'التخصص يجب أن يكون أكثر من 4 أحرف', 'error');
                return;
            }

            // تحقق رقم الهاتف
            const phoneDigits = phone.replace(/\D/g, '');
            if(phoneDigits.length <= 9) {
                Swal.fire('خطأ', 'رقم الهاتف يجب أن يكون أكثر من 9 أرقام', 'error');
                return;
            }

            // تحقق تاريخ التعيين
            if(!hireDate) {
                Swal.fire('خطأ', 'يرجى تحديد تاريخ التعيين', 'error');
                return;
            }

            // تحقق البريد الإلكتروني
            if(email.length > 0) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if(!emailPattern.test(email)) {
                    Swal.fire('خطأ', 'البريد الإلكتروني غير صحيح', 'error');
                    return;
                }

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

            // تحقق كلمة المرور
            if(password.length < 8) {
                Swal.fire('خطأ', 'كلمة المرور يجب أن تكون 8 أحرف على الأقل', 'error');
                return;
            }

            // إذا كل الشروط صحيحة → إرسال الفورم
            this.submit();
        });
    });
</script>