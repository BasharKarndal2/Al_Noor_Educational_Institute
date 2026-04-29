<!-- Modal -->
<div class="modal fade" id="editWorkinghourModal" tabindex="-1" aria-labelledby="editWorking_hourModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="editWorking_hourModalLabel">
                    <i class="fas fa-edit me-2"></i>تعديل بيانات الفوج
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">
                <form id="editWorkinghourModalForm" method="POST" action="">
                    @csrf
                    @method('PUT') <!-- مهم لتعديل بيانات -->
                    {{-- <input type="hidden" name="id" id="editId"> <!-- مخفي للاحتفاظ بالـ id --> --}}
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="editName" class="form-label">اسم الفوج</label>
                            <input type="text" class="form-control" id="editName" name="name" required value="{{ old('name') }}">
                        </div>

                        <div class="col-md-12">
                            <label for="editStatus" class="form-label">الحالة</label>
                            <select class="form-select" id="editStatus" name="status" required>
                                <option value="">اختر...</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="editNote" class="form-label">ملاحظات</label>
                            <textarea class="form-control" id="editNote" rows="2" name="note">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="editWorkinghourModalForm" class="btn btn-warning">تعديل البيانات</button>
            </div>
        </div>
    </div>
</div>

<script>
  const updateRouteTemplate = "{{ route('working_hours.update', ':id') }}";

  const modal = document.getElementById('editWorkinghourModal');

  modal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const id = button.getAttribute('data-id');

    // تحديث action الفورم دائمًا
    const actionUrl = updateRouteTemplate.replace(':id', id);
    document.getElementById('editWorkinghourModalForm').setAttribute('action', actionUrl);

    // تحقق من وجود بيانات قديمة من Validation (Blade)
    const oldName = @json(old('name'));
    const oldStatus = @json(old('status'));
    const oldNote = @json(old('note'));

    if(oldName || oldStatus || oldNote) {
      // لو في بيانات قديمة نحتفظ بها (لا نغيرها)
      document.getElementById('editName').value = oldName || '';
      document.getElementById('editStatus').value = oldStatus || '';
      document.getElementById('editNote').value = oldNote || '';
      // document.getElementById('editId').value = id; // تأكد من id مخفي أيضاً
    } else {
      // غير ذلك، جلب بيانات AJAX
      fetch(`/working_hours/${id}/edit`)
        .then(response => {
          if (!response.ok) throw new Error('خطأ في جلب البيانات');
          return response.json();
        })
        .then(data => {
          // document.getElementById('editId').value = data.id;
          document.getElementById('editName').value = data.name;
          document.getElementById('editStatus').value = data.status;
          document.getElementById('editNote').value = data.note;
        })
        .catch(error => {
          alert(error.message);
        });
    }
  });
</script>
