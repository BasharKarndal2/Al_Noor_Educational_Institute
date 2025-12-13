<div class="modal fade" id="viewSubmissionsModal" tabindex="-1" aria-labelledby="viewSubmissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable"> <!-- تم التغيير إلى modal-xl وزيادة scroll -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewSubmissionsModalLabel">
                    <i class="fas fa-users me-2"></i>الطلاب الذين سلموا الواجب
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الصورة</th>
                                <th>اسم الطالب</th>
                                <th>الملف المرفق</th>
                                <th>ملاحظات الطالب</th>
                                <th>تاريخ التسليم</th>
                                <th>ملاحظات المعلم</th>
                                <th>حالة التصحيح</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="submissionsTbody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<script>

function loadSubmissions(assignmentId) {
    if(!assignmentId) {
        console.error('assignmentId غير معرف');
        return;
    }

    console.log('جلب التسليمات للواجب', assignmentId);

    $.get(`/assignment_teacher/${assignmentId}/submitted-students`, function(submissions){
        let tbody = '';

     
        submissions.forEach((sub, index) => {

               let date = new Date(sub.created_at);
let formattedDate = date.getDate().toString().padStart(2,'0') + '-' +
                    (date.getMonth()+1).toString().padStart(2,'0') + '-' +
                    date.getFullYear() + ' ' +
                    date.getHours().toString().padStart(2,'0') + ':' +
                    date.getMinutes().toString().padStart(2,'0');
            tbody += `
            <tr>
                <td>${index + 1}</td>
                <td>
                    <img src="/storage/${sub.student.image_path}" alt="${sub.student.name}" 
                         style="width:50px; height:50px; border-radius:50%; object-fit:cover; margin-right:5px;">
              
                </td>
                <td>
                        ${sub.student.name}
              
                </td>
      <td>
    ${sub.submitted_file && sub.submitted_file.trim() !== '' 
        ? `<a href="/storage/${sub.submitted_file}" class="btn btn-sm btn-info" download="${sub.student.name}.pdf">
               <i class="fas fa-download"></i> تحميل الملف
           </a>` 
        : '<span class="text-muted">لا يوجد ملف</span>'
    }
</td>
                <td>${sub.submitted_text ? sub.submitted_text : '-'}</td>
            <td>${formattedDate}</td>
                <td><input type="text" class="form-control feedback" data-id="${sub.id}" value="${sub.feedback ?? ''}"></td>
                <td>
                    <select class="form-select grade" data-id="${sub.id}">
                        <option value="corrected" ${sub.status == 'corrected' ? 'selected' : ''}>تم التصحيح</option>
                        <option value="incorrected" ${sub.status == 'incorrected' ? 'selected' : ''}>لم يتم التصحيح</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary save-submission" data-id="${sub.id}"><i class="fas fa-save"></i></button>
                </td>
            </tr>
            `;
        });
        $('#submissionsTbody').html(tbody);
    });
}
// حفظ التغييرات
$(document).on('click', '.save-submission', function(){
    let id = $(this).data('id');
    let feedback = $(`.feedback[data-id="${id}"]`).val();
    let status = $(`.grade[data-id="${id}"]`).val();

    $.post(`/submission_teacher/${id}/update`, {
        _token: '{{ csrf_token() }}',
        feedback: feedback,
        status: status
    }, function(res){
        if(res.success){
            Swal.fire('تم الحفظ!', '', 'success');
        }
    });
});

</script>