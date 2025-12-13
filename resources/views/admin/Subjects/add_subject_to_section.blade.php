    <div class="modal fade" id="addsubject_to_sectionModalss" tabindex="-1" aria-labelledby="addsubject_to_sectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addsubject_to_sectionModalssModalLabel"><i class="fas fa-plus me-2"></i>اضافة مادة الى شعبة  </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addsubject_to_sectionssForm" method="POST" action="">
                        @csrf
                        <div class="row g-3">                        
                             <x-select-field label="الفوج الدراسي" id="working_hour_adds" name="working_hour_id" required />
                             <x-select-field label="المرحلة  الدراسية" id="education_stage_adds" name="education_stage_id" required />
                             <x-select-field label="الصف  الدراسية" id="classroom_adds" name="classroom_id" required />
                              <x-select-field label="الشعبة   الدراسية" id="section_adds" name="section_id" required />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="addsubject_to_sectionssForm" class="btn btn-primary">حفظ الصف</button>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let subjectId = null;
    const modal = document.getElementById('addsubject_to_sectionModalss');
    const stageUpdateRouteTemplate = "{{ route('subject.addsubject_to_section', ':id') }}";

    if (!modal) return;
  modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        if (!button) return;
  console.log('Modal is opening, subjectId:', subjectId);
        subjectId = button.getAttribute('data-id');
        console.log('فتح المودال لمادة ID:', subjectId);

        const actionUrl = stageUpdateRouteTemplate.replace(':id', subjectId);
        document.getElementById('addsubject_to_sectionssForm').setAttribute('action', actionUrl);

        
        // تحميل جديد
       loadOptionsIntoSelect('working_hour_adds', '/educational_stage/create', '-- اختر الفوج الدراسي --');

  
        setupDependentSelect(
            'working_hour_adds',
            'education_stage_adds',
            '/educational_stage/get_based_on_working/:id',
            'جاري تحميل المراحل...',
            '-- اختر المرحلة --'
        );

        setupDependentSelect(
            'education_stage_adds',
            'classroom_adds',
            '/classroom/get_based_on_stage/:id',
            'جاري تحميل الصفوف...',
            '-- اختر الصف --'
        );

        setupDependentSelectWithSubject(
            'classroom_adds',
            'section_adds',
            "/sectfsdfion/get_not_dsadin_subject",
            () => subjectId,
            'جاري تحميل الشعب...',
            '-- اختر الشعبة --'
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
