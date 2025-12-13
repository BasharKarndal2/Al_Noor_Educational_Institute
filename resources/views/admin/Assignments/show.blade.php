<div class="modal fade" id="viewAssignmentModal" tabindex="-1" aria-labelledby="viewAssignmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAssignmentModalLabel">تفاصيل الواجب/الاختبار</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- عنوان الواجب -->
                <h5 class="assignment-title">
                    <!-- سيتم ملؤه بالجافاسكريبت -->
                </h5>

                <!-- أزرار طباعة وتحميل -->
                <div class="mb-3">
                    <button class="btn btn-sm btn-outline-primary"><i class="fas fa-print"></i> طباعة</button>
                    <button class="btn btn-sm btn-outline-primary download-btn"><i class="fas fa-download"></i> تحميل</button>
                </div>

                <hr>

                <!-- تفاصيل الواجب -->
                <div class="assignment-details">
                    <!-- سيتم ملؤها بالجافاسكريبت -->
                </div>

                <!-- التعليمات -->
                <hr>
                <h6>التعليمات</h6>
                <div class="instructions-container">
                    <!-- سيتم ملؤها بالجافاسكريبت كـ <p> لكل تعليمه -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script>


    $(document).on('click', '.viewAssignmentBtn', function() {
    let assignmentId = $(this).data('id');

    $.get('/assignments/' + assignmentId, function(data) {
        // عنوان الواجب
        $('.assignment-title').html(
            data.title + ' <span class="badge bg-primary">' + (data.type || 'واجب') + '</span>'
        );

        // تفاصيل الواجب
        $('.assignment-details').html(`
            <p><strong>الصف:</strong> ${data.section_name}</p>
            <p><strong>المادة:</strong> ${data.subject_name}</p>
            <p><strong>المعلم:</strong> ${data.teacher_name}</p>
            <p><strong>تاريخ التسليم:</strong> ${data.due_date}</p>
            <p><strong>الملف المرفق:</strong> ${data.file_path ? `<a href="/storage/${data.file_path}" download>تحميل الملف</a>` : 'لا يوجد ملف'}</p>
        `);

        // التعليمات
        $('.instructions-container').html('');
        data.instructions.forEach(inst => {
            $('.instructions-container').append(`<p>${inst}</p>`);
        });

        $('#viewAssignmentModal').modal('show');
    });
});

</script>