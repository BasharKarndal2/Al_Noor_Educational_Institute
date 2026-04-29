<div class="modal fade" id="addAssessmentteacherModal" tabindex="-1" aria-labelledby="addAssessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="addAssessmentModalLabel">إضافة تقييم جديد</h5>
                </div>
                <div class="modal-body">



    <form id="addAssessmentteacherForm" method="POST" action="{{ route('teacherevaluation.store') }}">
                    @csrf
                    <div class="row g-3  ">
              <x-input-field nameinput="title" label="عنوان التقييم" type="text" />
      
            
            <x-select-field label="الشعبة الدراسية" id="section_adds" name="section_id" required />

            <x-select-field label="المواد الدراسية" id="subjct_adds" name="subjct_id" required />
            
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
                   <button type="submit" form="addAssessmentteacherForm" class="btn btn-primary">
        <i class="bi bi-save"></i> حفظ التقييم
    </button>
                   
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
<script>



document.addEventListener('DOMContentLoaded', function () {
    
    const modal = document.getElementById('addAssessmentteacherModal');
   
    if (!modal) return;
  modal.addEventListener('show.bs.modal', event => {
    console.log('fdsf')
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
   

    if (!sectionId || !subjectId ) {
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





  



});});

</script>
@endpush