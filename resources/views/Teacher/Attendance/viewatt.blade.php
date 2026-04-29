  <div class="modal fade" id="viewattendanceModal" tabindex="-1" aria-labelledby="viewattendanceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="viewAssessmentModalLabel">عرض سجل الحضور</h5>
                </div>
                <div class="modal-body">
                    <p><strong>الصف:</strong> <span id="viewClass"></span></p>
                    <p><strong>الشعبة:</strong> <span id="viewSection"></span></p>
                    <p><strong>المادة:</strong> <span id="viewSubject"></span></p>
                        <p><strong>المدرس:</strong> <span id="viewteacher"></span></p>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>رقم الطالب</th>
                                    <th>صورة الطالب</th>
                                    <th>اسم الطالب</th>
                                    <th>الحالة</th>
                                    <th>الملاحظات</th>
                                </tr>
                            </thead>
                            <tbody id="viewStudentsTable">
                                <!-- Dynamic content will be inserted here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x"></i> إغلاق</button>
                </div>
            </div>
        </div>
    </div>


<script>
function translateStatus(status) {
    switch (status) {
        case 'late':
            return 'متأخر';
        case 'present':
            return 'حاضر';
        case 'absent':
            return 'غائب';
        default:
            return status;
    }
}


    const storageBaseUrl = "{{ asset('storage') }}";
$(document).on('click', '.viewattendanceBtn', function () {
    let attendanceId = $(this).data('id');

    $.ajax({
        url: '/attendance/showdata/' + attendanceId,
        type: 'GET',
        success: function (response) {
            $('#viewClass').text(response.class_schedule.section.classroom.name);
            $('#viewSection').text(response.class_schedule.section.name);
            $('#viewSubject').text(response.class_schedule.subject.name);
             $('#viewteacher').text(response.class_schedule.teacher.full_name);
           

            $('#viewStudentsTable').empty();

            response.details.forEach(result => {
                const imageUrl = storageBaseUrl + '/' + result.student.image_path;

                $('#viewStudentsTable').append(`
                    <tr>
                        <td>${result.student_id}</td>
                        <td>
                            <img src="${imageUrl}" alt="صورة الطالب" 
                                 style="width: 40px; height: 40px; border-radius: 50%;">
                        </td>
                        <td>${result.student.name}</td>
                    <td>${translateStatus(result.status)}</td>

                        <td>${result.notes}</td>
                    </tr>
                `);
            });

            $('#viewattendanceModal').modal('show');
        },
        error: function () {
            alert('حدث خطأ أثناء جلب البيانات');
        }
    });
});
</script>