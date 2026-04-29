 <div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttendanceModalLabel">تسجيل حضور جديد</h5>
                </div>
                <div class="modal-body">

                    <form id="addAttendanceModalForm" method="POST" action="{{ route('teacheratt.store') }}">
@csrf
                   
                    <div class="row g-3">
            <x-input-field nameinput="title" label="عنوان سجل الحضور" type="text" />

            <x-select-field label="الشعبة الدراسية" id="section_adds" name="section_id" required />

            <x-select-field label="المواد الدراسية" id="subjct_adds" name="subjct_id" required />
        

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


@push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function () {
    
    const modal = document.getElementById('addAttendanceModal');
   
    if (!modal) return;
  modal.addEventListener('show.bs.modal', event => {
    
        // تحميل جديد
       loadOptionsIntoSelectsection('section_adds', '/teacher/getclassroom', '-- اختر الصف والشعبة الدراسي --');

  
  setupDependentSelect(
            'section_adds',
            'subjct_adds',
            '/teachergetsubject/insection/:id',
            'جاري تحميل المواد...',
            '-- اختر المادة --'
        );

function loadStudents() {
    const sectionId = document.getElementById('section_adds').value;
    const subjectId = document.getElementById('subjct_adds').value;


    if (!sectionId || !subjectId) {
        // إذا أحدهم مش محدد، نفرغ الجدول أو نوقف التحميل
        document.getElementById('addStudentsTable').innerHTML = '';
        return;
    }

    // ابني رابط الطلب مع الباراميترز (GET)
       const url = `/teacher/getstudent_by_section_andsubject_and_teacher?section_id=${sectionId}&subject_id=${subjectId}`;

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
        <input type="text" class="form-control" name="students[${student.id}][notes]"
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


function updatePeriodsWithSubject(selectedPeriodId = null, onLoaded = null) {
    const day = $('#day').val();
    const section = $('#section_adds').val();
    const subject = $('#subjct_adds').val();
    const $periodSelect = $('#period_number');

    // إذا لم تكن القيم مكتملة
    if (!day || !section || !subject) {
        $periodSelect.html('<option value="" disabled selected>اختر الحصة</option>');
        if (typeof onLoaded === 'function') onLoaded();
        return;
    }

    // جلب الحصص من السيرفر
    $.get('{{ route("class-schedules.periodsteacher") }}', { 
        day_of_week: day, 
        section_id: section, 
        subject_id: subject 
    })
    .done(function(data) {
        let options = '<option value="" disabled selected>اختر الحصة</option>';

        if (data.length === 0) {
            // إذا لا توجد حصص
            options += '<option value="" disabled>لا يوجد حصص</option>';
        } else {
            data.forEach(function(item) {
                const selected = selectedPeriodId && selectedPeriodId == item.id ? 'selected' : '';
                options += `<option value="${item.id}" ${selected}>${item.label}</option>`;
            });
        }

        $periodSelect.html(options);

        if (typeof onLoaded === 'function') onLoaded();
    })
    .fail(function() {
        alert('حدث خطأ أثناء جلب الحصص');
        if (typeof onLoaded === 'function') onLoaded();
    });
}

// استخدام الدالة عند تغيير اليوم أو القسم أو المادة
$('#day, #section_adds, #subjct_adds').on('change', function() {
    updatePeriodsWithSubject();
});





  



});});


</script>
@endpush