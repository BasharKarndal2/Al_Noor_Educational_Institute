<div class="modal fade" id="editSectionModal" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Header -->
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editSectionModalLabel">
                    <i class="fas fa-edit me-2"></i> تعديل بيانات المرحلة الدراسية
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <!-- Body -->
            <div class="modal-body">
                <form id="editSectionForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    

                    <div class="row g-3">

                        <!-- اسم المرحلة -->
                    <x-input-field nameinput="name" label="اسم  الصف الدراسي  " type="text" id="editEdName" />

                     <x-select-field label="الفوج الدراسي" id="working_houredit" name="working_hour_id" required />
                     <x-select-field label="المرحلة الدراسي" id="education_stageedit" name="education_stage_id" required />

                    <x-select-field label="الصف الدراسي الدراسي" id="classroomedit" name="classroom_id" required />

        
                       
                        <!-- الحالة -->
                       <x-status id='editStatus' />
                         <div class="col-md-6">
                                <label for="classCapacity" class="form-label">السعة القصوى</label>
                                <input type="number" name="maxvalue" class="form-control" id="classCapacityedit" min="4" max="40" value="30">
                            </div>
                  <x-notes id='editnote' />

                          
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="editSectionForm" class="btn btn-warning">
                    <i class="fas fa-save me-1"></i> تعديل البيانات
                </button>
            </div>

        </div>
    </div>
</div>


<script>


  const sectionUpdateRouteTemplate = "{{ route('section.update', ':id') }}";
  const modal = document.getElementById('editSectionModal');
   modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const actionUrl = sectionUpdateRouteTemplate.replace(':id', id);
    document.getElementById('editSectionForm').setAttribute('action', actionUrl);
   
    const oldName = @json(old('name'));
    const oldStatus = @json(old('status'));
    const oldNote = @json(old('note'));
    const oldWorkingHour = @json(old('working_hour_id'));

    if (oldName || oldNote || oldWorkingHour) {
      document.getElementById('editEdName').value = oldName || '';
      document.getElementById('editStatus').value = oldStatus || '';
      document.getElementById('editnote').value = oldNote || '';
    //    get_old_data_frome_workinhour('working_houredit', 'working_hour_id','/Educational_Stage/create');

        // get_old_data_frome_Eductional('education_stageedit', "working_hour_id",'education_stage_id', '/get_education_stage_based_on_Working/get/:id') 

      // استخدم القيمة القديمة
    } else {

            console.log('modal')
      // جلب بيانات الصف الدراسية الحالية
      fetch(`/Section/${id}/edit`)
        .then(response => {
          if (!response.ok) throw new Error('خطأ في جلب البيانات');
          return response.json();
        })
        .then(data => {
            
        console.log(data)
          document.getElementById('editEdName').value = data.name;
          document.getElementById('editStatus').value = data.status;
          document.getElementById('editnote').value = data.note;
          document.getElementById('classCapacityedit').value = data.maxvalue;

    
          loadWorkingHours(data.classroom.educational_stage.working_hour_id,'working_houredit');
bindSelectWithChild_Classroom({
    parentSelectId: 'working_houredit',
    childSelectId: 'education_stageedit',
    urlTemplate: '/educational_stage/get_based_on_working/:id',
    selectedValue: data.classroom.educational_stage.id,
    onLoaded: function () {
        // ← استدعِ الثانية بعد انتهاء تحميل المرحلة الدراسية
        bindSelectWithChild_Classroom({
            parentSelectId: 'education_stageedit',
            childSelectId: 'classroomedit',
            urlTemplate: '/classroom/get_based_on_stage/:id',
            selectedValue: data.classroom.id
        });
    }
});



  
        
        })
        .catch(error => {
          alert(error.message);
        });
    }
  });
  

</script>