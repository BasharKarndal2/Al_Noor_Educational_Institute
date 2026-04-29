<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editSubjectModalLabel"><i class="fas fa-edit me-2"></i>تعديل بيانات المادة</h5>
                
            </div>
            <div class="modal-body">
                <form id="editSubjectForm" method="POST" action="">
                    @method('PUT')
                    @csrf
                    <div class="row g-3">
                    <x-input-field nameinput="name" label="اسم  الصف الدراسي  " type="text" id="editEdName" />
                       <div class="col-md-6">
                                <label for="subjectLessons" class="form-label required">عدد الحصص أسبوعيًا</label>
                                <input type="number" 
       name="number_se" 
       class="form-control" 
       id="subjectLessonsedit" 
       min="1" max="10" 
       value="{{ old('number_se') }}" 
       required>
                                <div class="invalid-feedback">يرجى إدخال عدد الحصص</div>
                            </div>
                        <x-status id='editStatus' />
                  <x-notes id='editnote' />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="editSubjectForm" class="btn btn-primary">حفظ التعديلات</button>
            </div>
        </div>
    </div>
</div>





<script>

  const stageUpdateRouteTemplate = "{{ route('subject.update', ':id') }}";
  const modal = document.getElementById('editSubjectModal');
   modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');
    const actionUrl = stageUpdateRouteTemplate.replace(':id', id);
    document.getElementById('editSubjectForm').setAttribute('action', actionUrl);
    const oldName = @json(old('name'));
    const oldStatus = @json(old('status'));
    const oldNote = @json(old('note'));
    const oldnumber_se = @json(old('number_se'));
modal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  const id = button.getAttribute('data-id');
  const actionUrl = stageUpdateRouteTemplate.replace(':id', id);
  const form = document.getElementById('editSubjectForm');

  form.setAttribute('action', actionUrl);

  // استدعاء القيم القديمة من الـ Laravel عند وجود أخطاء تحقق (validation)
  const oldName = @json(old('name'));
  const oldStatus = @json(old('status'));
  const oldNote = @json(old('note'));
  const oldNumberSe = @json(old('number_se'));

  if (oldName || oldNote || oldStatus || oldNumberSe) {
    document.getElementById('editEdName').value = oldName || '';
    document.getElementById('editStatus').value = oldStatus || '';
    document.getElementById('editnote').value = oldNote || '';
    document.getElementById('subjectLessonsedit').value = oldNumberSe || '';
  } else {
    // جلب البيانات من السيرفر وعرضها في الحقول
    fetch(`/subject/${id}/edit`)
      .then(response => {
        if (!response.ok) throw new Error('خطأ في جلب البيانات');
        return response.json();
      })
      .then(data => {
        document.getElementById('editEdName').value = data.name || '';
        document.getElementById('editStatus').value = data.status || '';
        document.getElementById('editnote').value = data.note || '';
        document.getElementById('subjectLessonsedit').value = data.number_se || '';
      })
      .catch(error => alert(error.message));
  }
});
    if (oldName || oldNote) {
      document.getElementById('editEdName').value = oldName || '';
      document.getElementById('editStatus').value = oldStatus || '';
      document.getElementById('editnote').value = oldNote || '';
      document.getElementById('subjectLessonsedit').value = oldnumber_se || '';


    } else {

            
    
      fetch(`/subject/${id}/edit`)
        .then(response => {
          if (!response.ok) throw new Error('خطأ في جلب البيانات');
          return response.json();
        })
        .then(data => {
            
        console.log(data)
          document.getElementById('editEdName').value = data.name;
          document.getElementById('editStatus').value = data.status;
          document.getElementById('editnote').value = data.note;
            document.getElementById('subjectLessonsedit').value = data.number_se;
        })
        .catch(error => {
          alert(error.message);
        });
    }
  });
  

</script>