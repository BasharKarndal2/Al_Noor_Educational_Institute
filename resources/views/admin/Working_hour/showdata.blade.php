<!-- مودال دعم RTL -->
<div class="modal fade" id="viewWorking_hourModal" tabindex="-1" aria-labelledby="viewWorking_hourModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content" dir="rtl" style="text-align: right;">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="viewWorking_hourModalLabel">
          <i class="fas fa-users ms-2"></i> بيانات الفوج
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">اسم الفوج</label>
          <p class="fw-bold text-primary group-name">الفوج الأول</p>
        </div>

        <div class="mb-3">
          <label class="form-label">حالة الفوج</label>
          <p class="fw-bold group-status">
            <span class="badge bg-success">نشط</span>
          </p>
        </div>

        <div class="mb-3">
          <label class="form-label">ملاحظات</label>
          <p class="group-notes">لا توجد ملاحظات حالياً.</p>
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
  // تعبئة بيانات المودال عند الضغط على زر "عرض"
  const buttons = document.querySelectorAll(".view-group-btn");

  buttons.forEach(button => {
    button.addEventListener("click", function () {
      const name = this.dataset.name;
      const status = this.dataset.status;
      const notes = this.dataset.notes;

      document.querySelector(".group-name").textContent = name;
      document.querySelector(".group-notes").textContent = notes;

      const statusElement = document.querySelector(".group-status");
      if (status === "نشط") {
        statusElement.innerHTML = `<span class="badge bg-success">${status}</span>`;
      } else {
        statusElement.innerHTML = `<span class="badge bg-secondary">${status}</span>`;
      }
    });
  }); }); 
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

    // ❗️ يجب تعريف المتغيرات هنا داخل نفس الدالة
    const groupName = document.querySelector(".group-name").innerText.trim();
    let groupStatus = document.querySelector(".group-status").innerText.trim();

if(groupStatus === 'active'){
  groupStatus = 'نشط';
} else {
  groupStatus = 'غير نشط';
}
    const groupNotes = document.querySelector(".group-notes").innerText.trim();

    // كتابة البيانات
doc.setFillColor(220, 220, 220); // رمادي فاتح
doc.rect(50, 35, 500, 30, "F"); // X, Y, العرض, الارتفاع
doc.setTextColor(0, 0, 0);
doc.setFont("Amiri", "bold");
doc.setFontSize(16);
doc.text("بيانات الفوج", 400, 55, { align: "right" });
    

    doc.setFont("Amiri", "normal");
    doc.setFontSize(14);
    doc.text(`اسم الفوج: ${groupName}`, 400, 100, { align: "right" });

    doc.setFont("Amiri", "bold");
    doc.text(`حالة الفوج: ${groupStatus}`, 400, 130, { align: "right" });

    doc.setFont("Amiri", "normal");
    doc.text(`ملاحظات: ${groupNotes}`, 400, 160, { align: "right" });

    // حفظ الملف
    doc.save(`${' تقرير عن الفوج:'} ${groupName}.pdf`);
  });
};

</script>





