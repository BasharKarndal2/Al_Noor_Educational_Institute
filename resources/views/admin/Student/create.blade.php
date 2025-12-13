


<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addStudentModalLabel"><i class="fas fa-user-plus me-2"></i>إضافة طالب جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStudentForm" action="{{ route('student.store') }}" method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" >
                    @csrf

                    <div class="row g-3">
                        <x-input-field nameinput="name" id='nameadd' label="اسم الطالب الثلاثي" type="text" />
      <x-input-field nameinput="national_id" id="national_idadd" label="  رقم الهوية" type="text" />


                        <div class="col-md-6">
                            <label for="birthDate" class="form-label required">تاريخ الميلاد</label>
                            <input type="date" class="form-control" id="birthDateadd" name="date_of_birth"  value="{{ old('date_of_birth') }}" required>
                            <div class="invalid-feedback">يرجى إدخال تاريخ الميلاد</div>
                        </div>

                        <div class="col-md-6">
                            <label for="gender" class="form-label required">الجنس</label>
                            <select class="form-select" id="genderadd" name="gender" required>
                                <option value="">اختر...</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                            <div class="invalid-feedback">يرجى اختيار الجنس</div>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea class="form-control" id="addressadd" name="address" rows="2">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label required">رقم الهاتف</label>
                            <input type="text" class="form-control" id="phoneadd" name="phone" value="{{ old('phone') }}" required>
                            <div class="invalid-feedback">يرجى إدخال رقم الهاتف</div>
                        </div>

                        <div class="col-md-6">
                            <label for="emailadd" class="form-label required">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="emailadd" name="email" value="{{ old('email') }}" required>
                            <div class="invalid-feedback">يرجى إدخال البريد الإلكتروني</div>
                        </div>

                        <x-status />

                        <div class="col-md-6">
                            <label for="password" class="form-label required">كلمة المرور</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="passwordadd" name="password" required minlength="8">
                                <i class="fas fa-eye-slash password-toggle" data-target="password"></i>
                                <div class="invalid-feedback">كلمة المرور يجب أن تكون 8 أحرف على الأقل</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="teacherPhoto" class="form-label">صورة الطالب</label>
                            <input type="file" class="form-control" id="teacherPhoto" name="image_path" accept="image/*">
                            <small class="text-muted">الصور المسموح بها: JPG, PNG بحد أقصى 2MB</small>
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label">ملاحظات اضافية</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        </div>

                        <h1>مكان اضافة الطالب</h1>
                        <x-select-field label="الفوج الدراسي" id="working_houradd" name="working_hour_id" required />
                        <x-select-field label="المرحلة الدراسية" id="education_stageadd" name="education_stage_id" required />
                        <x-select-field label="الصف الدراسية" id="classroomadd" name="classroom_id" required />

                        <div class="col-md-12">
                            <label for="subjectsadd" class="form-label required">اختر المواد</label>
                            <select class="form-select" id="subjectsadd" name="subject_ids[]" multiple required>
                                <!-- سيتم تعبئة الخيارات من AJAX -->
                            </select>
                        </div>

                        <!-- مكان ديناميكي لظهور قائمة المعلمين لكل مادة -->
                        <div id="teachers-container" class="row g-3 mt-3"></div>

                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="addStudentForm" class="btn btn-primary">حفظ الطالب</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    loadOptionsIntoSelect('working_houradd', '/educational_stage/create', '-- اختر الفوج الدراسي --');

    setupDependentSelect(
        'working_houradd',
        'education_stageadd',
        '/educational_stage/get_based_on_working/:id',
        'جاري تحميل المراحل...',
        '-- اختر المرحلة --'
    );
    setupDependentSelect(
        'education_stageadd',
        'classroomadd',
        '/classroom/get_based_on_stage/:id',
        'جاري تحميل الصفوف...',
        '-- اختر الصف --'
    );
});
</script>

<script>
    $('#classroomadd').on('change', function () {
        let classroomId = $(this).val();
        $('#subjectsadd').empty().append('<option disabled>جارٍ التحميل...</option>');
        $('#teachers-container').empty();

        $.get('/classroom/' + classroomId + '/subjects', function (data) {
            let subjectSelect = $('#subjectsadd');
            subjectSelect.empty();
            data.forEach(subject => {
                subjectSelect.append(`<option value="${subject.id}">${subject.name}</option>`);
            });
        });
    });

  $('#subjectsadd').on('change', function () {
    let selectedSubjects = $(this).val() || [];
    $('#teachers-container').empty();
    let classroomId =  $('#classroomadd').val();
    selectedSubjects.forEach(subjectId => {
        $.get('/subject/' + subjectId + '/classroom/'+classroomId+'/teachers', function (subject) {
            let teacherSelect = `
                <div class="col-md-6">
                    <label class="form-label">اختر معلماً للمادة ( ${subject.name} )</label>
                    <select class="form-select" name="teachers_for_subject[${subjectId}]" required>
                        <option value="">اختر معلماً...</option>
                        ${subject.teachers.map(t => `<option value="${t.id}">${t.name}</option>`).join('')}
                    </select>
                </div>
            `;
            $('#teachers-container').append(teacherSelect);
        });
    });
});
</script>
<script>
    // دالة لفحص التكرار في قاعدة البيانات (phone, email, national_id)
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

    $(document).ready(function () {
        $('#addStudentForm').on('submit', async function (e) {
            e.preventDefault();

            const name       = $('#nameadd').val()?.trim();
            const nationalId = $('#national_idadd').val()?.trim();
            const birthDate  = $('#birthDateadd').val();
            const gender     = $('#genderadd').val();
            const phone      = $('#phoneadd').val()?.trim();
            const email      = $('#emailadd').val()?.trim();
            const password   = $('#passwordadd').val();
            const classroom  = $('#classroomadd').val();
            const subjects   = $('#subjectsadd').val() || [];

            // التحقق من الاسم
            if (!name || name.length < 3) {
                Swal.fire('خطأ', 'اسم الطالب يجب أن يكون 3 أحرف على الأقل', 'error');
                return;
            }

            // التحقق من رقم الهوية
            if (!nationalId || nationalId.length < 7 || nationalId.length > 15) {
                Swal.fire('خطأ', 'رقم الهوية يجب أن يكون بين 7 و 15 رقم', 'error');
                return;
            }
           
            // التحقق من تاريخ الميلاد
            if (!birthDate) {
                Swal.fire('خطأ', 'يرجى تحديد تاريخ الميلاد', 'error');
                return;
            }
            const today = new Date().setHours(0,0,0,0);
            const birth = new Date(birthDate).setHours(0,0,0,0);
            if (birth > today) {
                Swal.fire('خطأ', 'تاريخ الميلاد لا يمكن أن يكون في المستقبل', 'error');
                return;
            }

            // التحقق من الجنس
            if (!gender) {
                Swal.fire('خطأ', 'يرجى اختيار الجنس', 'error');
                return;
            }

            // التحقق من الهاتف
            const phoneDigits = phone.replace(/\D/g, '');
            if (phoneDigits.length < 9) {
                Swal.fire('خطأ', 'رقم الهاتف يجب أن يكون 9 أرقام على الأقل', 'error');
                return;
            }
        

            // التحقق من البريد الإلكتروني
            if (!email) {
                Swal.fire('خطأ', 'يرجى إدخال البريد الإلكتروني', 'error');
                return;
            }
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                Swal.fire('خطأ', 'صيغة البريد الإلكتروني غير صحيحة', 'error');
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
            

            // التحقق من كلمة المرور
            if (!password || password.length < 8) {
                Swal.fire('خطأ', 'كلمة المرور يجب أن تكون 8 أحرف على الأقل', 'error');
                return;
            }

            // التحقق من الصف
            if (!classroom) {
                Swal.fire('خطأ', 'يرجى اختيار الصف الدراسي', 'error');
                return;
            }

            // التحقق من المواد والمعلمين
            if (subjects.length === 0) {
                Swal.fire('خطأ', 'يرجى اختيار مادة واحدة على الأقل', 'error');
                return;
            }
            let missingTeacher = false;
            subjects.forEach(subId => {
                if (!$(`[name="teachers_for_subject[${subId}]"]`).val()) {
                    missingTeacher = true;
                }
            });
            if (missingTeacher) {
                Swal.fire('خطأ', 'يرجى اختيار معلم لكل مادة', 'error');
                return;
            }

            // ✅ إذا كل شيء تمام → إرسال الفورم
            this.submit();
        });
    });
</script>
