
<!-- Modal -->
<div class="modal fade" id="editTimetableModal" tabindex="-1" aria-labelledby="editTimetableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">تعديل جدول أسبوعي</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="" id="edit_editclassSechdulForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <!-- الحقول الأساسية -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <x-select-field label="الفوج الدراسي" id="edit_edit_working_hour_id" name="working_hour_id" required />
                        </div>
                        <div class="col-md-6">
                            <x-select-field label="المرحلة الدراسية" id="edit_edit_education_stage_id" name="education_stage_id" required />
                        </div>
                        <div class="col-md-6">
                            <x-select-field label="الصف الدراسي" id="edit_edit_classroom_id" name="classroom_id" required />
                        </div>
                        <div class="col-md-6">
                            <x-select-field label="الشعبة" id="edit_edit_section_id" name="section_id" required />
                        </div>
                    </div>

                    @php
                        $days = ['sunday'=>'الأحد','monday'=>'الاثنين','tuesday'=>'الثلاثاء','wednesday'=>'الأربعاء','thursday'=>'الخميس','friday'=>'الجمعة','saturday'=>'السبت'];
                        $dayColors = ['sunday'=>'#FFCDD2','monday'=>'#C8E6C9','tuesday'=>'#BBDEFB','wednesday'=>'#FFF9C4','thursday'=>'#D1C4E9','friday'=>'#FFE0B2','saturday'=>'#B2DFDB'];
                    @endphp

                    @foreach($days as $key => $day)
                        <div class="mb-4 p-3 rounded" style="background-color: {{ $dayColors[$key] }};">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-2">{{ $day }}</h5>
                                <button type="button" class="btn btn-sm btn-success" onclick="editPeriod('{{ $key }}')">
                                    <i class="fas fa-plus"></i> إضافة حصة
                                </button>
                            </div>
                            <div id="edit_periods-{{ $key }}"></div>
                        </div>
                    @endforeach

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">حفظ الجدول</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let subjectsList = []; // لتخزين المواد للشعبة الحالية

// عند الضغط على زر فتح المودال
$(document).on('click', '.editTimetableModalbtn', async function () {
    let sectionId = $(this).data('id');

    // تحديث رابط الفورم
    const stageUpdateRouteTemplate = "{{ route('class_schedule.update', ':id') }}";
    $('#edit_editclassSechdulForm').attr('action', stageUpdateRouteTemplate.replace(':id', sectionId));

    try {
        // جلب بيانات القسم والجدول
        const { sections, schedule } = await $.getJSON(`/schedule/${sectionId}/edit`);
        if (!sections || !sections.section || !sections.section.classroom) return;

        const sectionData = sections.section;
        const classroom = sectionData.classroom;
        const stage = classroom.educational_stage;

        // تعبئة الحقول الأساسية
        await loadWorkingHours(stage.working_hour_id, 'edit_edit_working_hour_id');

        await new Promise(resolve => {
            bindSelectWithChild_Classroom({
                parentSelectId: 'edit_edit_working_hour_id',
                childSelectId: 'edit_edit_education_stage_id',
                urlTemplate: '/educational_stage/get_based_on_working/:id',
                selectedValue: stage.id,
                onLoaded: resolve
            });
        });

        await new Promise(resolve => {
            bindSelectWithChild_Classroom({
                parentSelectId: 'edit_edit_education_stage_id',
                childSelectId: 'edit_edit_classroom_id',
                urlTemplate: '/classroom/get_based_on_stage/:id',
                selectedValue: classroom.id,
                onLoaded: resolve
            });
        });

        await new Promise(resolve => {
            bindSelectWithChild_Classroom({
                parentSelectId: 'edit_edit_classroom_id',
                childSelectId: 'edit_edit_section_id',
                urlTemplate: '/section/get_based_on_classroom/:id',
                selectedValue: sectionData.id,
                onLoaded: resolve
            });
        });

        // مسح الحصص القديمة
        Object.keys(schedule).forEach(day => {
            const container = document.getElementById(`edit_periods-${day}`);
            if(container) container.innerHTML = '';
        });

        // جلب المواد للشعبة
        subjectsList = await fetch(`/subject/get_in_section/${sectionData.id}`).then(res=>res.json());

        // تعبئة الحصص لكل يوم
        for(const day of Object.keys(schedule)){
            const periods = schedule[day];
            for(const period of periods){
                fillPeriod(day, period, sectionData.id);
            }
        }

        // فتح المودال بعد الانتهاء
        $('#editTimetableModal').modal('show');

    } catch(err) {
        console.error('خطأ في جلب بيانات الجدول:', err);
    }
});

// دالة لإضافة حصة جديدة أو تعبئة موجودة
function fillPeriod(day, period = null, sectionID = null) {
    const container = document.getElementById(`edit_periods-${day}`);
    if (!container) return;

    const index = container.children.length + 1;

    const html = `
    <div class="card mb-2 p-3 shadow-sm" data-index="${index}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">الحصة ${index}</h6>
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.card').remove()">حذف</button>
        </div>

        <input type="hidden" name="periods[${day}][${index}][period_number]" value="${period ? period.period_number : index}">
        <input type="hidden" name="periods[${day}][${index}][day_of_week]" value="${day}">

        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label">بداية</label>
                <input type="time" class="form-control" name="periods[${day}][${index}][start_time]" value="${period ? period.start_time : ''}" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">نهاية</label>
                <input type="time" class="form-control" name="periods[${day}][${index}][end_time]" value="${period ? period.end_time : ''}" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">المادة</label>
                <select class="form-select subject-select" id="edit_subject_${day}_${index}" name="periods[${day}][${index}][subject_id]" required>
                    <option value="">-- اختر المادة --</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">المعلم</label>
                <select class="form-select teacher-select" id="edit_teacher_${day}_${index}" name="periods[${day}][${index}][teacher_id]" required>
                    <option value="">اختر المعلم</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">لون</label>
                <input type="color" class="form-control form-control-color" name="periods[${day}][${index}][color]" value="${period ? period.color : '#ffffff'}">
            </div>

            <div class="col-md-2">
                <label class="form-label">نوع الحصة</label>
                <select class="form-select" name="periods[${day}][${index}][is_break]" required>
                    <option value="0" ${period && period.is_break==0 ? 'selected':''}>دراسية</option>
                    <option value="1" ${period && period.is_break==1 ? 'selected':''}>استراحة</option>
                </select>
            </div>
        </div>
    </div>
    `;

    container.insertAdjacentHTML('beforeend', html);

    const subjectSelect = $(`#edit_subject_${day}_${index}`);
    const teacherSelect = $(`#edit_teacher_${day}_${index}`);

    // تعبئة المواد
    if(subjectsList.length){
        subjectsList.forEach(sub=>{
            const option = new Option(sub.name, sub.id);
            if(period && period.subject && period.subject.id==sub.id) option.selected = true;
            subjectSelect.append(option);
        });
    }

    // جلب المعلمين عند اختيار المادة
    const loadTeachers = (subjectID) => {
        if (!sectionID || !subjectID) return;
        fetch(`/teachers-by-subject-section?section_id=${sectionID}&subject_id=${subjectID}`)
            .then(res => res.json())
            .then(data => {
                teacherSelect.empty();
                teacherSelect.append(new Option('اختر المعلم', '')); 
                data.forEach(t=>{
                    const option = new Option(t.full_name, t.id);
                    if(period && period.teacher && period.teacher.id==t.id) option.selected = true;
                    teacherSelect.append(option);
                });
            })
            .catch(err=>{
                teacherSelect.html('<option value="">لا يوجد معلمين</option>');
                console.error(err);
            });
    };

    // إذا الحصة موجودة مسبقاً، جلب المعلمين مباشرة
    if(period && period.subject) loadTeachers(period.subject.id);

    // عند تغيير المادة
    subjectSelect.on('change', function(){
        const subjectID = $(this).val();
        teacherSelect.empty().append(new Option('اختر المعلم', ''));
        if(subjectID){
            fetch(`/teachers-by-subject-section?section_id=${sectionID}&subject_id=${subjectID}`)
                .then(res=>res.json())
                .then(data=>{
                    data.forEach(t=>{
                        teacherSelect.append(new Option(t.full_name, t.id));
                    });
                })
                .catch(err=>{
                    console.error('خطأ في جلب المعلمين:', err);
                    teacherSelect.html('<option value="">لا يوجد معلمين</option>');
                });
        }
    });
}

// دالة لإضافة حصة جديدة بواسطة الزر
function editPeriod(day){
    const sectionID = $('#edit_edit_section_id').val();
    fillPeriod(day, null, sectionID);
}
</script>
@endpush
