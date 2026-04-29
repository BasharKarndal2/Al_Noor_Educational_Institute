

<div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <form class="row g-3" id="searchForm">
                            <div class="col-md-4">
                                <label for="searchInput" class="form-label">بحث</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchInput" placeholder="ابحث باسم الصف أو أي شيء...">
                                 
                                </div>
                            </div>
                              <div class="col-md-3">
        <label for="filterWorkingHour" class="form-label">فلترة حسب الفوج</label>
        <select id="filterWorkingHour" class="form-select">
        </select>
    </div>
    <div class="col-md-3">
        <label for="filterStage" class="form-label">فلترة حسب المرحلة</label>
        <select id="filterStage" class="form-select">
        </select>
    </div>
                         
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="reset" class="btn btn-outline-secondary w-100" id="resetFilters">
                                    <i class="fas fa-undo me-2"></i> إعادة تعيين
                                </button>
                            </div>
                        </form>
                    </div>
                </div>



<script>
document.addEventListener('DOMContentLoaded', function () {

loadOptionsIntoSelect('filterWorkingHour', '/educational_stage/create', '-- اختر الفوج الدراسي --');

  // المراحل الدراسية بناءً على الفوج الدراسي
        setupDependentSelect(
            'filterWorkingHour',
            'filterStage',
            '/educational_stage/get_based_on_working/:id',
            'جاري تحميل المراحل...',
            '-- اختر المرحلة --'
        );

});

</script>


