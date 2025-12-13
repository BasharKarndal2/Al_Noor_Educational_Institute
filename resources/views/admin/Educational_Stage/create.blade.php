<div class="modal fade" id="addEducational_StageModal" tabindex="-1" aria-labelledby="addEducational_StageModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addEducational_StageModalLabel">
                    <i class="fas fa-user-plus me-2"></i>إضافة مرحلة دراسية
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-right">
                <form id="addEducational_StageModalform" method="POST" action="{{ route('educational_stage.store') }}">
                    @csrf
                    <div class="row g-3  ">
              <x-input-field nameinput="name" label="اسم المرحلة الدراسية" type="text" />

            <x-select-field label="الفوج الدراسي" id="working_hour" name="working_hour_id" required />

              <x-status  />
                  <x-notes  />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="addEducational_StageModalform" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </div>
    </div>
</div>

<script>
    
  document.addEventListener('DOMContentLoaded', function () {
    const addStageButton = document.getElementById('addEducational_Stagebuttun');
    const working_hour = document.getElementById('working_hour');

    addStageButton.addEventListener('click', () => {
     
        
        fetch('/educational_stage/create') // عدّل الرابط حسب API الخاص بك
            .then(res => res.json())
            .then(data => {
                let options = '<option value="">-- اختر الفوج الدراسي --</option>';
                data.forEach(stage => {
                    options += `<option value="${stage.id}">${stage.name}</option>`;
                });
                working_hour.innerHTML = options;
            })
            .catch(err => {
                console.error('حدث خطأ أثناء جلب المراحل الدراسية:', err);
            });
    });
});
</script>


