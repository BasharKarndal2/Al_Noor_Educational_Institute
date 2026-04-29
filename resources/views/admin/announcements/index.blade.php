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
      <h2 class="page-title">إدارة الإعلانات</h2>

            <!-- Add Announcement Button -->
            <div class="mb-3">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">إضافة إعلان جديد</button>
            </div>
            @include('admin.announcements.create')
            @include('admin.announcements.edit')
            <!-- Announcements Table -->
            <div class="card">
    <div class="card-header">
        <h5 class="mb-0">قائمة الإعلانات</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped text-center align-middle" id="annoucement_table">
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الوصف</th>
                        <th>الصورة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($announcements as $announcement)
                        <tr>
                            <td>{{ $announcement->titel }}</td>
                            <td>{{ $announcement->discridtion }}</td>
                            <td>
                                @if($announcement->image_path)
                                    <a href="{{ asset('storage/' . $announcement->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $announcement->image_path) }}" 
                                             alt="صورة الإعلان" style="width: 80px; height: 60px; object-fit: cover;">
                                    </a>
                                @else
                                    لا يوجد صورة
                                @endif
                            </td>
                            <td>
                                <!-- زر تعديل -->
                                <button class="btn btn-sm btn-warning me-1" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editAnnouncementModal"
                                        data-id="{{ $announcement->id }}"
                                        data-title="{{ $announcement->titel }}"
                                        data-description="{{ $announcement->discridtion }}"
                                        data-image="{{ asset('public/' . $announcement->image_path) }}">
                                    تعديل
                                </button>

                                        <button class="btn btn-sm btn-outline-danger"
        data-bs-toggle="modal"
        data-bs-target="#confirmDeleteModal"
        data-id="{{ $announcement->id }}"
        data-route="{{ route('announcements.destroy', ':id') }}">
    <i class="fas fa-trash"></i>
</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">لا توجد إعلانات بعد</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
        </div>
    </div>
@include('admin.aleat_delet')

@endsection



@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#annoucement_table').DataTable({
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
        $('select[name="annoucement_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush