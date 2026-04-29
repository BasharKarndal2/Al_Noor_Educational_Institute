  <div class="modal fade" id="viewTimetableModal" tabindex="-1" aria-labelledby="viewTimetableModalLabel" aria-hidden="true">
       <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTimetableModalLabel">
                        <i class="fas fa-calendar-alt me-2"></i>جدول الصف الأول - الشعبة أ
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
               <div class="modal-body">
    <div class="timetable-container" id="scheduleContainer">
        <!-- سيتم ملؤه بالـ AJAX -->
    </div>
</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <!--<button type="button" class="btn btn-primary">-->
                    <!--    <i class="fas fa-print me-1"></i>طباعة الجدول-->
                    <!--</button>-->
                </div>
            </div>
        </div>
    </div>
@push('scripts')
 <script>


function loadSchedule(sectionId) {
    $.ajax({
        url: "/schedule/show/" + sectionId,
        type: "GET",
        success: function(data) {
            let daysNames = {
                sunday: "الأحد",
                monday: "الإثنين",
                tuesday: "الثلاثاء",
                wednesday: "الأربعاء",
                thursday: "الخميس",
                friday: "الجمعة",
                saturday: "السبت"
            };

            // تحديد عدد الحصص (ديناميكي)
            let maxPeriods = 0;
            Object.values(data).forEach(day => {
                day.forEach(p => {
                    if (p.period_number > maxPeriods) {
                        maxPeriods = p.period_number;
                    }
                });
            });

            // بناء رأس الجدول
            let table = `<table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th width="10%">اليوم</th>`;

            for (let i = 1; i <= maxPeriods; i++) {
                table += `<th>الحصة ${i}</th>`;
            }

            table += `</tr></thead><tbody>`;

            // بناء الصفوف
            Object.keys(data).forEach(day => {
                table += `<tr><td>${daysNames[day]}</td>`;

                for (let i = 1; i <= maxPeriods; i++) {
                    let period = data[day].find(p => p.period_number == i);

                    if (period) {
                        if (period.is_break) {
                            table += `<td class="period-type-2 text-center align-middle">
                                        <small>استراحة</small>
                                     </td>`;
                        } else {
                            table += `<td class="period-type-1">
                                        <strong>${period.subject ? period.subject.name : '---'}</strong><br>
                                        <small>${period.teacher ? period.teacher.full_name : '---'}</small>
                                      </td>`;
                        }
                    } else {
                        table += `<td></td>`;
                    }
                }

                table += `</tr>`;
            });

            table += "</tbody></table>";

            $("#scheduleContainer").html(table);
        },
        error: function(xhr) {
            console.error(xhr.responseText);
        }
    });
}
$(".viewSchedule").on("click", function() {
    let sectionId = $(this).data("section-id"); // صححت الاسم
    loadSchedule(sectionId);
    var myModal = new bootstrap.Modal(document.getElementById('viewTimetableModal'));
    myModal.show();
});

 </script>
 
 
 @endpush