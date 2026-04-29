<div class="modal fade" id="addAssessmentModal" tabindex="-1" aria-labelledby="addAssessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="addAssessmentModalLabel">إضافة تقييم جديد</h5>
                </div>
                <div class="modal-body">



    <form id="addAssessmentForm" method="POST" action="{{ route('evaluation.store') }}">
                    @csrf
                    <div class="row g-3  ">
              <x-input-field nameinput="title" label="عنوان التقييم" type="text" />

           <x-select-field label="الفوج الدراسي" id="working_hour_adds" name="working_hour_id" required />
            
          <x-select-field label="المرحلة الدراسية" id="education_stage_adds" name="education_stage_id" required />
                
                      <x-select-field label="الصف الدراسية" id="classroom_adds" name="classroom_id" required />
            <x-select-field label="الشعبة الدراسية" id="section_adds" name="section_id" required />

            <x-select-field label="المواد الدراسية" id="subjct_adds" name="subjct_id" required />
            <x-select-field label="المعلم" id="teacher_adds" name="teacher_id" required />
<div class="col-md-6">
    <label for="evaluation_date" class="form-label">تاريخ التقييم</label>
    <input type="date" class="form-control" id="evaluation_date" name="evaluation_date" required>
</div>

<div class="col-md-6">
    <label for="addFrequency" class="form-label">تكرار التقييم</label>
    <select class="form-select" id="addFrequency" name="frequency" required>
        <option value="">اختر التكرار</option>
        <option value="daily">يومي</option>
        <option value="weekly">أسبوعي</option>
        <option value="monthly">شهري</option>
    </select>
</div>



<div class="col-md-6">
    <label for="addType" class="form-label">نوع التقييم</label>
    <select class="form-select" id="addType" name="type" required>
        <option value="">اختر نوع التقييم</option>
        <option value="quiz">اختبار قصير</option>
        <option value="exam">اختبار نهائي</option>
        <option value="assignment">واجب منزلي</option>
        <option value="project">مشروع</option>
        <option value="activity">نشاط</option>
        <option value="participation">مشاركة</option>
    </select>
</div>
 <x-notes  />


          <div class="mb-3">
        <label class="form-label">الطلاب</label>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>رقم الطالب</th>
                        <th>صورة الطالب</th>
                        <th>اسم الطالب</th>
                        <th>الدرجة</th>
                        <th>الملاحظات</th>
                    </tr>
                </thead>
                <tbody id="addStudentsTable">
                
                </tbody>
            </table>
        </div>
    </div>
          
         
                    </div>
                </form>
                   
    <!-- باقي الحقول -->
   

    <!-- الجدول -->
    


                </div>
                <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                   <button type="submit" form="addAssessmentForm" class="btn btn-primary">
        <i class="bi bi-save"></i> حفظ التقييم
    </button>
                    
                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const modal = document.getElementById('addAssessmentModal');
   
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
        <img src="${imageUrl}" alt="صورة الطالب" class="teacher-photo me-2 protected-data" style="width: 40px; height: 40px; border-radius: 50%;">
    </td>
    <td>${student.name}</td>
    <td><input type="number" class="form-control" name="students[${student.id}][grade]" min="0" max="100"></td>
    <td><input type="text" class="form-control" name="students[${student.id}][notes]"></td>
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
