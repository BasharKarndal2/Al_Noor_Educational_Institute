    <div class="modal fade" id="addteacher_to_sectionModalss" tabindex="-1" aria-labelledby="addteacher_to_sectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addteacher_to_sectionModalssModalLabel"><i class="fas fa-plus me-2"></i>اضافة معلم  الى شعبة  </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addteacher_to_sectionssForm" method="POST" action="">
                        @csrf
                        <div class="row g-3">                        
                             <x-select-field label="الفوج الدراسي" id="working_hour_adds" name="working_hour_id" required />
                             <x-select-field label="المرحلة  الدراسية" id="education_stage_adds" name="education_stage_id" required />
                             <x-select-field label="الصف  الدراسية" id="classroom_adds" name="classroom_id" required />

                                <x-select-field label="الشعبة  الدراسية" id="section_adds" name="section_id" required />    
                              <x-select-field label="المواد   الدراسية" id="subjct_adds" name="subjct_id" required />
                       
                            </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="addteacher_to_sectionssForm" class="btn btn-primary">حفظ الصف</button>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let teacherId = null;
    const modal = document.getElementById('addteacher_to_sectionModalss');
    const stageUpdateRouteTemplate = "{{ route('teacher.addteacher_to_section', ':id') }}";

    if (!modal) return;
  modal.addEventListener('show.bs.modal', event => {
    
        const button = event.relatedTarget;
        if (!button) return;
  console.log('Modal is opening, teacherId:', teacherId);
        teacherId = button.getAttribute('data-id');
        console.log('فتح المودال لمادة ID:', teacherId);

        const actionUrl = stageUpdateRouteTemplate.replace(':id', teacherId);
        document.getElementById('addteacher_to_sectionssForm').setAttribute('action', actionUrl);

        
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
        // الشعب الدراسية بناءً على الصف الدراسي     




        setupDependentSelectWithSubject(
            'section_adds',
            'subjct_adds',
            "{{ route('subject.get_subjects_in_teacher_and_section_notjoin') }}",
            () => teacherId,
            'جاري تحميل المواد...',
            '-- اختر المادة --'
        );



    });




    function resetSelectOptions(ids) {
        ids.forEach(id => {
            const select = document.getElementById(id);
            if (select) {
                select.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.text = '-- اختر --';
                defaultOption.value = '';
                select.appendChild(defaultOption);
            }
        });
    }
});

</script>
