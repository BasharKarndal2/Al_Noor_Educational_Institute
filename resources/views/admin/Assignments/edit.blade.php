<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-labelledby="editAssignmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editAssignmentLabel">تعديل الواجب</h5>
            </div>

            {{-- Body --}}
            <div class="modal-body">
               <form id="editAssignmentForm" method="POST" enctype="multipart/form-data" action="">
    @csrf
    @method('PUT')
                    {{-- <input type="hidden" name="id" id="edit_assignment_id"> --}}

                    <div class="row g-3">
                        {{-- العنوان --}}
                        <x-input-field nameinput="title" label="عنوان الواجب" type="text" id="edit_title" />

                        {{-- الفوج الدراسي --}}
                        <x-select-field label="الفوج الدراسي" id="edit_working_hour_id" name="working_hour_id" required />

                        {{-- المرحلة الدراسية --}}
                        <x-select-field label="المرحلة الدراسية" id="edit_education_stage_id" name="education_stage_id" required />

                        {{-- الصف الدراسي --}}
                        <x-select-field label="الصف الدراسي" id="edit_classroom_id" name="classroom_id" required />

                        {{-- الشعبة الدراسية --}}
                        <x-select-field label="الشعبة الدراسية" id="edit_section_id" name="section_id" required />

                        {{-- المادة الدراسية --}}
                        <x-select-field label="المادة الدراسية" id="edit_subject_id" name="subject_id" required />

                        {{-- المعلم --}}
                        <x-select-field label="المعلم" id="edit_teacher_id" name="teacher_id" required />

                     

                        {{-- تاريخ التسليم --}}
                        <div class="col-md-6">
                            <label for="edit_due_date" class="form-label">آخر موعد للتسليم</label>
                            <input type="datetime-local" class="form-control" id="edit_due_date" name="due_date" required>
                        </div>

                        {{-- الحالة --}}
                        <div class="col-md-6">
                            <x-status id="edit_status" />
                        </div>

                        {{-- رفع الملف --}}
                        <div class="col-md-6">
                            <label for="edit_file" class="form-label">ملف الواجب</label>
                            <input type="file" class="form-control" name="file_path" id="edit_file">
                            <small class="text-muted">اتركه فارغًا إذا لا تريد تغيير الملف</small>
                        </div>

                        {{-- التعليمات --}}
                        <div class="col-md-12">
                            <label for="edit_description" class="form-label">التعليمات والإرشادات</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="5" required></textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="submit" form="editAssignmentForm" class="btn btn-warning">
                    <i class="bi bi-save"></i> حفظ التعديلات
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
<script>


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
$(document).on('click', '.editAssignmentBtn', function () {
    let assignmentId = $(this).data('id');
    const stageUpdateRouteTemplate = "{{ route('assignments.update', ':id') }}";
    const actionUrl = stageUpdateRouteTemplate.replace(':id', assignmentId);
    console.log(actionUrl);
    $('#editAssignmentForm').attr('action', actionUrl);


    $.get('/admin/assignment/' + assignmentId + '/edit', function (data) {
        // تعبئة الحقول الأساسية
 
        $('#edit_title').val(data.title);
 $('#edit_due_date').val(data.due_date);

 $('#edit_status').val(data.status);
 $('#edit_description').val(data.description);

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
        $('#editAssignmentModal').modal('show');
    });
});
// حفظ التعديل



</script>
