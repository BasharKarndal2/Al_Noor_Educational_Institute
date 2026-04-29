<!-- Modal إضافة مادة إلى طالب -->
<div class="modal fade" id="addSubjectToStudentModal" tabindex="-1" aria-labelledby="addSubjectToStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="addSubjectToStudentForm" method="POST" action="">
      @csrf

      <input id="classroomid" type="hidden" name="classroom_id" value="">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addSubjectToStudentModalLabel">
            <i class="fas fa-book me-2"></i>إضافة مادة إلى الطالب
          </h5>
        </div>
        <div class="modal-body">

          <!-- قائمة المواد -->
          <div class="mb-3">
            <label for="subject_ids" class="form-label">اختر المواد</label>
            <select id="subject_ids" class="form-select" multiple style="height: 150px;">
              <!-- خيارات المواد ستُحمّل ديناميكيًا -->
            </select>
            <small class="form-text text-muted">يمكنك اختيار المواد ثم حذفها بالضغط على (x) بجانب كل مادة.</small>
          </div>

          <!-- حاوية المواد المختارة مع اختيار المعلم -->
          <div id="selected-subjects" class="mt-3"></div>

          <!-- الحقول المخفية لإرسال IDs المواد -->
          <div id="hidden-subject-inputs"></div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
          <button type="submit" class="btn btn-primary">حفظ</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('addSubjectToStudentModal');
    const teacherUpdateRouteTemplate = "{{ route('student.addSubjects', ':id') }}";// عدل هذا المسار حسب تطبيقك

  modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const studentID = button.getAttribute('data-id');

    // ضبط action الفورم
    document.getElementById('addSubjectToStudentForm').action = teacherUpdateRouteTemplate.replace(':id', studentID);

    // تحميل المواد المتاحة للطالب (غير المختارة سابقًا)
    loadAvailableSubjects(studentID);
  });

  const select = document.getElementById('subject_ids');
  const selectedContainer = document.getElementById('selected-subjects');
  const hiddenInputsContainer = document.getElementById('hidden-subject-inputs');

  function loadAvailableSubjects(studentID) {
    select.innerHTML = '<option disabled>جاري تحميل المواد...</option>';
    select.disabled = true;
      const classroom = document.getElementById('classroomid');
    fetch(`student/get_subject_inclassroom/${studentID}`) // عدل هذا حسب API الخاص بك
      .then(res => res.json())
      .then(data => {
        console.log(data);
        classroom.value = data.classroom_id; // تعيين classroom_id في الحقل المخفي
        select.innerHTML = '';
        data.subject.forEach(subject => {
          const option = document.createElement('option');
          option.value = subject.id;
          option.textContent = subject.name;
          select.appendChild(option);
        });
        select.disabled = false;
      })
      .catch(err => {
        select.innerHTML = '<option disabled>خطأ في تحميل المواد</option>';
        select.disabled = true;
        console.error(err);
      });
  }

  select.addEventListener('change', function () {
    const selectedOptions = Array.from(select.selectedOptions);

    selectedOptions.forEach(option => {
      if (!document.getElementById('subject-wrapper-' + option.value)) {
        addSelectedSubject(option.value, option.text);
      }
      option.remove(); // نحذف المادة من select حتى لا يعاد اختيارها
    });

    select.value = null;
  });

  function addSelectedSubject(id, name) {
    // عنصر الحاوية الرئيسي لكل مادة
    const wrapper = document.createElement('div');
    wrapper.className = 'd-flex align-items-center gap-3 mb-3';
    wrapper.id = 'subject-wrapper-' + id;

    // اسم المادة
    const label = document.createElement('span');
    label.className = 'badge bg-primary';
    label.textContent = name;

    // select لاختيار المعلم الخاص بالمادة
    const teacherSelect = document.createElement('select');
    teacherSelect.name = `teachers_for_subject[${id}]`;
    teacherSelect.className = 'form-select form-select-sm';
    teacherSelect.required = true;
    teacherSelect.style.minWidth = '250px';
    teacherSelect.innerHTML = `<option value="">اختر معلماً...</option>`;

    // زر حذف المادة
    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'btn btn-danger btn-sm';
    removeBtn.textContent = '❌';

    removeBtn.addEventListener('click', () => {
      // حذف الحاوية
      wrapper.remove();

      // إعادة المادة إلى select
      const option = document.createElement('option');
      option.value = id;
      option.text = name;
      select.appendChild(option);

      // حذف الحقل المخفي
      document.getElementById('hidden-subject-' + id)?.remove();
    });

    wrapper.appendChild(label);
    wrapper.appendChild(teacherSelect);
    wrapper.appendChild(removeBtn);
    selectedContainer.appendChild(wrapper);

    // إضافة الحقل المخفي لإرسال id المادة
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'subject_ids[]';
    hiddenInput.value = id;
    hiddenInput.id = 'hidden-subject-' + id;
    hiddenInputsContainer.appendChild(hiddenInput);

    // تحميل المعلمين للمادة وإضافة الخيارات إلى select المعلمين
    fetch(`/subject/${id}/teachers`)
      .then(res => res.json())
      .then(data => {
        data.teachers.forEach(teacher => {
          const opt = document.createElement('option');
          opt.value = teacher.id;
          opt.textContent = teacher.full_name;
          teacherSelect.appendChild(opt);
        });
      })
      .catch(err => {
        console.error("خطأ في تحميل المعلمين:", err);
      });
  }
});
</script>
