 <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttendanceModalLabel">تسجيل حضور جديد</h5>
                </div>
                <div class="modal-body">

                    <form id="addAttendanceModalForm" method="POST" action="{{ route('attendance.store') }}">
@csrf
                   
                    <div class="row g-3">
            <x-input-field nameinput="title" label="عنوان سجل الحضور" type="text" />

           <x-select-field label="الفوج الدراسي" id="working_hour_adds" name="working_hour_id" required />
            
          <x-select-field label="المرحلة الدراسية" id="education_stage_adds" name="education_stage_id" required />
                
            <x-select-field label="الصف الدراسية" id="classroom_adds" name="classroom_id" required />
            <x-select-field label="الشعبة الدراسية" id="section_adds" name="section_id" required />

            <x-select-field label="المواد الدراسية" id="subjct_adds" name="subjct_id" required />
            <x-select-field label="المعلم" id="teacher_adds" name="teacher_id" required />

             <select class="form-select" name="day" id="day">
                                <option value="" disabled selected>اختر اليوم</option>
                                <option value="saturday">السبت</option>
                                <option value="sunday">الأحد</option>
                                <option value="monday">الإثنين</option>
                                <option value="tuesday">الثلاثاء</option>
                                <option value="wednesday">الأربعاء</option>
                                <option value="thursday">الخميس</option>
                                <option value="friday">الجمعة</option>
            </select>


                       <select class="form-select" name="period_number" id="period_number" required>
                                <option value="" disabled selected>اختر الحصة</option>
                        </select>
                        <div class="col-md-6">
    <label for="Attendance_date" class="form-label">تاريخ التقييم</label>
    <input type="date" class="form-control" id="Attendance_date" name="attendance_date" required>
</div>


                        
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>الطالب</th>
                                    <th>حالة الحضور</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                          <tbody id='addStudentsTable' >

                          </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <label>ملاحظات عامة</label>
                        <textarea class="form-control" id="addNotes" name="notes"></textarea>
                    </div>

                     </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                   <button type="submit" form="addAttendanceModalForm" class="btn btn-primary">حفظ البيانات</button>

                </div>
            </div>
        </div>
    </div>



    <script>
document.addEventListener('DOMContentLoaded', function () {
    
    const modal = document.getElementById('addAttendanceModal');
   
    if (!modal) return;
  modal.addEventListener('show.bs.modal', event => {
    
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


           setupDependentSelect(
            'section_adds',
            'subjct_adds',
            '/subject/get_in_section/:id',
       
            'جاري تحميل المواد...',
            '-- اختر المادة --'
        );
   

const subjectSelect = document.getElementById('subjct_adds');
const sectionSelect = document.getElementById('section_adds');
const teacherSelect = document.getElementById('teacher_adds');


$('#day, #section_adds, #teacher_adds').on('change', function() {
    const day = $('#day').val();
    const section = $('#section_adds').val();
    const teacher = $('#teacher_adds').val();

    if(day && section && teacher){
        $.get('{{ route("class-schedules.dayperiods") }}', { day_of_week: day, section_id: section, teacher_id: teacher })
            .done(function(data){
                console.log(data)
                const $select = $('#period_number');
                $select.empty().append('<option value="" disabled selected>اختر الحصة</option>');
                data.forEach(function(item){
                    $select.append(`<option value="${item.id}">${item.label}</option>`);
                });
            })
            .fail(function(){
                alert('حدث خطأ أثناء جلب الحصص');
            });
    }
});


 
function updateTeachers() {

    
    const subjectID = subjectSelect.value;
    const sectionID = sectionSelect.value;

    if (!subjectID || !sectionID) {
        // إذا أحد القيم غير مختارة، نفرغ قائمة المعلمين أو نرجع الخيار الافتراضي فقط
        teacherSelect.innerHTML = `<option value="">اختر المعلم</option>`;
        return;
    }

    fetch(`/teachers-by-subject-section?section_id=${sectionID}&subject_id=${subjectID}`)
        .then(res => res.json())
        .then(data => {
            let teacherOptions = `<option value="">اختر المعلم</option>`;
            data.forEach(t => {
                teacherOptions += `<option value="${t.id}">${t.full_name}</option>`;
            });
            teacherSelect.innerHTML = teacherOptions;
        })
        .catch(err => {
            console.error('خطأ في جلب المعلمين:', err);
            teacherSelect.innerHTML = `<option value="">لا يوجد معلمين</option>`;
        });
}
subjectSelect.addEventListener('change', updateTeachers);
sectionSelect.addEventListener('change', updateTeachers);

function loadStudents() {
    const sectionId = document.getElementById('section_adds').value;
    const subjectId = document.getElementById('subjct_adds').value;
    const teacherId = document.getElementById('teacher_adds').value;

    if (!sectionId || !subjectId || !teacherId) {
        // إذا أحدهم مش محدد، نفرغ الجدول أو نوقف التحميل
        document.getElementById('addStudentsTable').innerHTML = '';
        return;
    }

    // ابني رابط الطلب مع الباراميترز (GET)
    const url = `/student/getstudent_by_section_andsubject_and_teacher?section_id=${sectionId}&subject_id=${subjectId}&teacher_id=${teacherId}`;
 const storageBaseUrl = "{{ asset('storage') }}";
    fetch(url)
        .then(response => response.json())
        .then(data => {
              console.log(data);
            const tbody = document.getElementById('addStudentsTable');
            tbody.innerHTML = ''; // نمسح المحتوى القديم

            data.forEach(student => {
              const imageUrl = storageBaseUrl + '/' + student.image_path;
                tbody.innerHTML += `
                    <tr>
    <td>${student.id}</td>  
    <td>
        <img src="${imageUrl}" alt="صورة الطالب" class="teacher-photo me-2 protected-data" 
             style="width: 40px; height: 40px; border-radius: 50%;">
    </td>
    <td>${student.name}</td>
    <td>
        <select class="form-select" name="students[${student.id}][status]" required>
            <option value="">اختر الحالة</option>
            <option value="present">حاضر</option>
            <option value="absent">غائب</option>
            <option value="late">متأخر</option>
        </select>
    </td>
    <td>
        <input type="text" class="form-control" name="students[${student.id}][notes]"/>
    </td>
</tr> 
                `;
            });
        })
        .catch(error => {
            console.error('خطأ في جلب بيانات الطلاب:', error);
        });
}

// استمع لتغيير أي من الـ select لتحديث الجدول تلقائياً
document.getElementById('section_adds').addEventListener('change', loadStudents);
document.getElementById('subjct_adds').addEventListener('change', loadStudents);
document.getElementById('teacher_adds').addEventListener('change', loadStudents);


});});

</script>
