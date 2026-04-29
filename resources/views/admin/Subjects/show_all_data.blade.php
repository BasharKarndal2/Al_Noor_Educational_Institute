 <div class="modal fade" id="viewSubjectModal" tabindex="-1" aria-labelledby="viewSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="viewSubjectModalLabel"><i class="fas fa-book me-2"></i>بيانات المادة</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            <div class="subject-icon mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; background-color: #e3f2fd; color: #1565c0;">
                                <i class="fas fa-square-root-alt"></i>
                            </div>
                            <h4 class="subject-name">الرياضيات</h4>
                            <span class="badge bg-success">نشط</span>
                        </div>
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">كود المادة</label>
                                    <p class="form-control-static subject-code">MATH-101</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">عدد الحصص أسبوعيًا</label>
                                    <p class="form-control-static subject-lessons">5</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">المراحل</label>
                                    <div class="d-flex flex-wrap gap-2 subject-stages">
                                       
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <label class="form-label " >المعلمون</label>
                                    <div class="d-flex flex-wrap gap-2 subject-teachers">
                                       
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">الوصف</label>
                                    <p class="form-control-static subject-description">مادة الرياضيات تشمل أساسيات الحساب والجبر والهندسة</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">الصفوف المطبق عليها</label>
                                    <div class="d-flex flex-wrap gap-2 subject-sections">
                                        <span class="badge bg-secondary">الصف الأول أ</span>
                                        <span class="badge bg-secondary">الصف الأول ب</span>
                                        <span class="badge bg-secondary">الصف الثاني أ</span>
                                        <span class="badge bg-secondary">الصف الثالث أ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            
                    <a href=""  class="btn btn-primary"> <i class="fas fa-chalkboard-teacher me-1"></i> عرض المعلمين </a>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".view_subject_btn");
    const sectionUpdateRouteTemplate = "{{ route('subject.show', ':id') }}";
    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const subjectId = this.getAttribute("data-id");

            // تحديث رابط عرض الصفوف
            const actionUrl = sectionUpdateRouteTemplate.replace(':id', subjectId);
            document.querySelector("#viewSubjectModal .modal-footer a").setAttribute('href', actionUrl);

            fetch(`/subject/${subjectId}/show_all_data`)
                .then(response => {
                    if (!response.ok) throw new Error("فشل في جلب البيانات من الخادم");
                    return response.json();
                })
                .then(data => {
                    const subject = data.subject;

                    // تعبئة معلومات المادة
                    document.querySelector(".subject-name").textContent = subject.name;
                    document.querySelector(".subject-code").textContent = subject.id;
                    document.querySelector(".subject-description").textContent = subject.note ?? "لا يوجد وصف";
                    document.querySelector(".subject-lessons").textContent = subject.number_se ?? "—";

                    // الحالة
                    const badge = document.querySelector(".subject-name + .badge");
                    const isActive = subject.status === "active";
                    badge.textContent = isActive ? "نشط" : "غير نشط";
                    badge.className = `badge ${isActive ? 'bg-success' : 'bg-danger'}`;

                    // الأيقونة (تجاهلها إذا مش مستخدمة)

                    // === عرض المراحل التعليمية المرتبطة ===
                    const stageSet = new Set();
                    subject.sections.forEach(section => {
                        const stageName = section.classroom?.educational_stage?.name;
                        if (stageName) stageSet.add(stageName);
                    });

                    const stagesContainer = document.querySelector(".subject-stages");
                    stagesContainer.innerHTML = '';
                    stageSet.forEach(stageName => {
                        const span = document.createElement('span');
                        span.className = 'badge bg-info';
                        span.textContent = stageName;
                        stagesContainer.appendChild(span);
                    });

                    // === المعلمون ===
                    const teachersContainer = document.querySelector(".subject-teachers");
                    teachersContainer.innerHTML = '';
                    (subject.teachers ?? []).forEach(teacher => {
                        const span = document.createElement('span');
                        span.className = 'badge bg-light text-dark';
                        span.textContent = teacher.full_name;
                        teachersContainer.appendChild(span);
                    });

                    // // === الصفوف المرتبطة ===
                    const sectionsContainer = document.querySelector(".subject-sections");
                    sectionsContainer.innerHTML = '';
                    (subject.sections ?? []).forEach(section => {
                        const classroom = section.classroom?.name ?? "غير معروف";
                        const stage = section.classroom?.educational_stage?.name ?? "غير محدد";
                        const shift = section.classroom?.educational_stage?.working_hour?.name ?? "—";

                        const text = `شعبة ${section.name} - صف ${classroom} - ${stage} (${shift})`;

                        const span = document.createElement('span');
                        span.className = 'badge bg-secondary';
                        span.textContent = text;
                        sectionsContainer.appendChild(span);
                    });
                })
                .catch(error => {
                    alert(error.message);
                });
        });
    });
});
</script>
