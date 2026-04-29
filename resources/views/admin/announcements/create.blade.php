<div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAnnouncementModalLabel">إضافة إعلان جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <form  method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data" >
                     @csrf
                        <div class="mb-3">
                            <label for="announcementTitle" class="form-label">العنوان</label>
                            <input type="text" class="form-control" name="titel" id="announcementTitle" required>
                        </div>
                        <div class="mb-3">
                            <label for="announcementDescription" class="form-label">الوصف</label>
                            <textarea class="form-control" name="discridtion" id="announcementDescription" rows="4" required></textarea>
                        </div>


                        <div class="col-md-12">
                            <label for="announcementImage" class="form-label">صورةالإعلان</label>
                            <input type="file" class="form-control" id="announcementImage" name="image_path" accept="image/*" required>
                            <small class="text-muted">الصور المسموح بها: JPG, PNG 10MB</small>
                        </div>
                     
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary">حفظ</button>
                </div>
            </div>
        </div>
    </div>


<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("#addAnnouncementModal form");
    const titleInput = document.getElementById("announcementTitle");
    const descInput = document.getElementById("announcementDescription");
    const imageInput = document.getElementById("announcementImage");
    const saveBtn = document.querySelector("#addAnnouncementModal .btn-primary");

    // معاينة الصورة
    imageInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
            if (!file.type.startsWith("image/")) {
                     Swal.fire('خطأ',"الرجاء اختيار صورة فقط", 'error'); return;

                this.value = "";
                return;
            }
            if (file.size > 10 * 1024 * 1024) { // 2MB
                          Swal.fire('خطأ', "حجم الصورة يجب أن يكون أقل من 10MB", 'error'); return;

                this.value = "";
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                let imgPreview = document.getElementById("imagePreview");
                if (!imgPreview) {
                    imgPreview = document.createElement("img");
                    imgPreview.id = "imagePreview";
                    imgPreview.style.maxWidth = "100%";
                    imgPreview.style.marginTop = "10px";
                    imageInput.parentNode.appendChild(imgPreview);
                }
                imgPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    // التحقق من الحقول
    saveBtn.addEventListener("click", function () {
        let valid = true;
        let errors = [];

        if (titleInput.value.trim().length < 5) {
            valid = false;
            errors.push("العنوان يجب أن يحتوي على 5 أحرف على الأقل.");
        }

        if (descInput.value.trim().length < 5) {
            valid = false;
            errors.push("الوصف يجب أن يحتوي على 5 أحرف على الأقل.");
        }

        if (!imageInput.files.length) {
            valid = false;
            errors.push("الرجاء اختيار صورة للإعلان.");
        }

        if (!valid) {

                        Swal.fire('خطأ',errors.join("\n") , 'error'); return;

           
        } else {
            form.submit(); // يرسل النموذج إذا كله صحيح
        }
    });
});
</script>
