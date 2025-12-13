<div class="modal fade" id="addAssignmentModal" tabindex="-1" aria-labelledby="addAssignmentModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            {{-- Header --}}
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="addAssignmentModal">اضافة واجب </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
               <form id="addAssignmentForm" method="POST" enctype="multipart/form-data" action="{{ route('assignmentsteacher.store') }}">
    @csrf

                    {{-- <input type="hidden" name="id" id="edit_assignment_id"> --}}

                    <div class="row g-3">
                        {{-- العنوان --}}
                        <x-input-field nameinput="title" label="عنوان الواجب" type="text" id="add_title"  />


                        {{-- الشعبة الدراسية --}}
                        <x-select-field label="الشعبة الدراسية" id="add_section_id" name="section_id" required />

                        {{-- المادة الدراسية --}}
                        <x-select-field label="المادة الدراسية" id="add_subject_id" name="subject_id" required />

                     

                        {{-- تاريخ التسليم --}}
                        <div class="col-md-6">
                            <label for="add_due_date" class="form-label">آخر موعد للتسليم</label>
                            <input type="datetime-local" class="form-control" id="add_due_date" name="due_date" required>
                        </div>

                        {{-- الحالة --}}
                        <div class="col-md-6">
                            <x-status id="edit_status" />
                        </div>

                        {{-- رفع الملف --}}
                        <div class="col-md-6">
                            <label for="edit_file" class="form-label">ملف الواجب</label>
                            <input type="file" class="form-control" name="file_path" id="edit_file">
                   
                        </div>

                        {{-- التعليمات --}}
                        <div class="col-md-12">
                            <label for="edit_description" class="form-label">التعليمات والإرشادات</label>
                            <textarea class="form-control" id="Adddescription" name="description" rows="5" required></textarea>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="submit" form="addAssignmentForm" class="btn btn-warning">
                    <i class="bi bi-save"></i> حفظ 
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const modal = document.getElementById('addAssignmentModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', event => {

        // تحميل الصفوف والشعب
        loadOptionsIntoSelectsection('add_section_id', '/teacher/getclassroom', '-- اختر الصف والشعبة الدراسي --');

        // تحميل المواد بناءً على الشعبة
        setupDependentSelect(
            'add_section_id',
            'add_subject_id',
            '/teachergetsubject/insection/:id',
            'جاري تحميل المواد...',
            '-- اختر المادة --'
        );
    });
});
</script>
