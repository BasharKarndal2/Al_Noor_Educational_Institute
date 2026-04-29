<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="editAttendanceModalLabel">تعديل سجل الحضور</h5>
            </div>
            <div class="modal-body">
                <form id="editAttendanceModalForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="mb-3">
                        <label for="edit_title" class="form-label">العنوان</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>

                    <!-- فئات الاختيار المختلفة -->
                    <div class="mb-3">
                        <label for="edit_working_hour_id" class="form-label">الفوج</label>
                        <select name="working_hour_id" id="edit_working_hour_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_education_stage_id" class="form-label">المرحلة</label>
                        <select name="education_stage_id" id="edit_education_stage_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_classroom_id" class="form-label">الصف</label>
                        <select name="classroom_id" id="edit_classroom_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_section_id" class="form-label">الشعبة</label>
                        <select name="section_id" id="edit_section_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_subject_id" class="form-label">المادة</label>
                        <select name="subject_id" id="edit_subject_id" class="form-select" required></select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_teacher_id" class="form-label">المعلم</label>
                        <select name="teacher_id" id="edit_teacher_id" class="form-select" required></select>
                    </div>

                    <!-- تاريخ التقييم -->
                    <div class="mb-3">
                        <label for="edit_attendance_date" class="form-label">تاريخ السجل</label>
                        <input type="date" name="attendance_date" id="edit_attendance_date" class="form-control" required>
                    </div>


                    <div class="mb-3">
                        <label for="edit_day" class="form-label">اليوم </label>
                                    <select class="form-select" name="day" id="edit_day" required>
                                <option value="" disabled selected>اختر اليوم</option>
                                <option value="saturday">السبت</option>
                                <option value="sunday">الأحد</option>
                                <option value="monday">الإثنين</option>
                                <option value="tuesday">الثلاثاء</option>
                                <option value="wednesday">الأربعاء</option>
                                <option value="thursday">الخميس</option>
                                <option value="friday">الجمعة</option>
            </select>
                      
                    </div>
                     <select class="form-select" name="period_number" id="period_number_edit" required>
                                <option value="" disabled selected>اختر الحصة</option>
                        </select>
   
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>الطالب</th>
                                    <th>حالة الحضور</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody id="editStudentsTable">
                                <!-- سيتم تحميل الطلاب هنا -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-3">
                        <label>ملاحظات عامة</label>
                        <textarea class="form-control" id="editNotes" name="notes"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="editAttendanceModalForm" class="btn btn-primary">حفظ التعديلات</button>
            </div>
        </div>
    </div>
</div>





<script>



function updatePeriods(selectedPeriodId = null, onLoaded = null) {
    const day = document.getElementById('edit_day').value;
    const section = document.getElementById('edit_section_id').value;
    const teacher = document.getElementById('edit_teacher_id').value;
    const periodSelect = document.getElementById('period_number_edit');

    // إذا لم تكن القيم مكتملة
    if (!day || !section || !teacher) {
        periodSelect.innerHTML = `<option value="" disabled selected>اختر الحصة</option>`;
        if (typeof onLoaded === 'function') onLoaded();
        return;
    }

    // جلب الحصص من السيرفر
    $.get('{{ route("class-schedules.dayperiods") }}', { 
        day_of_week: day, 
        section_id: section, 
        teacher_id: teacher 
    })
    .done(function(data) {
        let options = `<option value="" disabled>اختر الحصة</option>`;
        data.forEach(item => {
            const selected = selectedPeriodId && selectedPeriodId == item.id ? 'selected' : '';
            options += `<option value="${item.id}" ${selected}>${item.label}</option>`;
        });
        periodSelect.innerHTML = options;

        if (typeof onLoaded === 'function') onLoaded();
    })
    .fail(function() {
        alert('حدث خطأ أثناء جلب الحصص');
        if (typeof onLoaded === 'function') onLoaded();
    });
}
function loadStudentsedit(results = []) {
console.log('Results:', results);

    const sectionId = document.getElementById('edit_section_id').value;
    const subjectId = document.getElementById('edit_subject_id').value;
    const teacherId = document.getElementById('edit_teacher_id').value;

    if (!sectionId || !subjectId || !teacherId) {
        document.getElementById('editStudentsTable').innerHTML = '';
           
        return;
     
    
    }

    const url = `/student/getstudent_by_section_andsubject_and_teacher?section_id=${sectionId}&subject_id=${subjectId}&teacher_id=${teacherId}`;
    const storageBaseUrl = "{{ asset('storage') }}";
    const tbody = document.getElementById('editStudentsTable');

    fetch(url)
        .then(response => response.json())
        .then(students => {
            console.log('Students:', students);
           

            let rows = '';

            students.forEach(student => {
             let status='';
                let notes = '';

                if (Array.isArray(results) && results.length > 0) {
                    const result = results.find(r => r.student.id === student.id);

                 
                    if (result) {
                        status=result.status;
                        notes = result.notes || '';
                    }
                }

              const imageUrl = student.image_path 
    ? storageBaseUrl + '/' + student.image_path 
    : storageBaseUrl + '/default.png';

                rows += `
                  <tr>
    <td>${student.id}</td>
    <td>
        <img src="${imageUrl}" alt="صورة الطالب" style="width: 40px; height: 40px; border-radius: 50%;">
    </td>
    <td>${student.name}</td>
    <td>
        <select class="form-select" name="students[${student.id}][status]">
            <option value="present" ${status === 'present' ? 'selected' : ''}>حاضر</option>
            <option value="absent" ${status === 'absent' ? 'selected' : ''}>غائب</option>
            <option value="excused" ${status === 'excused' ? 'selected' : ''}>إذن</option>
        </select>
    </td>
    <td>
        <input type="text" class="form-control" name="students[${student.id}][notes]" value="${notes}">
    </td>
</tr>
                `;
            });

            tbody.innerHTML = rows;
        })
        .catch(error => {
            console.error('خطأ في جلب بيانات الطلاب:', error);
        });
}
document.getElementById('edit_section_id').addEventListener('change', loadStudentsedit);
document.getElementById('edit_subject_id').addEventListener('change', loadStudentsedit);
document.getElementById('edit_teacher_id').addEventListener('change', loadStudentsedit);

document.getElementById('edit_section_id').addEventListener('change', updatePeriods);
document.getElementById('edit_day').addEventListener('change', updatePeriods);
document.getElementById('edit_teacher_id').addEventListener('change', updatePeriods);

function updateTeachers(selectedTeacherId = null, onLoaded = null) {
    const subjectSelect = document.getElementById('edit_subject_id');
    const sectionSelect = document.getElementById('edit_section_id');
    const teacherSelect = document.getElementById('edit_teacher_id');

    const subjectID = subjectSelect.value;
    const sectionID = sectionSelect.value;

    if (!subjectID || !sectionID) {
        teacherSelect.innerHTML = `<option value="">اختر المعلم</option>`;
        if (typeof onLoaded === 'function') onLoaded();
        return;
    }

    fetch(`/teachers-by-subject-section?section_id=${sectionID}&subject_id=${subjectID}`)
        .then(res => res.json())
        .then(data => {
            let teacherOptions = `<option value="">اختر المعلم</option>`;
            data.forEach(t => {
                const selected = selectedTeacherId && selectedTeacherId == t.id ? 'selected' : '';
                teacherOptions += `<option value="${t.id}" ${selected}>${t.full_name}</option>`;
            });
            teacherSelect.innerHTML = teacherOptions;
            if (typeof onLoaded === 'function') onLoaded();
        })
        .catch(err => {
            console.error('خطأ في جلب المعلمين:', err);
            teacherSelect.innerHTML = `<option value="">لا يوجد معلمين</option>`;
            if (typeof onLoaded === 'function') onLoaded();
        });
}




// تحديث قائمة المعلمين عند تغيير المادة أو الشعبة
document.getElementById('edit_subject_id').addEventListener('change', () => updateTeachers());
document.getElementById('edit_section_id').addEventListener('change', () => updateTeachers());

// حدث الضغط على زر تعديل التقييم
$(document).on('click', '.editattBtn', function () {
  
    let attId = $(this).data('id');
  const stageUpdateRouteTemplate = "{{ route('attendance.update', ':id') }}";

     const actionUrl = stageUpdateRouteTemplate.replace(':id', attId);
    document.getElementById('editAttendanceModalForm').setAttribute('action', actionUrl);


    $.get('/admin/attendance/' + attId + '/edit', function (data) {
        // تعبئة الحقول الأساسية
        
        $('#edit_title').val(data.title);
       $('#edit_day').val(data.class_schedule.day_of_week);
      
      
        $('#edit_attendance_date').val(data.attendance_date);
        $('#editNotes').val(data.description);

        // تسلسل تحميل القيم المرتبطة
        loadWorkingHours(data.class_schedule.section.classroom.educational_stage.working_hour_id, 'edit_working_hour_id');

        bindSelectWithChild_Classroom({
            parentSelectId: 'edit_working_hour_id',
            childSelectId: 'edit_education_stage_id',
            urlTemplate: '/educational_stage/get_based_on_working/:id',
            selectedValue: data.class_schedule.section.classroom.educational_stage.id,
            onLoaded: function () {
                bindSelectWithChild_Classroom({
                    parentSelectId: 'edit_education_stage_id',
                    childSelectId: 'edit_classroom_id',
                    urlTemplate: '/classroom/get_based_on_stage/:id',
                    selectedValue: data.class_schedule.section.classroom.id,
                    onLoaded: function () {
                        bindSelectWithChild_Classroom({
                            parentSelectId: 'edit_classroom_id',
                            childSelectId: 'edit_section_id',
                            urlTemplate: '/section/get_based_on_classroom/:id',
                            selectedValue: data.class_schedule.section.id,
                            onLoaded: function () {
                                bindSelectWithChild_Classroom({
                                    parentSelectId: 'edit_section_id',
                                    childSelectId: 'edit_subject_id',
                                    urlTemplate: '/subject/get_in_section/:id',
                                    selectedValue: data.class_schedule.subject.id,
                                    onLoaded: function () {
                                       updateTeachers(data.class_schedule.teacher.id, () => {
                                     loadStudentsedit(data.details);
                                     updatePeriods(data.class_schedule.period_number);
});
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });

 
        // فتح المودال
        $('#editAttendanceModal').modal('show');
    });
});
// حفظ التعديل



</script>
