<!-- Modal تعديل ولي أمر -->
<div class="modal fade" id="editParentModal" tabindex="-1" aria-labelledby="editParentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editParentModalLabel">تعديل بيانات ولي الأمر</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editParentModalForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="editParentId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">الاسم الكامل</label>
                            <input type="text" id="editParentName" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">رقم الهوية</label>
                            <input type="text" id="editParentIdNumber" name="national_id" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">تاريخ الميلاد</label>
                            <input type="date" id="editParentBirthDate" name="date_of_birth" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">الجنس</label>
                            <select id="editParentGender" name="gender" class="form-select" required>
                                <option value="" disabled>اختر...</option>
                                <option value="male">ذكر</option>
                                <option value="female">أنثى</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">صلة القرابة</label>
                            <select id="editParentRelation" name="relation" class="form-select" required>
                                <option value="" disabled>اختر صلة القرابة</option>
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
                            <input type="text" id="editParentPhone" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">الحالة</label>
                            <select id="editParentStatus" name="status" class="form-select" required>
                                <option value="active">نشط</option>
                                <option value="inactive">غير نشط</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">العنوان</label>
                            <input type="text" id="editParentAddress" name="address" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">البريد الإلكتروني</label>
                            <input type="email" id="editParentEmail" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">كلمة المرور الجديدة (اختياري)</label>
                            <input type="password" id="editParentPassword" name="password" class="form-control">
                        </div>

                        <!-- عرض الصورة القديمة -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">صورة ولي الأمر الحالية</label>
                            <div class="mb-2">
                                <img id="editParentCurrentImage" src="" alt="صورة ولي الأمر" class="teacher-photo me-2" style="width: 80px; height: 80px; border-radius: 50%;">
                            </div>
                            <label class="form-label">تغيير الصورة (اختياري)</label>
                            <input type="file" id="editParentImage" name="image_path" class="form-control">
                            <small class="form-text text-muted">الصور المسموح بها: JPG, PNG بحد أقصى 2MB</small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">تحديث البيانات</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
let originalParentData = {};

function editParent(id) {
    $.ajax({
        url: '/pearant/' + id,
        type: 'GET',
        success: function(data) {
            // تعبئة الحقول
            $('#editParentId').val(data.id);
            $('#editParentName').val(data.name);
            $('#editParentIdNumber').val(data.national_id);
            $('#editParentBirthDate').val(data.date_of_birth);
            $('#editParentGender').val(data.gender);
            $('#editParentRelation').val(data.relation);
            $('#editParentPhone').val(data.phone);
            $('#editParentStatus').val(data.status);
            $('#editParentAddress').val(data.address);
            $('#editParentEmail').val(data.email);

            let imageUrl = data.image_path ? '/storage/' + data.image_path : '/images/default.png';
            $('#editParentCurrentImage').attr('src', imageUrl);

            // حفظ القيم الأصلية للمقارنة لاحقًا
            originalParentData = {
                id: data.id,
                name: data.name,
                national_id: data.national_id,
                date_of_birth: data.date_of_birth,
                gender: data.gender,
                relation: data.relation,
                phone: data.phone,
                status: data.status,
                address: data.address,
                email: data.email,
                image_path: data.image_path
            };

            $('#editParentModal').modal('show');
        },
        error: function() {
            Swal.fire('خطأ', 'تعذر جلب بيانات ولي الأمر', 'error');
        }
    });
}

// دالة تحقق من الفريدة
async function checkUnique(field, value, excludeId = null) {
    try {
        const response = await $.ajax({
            url: '/check-unique_parent',
            type: 'POST',
            data: {
                field: field,
                value: value,
                exclude_id: excludeId,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });
        return !response.exists;
    } catch (error) {
        return false;
    }
}

// تحقق نهائي عند الإرسال
$('#editParentModalForm').on('submit', async function(e) {
    e.preventDefault();
    let isValid = true;
    const parentId = $('#editParentId').val();

    // جلب القيم الجديدة
       let newData = {
        name: $('#editParentName').val().trim(),
        national_id: $('#editParentIdNumber').val().trim(),
        phone: $('#editParentPhone').val().trim(),
        email: $('#editParentEmail').val().trim()
    };
if (newData.name !== originalParentData.name) {
        if (newData.name.length <= 6) {
            Swal.fire('خطأ', 'الاسم يجب أن يكون أكثر من 6 أحرف', 'error');
            isValid = false;
        }
    }
    // فحص البريد الإلكتروني إذا تغيّر
    if (newData.email !== originalParentData.email) {
        if (newData.email.length > 0) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(newData.email)) {
                Swal.fire('خطأ', 'البريد الإلكتروني غير صحيح', 'error');
                isValid = false;
            } else {
                const emailUnique = await checkUnique('email', newData.email, parentId);
                if (!emailUnique) {
                    Swal.fire('خطأ', 'البريد الإلكتروني مستخدم من قبل', 'error');
                    isValid = false;
                }
            }
        }
    }

    // فحص رقم الهوية إذا تغيّر
    if (newData.national_id !== originalParentData.national_id) {
    if (newData.national_id.length) {
        // تحقق من الطول
        if (newData.national_id.length < 9 || newData.national_id.length > 15) {
            Swal.fire('خطأ', 'رقم الهوية يجب أن يكون بين 9 و 15 رقم', 'error');
            isValid = false;
        } else {
            // تحقق من الفريدة إذا الطول صحيح
            const idUnique = await checkUnique('national_id', newData.national_id, parentId);
            if (!idUnique) {
                Swal.fire('خطأ', 'رقم الهوية موجود من قبل', 'error');
                isValid = false;
            }
        }
    }
}


if (newData.phone !== originalParentData.phone) {
    if (newData.phone.length) {
        // تحقق من الطول
        if (newData.phone.length < 9 || newData.phone.length > 12) {
            Swal.fire('خطأ', 'رقم الهاتف يجب أن يكون بين 9 و 12 رقم', 'error');
            isValid = false;
        } else {
            // تحقق من الفريدة إذا الطول صحيح
            const phoneUnique = await checkUnique('phone', newData.phone, parentId);
            if (!phoneUnique) {
                Swal.fire('خطأ', 'رقم الهاتف موجود من قبل', 'error');
                isValid = false;
            }
        }
    }
}

    if (!isValid) return;

    // إرسال البيانات إذا كانت صحيحة
    const formData = new FormData(this);

    Swal.fire({
        title: 'جاري تحديث البيانات...',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });

    $.ajax({
        url: '/pearant/update',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            Swal.fire('نجاح', 'تم تحديث بيانات ولي الأمر', 'success');
            $('#editParentModal').modal('hide');
            location.reload();
        },
        error: function() {
            Swal.fire('خطأ', 'حدث خطأ أثناء التحديث', 'error');
        }
    });
});
</script>