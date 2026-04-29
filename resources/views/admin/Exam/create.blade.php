<div class="modal fade" id="addExamModal" tabindex="-1" aria-labelledby="addExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            {{-- Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addExamModalLabel">إضافة اختبار جديد</h5>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form id="addExamModalForm" method="POST" enctype="multipart/form-data" action="{{ route('exams.store') }}">
                    @csrf
                    <div class="row g-3">

                        {{-- عنوان الاختبار --}}
                        <x-input-field nameinput="title" label="عنوان الاختبار" type="text" />

                        {{-- الفوج الدراسي --}}
                        <x-select-field label="الفوج الدراسي" id="working_hour_adds" name="working_hour_id" required />

                        {{-- المرحلة الدراسية --}}
                        <x-select-field label="المرحلة الدراسية" id="education_stage_adds" name="education_stage_id" required />

                        {{-- الصف الدراسي --}}
                        <x-select-field label="الصف الدراسي" id="classroom_adds" name="classroom_id" required />

                        {{-- الشعبة الدراسية --}}
                        <x-select-field label="الشعبة الدراسية" id="section_adds" name="section_id" required />

                        {{-- المادة الدراسية --}}
                        <x-select-field label="المادة الدراسية" id="subjct_adds" name="subject_id" required />

                        {{-- المعلم --}}
                        <x-select-field label="المعلم" id="teacher_adds" name="teacher_id" required />

                        {{-- تاريخ الاختبار --}}
                        <div class="col-md-4">
                            <label for="exam_date" class="form-label">تاريخ الاختبار</label>
                            <input type="date" class="form-control" id="exam_date" name="exam_date" required>
                        </div>

                        {{-- وقت البداية --}}
                        <div class="col-md-4">
                            <label for="start_time" class="form-label">وقت البداية</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>

                        {{-- وقت النهاية --}}
                        <div class="col-md-4">
                            <label for="end_time" class="form-label">وقت النهاية</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>

                        {{-- القاعة --}}
                        <div class="col-md-6">
                            <label for="loc" class="form-label">القاعة</label>
                            <input type="text" class="form-control" id="loc" name="loc">
                        </div>

                        {{-- الملف المرفق --}}
                        <div class="col-md-6">
                            <label for="exam_file" class="form-label">ملف تفاصيل الاختبار</label>
                            <input type="file" class="form-control" name="exam_file" id="exam_file">
                        </div>

                        {{-- الوصف / التفاصيل --}}
                        <div class="col-md-12">
                            <label for="description_add" class="form-label">التعليمات والإرشادات</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description_add" rows="5" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="addExamModalForm" class="btn btn-primary">
                    <i class="bi bi-save"></i> حفظ الاختبار
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addExamModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', event => {
        loadOptionsIntoSelect('working_hour_adds', '/educational_stage/create', '-- اختر الفوج الدراسي --');

        setupDependentSelect('working_hour_adds','education_stage_adds','/educational_stage/get_based_on_working/:id','جاري تحميل المراحل...','-- اختر المرحلة --');
        setupDependentSelect('education_stage_adds','classroom_adds','/classroom/get_based_on_stage/:id','جاري تحميل الصفوف...','-- اختر الصف --');
        setupDependentSelect('classroom_adds','section_adds','/section/get_based_on_classroom/:id','جاري تحميل الشعب...','-- اختر الشعبة --');
        setupDependentSelect('section_adds','subjct_adds','/subject/get_in_section/:id','جاري تحميل المواد...','-- اختر المادة --');

        const subjectSelect = document.getElementById('subjct_adds');
        const sectionSelect = document.getElementById('section_adds');
        const teacherSelect = document.getElementById('teacher_adds');

        function updateTeachers() {
            const subjectID = subjectSelect.value;
            const sectionID = sectionSelect.value;

            if (!subjectID || !sectionID) {
                teacherSelect.innerHTML = `<option value="">اختر المعلم</option>`;
                return;
            }

            fetch(`/teachers-by-subject-section?section_id=${sectionID}&subject_id=${subjectID}`)
                .then(res => res.json())
                .then(data => {
                    let teacherOptions = `<option value="">اختر المعلم</option>`;
                    data.forEach(t => {
                        teacherOptions += `<option value="${t.id}">${t.full_name}</option>`;
                    });
                    teacherSelect.innerHTML = teacherOptions;
                })
                .catch(err => {
                    console.error('خطأ في جلب المعلمين:', err);
                    teacherSelect.innerHTML = `<option value="">لا يوجد معلمين</option>`;
                });
        }

        subjectSelect.addEventListener('change', updateTeachers);
        sectionSelect.addEventListener('change', updateTeachers);
    });
});
</script>
