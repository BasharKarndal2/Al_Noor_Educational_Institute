
document.addEventListener("DOMContentLoaded", function () {

    // زر تصدير Excel
    const exportBtn = document.getElementById('exportExcel');
    if (exportBtn) {
        exportBtn.addEventListener('click', function () {
            const tableId = this.dataset.tableId;
            const fileName = this.dataset.filename || 'تقرير';

            const table = document.getElementById(tableId);
            if (!table) return;

            const headers = [];
            const headerCells = table.querySelectorAll('thead th');
            headerCells.forEach((th, index) => {
                if (index !== headerCells.length - 1) {
                    headers.push(th.innerText.trim());
                }
            });

            const data = [];
            table.querySelectorAll('tbody tr').forEach(tr => {
                const row = [];
                const tdCells = tr.querySelectorAll('td');
                tdCells.forEach((td, index) => {
                    if (index !== tdCells.length - 1) {
                        row.push(td.innerText.trim());
                    }
                });
                data.push(row);
            });

            const ws = XLSX.utils.aoa_to_sheet([headers, ...data]);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
            XLSX.writeFile(wb, fileName + '.xlsx');
        });
    }

    // زر تصدير PDF
    const pdfBtn = document.getElementById("generatepdf");
    if (pdfBtn) {
        pdfBtn.addEventListener("click", function () {
            const tableId = this.dataset.tableId;
            const fileName = this.dataset.filename || 'تقرير';
            const reportTitle = this.dataset.reportTitle || 'تقرير';

            const table = document.getElementById(tableId);
            if (!table) return;

            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                orientation: "portrait",
                unit: "pt",
                format: "a4"
            });

            // تأكد من تعريف الخطوط Amiri قبل استخدام الكود
            if (typeof AmiriRegular !== 'undefined' && typeof AmiriBold !== 'undefined') {
                doc.addFileToVFS("Amiri-Regular.ttf", AmiriRegular);
                doc.addFont("Amiri-Regular.ttf", "Amiri", "normal");
                doc.addFileToVFS("Amiri-Bold.ttf", AmiriBold);
                doc.addFont("Amiri-Bold.ttf", "Amiri", "bold");
            }

            doc.setFont("Amiri", "bold");
            doc.setFontSize(16);
            doc.text(reportTitle, 400, 40, { align: "right" });

            const headers = [];
            const headerCells = table.querySelectorAll("thead tr th");
            headerCells.forEach((th, i) => {
                if (i !== headerCells.length - 1) {
                    headers.push(th.textContent.trim());
                }
            });

            const data = [];
            table.querySelectorAll("tbody tr").forEach(row => {
                const rowData = [];
                const tdCells = row.querySelectorAll("td");
                tdCells.forEach((td, i) => {
                    if (i !== tdCells.length - 1) {
                        rowData.push(td.textContent.trim());
                    }
                });
                data.push(rowData);
            });

           doc.autoTable({
    head: [headers],
    body: data,
    startY: 60,
    styles: {
        font: "Amiri",
        fontSize: 12,
        halign: 'right', // محاذاة النص لليمين
        cellPadding: 5,
    },
    headStyles: {
        halign: 'right', // محاذاة رؤوس الأعمدة لليمين
        fillColor: [52, 58, 64], // اللون الرمادي الداكن
        textColor: 255,
        fontStyle: 'bold'
    },
    bodyStyles: {
        halign: 'right' // محاذاة محتوى الخلايا لليمين
    },
    columnStyles: {
        0: {halign: 'right'}, // مثال: العمود الأول لليمين
        // يمكنك إضافة المزيد إذا أردت أعمدة محددة
    },
    margin: { left: 40, right: 40 },
    didDrawPage: function (data) {
        // ضبط النص ليبدأ من اليمين
        doc.setR2L(true);
    }
});
            doc.save(fileName + ".pdf");
        });
    }

});

