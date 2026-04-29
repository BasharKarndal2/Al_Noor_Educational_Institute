<!-- Modal تعديل: اضافة مادة إلى معلم -->
<div class="modal fade" id="addsubject_to_teacherModal" tabindex="-1" aria-labelledby="addsubject_to_teacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addsubject_to_teacherModalLabel"><i class="fas fa-plus me-2"></i>إضافة مادة إلى معلم</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addsubject_to_teacherForm" method="POST" >
                    @csrf
                    <div class="mb-3 col-12">
                        <div id="selected-subjects" class="mb-3">
                            <!-- سيتم عرض المواد المختارة هنا -->
                        </div>
                        <label for="subject_ids" class="form-label">اختر المواد</label>
                        <select id="subject_ids" class="form-select" multiple style="height: 150px;">
                            <!-- سيتم تعبئتها ديناميكيًا -->
                        </select>
                        <small class="form-text text-muted">يمكنك اختيار المواد ثم حذفها بالضغط على (x) بجانبها.</small>
                    </div>
                    <div id="hidden-subject-inputs"></div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="addsubject_to_teacherForm" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addsubject_to_teacherModal');
    const routeTemplate = "{{ route('teacher.addsubject_to_teacher', ':id') }}";

    modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const teacherID = button.getAttribute('data-id');

        const actionUrl = routeTemplate.replace(':id', teacherID);
        document.getElementById('addsubject_to_teacherForm').setAttribute('action', actionUrl);

        loadSubjectsForTeacher(teacherID);
    });
});

function loadSubjectsForTeacher(teacherID) {
    const select = document.getElementById('subject_ids');
    select.innerHTML = `<option>جاري تحميل المواد...</option>`;
    select.disabled = true;

    const url = `/subject/get_not_in_teacher/${teacherID}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            let options = '';
            data.forEach(subject => {
                options += `<option value="${subject.id}">${subject.name}</option>`;
            });
            select.innerHTML = options;
            select.disabled = false;
        })
        .catch(err => {
            console.error('خطأ في تحميل المواد:', err);
            select.innerHTML = `<option>خطأ في تحميل المواد</option>`;
            select.disabled = true;
        });
}

// التعامل مع الإضافة/الإزالة
const select = document.getElementById('subject_ids');
const selectedContainer = document.getElementById('selected-subjects');
const hiddenInputsContainer = document.getElementById('hidden-subject-inputs');

select.addEventListener('change', function () {
    const selectedOptions = Array.from(select.selectedOptions);
    selectedOptions.forEach(option => {
        if (!document.getElementById('label-subject-' + option.value)) {
            addSelectedSubjectLabel(option.value, option.text);
            addHiddenInput(option.value);
        }
        option.remove(); // أزل من select
    });
    select.selectedIndex = -1;
});

function addSelectedSubjectLabel(id, name) {
    const wrapper = document.createElement('div');
    wrapper.className = 'd-flex align-items-center gap-2 mb-2';
    wrapper.id = 'label-wrapper-subject-' + id;

    const label = document.createElement('span');
    label.className = 'badge bg-primary';
    label.style.cursor = 'default';
    label.innerHTML = `${name}`;

    const priceInput = document.createElement('input');
    priceInput.type = 'number';
    priceInput.name = `subject_prices[${id}]`;
    priceInput.placeholder = 'السعر';
    priceInput.required = true;
    priceInput.min = 0;
    priceInput.step = 'any';
    priceInput.className = 'form-control form-control-sm w-auto';
    priceInput.style.direction = 'ltr';

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'btn-close btn-close-white btn-sm';
    closeBtn.setAttribute('aria-label', 'حذف');

    closeBtn.addEventListener('click', () => {
        wrapper.remove();
        document.getElementById('hidden-subject-' + id)?.remove();

        // إرجاع المادة إلى قائمة الاختيار
        const option = document.createElement('option');
        option.value = id;
        option.text = name;
        select.appendChild(option);
    });

    wrapper.appendChild(label);
    wrapper.appendChild(priceInput);
    wrapper.appendChild(closeBtn);
    selectedContainer.appendChild(wrapper);
}


function addHiddenInput(id) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'subject_ids[]';
    input.value = id;
    input.id = 'hidden-subject-' + id;
    hiddenInputsContainer.appendChild(input);
}
</script>




