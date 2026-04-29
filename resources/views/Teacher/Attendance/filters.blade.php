 <div class="row mb-3">
                <div class="col-md-3">
                    <label>التاريخ</label>
                    <input type="date" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>الصف</label>
                    <select class="form-control">
                        <option>كل الصفوف</option>
                        <option>الصف الأول</option>
                        <option>الصف الثاني</option>
                        <option>الصف الثالث</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>الشعبة</label>
                    <select class="form-control">
                        <option>كل الشعب</option>
                        <option>الشعبة أ</option>
                        <option>الشعبة ب</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>حالة الحضور</label>
                    <select class="form-control">
                        <option>كل الحالات</option>
                        <option>حاضر</option>
                        <option>غائب</option>
                        <option>متأخر</option>
                    </select>
                </div>
            </div>
             <div class="d-flex justify-content-between mb-3">
                <button class="btn btn-info" onclick="filterData()">تصفية البيانات</button>
                <button class="btn btn-secondary" onclick="resetFilters()">إعادة تعيين</button>
                <button class="btn btn-success" onclick="exportToExcel()">تصدير للإكسل</button>
            </div>