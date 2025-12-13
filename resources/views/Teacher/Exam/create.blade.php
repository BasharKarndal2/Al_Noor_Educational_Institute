<div class="modal fade" id="addExamModal" tabindex="-1" aria-labelledby="addExamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            {{-- Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addExamModalLabel">إضافة اختبار جديد</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form id="addExamModalForm" method="POST" enctype="multipart/form-data" action="{{ route('examsteacher.store') }}">
                    @csrf
                    <div class="row g-3">

                        {{-- عنوان الاختبار --}}
                        <x-input-field nameinput="title" label="عنوان الاختبار" type="text" />

                        {{-- الشعبة الدراسية --}}
                        <x-select-field label="الصف والشعبة الدراسية" id="section_adds" name="section_id" required />

                        {{-- المادة الدراسية --}}
                        <x-select-field label="المادة الدراسية" id="subjct_adds" name="subject_id" required />

                        {{-- تاريخ الاختبار --}}
                        <div class="col-md-4">
                            <label for="exam_date" class="form-label">تاريخ الاختبار</label>
                            <input type="date" class="form-control" id="exam_date" name="exam_date" required>
                        </div>

                        {{-- وقت البداية --}}
                        <div class="col-md-4">
                            <label for="start_time" class="form-label">وقت البداية</label>
                            <input type="time" class="form-control" id="start_time" name="start_time" required>
                        </div>

                        {{-- وقت الانتهاء --}}
                        <div class="col-md-4">
                            <label for="end_time" class="form-label">وقت الانتهاء</label>
                            <input type="time" class="form-control" id="end_time" name="end_time" required>
                        </div>
                        {{-- مكان الاختبار --}}
                        <div class="col-md-6">
                            <x-input-field nameinput="loc" label="مكان الاختبار" type="text" id="add_loc" />
                        </div>

                        {{-- رفع الملف --}}
                        <div class="col-md-6">
                            <label for="exam_file" class="form-label">ملف الاختبار</label>
                            <input type="file" class="form-control" name="exam_file" id="exam_file">
                        </div>

                        {{-- التعليمات --}}
                        <div class="col-md-12">
                            <label for="description_add" class="form-label">التعليمات والإرشادات</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description_add" rows="5" required name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="submit" form="addExamModalForm" class="btn btn-primary">
                    <i class="bi bi-save"></i> حفظ الاختبار
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    const modal = document.getElementById('addExamModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', event => {

        // تحميل الصفوف والشعب
        loadOptionsIntoSelectsection('section_adds', '/teacher/getclassroom', '-- اختر الصف والشعبة الدراسي --');

        // تحميل المواد بناءً على الشعبة
        setupDependentSelect(
            'section_adds',
            'subjct_adds',
            '/teachergetsubject/insection/:id',
            'جاري تحميل المواد...',
            '-- اختر المادة --'
        );
    });
});
</script>
