<!-- Modal تعديل المرحلة الدراسية -->
<div class="modal fade" id="editEducationalStageModal" tabindex="-1" aria-labelledby="editEducationalStageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editEducationalStageModalLabel">
                    <i class="fas fa-edit me-2"></i> تعديل بيانات المرحلة الدراسية
                </h5>
                
            </div>

            <!-- Body -->
            <div class="modal-body">
                <form id="editEducationalStageForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    

                    <div class="row g-3">

                        <!-- اسم المرحلة -->
                    <x-input-field nameinput="name" label="اسم المرحلة الدراسية" type="text" id="editEdName" />

                     <x-select-field label="الفوج الدراسي" id="working_houredit" name="working_hour_id" required />
        
                       
                        <!-- الحالة -->
                       <x-status id='editStatus' />
                  <x-notes id='editnote' />

            
                    

                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="editEducationalStageForm" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i> تعديل البيانات
                </button>
            </div>

        </div>
    </div>
</div>


<script>




  const stageUpdateRouteTemplate = "{{ route('educational_stage.update', ':id') }}";

  const modal = document.getElementById('editEducationalStageModal');

  modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');

    const actionUrl = stageUpdateRouteTemplate.replace(':id', id);
    document.getElementById('editEducationalStageForm').setAttribute('action', actionUrl);

    // old inputs from session
    const oldName = @json(old('name'));
    const oldStatus = @json(old('status'));
    const oldNote = @json(old('note'));
       console.log("القيمة القديمة:", @json(old('working_hour_id')));
    const oldWorkingHour = @json(old('working_hour_id'));

    if (oldName || oldNote || oldWorkingHour) {
      document.getElementById('editEdName').value = oldName || '';
      document.getElementById('editStatus').value = oldStatus || '';
      document.getElementById('editnote').value = oldNote || '';
      
      loadWorkingHours(oldWorkingHour); // استخدم القيمة القديمة
    } else {
      // جلب بيانات المرحلة الدراسية الحالية
      fetch(`/educational_stage/${id}/edit`)
        .then(response => {
          if (!response.ok) throw new Error('خطأ في جلب البيانات');
          return response.json();
        })
        .then(data => {
          
          document.getElementById('editEdName').value = data.name;
          document.getElementById('editStatus').value = data.status;
          document.getElementById('editnote').value = data.note;
          loadWorkingHours(data.working_hour_id,'working_houredit');

     
          // تعبئة الأفواج الدراسية مع الفوج المحدد
        })
        .catch(error => {
          alert(error.message);
        });
    }
  });

  // جلب الأفواج الدراسية وتحديد الفوج المناسب
 
</script>




