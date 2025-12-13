<div class="modal fade" id="editClassroomModal" tabindex="-1" aria-labelledby="editClassroomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editClassroomModalLabel">
                    <i class="fas fa-edit me-2"></i> تعديل بيانات المرحلة الدراسية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <!-- Body -->
            <div class="modal-body">
                <form id="editClassroomForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    

                    <div class="row g-3">

                        <!-- اسم المرحلة -->
                    <x-input-field nameinput="name" label="اسم  الصف الدراسي  " type="text" id="editEdName" />

                     <x-select-field label="الفوج الدراسي" id="working_houredit" name="working_hour_id" required />
                     <x-select-field label="المرحلة الدراسي" id="education_stageedit" name="education_stage_id" required />

        
                       
                        <!-- الحالة -->
                       <x-status id='editStatus' />
                  <x-notes id='editnote' />


                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="editClassroomForm" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i> تعديل البيانات
                </button>
            </div>

        </div>
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const modal = document.getElementById('editClassroomModal');
    const editForm = document.getElementById('editClassroomForm');

    // تسجيل حدث submit مرة واحدة
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.getElementById('editEdName').value.trim();
        const workingHour = document.getElementById('working_houredit').value;
        const educationStage = document.getElementById('education_stageedit').value;
        const note = document.getElementById('editnote').value.trim();
        const status = document.getElementById('editStatus').value;

        let errors = [];

        if (name.length < 3) errors.push('اسم الصف يجب أن يكون أكثر من 3 حروف');
        if (!workingHour) errors.push('يجب اختيار الفوج الدراسي');
        if (!educationStage) errors.push('يجب اختيار المرحلة الدراسية');
        if (note.length < 3) errors.push('الملاحظات يجب أن تكون أكثر من 3 حروف');
        if (!status) errors.push('يجب اختيار الحالة');

        if (errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ في البيانات',
                html: errors.join('<br>')
            });
            return;
        }

        editForm.submit();
    });

    // عند فتح المودال
    modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');

        // تعديل action الفورم
        const actionUrl = `{{ route('classroom.update', ':id') }}`.replace(':id', id);
        editForm.setAttribute('action', actionUrl);

        // جلب البيانات الحالية
        fetch(`/classroom/${id}/edit`)
            .then(response => {
                if (!response.ok) throw new Error('خطأ في جلب البيانات');
                return response.json();
            })
            .then(data => {
                document.getElementById('editEdName').value = data.name;
                document.getElementById('editStatus').value = data.status;
                document.getElementById('editnote').value = data.note;

                // تحميل الفوج الدراسيط
                loadWorkingHours(data.educational_stage.working_hour_id,'working_houredit');

                // ربط الفوج مع المرحلة الدراسية
                bindSelectWithChild_Classroom({
                    parentSelectId: 'working_houredit',
                    childSelectId: 'education_stageedit',
                    urlTemplate: '/educational_stage/get_based_on_working/:id',
                    selectedValue: data.educational_stage.id
                });
            })
            .catch(error => alert(error.message));
    });

});
</script>

@endpush




