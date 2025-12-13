    <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addSectionModalLabel"><i class="fas fa-plus me-2"></i>إضافة شعبة  جديدة</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSectionForm" method="POST" action="{{ route('section.store') }}">
                        @csrf
                        <div class="row g-3">
                             <x-input-field nameinput="name" label="اسم  الشعبة " type="text" />
                             <x-select-field label="الفوج الدراسي" id="working_hour" name="working_hour_id" required />
                             <x-select-field label="المرحلة  الدراسية" id="education_stage" name="education_stage_id" required />
                             <x-select-field label="الصف  الدراسية" id="classroom" name="classroom_id" required />
                             
                            
                            
                            <div class="col-md-6">
                                <label for="classCapacity" class="form-label">السعة القصوى</label>
                                <input type="number" name="maxvalue" class="form-control" id="classCapacity" min="10" max="40" value="30">
                            </div>

                          <x-status  />
                         <x-notes  />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" form="addSectionForm" class="btn btn-primary">حفظ الصف</button>
                </div>
            </div>
        </div>
    </div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    
setupStageLoader('addSectionBtn', 'working_hour', '/educational_stage/create')
setupDependentSelect(
  'working_hour', 
  'education_stage', 
  '/educational_stage/get_based_on_working/:id',  // ← لاحظ وجود النقطتين هنا
  'جاري تحميل المراحل...', 
  '-- اختر المرحلة --'
);
setupDependentSelect(
  'education_stage', 
  'classroom', 
  '/classroom/get_based_on_stage/:id',  // ← لاحظ وجود النقطتين هنا
  'جاري تحميل الصفوف...', 
  '-- اختر الصف --'
);


});
</script>
