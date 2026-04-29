 <!-- فلترة البحث -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <form class="row g-3" id="searchForm">
                            <div class="col-md-4">
                                <label for="searchInput" class="form-label">بحث</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchInput" placeholder="ابحث باسم المعلم أو التخصص...">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="specializationFilter" class="form-label">التخصص</label>
                                <select id="specializationFilter" class="form-select">
                                    <option value="">كل التخصصات</option>
                                   
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label">الحالة</label>
                                <select id="statusFilter" class="form-select">
                                    <option value="">كل الحالات</option>
                                    <option value="active">نشط</option>
                                    <option value="inactive">غير نشط</option>
                                    <option value="on_leave">في إجازة</option>
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
                        // جلب التخصصات من الخادم
                        fetch("{{ route('teachers.specializations') }}")
                            .then(response => response.json())
                            .then(data => {
                                const specializationFilter = document.getElementById('specializationFilter');
                                data.forEach(specialization => {
                                    const option = document.createElement('option');
                                    option.value = specialization;
                                    option.textContent = specialization;
                                    specializationFilter.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching specializations:', error));
                    });
                </script>

