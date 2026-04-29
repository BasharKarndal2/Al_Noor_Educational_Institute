<!-- Modal تعديل المعلم -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editTeacherModalLabel"><i class="fas fa-user-edit me-2"></i> تعديل بيانات المعلم</h5>
              
            </div>

            <!-- Body -->
            <div class="modal-body text-right">
                <form id="editTeacherForm" method="POST" action="" enctype="multipart/form-data" >
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- الاسم الثلاثي -->
                        <div class="col-md-6">
                            <label for="edit_full_name" class="form-label required">الاسم الثلاثي</label>
                            <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                        </div>

                        <!-- رقم الهوية -->
                        <div class="col-md-6">
                            <label for="edit_national_id" class="form-label required">رقم الهوية</label>
                            <input type="text" class="form-control" id="edit_national_id" name="national_id" required>
                        </div>

                        <!-- تاريخ الميلاد -->
                        <div class="col-md-6">
                            <label for="edit_birth_date" class="form-label required">تاريخ الميلاد</label>
                            <input type="date" class="form-control" id="edit_birth_date" name="birth_date" required>
                        </div>

                        <!-- الجنس -->
                        <div class="col-md-6">
                            <label for="edit_gender" class="form-label required">الجنس</label>
                            <select class="form-select" id="edit_gender" name="gender" required>
                                <option value="">اختر...</option>
                                <option value="male">ذكر</option>
                                <option value="female">أنثى</option>
                            </select>
                        </div>

                        <!-- الحالة الاجتماعية -->
                        <div class="col-md-6">
                            <label for="edit_marital" class="form-label required">الحالة الإجتماعية</label>
                            <select class="form-select" id="edit_marital" name="marital" required>
                                <option value="">اختر...</option>
                                <option value="single">أعزب</option>
                                <option value="married">متزوج</option>
                            </select>
                        </div>

                        <!-- التخصص -->
                        <div class="col-md-6">
                            <label for="edit_specialization" class="form-label required">التخصص</label>
                            <input type="text" class="form-control" id="edit_specialization" name="specialization" required>
                        </div>

                        <!-- سنوات الخبرة -->
                        <div class="col-md-6">
                            <label for="edit_experience" class="form-label">سنوات الخبرة</label>
                            <input type="number" class="form-control" id="edit_experience" name="experience" min="0">
                        </div>

                        <!-- رقم الهاتف -->
                        <div class="col-md-6">
                            <label for="edit_phone" class="form-label required">رقم الهاتف</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" required>
                        </div>

                        <!-- الحالة -->
                        <div class="col-md-6">
                            <label for="edit_status" class="form-label required">الحالة</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                                <option value="on_leave">في إجازة</option>
                            </select>
                        </div>

                        <!-- تاريخ التعيين -->
                        <div class="col-md-6">
                            <label for="edit_hire_date" class="form-label required">تاريخ التعيين</label>
                            <input type="date" class="form-control" id="edit_hire_date" name="hire_date" required>
                        </div>

                        <!-- العنوان -->
                        <div class="col-12">
                            <label for="edit_address" class="form-label">العنوان</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="2"></textarea>
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div class="col-md-6">
                            <label for="edit_email" class="form-label required">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>

                        <!-- كلمة المرور (اختياري) -->
                        <div class="col-md-6">
                            <label for="edit_password" class="form-label">كلمة المرور (اختياري)</label>
                            <input type="password" class="form-control" id="edit_password" name="password" placeholder="اتركه فارغًا إذا لم تريد التغيير">
                        </div>

                        <!-- صورة المعلم -->
                        <div class="col-md-6">
                            <label for="edit_teacherPhoto" class="form-label">تغيير صورة المعلم</label>
                            <input type="file" class="form-control" id="edit_teacherPhoto" name="image_path" accept="image/*">
                            <img id="edit_teacherPhotoPreview" name="edit_teacherPhotoPreviewname" src="" alt="صورة المعلم" style="width: 40px; height: 40px; border-radius: 50%; margin-top: 8px;">
                        </div>
                        <input type="hidden" id="old_image_path" name="old_image_path" value="">

                        <!-- ملاحظات اضافية -->
                        <div class="col-12">
                            <label for="edit_note" class="form-label">ملاحظات اضافية</label>
                            <textarea class="form-control" id="edit_note" name="note" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="editTeacherForm" class="btn btn-warning">تحديث البيانات</button>
            </div>
        </div>
    </div>
</div>

<script>
  const teacherUpdateRouteTemplate = "{{ route('teaher.update', ':id') }}";
  const modal = document.getElementById('editTeacherModal');

  modal.addEventListener('show.bs.modal', event => {
    const oldValues = {
      full_name: @json(old('full_name')),
      national_id: @json(old('national_id')),
      birth_date: @json(old('birth_date')),
      gender: @json(old('gender')),
      marital: @json(old('marital')),
      specialization: @json(old('specialization')),
      experience: @json(old('experience')),
      phone: @json(old('phone')),
      status: @json(old('status')),
      hire_date: @json(old('hire_date')),
      address: @json(old('address')),
      email: @json(old('email')),
      password: @json(old('password')),
      note: @json(old('note')),
      old_image_path: @json(old('old_image_path'))
    };

    const hasOldData = Object.values(oldValues).some(v => v !== null && v !== '');

    if (hasOldData) {
      for (const key in oldValues) {
        const input = document.getElementById(`edit_${key === 'birth_date' || key === 'hire_date' ? key : key}`);
        if (!input) continue;

        let value = oldValues[key] || '';

        // معالجة خاصة للتواريخ
        if ((key === 'birth_date' || key === 'hire_date') && value) {
          const date = new Date(value);
          if (!isNaN(date)) {
            const yyyy = date.getFullYear();
            const mm = String(date.getMonth() + 1).padStart(2, '0');
            const dd = String(date.getDate()).padStart(2, '0');
            value = `${yyyy}-${mm}-${dd}`;
          } else {
            value = '';
          }
        }

        input.value = value;
      }

      const imgPreview = document.getElementById('edit_teacherPhotoPreview');
      imgPreview.src = oldValues.old_image_path
        ? `/storage/${oldValues.old_image_path}`
        : '/images/default-user.png';

      return;
    }

    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const actionUrl = teacherUpdateRouteTemplate.replace(':id', id);
    document.getElementById('editTeacherForm').setAttribute('action', actionUrl);

    fetch(`/teaher/${id}/edit`)
      .then(res => {
        if (!res.ok) throw new Error("فشل في تحميل البيانات");
        return res.json();
      })
      .then(data => {
        const fill = (id, value) => {
          const el = document.getElementById(id);
          if (el) el.value = value || '';
        };

        fill('edit_full_name', data.full_name);
        fill('edit_national_id', data.national_id);
        fill('edit_birth_date', data.birth_date);
        fill('edit_gender', data.gender);
        fill('edit_marital', data.marital);
        fill('edit_specialization', data.specialization);
        fill('edit_experience', data.experience);
        fill('edit_phone', data.phone);
        fill('edit_status', data.status);
        fill('edit_hire_date', data.hire_date);
        fill('edit_address', data.address);
        fill('edit_email', data.email);
        fill('edit_password', ''); // اتركها فارغة دائماً
        fill('edit_note', data.notes);
        fill('old_image_path', data.image_path);

        const imgPreview = document.getElementById('edit_teacherPhotoPreview');
        imgPreview.src = data.image_path
          ? `/storage/${data.image_path}`
          : '/images/default-user.png';
      })
      .catch(err => {
        alert(err.message);
        console.error("خطأ في جلب بيانات المعلم:", err);
      });
  });

  // معاينة الصورة الجديدة عند اختيارها
  const fileInput = document.getElementById('edit_teacherPhoto');
  const imgPreview = document.getElementById('edit_teacherPhotoPreview');
  fileInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = e => imgPreview.src = e.target.result;
      reader.readAsDataURL(file);
    }
  });
</script>
