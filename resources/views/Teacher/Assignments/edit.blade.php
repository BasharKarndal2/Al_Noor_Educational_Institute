<div class="modal fade" id="editAssignmentModal" tabindex="-1" aria-labelledby="editAssignmentLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editAssignmentLabel">تعديل الواجب</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
               <form id="editAssignmentForm" method="POST" enctype="multipart/form-data" action="">
    @csrf
    @method('PUT')
                    {{-- <input type="hidden" name="id" id="edit_assignment_id"> --}}

                    <div class="row g-3">
                        {{-- العنوان --}}
                        <x-input-field nameinput="title" label="عنوان الواجب" type="text" id="edit_title" />


                        {{-- الشعبة الدراسية --}}
                        <x-select-field label="الشعبة الدراسية" id="edit_section_id" name="section_id" required />

                        {{-- المادة الدراسية --}}
                        <x-select-field label="المادة الدراسية" id="edit_subject_id" name="subject_id" required />

                     

                        {{-- تاريخ التسليم --}}
                        <div class="col-md-6">
                            <label for="edit_due_date" class="form-label">آخر موعد للتسليم</label>
                            <input type="datetime-local" class="form-control" id="edit_due_date" name="due_date" required>
                        </div>

                        {{-- الحالة --}}
                <div class="col-md-6">
              <label for="edit_status" class="form-label required">الحالة</label>
              <select class="form-select" id="edit_status" name="status" required>
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
                <option value="on_leave">في إجازة</option>
              </select>
            </div>

                        {{-- رفع الملف --}}
                        <div class="col-md-6">
                            <label for="edit_file" class="form-label">ملف الواجب</label>
                            <input type="file" class="form-control" name="file_path" id="edit_file">
                            <small class="text-muted">اتركه فارغًا إذا لا تريد تغيير الملف</small>
                        </div>

                        {{-- التعليمات --}}
                        <div class="col-md-12">
                            <label for="edit_description" class="form-label">التعليمات والإرشادات</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="5" required></textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="submit" form="editAssignmentForm" class="btn btn-warning">
                    <i class="bi bi-save"></i> حفظ التعديلات
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
<script>



// حدث الضغط على زر تعديل التقييم
$(document).on('click', '.editAssignmentBtn', function () {
    let assignmentId = $(this).data('id');
    const stageUpdateRouteTemplate = "{{ route('assignmentsteacher.update', ':id') }}";
    const actionUrl = stageUpdateRouteTemplate.replace(':id', assignmentId);

    $('#editAssignmentForm').attr('action', actionUrl);


    $.get('/admin/assignment/' + assignmentId + '/edit', function (data) {
        // تعبئة الحقول الأساسية
 
        $('#edit_title').val(data.title);
 $('#edit_due_date').val(data.due_date);

 $('#edit_status').val(data.status);
 $('#edit_description').val(data.description);
 // تسلسل تحميل القيم المرتبطة
        loadsectioninteacher(data.section.id, "edit_section_id");
        bindSelectWithChild_Classroom({
            parentSelectId: 'edit_section_id',
            childSelectId: 'edit_subject_id',
            urlTemplate: '/teachergetsubject/insection/:id',
            selectedValue: data.subject.id,
            
        });


        // فتح المودال
        $('#editAssignmentModal').modal('show');
    });
});
// حفظ التعديل



</script>
