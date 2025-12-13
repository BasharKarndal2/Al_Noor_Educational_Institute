<!-- Modal استبدال المعلم -->
<div class="modal fade" id="replaceTeacherModal" tabindex="-1" aria-labelledby="replaceTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="replaceTeacherModalLabel">
                    <i class="fas fa-exchange-alt me-2"></i> استبدال معلم
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="replaceTeacherForm">
                    @csrf
                    <input type="hidden" id="section_id" name="section_id">

                    <div class="mb-3">
                        <label for="current_teacher" class="form-label">اختر المعلم الحالي</label>
                        <select id="current_teacher" class="form-select" required>
                           <option value="">اختر المعلم</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subject_id" class="form-label">اختر المادة</label>
                        <select id="subject_id" class="form-select" required>
                                              <option value="">اختر المادة</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="new_teacher" class="form-label">اختر المعلم الجديد</label>
                        <select id="new_teacher" class="form-select" required>
                                                                       <option value="">اختر المعلم الجديد</option>

                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="replaceTeacherBtn">استبدال</button>
            </div>
        </div>
    </div>
</div>


<script>


    document.addEventListener('DOMContentLoaded', function() {
    const replaceModal = document.getElementById('replaceTeacherModal');
    const sectionIdInput = document.getElementById('section_id');
    const currentTeacherSelect = document.getElementById('current_teacher');
    const subjectSelect = document.getElementById('subject_id');
    const newTeacherSelect = document.getElementById('new_teacher');
    const replaceBtn = document.getElementById('replaceTeacherBtn');

    // عند فتح المودال
    replaceModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const sectionId = button.getAttribute('data-id');
        sectionIdInput.value = sectionId;

        // جلب المعلمين في الشعبة
        fetch(`/section/${sectionId}/teachers`)
            .then(res => res.json())
            .then(data => {
             
                data.forEach(teacher => {
                    currentTeacherSelect.innerHTML += `<option value="${teacher.id}">${teacher.full_name}</option>`;
                });

                // تنظيف الـ selects الأخرى
            
            });
    });

    // عند اختيار المعلم الحالي، جلب المواد التي يدرسها
    currentTeacherSelect.addEventListener('change', function() {
        const sectionId = sectionIdInput.value;
        const teacherId = this.value;

        fetch(`/section/${sectionId}/teacher/${teacherId}/subjects`)
            .then(res => res.json())
            .then(data => {
             
                data.forEach(subject => {
                    subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
                });

                newTeacherSelect.innerHTML = '';
            });
    });

    // عند اختيار المادة، جلب المعلمين الآخرين الذين يدرسون نفس المادة
    subjectSelect.addEventListener('change', function() {
        const sectionId = sectionIdInput.value;
        const subjectId = this.value;

        fetch(`/section/${sectionId}/subject/${subjectId}/available-teachers`)
            .then(res => res.json())
            .then(data => { 
                console.log(data);
                
                newTeacherSelect.innerHTML = '';
                data.forEach(teacher => {
                    newTeacherSelect.innerHTML += `<option value="${teacher.id}">${teacher.full_name}</option>`;
                });
            });
    });

    // تنفيذ الاستبدال
    replaceBtn.addEventListener('click', function() {
        const payload = {
            section_id: sectionIdInput.value,
            current_teacher_id: currentTeacherSelect.value,
            subject_id: subjectSelect.value,
            new_teacher_id: newTeacherSelect.value,
            _token: document.querySelector('input[name=_token]').value
        };

        fetch('/section/replace-teacher', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                alert(data.message);
                location.reload(); // لتحديث البيانات بعد الاستبدال
            } else {
                alert(data.message);
            }
        })
        .catch(err => console.error(err));
    });
});

</script>