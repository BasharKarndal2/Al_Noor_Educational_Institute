<div class="modal fade" id="addAssignmentModal" tabindex="-1" aria-labelledby="addAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            {{-- Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addAssignmentModalLabel">إضافة واجب جديد</h5>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form id="addAssignmentModalForm" method="POST"  enctype="multipart/form-data"  action="{{ route('assignments.store') }}">
                    @csrf
                    <div class="row g-3">

                        {{-- العنوان --}}
                        <x-input-field nameinput="title" label="عنوان الواجب" type="text"  />

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

                        {{-- نوع الواجب --}}
                    
                        {{-- تاريخ التسليم --}}
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">آخر موعد للتسليم</label>
                            <input type="datetime-local" class="form-control" id="due_date" name="due_date" required>
                        </div>
                      <x-status  />

                     <div class="col-md-6">
                            <label for="file" class="form-label">ملفات المرفقة  </label>
                             <input type="file" class="form-control" name="file_path" id="file" >
                        </div> 

                      <div class="col-md-12">
                            <label for="description_add" class="form-label">التعليمات والإرشادات</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description_add"  rows="5"  required name="description">{{ old('description') }}</textarea>
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
                <button type="submit" form="addAssignmentModalForm" class="btn btn-primary">
                    <i class="bi bi-save"></i> حفظ الواجب
                </button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const modal = document.getElementById('addAssignmentModal');
   
    if (!modal) return;
  modal.addEventListener('show.bs.modal', event => {
    
        // تحميل جديد
       loadOptionsIntoSelect('working_hour_adds', '/educational_stage/create', '-- اختر الفوج الدراسي --');

  // المراحل الدراسية بناءً على الفوج الدراسي
        setupDependentSelect(
            'working_hour_adds',
            'education_stage_adds',
            '/educational_stage/get_based_on_working/:id',
            'جاري تحميل المراحل...',
            '-- اختر المرحلة --'
        );

        // الصفوف الدراسية بناءً على المرحلة الدراسية
        setupDependentSelect(
            'education_stage_adds',
            'classroom_adds',
            '/classroom/get_based_on_stage/:id',
            'جاري تحميل الصفوف...',
            '-- اختر الصف --'
        );


        setupDependentSelect(
            'classroom_adds',
            'section_adds',
            '/section/get_based_on_classroom/:id',
            'جاري تحميل الشعب...',
            '-- اختر الشعبة --'
        );


           setupDependentSelect(
            'section_adds',
            'subjct_adds',
            '/subject/get_in_section/:id',
       
            'جاري تحميل المواد...',
            '-- اختر المادة --'
        );
   

const subjectSelect = document.getElementById('subjct_adds');
const sectionSelect = document.getElementById('section_adds');
const teacherSelect = document.getElementById('teacher_adds');

 
function updateTeachers() {

    
    const subjectID = subjectSelect.value;
    const sectionID = sectionSelect.value;

    if (!subjectID || !sectionID) {
        // إذا أحد القيم غير مختارة، نفرغ قائمة المعلمين أو نرجع الخيار الافتراضي فقط
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



});});

</script>
