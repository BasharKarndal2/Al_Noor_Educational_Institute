<div class="modal fade" id="addClassroomModal" tabindex="-1" aria-labelledby="addClassroomModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addClassroomModalLabel">
                    <i class="fas fa-user-plus me-2"></i>إضافة مرحلة دراسية
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-right">
                <form id="addClassroomform" method="POST" action="{{ route('classroom.store') }}">
                    @csrf
                    <div class="row g-3  ">
              <x-input-field nameinput="name" id='add_name' label="اسم  الصف الدراسي" type="text" />

            <x-select-field label="الفوج الدراسي" id="working_hour" name="working_hour_id" required />
            <x-select-field label="المرحلة  الدراسية" id="education_stage" name="education_stage_id" required />
              <x-status  />
              
                  <x-notes  id="add_note"  />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="addClassroomform" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </div>
    </div>
</div>




@push('scripts')


<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============================
    // التحقق من الحقول قبل الإرسال
    // ============================
    const form = document.getElementById('addClassroomform');
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // منع الإرسال مؤقتاً

        const name = document.getElementById('add_name').value.trim();
        const workingHour = document.getElementById('working_hour').value;
        const educationStage = document.getElementById('education_stage').value;
        const note = document.getElementById('add_note').value.trim();

        let errors = [];

        // التحقق من الاسم
        if (name.length < 3) errors.push('اسم الصف يجب أن يكون أكثر من 3 حروف');

        // التحقق من الفوج الدراسي
        if (!workingHour) errors.push('يجب اختيار الفوج الدراسي');

        // التحقق من المرحلة الدراسية
        if (!educationStage) errors.push('يجب اختيار المرحلة الدراسية');

        // التحقق من الملاحظات (اختياري، يمكن جعله إلزامي)
        if (note.length < 3) errors.push('الملاحظات يجب أن تكون أكثر من 3 حروف');

        if (errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ في البيانات',
                html: errors.join('<br>'),
            });
            return; // إيقاف الإرسال
        }

        // إذا كانت كل الحقول صحيحة، نرسل الفورم
        form.submit();
    });

    // ============================
    // إعداد التحميل الديناميكي للقوائم
    // ============================
    setupStageLoader('addClassroombuttun', 'working_hour', '/educational_stage/create');
    setupDependentSelect(
        'working_hour', 
        'education_stage', 
        '/educational_stage/get_based_on_working/:id',  
        'جاري تحميل المراحل...', 
        '-- اختر المرحلة --'
    );

});
</script>
@endpush
