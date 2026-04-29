@extends('layout.parent.dashboard')

@section('conten')
<style>

    .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* تحسين التمرير على الأجهزة المحمولة */
}

.table {
    min-width: 800px; /* الحد الأدنى لعرض الجدول لضمان الحاجة إلى التمرير */
    direction: rtl; /* دعم الاتجاه من اليمين إلى اليسار */
}

.table th, .table td {
    white-space: nowrap; /* منع التفاف النص */
    padding: 8px; /* هوامش مناسبة */
}

/* تنسيقات للشاشات الصغيرة */
@media (max-width: 768px) {
    .table th, .table td {
        font-size: 14px; /* تقليل حجم الخط */
        padding: 6px; /* تقليل الهوامش */
    }
}

@media (max-width: 576px) {
    .table th, .table td {
        font-size: 12px; /* تقليل حجم الخط أكثر */
        padding: 4px; /* تقليل الهوامش أكثر */
    }
}
</style>
@include('parent.show_assig')
<div class="container-fluid py-4">
    <h2 class="page-title mb-4"><i class="fas fa-book me-2"></i>الواجبات المدرسية</h2>

    <!-- اختيار الابن -->
    <div class="row mb-4 align-items-end">
        <div class="col-md-4">
            <label for="childSelect" class="form-label">اختر الطالب:</label>
            <select class="form-select" id="childSelect">
                <option value="">اختر الطالب...</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="reloadChildren" class="btn btn-secondary w-100">
                <i class="fas fa-sync-alt"></i> إعادة تعيين
            </button>
        </div>
    </div>

    <!-- إحصائيات الواجبات -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-primary text-white">
                <div class="stat-icon"><i class="fas fa-tasks"></i></div>
                <div class="stat-info">
                    <h3 id="newAssignments">0</h3>
                    <p>واجبات جديدة</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-warning text-white">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <h3 id="nearDueAssignments">0</h3>
                    <p>قريبة من الموعد</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-success text-white">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-info">
                    <h3 id="completedAssignments">0</h3>
                    <p>واجبات مكتملة</p>
                </div>
            </div>
        </div>
    </div>

    <!-- قائمة الواجبات -->
    <div class="homework-list" id="homeworkList"></div>

</div>



@endsection

@push('stack')
<script>
$(document).ready(function() {

    function loadChildren() {
        $.get("{{ route('get.childrenselect') }}", function(data) {
            $('#childSelect').empty().append('<option value="">اختر الطالب...</option>');
            $.each(data, function(i, child) {
                $('#childSelect').append('<option value="'+child.id+'">'+child.name+'</option>');
            });
        });
    }
    loadChildren();

    function clearHomework() {
        $('#newAssignments').text('0');
        $('#nearDueAssignments').text('0');
        $('#completedAssignments').text('0');
        $('#homeworkList').empty();
    }

    function loadHomework(childId) {
        clearHomework();

        $.get("{{ route('parent.homework.data') }}", { child_id: childId }, function(res) {
            $('#newAssignments').text(res.new);
            $('#nearDueAssignments').text(res.nearDue);
            $('#completedAssignments').text(res.completed);

            res.list.forEach(function(hw) {
                let statusClass = hw.status === 'submitted' ? 'completed' : (hw.status === 'urgent' ? 'urgent' : '');
                let iconClass = hw.status === 'submitted' ? 'fa-check-circle text-success' : (hw.status === 'urgent' ? 'fa-exclamation-circle text-danger' : 'fa-clock text-warning');
                let badgeHtml = hw.status === 'submitted' ? '<span class="badge bg-success">تم التسليم</span>' : (hw.status === 'urgent' ? '<span class="badge bg-danger">عاجل</span>' : '');

                let hwHtml = `
                    <div class="homework-item ${statusClass}">
                        <div class="homework-icon">
                            <i class="fas ${iconClass}"></i>
                        </div>
                        <div class="homework-content">
                            <div class="d-flex justify-content-between">
                                <h5>${hw.title}</h5>
                                ${badgeHtml}
                            </div>
                            <div class="homework-meta">
                                <span><i class="fas fa-book me-1"></i>${hw.subject}</span>
                                <span><i class="fas fa-user me-1"></i>${hw.teacher}</span>
                                ${hw.status !== 'submitted' ? '<span><i class="fas fa-clock me-1"></i>'+hw.due_date+'</span>' : ''}
                            </div>
                            <p class="homework-desc">${hw.desc}</p>
                            <button class="btn btn-sm btn-outline-primary viewAssignmentdsBtn" data-id="${hw.id}" data-bs-toggle="modal" data-bs-target="#assignmentDetailsModal">
                                <i class="fas fa-eye me-1"></i>عرض التفاصيل
                            </button>
                        </div>
                    </div>
                `;
                $('#homeworkList').append(hwHtml);
            });
        });
    }

    $('#childSelect').on('change', function() {
        var childId = $(this).val();
        if(childId) loadHomework(childId);
        else clearHomework();
    });

    $('#reloadChildren').on('click', function() {
        $('#childSelect').val('');
        clearHomework();
    });

});
</script>
@endpush
