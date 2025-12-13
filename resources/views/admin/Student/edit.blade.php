<!-- Modal تعديل الطالب -->
<div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="editStudentModalLabel"><i class="fas fa-user-edit me-2"></i> تعديل بيانات الطالب</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body text-right">
        <form id="editstudentForm" method="POST" action="" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          <div class="row g-3">
            <div class="col-md-6">
              <label for="edit_name" class="form-label required">الاسم الثلاثي</label>
              <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>

            <div class="col-md-6">
              <label for="edit_national_id" class="form-label required">رقم الهوية</label>
              <input type="text" class="form-control" id="edit_national_id" name="national_id" required>
            </div>

            <div class="col-md-6">
              <label for="edit_date_of_birth" class="form-label required">تاريخ الميلاد</label>
              <input type="date" class="form-control" id="edit_date_of_birth" name="date_of_birth" required>
            </div>

            <div class="col-md-6">
              <label for="edit_gender" class="form-label required">الجنس</label>
              <select class="form-select" id="edit_gender" name="gender" required>
                <option value="">اختر...</option>
                <option value="male">ذكر</option>
                <option value="female">أنثى</option>
              </select>
            </div>

            <div class="col-md-6">
              <label for="edit_phone" class="form-label required">رقم الهاتف</label>
              <input type="text" class="form-control" id="edit_phone" name="phone" required>
            </div>

            <div class="col-md-6">
              <label for="edit_status" class="form-label required">الحالة</label>
              <select class="form-select" id="edit_status" name="status" required>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
                <option value="on_leave">في إجازة</option>
              </select>
            </div>

            <div class="col-12">
              <label for="edit_address" class="form-label">العنوان</label>
              <textarea class="form-control" id="edit_address" name="address" rows="2"></textarea>
            </div>

            <div class="col-md-6">
              <label for="edit_email" class="form-label required">البريد الإلكتروني</label>
              <input type="email" class="form-control" id="edit_email" name="email" required data-old-email="">
            </div>

            <div class="col-md-6">
              <label for="edit_password" class="form-label">كلمة المرور (اختياري)</label>
              <input type="password" class="form-control" id="edit_password" name="password" placeholder="اتركه فارغًا إذا لم تريد التغيير">
            </div>

            <div class="col-md-6">
              <label for="edit_studentPhoto" class="form-label">تغيير صورة الطالب</label>
              <input type="file" class="form-control" id="edit_studentPhoto" name="image_path" accept="image/*">
              <img id="edit_studentPhotoPreview" src="" alt="صورة الطالب" style="width: 40px; height: 40px; border-radius: 50%; margin-top: 8px;">
            </div>

            <input type="hidden" id="old_image_path" name="old_image_path" value="">

            <div class="col-12">
              <label for="edit_note" class="form-label">ملاحظات إضافية</label>
              <textarea class="form-control" id="edit_note" name="note" rows="2"></textarea>
            </div>
          </div>
        </form>
      </div>

      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" form="editstudentForm" class="btn btn-warning">تحديث البيانات</button>
      </div>

    </div>
  </div>
</div>

<script>
const studentUpdateRouteTemplate = "{{ route('student.update', ':id') }}";
const modal = document.getElementById('editStudentModal');

modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const actionUrl = studentUpdateRouteTemplate.replace(':id', id);
    document.getElementById('editstudentForm').setAttribute('action', actionUrl);

    fetch(`/student/${id}/edit`)
        .then(res => {
            if (!res.ok) throw new Error("فشل في تحميل البيانات");
            return res.json();
        })
        .then(data => {
            const fill = (id, value) => {
                const el = document.getElementById(id);
                if (el) el.value = value || '';
            };

            fill('edit_name', data.name);
            fill('edit_national_id', data.national_id);
            fill('edit_date_of_birth', data.date_of_birth);
            fill('edit_gender', data.gender);
            fill('edit_phone', data.phone);
            fill('edit_status', data.status);
            fill('edit_address', data.address);
            fill('edit_email', data.email);
            fill('edit_password', '');
            fill('edit_note', data.note);
            fill('old_image_path', data.image_path);

            const emailInput = document.getElementById('edit_email');
            emailInput.setAttribute('data-old-email', data.email || '');

            const imgPreview = document.getElementById('edit_studentPhotoPreview');
            imgPreview.src = data.image_path ? `/storage/${data.image_path}` : '/images/default-user.png';
        })
        .catch(err => {
            alert(err.message);
            console.error("خطأ في جلب بيانات الطالب:", err);
        });
});

// معاينة الصورة الجديدة
document.getElementById('edit_studentPhoto').addEventListener('change', function () {
    const file = this.files[0];
    const imgPreview = document.getElementById('edit_studentPhotoPreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = e => imgPreview.src = e.target.result;
        reader.readAsDataURL(file);
    }
});

// دالة لفحص التكرار في قاعدة البيانات (email فقط عند تغييره)
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

// حماية الحقول وفحص البريد فقط عند تغييره
document.getElementById('editstudentForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const id = this.getAttribute('action').split('/').pop();
    const name = document.getElementById('edit_name').value.trim();
    const nationalId = document.getElementById('edit_national_id').value.trim();
    const birthDate = document.getElementById('edit_date_of_birth').value;
    const gender = document.getElementById('edit_gender').value;
    const phone = document.getElementById('edit_phone').value.trim();
    const emailInput = document.getElementById('edit_email');
    const email = emailInput.value.trim();
    const oldEmail = emailInput.getAttribute('data-old-email');
    const password = document.getElementById('edit_password').value;
    const status = document.getElementById('edit_status').value;

    // تحقق من الاسم
    if (!name || name.length < 3) { Swal.fire('خطأ', 'اسم الطالب يجب أن يكون 3 أحرف على الأقل', 'error'); return; }

    // تحقق من الهوية
    if (!nationalId || nationalId.length < 7 || nationalId.length > 15) { Swal.fire('خطأ', 'رقم الهوية يجب أن يكون بين 7 و 15 رقم', 'error'); return; }

    // تحقق من تاريخ الميلاد
    if (!birthDate) { Swal.fire('خطأ', 'يرجى تحديد تاريخ الميلاد', 'error'); return; }
    const today = new Date().setHours(0,0,0,0);
    const birth = new Date(birthDate).setHours(0,0,0,0);
    if (birth > today) { Swal.fire('خطأ', 'تاريخ الميلاد لا يمكن أن يكون في المستقبل', 'error'); return; }

    // تحقق من الجنس
    if (!gender) { Swal.fire('خطأ', 'يرجى اختيار الجنس', 'error'); return; }

    // تحقق من الهاتف
    const phoneDigits = phone.replace(/\D/g, '');
    if (phoneDigits.length < 9) { Swal.fire('خطأ', 'رقم الهاتف يجب أن يكون 9 أرقام على الأقل', 'error'); return; }

    // تحقق من البريد
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email || !emailPattern.test(email)) { Swal.fire('خطأ', 'صيغة البريد الإلكتروني غير صحيحة', 'error'); return; }
    if (email !== oldEmail) {
        const isUnique = await checkUnique('email', email, id);
        if (!isUnique) { Swal.fire('خطأ', 'البريد الإلكتروني مستخدم مسبقًا', 'error'); return; }
    }

    // تحقق من كلمة المرور إذا تم إدخالها
    if (password && password.length < 8) { Swal.fire('خطأ', 'كلمة المرور يجب أن تكون 8 أحرف على الأقل', 'error'); return; }

    // تحقق من الحالة
    if (!status) { Swal.fire('خطأ', 'يرجى اختيار الحالة', 'error'); return; }

    // إذا كل شيء تمام → إرسال الفورم
    this.submit();
});
</script>
