    <div class="modal fade" id="addsubject_to_sectionModal" tabindex="-1" aria-labelledby="addsubject_to_sectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addsubject_to_sectionModalLabel"><i class="fas fa-plus me-2"></i>اضافة مادة الى شعبة  </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addsubject_to_sectionForm" method="POST" action="">
                        @csrf
                     <div class="mb-3 col-12">

                        <div id="selected-subjects" class="mb-3">
    <!-- هنا سيتم عرض الـ labels للمواد المختارة -->
</div>
    <label for="subject_ids" class="form-label">اختر المواد</label>
    <select id="subject_ids" class="form-select" multiple style="height: 150px;">
        <!-- يتم تعبئتها ديناميكيًا -->
    </select>
    <small class="form-text text-muted">يمكنك اختيار المواد ثم حذفها بالضغط على (x) بجانبها.</small>
</div>



<!-- عناصر مخفية ترسل في الفورم -->
<div id="hidden-subject-inputs"></div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="addsubject_to_sectionForm" class="btn btn-primary">حفظ البيانات</button>
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // تعريف المتغير subjectId في نطاق السكربت العام
    let subjectId = null;

    const modal = document.getElementById('addsubject_to_sectionModal');
    const stageUpdateRouteTemplate = "{{ route('section.addsubjects_to_section', ':id') }}";

 modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const sectionID = button.getAttribute('data-id');  // رقم الشعبة

    // تحديث رابط الفورم (كما قبل)
    const actionUrl = stageUpdateRouteTemplate.replace(':id', sectionID);
    document.getElementById('addsubject_to_sectionForm').setAttribute('action', actionUrl);

    // بعد تحديث الـ sectionID، استدعي دالة لتحميل المواد مع إرسال رقم الشعبة
    loadSubjectsForSection(sectionID);
});});


function loadSubjectsForSection(sectionID) {
    const select = document.getElementById('subject_ids');

    // تهيئة الاختيارات مؤقتاً أثناء التحميل
    select.innerHTML = `<option>جاري تحميل المواد...</option>`;
    select.disabled = true;

    // بناء رابط الـ API مع ارسال رقم الشعبة كـ query param أو جزء من الرابط
    const url = `/subject/get_not_in_section?section_id=${sectionID}`;

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




const select = document.getElementById('subject_ids');
const selectedContainer = document.getElementById('selected-subjects');
const hiddenInputsContainer = document.getElementById('hidden-subject-inputs');

select.addEventListener('change', function () {
    const selectedOptions = Array.from(select.selectedOptions);

    selectedOptions.forEach(option => {
        // أضف label إذا لم يكن موجوداً أصلاً
        if (!document.getElementById('label-subject-' + option.value)) {
            addSelectedSubjectLabel(option.value, option.text);
            addHiddenInput(option.value);
        }
        // أزل المادة من select
        option.remove();
    });

    // إلغاء التحديد بعد النقل
    select.selectedIndex = -1;
});

function addSelectedSubjectLabel(id, name) {
    const label = document.createElement('span');
    label.className = 'badge bg-primary me-2 mb-2';
    label.id = 'label-subject-' + id;
    label.style.cursor = 'default';
    label.innerHTML = `
        ${name} 
        <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="حذف"></button>
    `;

    label.querySelector('button').addEventListener('click', () => {
        // إزالة اللابل
        label.remove();
        // إزالة الحقل المخفي
        document.getElementById('hidden-subject-' + id).remove();
        // إعادة إضافة الخيار إلى select
        const option = document.createElement('option');
        option.value = id;
        option.text = name;
        select.appendChild(option);
    });

    selectedContainer.appendChild(label);
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

