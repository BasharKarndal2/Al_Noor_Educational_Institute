<!-- مودال تفاصيل الاختبار -->
<div class="modal fade" id="examDetailsModal" tabindex="-1" aria-labelledby="examDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="examDetailsModalLabel">
                    <i class="fas fa-info-circle me-2"></i>تفاصيل الاختبار
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <div class="exam-details">
                    <h4 class="detail-title mb-4">عنوان الاختبار</h4>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-book me-2"></i>المادة:</span>
                                <span class="detail-value">---</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-user me-2"></i>المعلم:</span>
                                <span class="detail-value">---</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-calendar me-2"></i>تاريخ الاختبار:</span>
                                <span class="detail-value">---</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-clock me-2"></i>الوقت:</span>
                                <span class="detail-value">---</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="fas fa-door-open me-2"></i>القاعة:</span>
                                <span class="detail-value">---</span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section mb-4">
                        <h5 class="section-title"><i class="fas fa-align-left me-2"></i>وصف الاختبار</h5>
                        <p class="section-content">---</p>
                    </div>

                    <div class="detail-section mb-4">
                        <h5 class="section-title"><i class="fas fa-paperclip me-2"></i>الملفات المرفقة</h5>
                        <div class="attachments">
                            <p>لا توجد ملفات</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

@push('stack')
<script>
$(document).on('click', '.viewExamBtn', function() {
    let examId = $(this).data('id');

    $.get('/chiled/exams/' + examId, function(data) {
        // عنوان الاختبار
        $('#examDetailsModal .detail-title').text(data.title);

        // المادة والمعلم والتاريخ والوقت والقاعة
        $('#examDetailsModal .detail-item').each(function() {
            let label = $(this).find('.detail-label').text();
            if(label.includes('المادة')) {
                $(this).find('.detail-value').text(data.subject_name);
            } else if(label.includes('المعلم')) {
                $(this).find('.detail-value').text(data.teacher_name);
            } else if(label.includes('تاريخ')) {
                $(this).find('.detail-value').text(data.exam_date);
            } else if(label.includes('الوقت')) {
                $(this).find('.detail-value').text(data.start_time + ' - ' + data.end_time);
            } else if(label.includes('القاعة')) {
                $(this).find('.detail-value').text(data.loc || 'غير محدد');
            }
        });

        // الوصف
        $('#examDetailsModal .section-content').text(data.description || 'لا يوجد وصف');

        // الملفات المرفقة
        let attachmentsHtml = '';
        if(data.exam_file && data.exam_file.trim() !== '') {
            attachmentsHtml = `
                <a href="/storage/${data.exam_file}" class="attachment-item btn btn-sm btn-outline-primary" download>
                    <i class="fas fa-file-pdf me-2"></i>تحميل الملف
                </a>
            `;
        } else {
            attachmentsHtml = '<p>لا توجد ملفات مرفقة</p>';
        }
        $('#examDetailsModal .attachments').html(attachmentsHtml);

        // فتح المودال
        $('#examDetailsModal').modal('show');
    });
});
</script>
@endpush