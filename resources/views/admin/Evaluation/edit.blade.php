<!-- HTML Modal structure -->
<div class="modal fade" id="editEvaluationModal" tabindex="-1" aria-labelledby="editEvaluationLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editEvaluationLabel">تعديل التقييم</h5>
            </div>
            <form id="editEvaluationForm"  method="POST" action="">
                
              @csrf
                    @method('PUT')
                    
                <input type="hidden" name="id" id="edit_evaluation_id">

                <div class="modal-body">
                    <!-- الحقول الأساسية -->
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">العنوان</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>

                    <!-- فئات الاختيار المختلفة -->
                    <div class="mb-3">
                        <label for="edit_working_hour_id" class="form-label">الفوج</label>
                        <select name="working_hour_id" id="edit_working_hour_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_education_stage_id" class="form-label">المرحلة</label>
                        <select name="education_stage_id" id="edit_education_stage_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_classroom_id" class="form-label">الصف</label>
                        <select name="classroom_id" id="edit_classroom_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_id" class="form-label">الشعبة</label>
                        <select name="section_id" id="edit_section_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_subject_id" class="form-label">المادة</label>
                        <select name="subject_id" id="edit_subject_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_teacher_id" class="form-label">المعلم</label>
                        <select name="teacher_id" id="edit_teacher_id" class="form-select" required></select>
                    </div>

                    <!-- تاريخ التقييم -->
                    <div class="mb-3">
                        <label for="edit_evaluation_date" class="form-label">تاريخ التقييم</label>
                        <input type="date" name="evaluation_date" id="edit_evaluation_date" class="form-control" required>
                    </div>

                    <!-- التكرار -->
                    <div class="col-md-6">
                        <label for="Frequency_edit" class="form-label">تكرار التقييم</label>
                        <select class="form-select" id="Frequency_edit" name="frequency" required>
                            <option value="">اختر التكرار</option>
                            <option value="daily">يومي</option>
                            <option value="weekly">أسبوعي</option>
                            <option value="monthly">شهري</option>
                        </select>
                    </div>

                    <!-- نوع التقييم -->
                    <div class="col-md-6">
                        <label for="Type_edit" class="form-label">نوع التقييم</label>
                        <select class="form-select" id="Type_edit" name="type" required>
                            <option value="">اختر نوع التقييم</option>
                            <option value="quiz">اختبار قصير</option>
                            <option value="exam">اختبار نهائي</option>
                            <option value="assignment">واجب منزلي</option>
                            <option value="project">مشروع</option>
                            <option value="activity">نشاط</option>
                            <option value="participation">مشاركة</option>
                        </select>
                    </div>

                    <!-- الملاحظات -->
                    <div class="mb-3">
                        <label for="edit_note" class="form-label">ملاحظات</label>
                        <textarea name="note" id="edit_note" class="form-control"></textarea>
                    </div>

                    <!-- جدول الطلاب -->
                    <div id="edit_students_container" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>الرقم</th>
                                    <th>صورة الطالب</th>
                                    <th>اسم الطالب</th>
                                    <th>الدرجة</th>
                                    <th>الملاحظات</th>
                                </tr>
                            </thead>
                            <tbody id="editStudentsTable">
                                <!-- تعبئة بالـ JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit"  form="editEvaluationForm" class="btn btn-warning">حفظ التعديل</button>
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function loadStudentsedit(results = []) {

    const sectionId = document.getElementById('edit_section_id').value;
    const subjectId = document.getElementById('edit_subject_id').value;
    const teacherId = document.getElementById('edit_teacher_id').value;

    if (!sectionId || !subjectId || !teacherId) {
        document.getElementById('editStudentsTable').innerHTML = '';
           
        return;
     
    
    }

    const url = `/student/getstudent_by_section_andsubject_and_teacher?section_id=${sectionId}&subject_id=${subjectId}&teacher_id=${teacherId}`;
    const storageBaseUrl = "{{ asset('storage') }}";
    const tbody = document.getElementById('editStudentsTable');

    fetch(url)
        .then(response => response.json())
        .then(students => {
            console.log('Students:', students);
           

            let rows = '';

            students.forEach(student => {
                let grade = '';
                let notes = '';

                if (Array.isArray(results) && results.length > 0) {
                    const result = results.find(r => r.student.id === student.id);
                   console.log(result,"result")
                    if (result) {
                        grade = result.grade || '';
                        notes = result.feedback || '';
                    }
                }

                const imageUrl = storageBaseUrl + '/' + student.image_path;

                rows += `
                    <tr>
                        <td>${student.id}</td>
                        <td><img src="${imageUrl}" alt="صورة الطالب" class="teacher-photo me-2 protected-data" style="width: 40px; height: 40px; border-radius: 50%;"></td>
                        <td>${student.name}</td>
                        <td><input type="number" class="form-control" name="students[${student.id}][grade]" min="0" max="100" value="${grade}"></td>
                        <td><input type="text" class="form-control" name="students[${student.id}][notes]" value="${notes}"></td>
                    </tr>
                `;
            });

            tbody.innerHTML = rows;
        })
        .catch(error => {
            console.error('خطأ في جلب بيانات الطلاب:', error);
        });
}
document.getElementById('edit_section_id').addEventListener('change', loadStudentsedit);
document.getElementById('edit_subject_id').addEventListener('change', loadStudentsedit);
document.getElementById('edit_teacher_id').addEventListener('change', loadStudentsedit);

function updateTeachers(selectedTeacherId = null, onLoaded = null) {
    const subjectSelect = document.getElementById('edit_subject_id');
    const sectionSelect = document.getElementById('edit_section_id');
    const teacherSelect = document.getElementById('edit_teacher_id');

    const subjectID = subjectSelect.value;
    const sectionID = sectionSelect.value;

    if (!subjectID || !sectionID) {
        teacherSelect.innerHTML = `<option value="">اختر المعلم</option>`;
        if (typeof onLoaded === 'function') onLoaded();
        return;
    }

    fetch(`/teachers-by-subject-section?section_id=${sectionID}&subject_id=${subjectID}`)
        .then(res => res.json())
        .then(data => {
            let teacherOptions = `<option value="">اختر المعلم</option>`;
            data.forEach(t => {
                const selected = selectedTeacherId && selectedTeacherId == t.id ? 'selected' : '';
                teacherOptions += `<option value="${t.id}" ${selected}>${t.full_name}</option>`;
            });
            teacherSelect.innerHTML = teacherOptions;
            if (typeof onLoaded === 'function') onLoaded();
        })
        .catch(err => {
            console.error('خطأ في جلب المعلمين:', err);
            teacherSelect.innerHTML = `<option value="">لا يوجد معلمين</option>`;
            if (typeof onLoaded === 'function') onLoaded();
        });
}

// تحديث قائمة المعلمين عند تغيير المادة أو الشعبة
document.getElementById('edit_subject_id').addEventListener('change', () => updateTeachers());
document.getElementById('edit_section_id').addEventListener('change', () => updateTeachers());

// حدث الضغط على زر تعديل التقييم
$(document).on('click', '.editEvaluationBtn', function () {
    let evaluationId = $(this).data('id');
  const stageUpdateRouteTemplate = "{{ route('evaluation.update', ':id') }}";

     const actionUrl = stageUpdateRouteTemplate.replace(':id', evaluationId);
    document.getElementById('editEvaluationForm').setAttribute('action', actionUrl);


    $.get('/admin/evaluations/' + evaluationId + '/edit', function (data) {
        // تعبئة الحقول الأساسية
        $('#edit_evaluation_id').val(data.id);
        $('#edit_title').val(data.title);
        $('#Frequency_edit').val(data.frequency);
        $('#Type_edit').val(data.type);
        $('#edit_evaluation_date').val(data.evaluation_date);
        $('#edit_note').val(data.description);

        // تسلسل تحميل القيم المرتبطة
        loadWorkingHours(data.section.classroom.educational_stage.working_hour_id, 'edit_working_hour_id');

        bindSelectWithChild_Classroom({
            parentSelectId: 'edit_working_hour_id',
            childSelectId: 'edit_education_stage_id',
            urlTemplate: '/educational_stage/get_based_on_working/:id',
            selectedValue: data.section.classroom.educational_stage.id,
            onLoaded: function () {
                bindSelectWithChild_Classroom({
                    parentSelectId: 'edit_education_stage_id',
                    childSelectId: 'edit_classroom_id',
                    urlTemplate: '/classroom/get_based_on_stage/:id',
                    selectedValue: data.section.classroom.id,
                    onLoaded: function () {
                        bindSelectWithChild_Classroom({
                            parentSelectId: 'edit_classroom_id',
                            childSelectId: 'edit_section_id',
                            urlTemplate: '/section/get_based_on_classroom/:id',
                            selectedValue: data.section.id,
                            onLoaded: function () {
                                bindSelectWithChild_Classroom({
                                    parentSelectId: 'edit_section_id',
                                    childSelectId: 'edit_subject_id',
                                    urlTemplate: '/subject/get_in_section/:id',
                                    selectedValue: data.subject.id,
                                    onLoaded: function () {
                                       updateTeachers(data.teacher.id, () => {
    loadStudentsedit(data.results);
});
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });

        // فتح المودال
        $('#editEvaluationModal').modal('show');
    });
});
// حفظ التعديل



</script>
