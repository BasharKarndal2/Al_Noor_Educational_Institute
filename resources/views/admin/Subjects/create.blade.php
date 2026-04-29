<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addSubjectModalLabel"><i class="fas fa-plus me-2"></i>إضافة مادة جديدة</h5>
                  
                </div>
                <div class="modal-body">
                    <form id="addSubjectForm" method="POST" action="{{ route('subject.store') }}">

                        @csrf
                        <div class="row g-3">
                            <x-input-field nameinput="name" label="اسم  المادة " type="text" />
                            <div class="col-md-6">
                                <label for="subjectLessons" class="form-label required">عدد الحصص أسبوعيًا</label>
                                <input type="number" 
       name="number_se" 
       class="form-control" 
       id="subjectLessons" 
       min="1" max="10" 
       value="{{ old('number_se') }}" 
       required>
                                <div class="invalid-feedback">يرجى إدخال عدد الحصص</div>
                            </div>
                              <x-status  />
                         <x-notes  />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="addSubjectForm" class="btn btn-primary">حفظ المادة</button>
                </div>
            </div>
        </div>
    </div>