<div class="modal fade" id="addQuestionModal" tabindex="-1" aria-labelledby="addQuestionModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addEducational_StageModalLabel">
                    <i class="fas fa-user-plus me-2"></i>إضافة  اسئلة جديدة
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-right">
                <form id="addQuestionform" method="POST" action="{{ route('questins.store') }}">
                    @csrf
                    <div class="row g-3  ">
                     
                        <x-select-field label="الفوج الدراسي" id="working_hour" name="working_hour_id" required />
                        <x-select-field label="المرحلة الدراسية" id="education_stage" name="education_stage_id" required />
                        <x-select-field label="الصف الدراسية" id="classroom" name="classroom_id" required />
                        <x-select-field label="المادة الدراسية" id="subjects" name="subject_id" required />
  <div id="questions-container">
    <div class="question-item border rounded p-3 mb-4">
        <x-input-field   label='نص السؤال' nameinput="questions[0][name]" rows="3" type="text" />

        <div class="form-group">
            <label class="form-label">الخيارات</label>

            <div class="form-check">
                <input class="form-check-input" type="radio" name="questions[0][correct_option]" value="a" required>
                <label class="form-check-label d-flex w-100">
                    <span class="me-2">A:</span>
                    <input type="text" name="questions[0][option_a]" class="form-control" placeholder="الخيار A" required>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="questions[0][correct_option]" value="b">
                <label class="form-check-label d-flex w-100">
                    <span class="me-2">B:</span>
                    <input type="text" name="questions[0][option_b]" class="form-control" placeholder="الخيار B" required>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="questions[0][correct_option]" value="c">
                <label class="form-check-label d-flex w-100">
                    <span class="me-2">C:</span>
                    <input type="text" name="questions[0][option_c]" class="form-control" placeholder="الخيار C" required>
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="questions[0][correct_option]" value="d">
                <label class="form-check-label d-flex w-100">
                    <span class="me-2">D:</span>
                    <input type="text" name="questions[0][option_d]" class="form-control" placeholder="الخيار D" required>
                </label>
            </div>
        </div>

        <button type="button" class="btn btn-danger mt-2 remove-question">إزالة السؤال</button>
    </div>
</div>

<button type="button" class="btn btn-success" id="addQuestionBtn">إضافة سؤال آخر</button>

                        


</div>
         

                 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="addQuestionform" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    let questionIndex = 1;

    document.getElementById('addQuestionBtn').addEventListener('click', function () {
        const container = document.getElementById('questions-container');
        const newItem = container.firstElementChild.cloneNode(true);

        // تحديث أسماء الحقول
        newItem.querySelectorAll('input').forEach(input => {
            if (input.name) {
                input.name = input.name.replace(/\[\d+\]/, `[${questionIndex}]`);
                if (input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            }
        });

        container.appendChild(newItem);
        questionIndex++;
    });

    // زر الحذف
    document.getElementById('questions-container').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-question')) {
            if (document.querySelectorAll('.question-item').length > 1) {
                e.target.closest('.question-item').remove();
            } else {
              Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'الرجاء اختيار اليوم أولاً',
        });
        return;
            }
        }
    });

    // روابط التحميل
          loadOptionsIntoSelect('working_hour', '/educational_stage/create', '-- اختر الفوج الدراسي --');

    setupDependentSelect('working_hour', 'education_stage', '/educational_stage/get_based_on_working/:id', 'جاري تحميل المراحل...', '-- اختر المرحلة --');
    setupDependentSelect('education_stage', 'classroom', '/classroom/get_based_on_stage/:id', 'جاري تحميل الصفوف...', '-- اختر الصف --');

 $('#classroom').on('change', function () {
        let classroomId = $(this).val();
        $('#subjects').empty().append('<option disabled>جارٍ التحميل...</option>');
        $('#teachers-container').empty();

        $.get('/classroom/' + classroomId + '/subjects', function (data) {
            let subjectSelect = $('#subjects');
            subjectSelect.empty();
            data.forEach(subject => {
                subjectSelect.append(`<option value="${subject.id}">${subject.name}</option>`);
            });
        });
    });

   


});
</script>
