<div class="modal fade" id="viewTeacherModal" tabindex="-1" aria-labelledby="viewTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewTeacherModalLabel"><i class="fas fa-user-tie me-2"></i>بيانات المعلم</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <img id="teacherPhotoView" src="" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 id="teacherNameView">أحمد محمد</h4>
                        <span id="teacherStatusView" class="badge bg-success">نشط</span>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">رقم الهوية</label>
                                <p class="form-control-static" id="teacherIdView">1234567890</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">التخصص</label>
                                <p class="form-control-static" id="teacherSpecializationView">الرياضيات</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تاريخ الميلاد</label>
                                <p class="form-control-static" id="teacherBirthDateView">15/05/1985</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">سنوات الخبرة</label>
                                <p class="form-control-static" id="teacherExperienceView">10 سنوات</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">رقم الهاتف</label>
                                <p class="form-control-static" id="teacherPhoneView">+966501234567</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">البريد الإلكتروني</label>
                                <p class="form-control-static" id="teacherEmailView">ahmed@example.com</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تاريخ التعيين</label>
                                <p class="form-control-static" id="teacherHireDateView">01/09/2015</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الجنس</label>
                                <p class="form-control-static" id="teacherGenderView">ذكر</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label">العنوان</label>
                                <p class="form-control-static" id="teacherAddressView">الرياض، حي العليا، شارع الملك فهد</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">الصفوف المكلف بها</label>
                                <div class="d-flex flex-wrap gap-2" id="teacherClassesView">
                                    <span class="badge bg-secondary">الصف الأول أ</span>
                                    <span class="badge bg-secondary">الصف الثاني ب</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">المواد المكلف بها</label>
                                <div class="d-flex flex-wrap gap-2" id="teacherSubjectsView">
                                    <span class="badge bg-info">الرياضيات</span>
                                    <span class="badge bg-info">العلوم</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <!--<button type="button" class="btn btn-primary">-->
                <!--    <i class="fas fa-print me-1"></i> طباعة بطاقة المعلم-->
                <!--</button>-->
            </div>
        </div>
    </div>
</div>




<script>
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".view_teacher_btn");
   

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const teacherId = this.getAttribute("data-id");

            // تحديث الرابط (إذا عندك زر إضافي مثل عرض المواد)
 
           
            fetch(`/teachers/${teacherId}`)
                .then(response => {
                    if (!response.ok) throw new Error("فشل في جلب بيانات المعلم من الخادم");
                    return response.json();
                })
                .then(data => {
                    const teacher = data;

                   console.log(teacher);
                    
                         const imgPreview = document.getElementById('teacherPhotoView');
        imgPreview.src = teacher.photo
          ? `/storage/${teacher.photo}`
          : '/images/default.png';
                    // الصورة
                 

                    // الاسم
                    document.getElementById("teacherNameView").textContent = teacher.full_name;

                    // الحالة
                    const statusElement = document.getElementById("teacherStatusView");
                    const isActive = teacher.status === "active";
                    statusElement.textContent = isActive ? "نشط" : "غير نشط";
                    statusElement.className = `badge ${isActive ? "bg-success" : "bg-danger"}`;

                    // باقي البيانات
                    document.getElementById("teacherIdView").textContent = teacher.national_id ?? "—";
                    document.getElementById("teacherSpecializationView").textContent = teacher.specialization ?? "—";
                    document.getElementById("teacherBirthDateView").textContent = teacher.birth_date ?? "—";
                    document.getElementById("teacherExperienceView").textContent = teacher.experience ? teacher.experience + " سنوات" : "—";
                    document.getElementById("teacherPhoneView").textContent = teacher.phone ?? "—";
                    document.getElementById("teacherEmailView").textContent = teacher.email ?? "—";
                    document.getElementById("teacherHireDateView").textContent = teacher.hire_date ?? "—";
                    document.getElementById("teacherGenderView").textContent = teacher.gender === "male" ? "ذكر" : "أنثى";
                    document.getElementById("teacherAddressView").textContent = teacher.address ?? "—";

                    // الصفوف
                    const classesContainer = document.getElementById("teacherClassesView");
                    classesContainer.innerHTML = "";
                    (teacher.classes ?? []).forEach(cls => {
                        const span = document.createElement("span");
                        span.className = "badge bg-secondary";
                        span.textContent = cls;
                        classesContainer.appendChild(span);
                    });

                    // المواد
                    const subjectsContainer = document.getElementById("teacherSubjectsView");
                    subjectsContainer.innerHTML = "";
                    (teacher.subjects ?? []).forEach(sub => {
                        const span = document.createElement("span");
                        span.className = "badge bg-info";
                        span.textContent = sub;
                        subjectsContainer.appendChild(span);
                    });

                        const teacherModal = new bootstrap.Modal(document.getElementById("viewTeacherModal"));
        teacherModal.show();
                })
                .catch(error => {
                    alert(error.message);
                });
        });
    });
});
</script>
