<!-- Modal -->
<div class="modal fade" id="addWorking_hourModal" tabindex="-1" aria-labelledby="addWorking_hourModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addWorking_hourModalLabel">
                    <i class="fas fa-user-plus me-2"></i>إضافة فوج جديد
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addWorking_hourForm" method="POST" action="{{ route('working_hours.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fullName" class="form-label">اسم الفوج</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="fullName" name="name" value="{{ old('name') }}" required>
                        
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">اختر...</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="note" class="form-label">ملاحظات</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note" rows="2" name="note">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" form="addWorking_hourForm" class="btn btn-primary">حفظ البيانات</button>
            </div>
        </div>
    </div>
</div>



@push('scripts')

<script>
document.getElementById('addWorking_hourForm').addEventListener('submit', function(e) {
    let name = document.getElementById('fullName').value.trim();
    let note = document.getElementById('note').value.trim();

    if (name.length < 3) {
        e.preventDefault(); // منع الإرسال
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'اسم الفوج يجب أن يكون أكثر من 3 حروف',
        });
        return;
    }

    if (note.length < 3) {
        e.preventDefault(); // منع الإرسال
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'الملاحظات يجب أن تكون أكثر من 3 حروف',
        });
        return;
    }
});
</script>
@endpush