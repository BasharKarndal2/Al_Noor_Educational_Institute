<div class="modal fade" id="viewChildModal" tabindex="-1" aria-labelledby="viewChildModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewChildModalLabel">بيانات الطالب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <h5 id="studentName"></h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>الصف:</strong> <span id="studentClass"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>آخر نشاط:</strong> <span id="studentLastActivity"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>نسبة الحضور:</strong> <span id="studentPercentage"></span>%
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>المواد المسجلة:</strong>
                                <ul id="studentSubjects"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <!--<button type="button" class="btn btn-outline-primary"><i class="fas fa-print"></i> طباعة بطاقة الطالب</button>-->
            </div>
        </div>
    </div>
</div>

@push('stack')
<script>
$(document).on('click', '.viewStudent', function() {
    let id = $(this).data('id');
    
    $.get("/chiled/" + id, function(data) {
        // اسم الطالب وحالته
        $('#studentName').html(data.name + 
            ' <span class="badge ' + 
            (data.status === 'active' ? 'bg-success">نشط' : 'bg-danger">غير نشط') + 
            '</span>');
        $('#studentClass').text(data.class);
        $('#studentLastActivity').text(data.last_activity);
        $('#studentPercentage').text(data.percentage);

        // تفريغ المواد القديمة
        $('#studentSubjects').empty();

        data.subjects.forEach(function(subject) {
            let whatsappIcon = '';
            if(subject.teacher_whatsapp){
                whatsappIcon = ' <a href="https://wa.me/' + subject.teacher_whatsapp + '" target="_blank"><i class="fab fa-whatsapp text-success"></i></a>';
            }

            let iconHtml = subject.icon ? '<i class="' + subject.icon + ' me-1"></i>' : '';

            $('#studentSubjects').append(
                '<li>' + iconHtml + subject.subject_name + ' - ' + subject.teacher_name + whatsappIcon + '</li>'
            );
        });
    });
});
</script>
@endpush