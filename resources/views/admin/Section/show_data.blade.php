<!-- Modal عرض بيانات الصف -->
<div class="modal fade" id="viewClassModal" tabindex="-1" aria-labelledby="viewClassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewClassModalLabel"><i class="fas fa-school me-2"></i>بيانات الصف</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="class-icon mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                        <h4 class="class-name">
                            <samp class="section-name"></samp>
                        </h4>
                        <span class="badge bg-success">نشط</span>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">الفوج</label>
                                <p class="form-control-static working_houre"></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">المرحلة</label>
                                <p class="form-control-static grade"></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label">المعلمين</label>
                                <div class="d-flex flex-wrap gap-2 teachers-list">
                                    <!-- المعلمين ستظهر هنا -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">عدد الطلاب</label>
                                <p class="form-control-static students"></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تاريخ الإنشاء</label>
                                <p class="form-control-static created-at"></p>
                            </div>
                            <div class="col-12">
                                <label class="form-label">المواد الدراسية</label>
                                <div class="d-flex flex-wrap gap-2 subjects-list">
                                    <!-- المواد ستظهر هنا -->
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">الوصف</label>
                                <p class="form-control-static description"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="" class="btn btn-primary" id="show_student">
                    <i class="fas fa-users me-1"></i> عرض الطلاب
                </a>
                <a href="" class="btn btn-primary" id="show_teacher">
                    <i class="fas fa-chalkboard-teacher me-1"></i> عرض المعلمين
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".view-class-btn");
    const sectionUpdateRouteTemplate = "{{ route('section.show', ':id') }}";
    const sectionTeacherRouteTemplate = "{{ route('section.showteacherinsection', ':id') }}";

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            const sectionId = this.getAttribute("data-id");

            // تحديث روابط الطلاب والمعلمين
            document.getElementById('show_student').setAttribute('href', sectionUpdateRouteTemplate.replace(':id', sectionId));
            document.getElementById('show_teacher').setAttribute('href', sectionTeacherRouteTemplate.replace(':id', sectionId));

            fetch(`/section/${sectionId}/show_data`)
                .then(response => {
                    if (!response.ok) throw new Error('خطأ في جلب البيانات');
                    return response.json();
                })
                .then(data => {
                    // بيانات الصف
                    document.querySelector(".class-name .section-name").textContent = data.name || '';
                    document.querySelector(".grade").textContent = data.classroom?.educational_stage?.name || '';
                    document.querySelector(".working_houre").textContent = data.classroom?.educational_stage?.working_hour?.name || '';
                    document.querySelector(".students").textContent = `${data.students_count || 0} / ${data.maxvalue || 0}`;
                    document.querySelector(".created-at").textContent = data.created_at?.split("T")[0] || '';
                    document.querySelector(".description").textContent = data.note || '';

                    // المواد الدراسية بدون تكرار
                    const subjectsContainer = document.querySelector(".subjects-list");
                    subjectsContainer.innerHTML = "";
                    if (data.subject_teachers) {
                        const uniqueSubjects = [...new Set(data.subject_teachers.map(st => st.subject?.name).filter(Boolean))];
                        uniqueSubjects.forEach(subjectName => {
                            const badge = document.createElement("span");
                            badge.className = "badge bg-info";
                            badge.textContent = subjectName;
                            subjectsContainer.appendChild(badge);
                        });
                    }

                    // المعلمين مع المواد الخاصة بكل معلم
                    const teachersContainer = document.querySelector(".teachers-list");
                    teachersContainer.innerHTML = "";
                    if (data.subject_teachers) {
                        const teachersMap = {};
                        data.subject_teachers.forEach(st => {
                            if (!st.teacher) return;
                            if (!teachersMap[st.teacher.id]) {
                                teachersMap[st.teacher.id] = { name: st.teacher.full_name, subjects: [] };
                            }
                            if (st.subject?.name && !teachersMap[st.teacher.id].subjects.includes(st.subject.name)) {
                                teachersMap[st.teacher.id].subjects.push(st.subject.name);
                            }
                        });

                        Object.values(teachersMap).forEach(teacherData => {
                            const teacherDiv = document.createElement("div");
                            teacherDiv.className = "d-flex align-items-center mb-1";
                            teacherDiv.innerHTML = `
                                <span class="me-2">${teacherData.name}</span>
                                ${teacherData.subjects.map(subject => `<span class="badge bg-secondary me-1">${subject}</span>`).join('')}
                            `;
                            teachersContainer.appendChild(teacherDiv);
                        });
                    }
                })
                .catch(error => {
                    alert(error.message);
                });
        });
    });
});
</script>
