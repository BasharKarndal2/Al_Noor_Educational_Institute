<!-- Modal إضافة ولي أمر -->
<div class="modal fade" id="addParentModal" tabindex="-1" aria-labelledby="addParentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addParentModalLabel">إضافة ولي أمر جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addParentModalform" method="POST" action="{{ route('pearant.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">الاسم الكامل</label>
                            <input type="text" id="parentName" name="name" class="form-control" placeholder="يرجى إدخال اسم ولي الأمر" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">رقم الهوية</label>
                            <input type="text" id="parentId" name="national_id" class="form-control" placeholder="يرجى إدخال رقم الهوية" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">تاريخ الميلاد</label>
                            <input type="date" id="parentBirthDate" name="date_of_birth" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">الجنس</label>
                            <select id="parentGender" name="gender" class="form-select" required>
                                <option value="" selected disabled>اختر...</option>
                                <option value="male">ذكر</option>
                                <option value="female">أنثى</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">صلة القرابة</label>
                            <select id="parentRelation" name="relation" class="form-select" required>
                                <option value="" selected disabled>اختر صلة القرابة</option>
                                <option value="father">الأب</option>
                                <option value="mother">الأم</option>
                                <option value="brother">الأخ</option>
                                <option value="sister">الأخت</option>
                                <option value="uncle">العم/الخال</option>
                                <option value="other">آخر</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">رقم الهاتف</label>
                            <input type="text" id="parentPhone" name="phone" class="form-control" placeholder="يرجى إدخال رقم هاتف صحيح" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">الحالة</label>
                            <select name="status" class="form-select" required>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="address" class="form-control" placeholder="يرجى إدخال العنوان">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">البريد الإلكتروني</label>
                            <input type="email" id="parentEmail" name="email" class="form-control" placeholder="يرجى إدخال البريد الإلكتروني" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">كلمة المرور</label>
                            <input type="password" id="parentPassword" name="password" class="form-control" placeholder="أدخل كلمة المرور" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">صورة ولي الأمر</label>
                            <input type="file" name="image_path" class="form-control">
                            <small class="form-text text-muted">الصور المسموح بها: JPG, PNG بحد أقصى 2MB</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ ولي الأمر</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
async function checkUnique(field, value) {
    try {
        const response = await $.ajax({
            url: '/check-unique_parent', // ضع هنا رابط التحقق من البريد في Laravel
            type: 'POST',
            data: {
                field: field,
                value: value,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });
        return !response.exists; // true إذا غير موجود
    } catch (error) {
        return false;
    }
}

$(document).ready(function() {

    $('#addParentModalform').on('submit', async function(e) {
        e.preventDefault();

        const name = $('#parentName').val().trim();
        const idNumber = $('#parentId').val().trim();
        const birthDate = $('#parentBirthDate').val();
        const gender = $('#parentGender').val();
        const relation = $('#parentRelation').val();
        const phone = $('#parentPhone').val().trim();
        const email = $('#parentEmail').val().trim();
        const password = $('#parentPassword').val();

        if(name.length < 6) {
            Swal.fire('خطأ', 'الاسم الكامل يجب أن يكون 6 أحرف على الأقل', 'error'); return;
        }
        if(idNumber.length <= 7 || idNumber.length >= 15) {
            Swal.fire('خطأ', 'رقم الهوية يجب أن يكون أكثر من 7 وأقل من 15 رقم', 'error'); return;
  }

   const isnational_id = await checkUnique('national_id', idNumber);
            if(!isnational_id) { Swal.fire('خطأ', 'رقم الهوية موجود من قبل ', 'error'); return; }
        if(!birthDate) {
            Swal.fire('خطأ', 'يرجى تحديد تاريخ الميلاد', 'error'); return;
        }
        const today = new Date().setHours(0,0,0,0);
        const birth = new Date(birthDate).setHours(0,0,0,0);
        if(birth > today) {
            Swal.fire('خطأ', 'تاريخ الميلاد لا يمكن أن يكون في المستقبل', 'error'); return;
        }
        if(!gender) { Swal.fire('خطأ', 'يرجى اختيار الجنس', 'error'); return; }
        if(!relation) { Swal.fire('خطأ', 'يرجى اختيار صلة القرابة', 'error'); return; }
        const phoneDigits = phone.replace(/\D/g, '');
        if(phoneDigits.length <= 9) { Swal.fire('خطأ', 'رقم الهاتف يجب أن يكون أكثر من 9 أرقام', 'error'); return; }
        if(email.length > 0) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!emailPattern.test(email)) { Swal.fire('خطأ', 'البريد الإلكتروني غير صحيح', 'error'); return; }
            const isUnique = await checkUnique('email', email);
            if(!isUnique) { Swal.fire('خطأ', 'البريد الإلكتروني مستخدم من قبل', 'error'); return; }
        }
        if(password.length <= 8) { Swal.fire('خطأ', 'كلمة المرور يجب أن تكون أكثر من 8 أحرف', 'error'); return; }

        Swal.fire({
            title: 'جاري تسجيل ولي الأمر',
            html: 'الرجاء الانتظار...',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        setTimeout(() => { this.submit(); }, 500);
    });

});
</script>
