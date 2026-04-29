 <div class="modal fade" id="assignmentDetailsModal" tabindex="-1" aria-labelledby="assignmentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="assignmentDetailsModalLabel"><i class="fas fa-info-circle me-2"></i>تفاصيل الواجب</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="assignment-details">
                        <h4 class="detail-title mb-4">تمارين الرياضيات - الفصل الثالث</h4>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-book me-2"></i>المادة:</span>
                                    <span class="detail-value">الرياضيات</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-user me-2"></i>المعلم:</span>
                                    <span class="detail-value">أ. أحمد محمد</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-calendar me-2"></i>تاريخ الإعطاء:</span>
                                    <span class="detail-value">15/10/2023</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label"><i class="fas fa-clock me-2"></i>موعد التسليم:</span>
                                    <span class="detail-value text-danger">20/10/2023 (غداً)</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="detail-section mb-4">
                            <h5 class="section-title"><i class="fas fa-align-left me-2"></i>تعليمات الواجب</h5>
                            <p class="section-content">حل جميع التمارين من الصفحة 45 إلى 50 في الكتاب المدرسي، مع ضرورة توضيح خطوات الحل بشكل كامل وعدم الاكتفاء بالإجابة النهائية فقط.</p>
                        </div>
                        
                        <div class="detail-section mb-4">
                            <h5 class="section-title"><i class="fas fa-paperclip me-2"></i>الملفات المرفقة</h5>
                            <div class="attachments">
                                <a href="#" class="attachment-item">
                                    <i class="fas fa-file-pdf me-2"></i>تمارين_الفصل_الثالث.pdf
                                </a>
                            </div>
                        </div>
                        
                        <div class="detail-section">
                            <h5 class="section-title"><i class="fas fa-upload me-2"></i>تسليم الواجب</h5>

                            <form class="submit-form" id="assig_submint"  method="POST" action=""  enctype="multipart/form-data">
                              @csrf
    @method('PUT')
                              
                              <input type="text" id="assig_id" value="" hidden>
                                <div class="mb-3">
                                    <label for="homeworkFile" class="form-label">رفع الحل</label>
                                    <input class="form-control" type="file" name="submitted_file" id="homeworkFile" >
                                </div>
                                <div class="mb-3">
                                    <label for="homeworkNotes" class="form-label">ملاحظات (اختياري)</label>
                                    <textarea class="form-control" name="submitted_text" id="homeworkNotes" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i>تسليم الواجب
                                </button>
                            </form>
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
       
$(document).on('click', '.viewAssignmentdsBtn', function() {
    let assignmentId = $(this).data('id');


  const stageUpdateRouteTemplate = "{{ route('student.Assignment_submissions', ':id') }}";
const actionUrl = stageUpdateRouteTemplate.replace(':id', assignmentId);
$('#assig_submint').attr('action', actionUrl);

  
    $.get('/student/assignments/' + assignmentId, function(data) {
        // عنوان الواجب
        $('#assignmentDetailsModal .detail-title').text(data.title);
 
        // المادة والمعلم
        $('#assignmentDetailsModal .detail-item').each(function() {
            let label = $(this).find('.detail-label').text();
            if(label.includes('المادة')) {
                $(this).find('.detail-value').text(data.subject_name);
            } else if(label.includes('المعلم')) {
                $(this).find('.detail-value').text(data.teacher_name);
            } else if(label.includes('تاريخ الإعطاء')) {
                $(this).find('.detail-value').text(data.created_at);
            } else if(label.includes('موعد التسليم')) {
                let due = new Date(data.due_date);
                let now = new Date();
                let diffDays = Math.ceil((due - now)/(1000*60*60*24));
                let text = due.toLocaleDateString();
                if(diffDays === 1) text += ' (غداً)';
                $(this).find('.detail-value').text(text);
            }
        });

        // التعليمات
        $('#assignmentDetailsModal .section-content').text(data.description || 'لا توجد تعليمات');

        // الملفات المرفقة
        let attachmentsHtml = '';
        if(data.file_path) {
            attachmentsHtml = `
                <a href="/storage/${data.file_path}" class="attachment-item" download>
                    <i class="fas fa-file-pdf me-2"></i>${data.title || 'ملف الواجب'}
                </a>
            `;
        } else {
            attachmentsHtml = '<p>لا توجد ملفات مرفقة</p>';
        }
        $('#assignmentDetailsModal .attachments').html(attachmentsHtml);

        // تحقق من التسليم
        if(data.submitted) {
            $('#assignmentDetailsModal .submit-form').hide();
        } else {
            $('#assignmentDetailsModal .submit-form').show();
        }

        // فتح المودال
        $('#assignmentDetailsModal').modal('show');
    });
});
</script>

@endpush