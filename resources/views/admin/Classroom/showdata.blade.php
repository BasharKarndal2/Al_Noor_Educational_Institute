<!-- مودال دعم RTL -->
<div class="modal fade" id="viewClassroomModal" tabindex="-1" aria-labelledby="viewClassroomModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content" dir="rtl" style="text-align: right;">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewviewEduModalLabel">
          <i class="fas fa-users ms-2"></i> بيانات الصف الدراسي
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">اسم الصف</label>
          <p class="fw-bold text-primary group-name">الأول</p>
        </div>

        <div class="mb-3">
          <label class="form-label">الفوج التابع له الصف</label>
          <p class="fw-bold text-primary working_houre-name" id="working_name">الصباحي</p>
        </div>

        <div class="mb-3">
          <label class="form-label">المرحلة التابع له الصف</label>
          <p class="fw-bold text-primary stage-name" id="stage_name">الصباحي</p>
        </div>

        <div class="mb-3">
          <label class="form-label">حالة الصف</label>
          <p class="fw-bold group-status">
            <span class="badge bg-success">نشط</span>
          </p>
        </div>

        <div class="mb-3">
          <label class="form-label">ملاحظات</label>
          <p class="group-notes">لا توجد ملاحظات حالياً.</p>
        </div>

        <div class="mb-3">
          <label class="form-label">عدد الطلاب</label>
          <p class="group-nustudent">0</p>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
        <button type="button" class="btn btn-primary" id="printBtn">
          <i class="fas fa-print me-1"></i> طباعة البيانات
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll(".view-group-btn");

  buttons.forEach(button => {
    button.addEventListener("click", function () {
      const id = this.dataset.id;

      fetch(`/classroom/${id}/edit`)
        .then(response => response.json())
        .then(data => {
          console.log(data);

          document.querySelector(".group-name").textContent = data.name;
          document.querySelector(".group-notes").textContent = data.note;
          document.getElementById('stage_name').textContent = data.educational_stage.name;
          document.getElementById('working_name').textContent = data.educational_stage.working_hour.name;

          // حساب عدد الطلاب
          let totalStudents = 0;
          if (data.sections && data.sections.length > 0) {
              data.sections.forEach(section => {
                  if (section.students) {
                      totalStudents += section.students.length;
                  }
              });
          }
          document.querySelector(".group-nustudent").textContent = totalStudents;

          // الحالة
          const statusElement = document.querySelector(".group-status");
          if (data.status === "active") {
            statusElement.innerHTML = `<span class="badge bg-success">نشط</span>`;
          } else {
            statusElement.innerHTML = `<span class="badge bg-secondary">غير نشط</span>`;
          }
        })
        .catch(error => {
          console.error("خطأ أثناء جلب البيانات:", error);
        });
    });
  });
});
</script>

<script>
window.onload = () => {
  const { jsPDF } = window.jspdf;

  document.getElementById("printBtn").addEventListener("click", () => {
    const doc = new jsPDF({
      orientation: "portrait",
      unit: "pt",
      format: "a4"
    });

    // تحميل الخطوط
    doc.addFileToVFS("Amiri-Regular.ttf", AmiriRegular);
    doc.addFont("Amiri-Regular.ttf", "Amiri", "normal");
    doc.addFileToVFS("Amiri-Bold.ttf", AmiriBold);
    doc.addFont("Amiri-Bold.ttf", "Amiri", "bold");

    // جمع البيانات
    const groupName = document.querySelector(".group-name").innerText.trim();
    const working_name = document.getElementById('working_name').innerText.trim();
    const groupStage = document.getElementById('stage_name').innerText.trim();
    let groupStatus = document.querySelector(".group-status").innerText.trim();
    const groupNotes = document.querySelector(".group-notes").innerText.trim();
    const totalStudents = document.querySelector(".group-nustudent").innerText.trim();

    if(groupStatus === 'active'){
      groupStatus = 'نشط';
    } else {
      groupStatus = 'غير نشط';
    }

    // كتابة البيانات
    doc.setFillColor(220, 220, 220);
    doc.rect(50, 35, 500, 30, "F");
    doc.setTextColor(0, 0, 0);
    doc.setFont("Amiri", "bold");
    doc.setFontSize(16);
    doc.text("بيانات الصف الدراسي", 400, 55, { align: "right" });

    doc.setFont("Amiri", "normal");
    doc.setFontSize(14);
    doc.text(`اسم الصف الدراسية: ${groupName}`, 400, 100, { align: "right" });
    doc.text(`فوج الصف الدراسية: ${working_name}`, 400, 130, { align: "right" });
    doc.text(`مرحلة الصف: ${groupStage}`, 400, 160, { align: "right" });
    doc.text(`حالة الصف الدراسية: ${groupStatus}`, 400, 180, { align: "right" });
    doc.text(`عدد الطلاب: ${totalStudents}`, 400, 210, { align: "right" });
    doc.text(`ملاحظات: ${groupNotes}`, 400, 240, { align: "right" });

    doc.save(`تقرير عن المرحلة الدراسية : ${groupName}.pdf`);
  });
};
</script>
