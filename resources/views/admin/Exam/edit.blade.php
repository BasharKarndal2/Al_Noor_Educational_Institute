<div class="modal fade" id="editExamModal" tabindex="-1" aria-labelledby="editExamLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editExamLabel">تعديل الاختبار</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form id="editExamForm" method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- العنوان --}}
                        <x-input-field nameinput="title" label="عنوان الاختبار" type="text" id="edit_title" />

                        {{-- الفوج الدراسي --}}
                        <x-select-field label="الفوج الدراسي" id="edit_working_hour_id" name="working_hour_id" required />

                        {{-- المرحلة الدراسية --}}
                        <x-select-field label="المرحلة الدراسية" id="edit_education_stage_id" name="education_stage_id" required />

                        {{-- الصف الدراسي --}}
                        <x-select-field label="الصف الدراسي" id="edit_classroom_id" name="classroom_id" required />

                        {{-- الشعبة --}}
                        <x-select-field label="الشعبة الدراسية" id="edit_section_id" name="section_id" required />

                        {{-- المادة --}}
                        <x-select-field label="المادة الدراسية" id="edit_subject_id" name="subject_id" required />

                        {{-- المعلم --}}
                        <x-select-field label="المعلم" id="edit_teacher_id" name="teacher_id" required />

                        {{-- تاريخ الاختبار --}}
                        <div class="col-md-4">
                            <label for="edit_exam_date" class="form-label">تاريخ الاختبار</label>
                            <input type="date" class="form-control" id="edit_exam_date" name="exam_date" required>
                        </div>

                        {{-- وقت البداية --}}
                        <div class="col-md-4">
                            <label for="edit_start_time" class="form-label">وقت البداية</label>
                            <input type="time" class="form-control" id="edit_start_time" name="start_time" required>
                        </div>

                        {{-- وقت النهاية --}}
                        <div class="col-md-4">
                            <label for="edit_end_time" class="form-label">وقت النهاية</label>
                            <input type="time" class="form-control" id="edit_end_time" name="end_time" required>
                        </div>

                        {{-- القاعة --}}
                        <div class="col-md-6">
                            <label for="edit_loc" class="form-label">القاعة</label>
                            <input type="text" class="form-control" id="edit_loc" name="loc">
                        </div>

                        {{-- رفع الملف --}}
                        <div class="col-md-6">
                            <label for="edit_exam_file" class="form-label">ملف الاختبار</label>
                            <input type="file" class="form-control" name="exam_file" id="edit_exam_file">
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
                <button type="submit" form="editExamForm" class="btn btn-warning">
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

document.getElementById('edit_subject_id').addEventListener('change', () => updateTeachers());
document.getElementById('edit_section_id').addEventListener('change', () => updateTeachers());

// فتح المودال وتعبئة الحقول عند الضغط على زر تعديل الاختبار
$(document).on('click', '.editExamBtn', function () {
console.log('sfdsd');

    let examId = $(this).data('id');
    const actionUrl = "{{ route('exams.update', ':id') }}".replace(':id', examId);
    $('#editExamForm').attr('action', actionUrl);

    $.get('/admin/exams/' + examId + '/edit', function (data) {
        $('#edit_title').val(data.title);
        $('#edit_exam_date').val(data.exam_date);
        $('#edit_start_time').val(data.start_time);
        $('#edit_end_time').val(data.end_time);
        $('#edit_loc').val(data.loc);
        $('#edit_description').val(data.description);

        $('#edit_section_id').val(data.section_id);
        $('#edit_subject_id').val(data.subject_id);
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
       

        $('#editExamModal').modal('show');
    });
});
</script>
