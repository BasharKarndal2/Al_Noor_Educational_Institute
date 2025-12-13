@extends('layout.admin.dashboard')


@section('content')

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
<x-alert type="success" />
<x-alert type="danger" />
<x-alert type="info" />

@include('admin.Pearant.create')
@include('admin.Pearant.edite')
@include('admin.aleat_delet')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="page-title mb-0"><i class="fas fa-users me-2"></i>إدارة أولياء الأمور</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addParentModal">
            <i class="fas fa-plus me-2"></i>إضافة ولي امر جديد
        </button>
    </div>

    <!-- جدول الطلاب -->
    
        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>قائمة أولياء الأمور</h5>
                                    <div>
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-file-export"></i> تصدير</button>
                                        <button class="btn btn-outline-primary btn-sm"><i class="fas fa-print"></i> طباعة</button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="pearent_table432">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>اسم ولي الأمر</th>
                                                <th>الطالب المرتبط</th>
                                                <th>رقم الهاتف</th>
                                                <th>البريد الإلكتروني</th>
                                                <th>الحالة</th>
                                                <th>الإجراءات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pearants as $pearant )
                                                      <tr>
                                                <td>{{ $pearant->id }}</td>
                                                <td>{{ $pearant->name }} </td>
                                                <td>
    @forelse ($pearant->students as $student)
        <span>{{ $student->name }}</span>  
    @empty
        <span>لم يرتبط بأي ابن بعد</span>  
    @endforelse
</td>
                                                <td>{{ $pearant->phone }}</td>
                                                <td>{{ $pearant->email }}</td>
                                                <td>
    <span class="badge {{ $pearant->status === 'active' ? 'bg-success' : 'bg-warning' }}">
        {{ $pearant->status === 'active' ? 'نشط' : 'غير نشط' }}
    </span>
</td>
                                                <td class="action-btns">

                                                 <a href="{{ route('showstudentper', $pearant->id) }}" 
   class="btn btn-sm btn-primary">
   <i class="bi bi-link"></i> ربط
</a>
                                                    {{-- <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#viewParentModal" ><i class="fas fa-eye"></i></button> --}}
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"  onclick="editParent({{ $pearant->id }})"><i class="fas fa-edit"></i></button>
                                                   
                                                   
                                                        <button class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal"
                                                data-id="{{ $pearant->id }}"
                                                data-route="{{ route('pearant.destroy', ':id') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                 <td> لاي يوجد بيانات</td>
                                                </tr>
                                            @endforelse
                                      
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
    </div>
</div>

@endsection




@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#pearent_table432').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
     url: '{{ asset('js/datatables/ar.json') }}'
        },
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "الكل"]] // إضافة خيار الكل
    });

    // تغيير id للـ select تبع عدد الصفوف
    table.on('init', function() {
        $('select[name="pearent_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush

