

<!-- Modal -->
<div class="modal fade" id="addTimetableModal" tabindex="-1" aria-labelledby="addTimetableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">إضافة جدول أسبوعي</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('class_schedule.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <!-- الحقول الأساسية -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <x-select-field label="الفوج الدراسي" id="working_hour" name="working_hour_id" required />
                        </div>
                        <div class="col-md-6">
                            <x-select-field label="المرحلة الدراسية" id="education_stage" name="education_stage_id" required />
                        </div>
                        <div class="col-md-6">
                            <x-select-field label="الصف الدراسية" id="classroom" name="classroom_id" required />
                        </div>
                        <div class="col-md-6">
                            <x-select-field label="الصفوف الدراسية" id="section" name="section_id" required />
                        </div>
                    </div>

                    @php
                        $days = [
                            'sunday' => 'الأحد',
                            'monday' => 'الاثنين',
                            'tuesday' => 'الثلاثاء',
                            'wednesday' => 'الأربعاء',
                            'thursday' => 'الخميس',
                            'friday' => 'الجمعة',
                            'saturday' => 'السبت',
                        ];

                        $dayColors = [
                            'sunday' => '#FFCDD2',
                            'monday' => '#C8E6C9',
                            'tuesday' => '#BBDEFB',
                            'wednesday' => '#FFF9C4',
                            'thursday' => '#D1C4E9',
                            'friday' => '#FFE0B2',
                            'saturday' => '#B2DFDB',
                        ];
                    @endphp

                    @foreach($days as $key => $day)
                        <div class="mb-4 p-3 rounded" style="background-color: {{ $dayColors[$key] }};">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-2">{{ $day }}</h5>
                                <button type="button" class="btn btn-sm btn-success" onclick="addPeriod('{{ $key }}')">
                                    <i class="fas fa-plus"></i> إضافة حصة
                                </button>
                            </div>
                            <div id="periods-{{ $key }}"></div>
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
// قائمة المواد للشعبة في مودال الإضافة
let subjectsListAdd = [];

// تحميل الخيارات الأساسية والاعتماديات
loadOptionsIntoSelect('working_hour', '/educational_stage/create', '-- اختر الفوج الدراسي --');
setupDependentSelect('working_hour', 'education_stage', '/educational_stage/get_based_on_working/:id', 'جاري تحميل المراحل...', '-- اختر المرحلة --');
setupDependentSelect('education_stage', 'classroom', '/classroom/get_based_on_stage/:id', 'جاري تحميل الصفوف...', '-- اختر الصف --');
setupDependentSelect('classroom', 'section', '/section/get_based_on_classroom/:id', 'جاري تحميل الشعب...', '-- اختر الشعبة --');

// تحميل المواد عند تغيير الشعبة
const sectionSelectAdd = document.getElementById('section');
sectionSelectAdd.addEventListener('change', function() {
    const sectionID = this.value;
    if (!sectionID) return;

    // فحص إذا كانت الشعبة لديها جدول مسبق
    fetch(`/check-section-schedule/${sectionID}`)
        .then(res => res.json())
        .then(data => {
            if(data.exists){
                Swal.fire({
                    icon: 'info',
                    title: 'تنبيه',
                    text: 'الرجاء تغيير الشعبة لأن هذه الشعبة تم اضافة لها جدول من قبل. إذا أردت الإضافة، قم بحذف الجدول السابق.',
                });
                // مسح كل الحصص
                document.querySelectorAll('[id^="periods-"]').forEach(div => div.innerHTML = '');
                return;
            }

            // تحميل المواد إذا لم يكن هناك جدول مسبق
            fetch(`/subject/get_in_section/${sectionID}`)
                .then(res => res.json())
                .then(data => { subjectsListAdd = data; })
                .catch(err => console.error('خطأ في تحميل المواد:', err));
        })
        .catch(err => console.error('خطأ في فحص الجدول:', err));
});

// إضافة حصة جديدة في مودال الإضافة
function addPeriod(day) {
    const container = document.getElementById(`periods-${day}`);
    const index = container.children.length + 1;

    const html = `
        <div class="card mb-2 p-3 shadow-sm" data-index="${index}">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">الحصة ${index}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.card').remove()">حذف</button>
            </div>

            <input type="hidden" name="periods[${day}][${index}][period_number]" value="${index}">
            <input type="hidden" name="periods[${day}][${index}][day_of_week]" value="${day}">

            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">بداية</label>
                    <input type="time" class="form-control" name="periods[${day}][${index}][start_time]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">نهاية</label>
                    <input type="time" class="form-control" name="periods[${day}][${index}][end_time]" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">المادة</label>
                    <select class="form-select subject-select-add" id="add_subject_${day}_${index}" name="periods[${day}][${index}][subject_id]" required>
                        <option value="">-- اختر المادة --</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">المعلم</label>
                    <select class="form-select teacher-select-add" id="add_teacher_${day}_${index}" name="periods[${day}][${index}][teacher_id]" required>
                        <option value="">اختر المعلم</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">لون</label>
                    <input type="color" class="form-control form-control-color" name="periods[${day}][${index}][color]" value="#ffffff">
                </div>
                <div class="col-md-2">
                    <label class="form-label">نوع الحصة</label>
                    <select class="form-select" name="periods[${day}][${index}][is_break]" required>
                        <option value="0">دراسية</option>
                        <option value="1">استراحة</option>
                    </select>
                </div>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', html);

    // تعبئة المواد
    const subjectSelect = document.getElementById(`add_subject_${day}_${index}`);
    if(subjectsListAdd.length){
        subjectsListAdd.forEach(sub => {
            const option = document.createElement('option');
            option.value = sub.id;
            option.textContent = sub.name;
            subjectSelect.appendChild(option);
        });
    }

    // جلب المعلمين عند اختيار المادة
    subjectSelect.addEventListener('change', function() {
        const sectionID = document.getElementById('section').value;
        const subjectID = this.value;
        const teacherSelect = document.getElementById(`add_teacher_${day}_${index}`);

        teacherSelect.innerHTML = `<option value="">اختر المعلم</option>`;
        if(!sectionID || !subjectID) return;

        fetch(`/teachers-by-subject-section?section_id=${sectionID}&subject_id=${subjectID}`)
            .then(res => res.json())
            .then(data => {
                data.forEach(t => {
                    const option = document.createElement('option');
                    option.value = t.id;
                    option.textContent = t.full_name;
                    teacherSelect.appendChild(option);
                });
            })
            .catch(err => {
                console.error('خطأ في جلب المعلمين:', err);
                teacherSelect.innerHTML = `<option value="">لا يوجد معلمين</option>`;
            });
    });
}
</script>
@endpush
