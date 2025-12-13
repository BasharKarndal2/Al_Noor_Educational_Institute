<!-- Edit Announcement Modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAnnouncementModalLabel">تعديل إعلان</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- الفورم -->
                <form id="editAnnouncementForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="editAnnouncementId">

                    <div class="mb-3">
                        <label for="editAnnouncementTitle" class="form-label">العنوان</label>
                        <input type="text" class="form-control" name="titel" id="editAnnouncementTitle" required>
                    </div>

                    <div class="mb-3">
                        <label for="editAnnouncementDescription" class="form-label">الوصف</label>
                        <textarea class="form-control" name="discridtion" id="editAnnouncementDescription" rows="4" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="editAnnouncementImage" class="form-label">الصورة الجديدة (اختياري)</label>
                        <input type="file" class="form-control" name="image_path" id="editAnnouncementImage" accept="image/*">
                        <div id="currentImage" class="mt-2"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary" form="editAnnouncementForm">حفظ التعديلات</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const editModal = document.getElementById("editAnnouncementModal");

    editModal.addEventListener("show.bs.modal", function (event) {
        const button = event.relatedTarget;

        // قراءة البيانات من الزر
        const id = button.getAttribute("data-id");
        const title = button.getAttribute("data-title");
        const description = button.getAttribute("data-description");
        const image = button.getAttribute("data-image");

        // تعبئة الحقول
        document.getElementById("editAnnouncementId").value = id;
        document.getElementById("editAnnouncementTitle").value = title;
        document.getElementById("editAnnouncementDescription").value = description;

        // عرض الصورة الحالية
        const currentImageDiv = document.getElementById("currentImage");
        if (image) {
            currentImageDiv.innerHTML = `<p>الصورة الحالية:</p>
                <img src="${image}" alt="صورة الإعلان" style="max-width: 150px; height: auto;">`;
        } else {
            currentImageDiv.innerHTML = `<p>لا يوجد صورة حالياً</p>`;
        }

        // تغيير رابط الفورم لمسار update الصحيح
        const studentUpdateRouteTemplate = "{{ route('announcements.update', ':id') }}";
        const actionUrl = studentUpdateRouteTemplate.replace(':id', id);
        document.getElementById('editAnnouncementForm').setAttribute('action', actionUrl);
    });

    // ======= التحقق من الحقول قبل الإرسال =======
    const editForm = document.getElementById("editAnnouncementForm");

    editForm.addEventListener("submit", function (e) {
        const title = document.getElementById("editAnnouncementTitle").value.trim();
        const description = document.getElementById("editAnnouncementDescription").value.trim();
        const imageInput = document.getElementById("editAnnouncementImage");
        let errors = [];

        // التحقق من العنوان
        if (title.length < 5) {
            errors.push("العنوان يجب أن يحتوي على 5 أحرف على الأقل.");
        }

        // التحقق من الوصف
        if (description.length < 5) {
            errors.push("الوصف يجب أن يحتوي على 5 أحرف على الأقل.");
        }

        // التحقق من الصورة الجديدة (اختياري)
        if (imageInput.files.length > 0) {
            const file = imageInput.files[0];
            if (!file.type.startsWith("image/")) {
                errors.push("الرجاء اختيار ملف صورة صالح.");
            }
            if (file.size > 10 * 1024 * 1024) { // 2MB
                errors.push("حجم الصورة يجب أن يكون أقل من 10MB.");
            }
        }

        // إذا في أخطاء → منع الإرسال
        if (errors.length > 0) {
            e.preventDefault();
              Swal.fire('خطأ',errors.join("\n") , 'error'); return;
        }
    });
});
</script>

