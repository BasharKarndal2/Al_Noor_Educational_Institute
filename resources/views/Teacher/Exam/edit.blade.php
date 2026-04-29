<div class="modal fade" id="editExamModal" tabindex="-1" aria-labelledby="editExamLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editExamLabel">تعديل الاختبار</h5>
            </div>

            {{-- Body --}}
            <div class="modal-body">
               <form id="editExamForm" method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        {{-- العنوان --}}
                        <x-input-field nameinput="title" label="عنوان الاختبار" type="text" id="edit_title" />

                        {{-- الشعبة الدراسية --}}
                        <x-select-field label="الشعبة الدراسية" id="edit_section_id" name="section_id" required />

                        {{-- المادة الدراسية --}}
                        <x-select-field label="المادة الدراسية" id="edit_subject_id" name="subject_id" required />

                        {{-- تاريخ ووقت الاختبار --}}
                        <div class="col-md-4">
                            <label for="edit_exam_date" class="form-label">تاريخ الاختبار</label>
                            <input type="date" class="form-control" id="edit_exam_date" name="exam_date" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_start_time" class="form-label">وقت البداية</label>
                            <input type="time" class="form-control" id="edit_start_time" name="start_time" required>
                        </div>
                        <div class="col-md-4">
                            <label for="edit_end_time" class="form-label">وقت النهاية</label>
                            <input type="time" class="form-control" id="edit_end_time" name="end_time" required>
                        </div>

                        {{-- مكان الاختبار --}}
                        <div class="col-md-6">
                            <x-input-field nameinput="loc" label="مكان الاختبار" type="text" id="edit_loc" />
                        </div>

                        {{-- رفع الملف --}}
                        <div class="col-md-6">
                            <label for="edit_file" class="form-label">ملف الاختبار</label>
                            <input type="file" class="form-control" name="exam_file" id="edit_file">
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
                <button type="submit" form="editExamForm" class="btn btn-warning">
                    <i class="bi bi-save"></i> حفظ التعديلات
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).on('click', '.editExamteacherBtn', function () {
    let examId = $(this).data('id');
    const updateRouteTemplate = "{{ route('examsteacher.update', ':id') }}";
    const actionUrl = updateRouteTemplate.replace(':id', examId);
    $('#editExamForm').attr('action', actionUrl);

       $.get('/admin/exams/' + examId + '/edit', function (data) {
        // تعبئة الحقول الأساسية
        $('#edit_title').val(data.title);
        $('#edit_exam_date').val(data.exam_date);
        $('#edit_start_time').val(data.start_time);
        $('#edit_end_time').val(data.end_time);
        $('#edit_loc').val(data.loc);
        $('#edit_description').val(data.description);

        // تحميل الشعبة والمادة
        loadsectioninteacher(data.section.id, "edit_section_id");
        bindSelectWithChild_Classroom({
            parentSelectId: 'edit_section_id',
            childSelectId: 'edit_subject_id',
            urlTemplate: '/teachergetsubject/insection/:id',
            selectedValue: data.subject.id,
        });

        // فتح المودال
        $('#editExamModal').modal('show');
    });
});
</script>
