  <div class="modal fade" id="viewAssessmentModal" tabindex="-1" aria-labelledby="viewAssessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="viewAssessmentModalLabel">عرض التقييم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>الصف:</strong> <span id="viewClass"></span></p>
                    <p><strong>الشعبة:</strong> <span id="viewSection"></span></p>
                    <p><strong>المادة:</strong> <span id="viewSubject"></span></p>
                    <p><strong>نوع التقييم:</strong> <span id="viewType"></span></p>
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


    const storageBaseUrl = "{{ asset('storage') }}";
$(document).on('click', '.viewAssessmentBtn', function () {
    let evaluationId = $(this).data('id');

    $.ajax({
        url: '/evaluations/showdata/' + evaluationId,
        type: 'GET',
        success: function (response) {
            $('#viewClass').text(response.section.classroom.name);
            $('#viewSection').text(response.section.name);
            $('#viewSubject').text(response.subject.name);
            $('#viewType').text(response.type);

            $('#viewStudentsTable').empty();

            response.results.forEach(result => {
                const imageUrl = storageBaseUrl + '/' + result.student.image_path;

                $('#viewStudentsTable').append(`
                    <tr>
                        <td>${result.student_id}</td>
                        <td>
                            <img src="${imageUrl}" alt="صورة الطالب" 
                                 style="width: 40px; height: 40px; border-radius: 50%;">
                        </td>
                        <td>${result.student.name}</td>
                        <td>${result.grade}</td>
                        <td>${result.feedback}</td>
                    </tr>
                `);
            });

            $('#viewAssessmentModal').modal('show');
        },
        error: function () {
            alert('حدث خطأ أثناء جلب البيانات');
        }
    });
});
</script>