@extends('layout.Student.dashboard')

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
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-calendar-week me-2"></i>الجدول الأسبوعي</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>اليوم</th>
                        <th>االحصة</th>
                        <th>الوقت</th>
                        <th>المادة</th>
                        <th>المعلم</th>
                        <th>الشعبة</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        function getContrastColor($color) {
                            if (!$color) return '#000';
                            $color = trim($color);

                            if (str_starts_with($color, 'rgb')) {
                                preg_match_all('/\d+/', $color, $rgb);
                                [$r, $g, $b] = array_map('intval', $rgb[0]);
                            } elseif (str_starts_with($color, '#')) {
                                $hex = str_replace('#', '', $color);
                                $r = hexdec(substr($hex,0,2));
                                $g = hexdec(substr($hex,2,2));
                                $b = hexdec(substr($hex,4,2));
                            } else {
                                $r = 68; $g = 68; $b = 68;
                            }

                            $yiq = (($r*299)+($g*587)+($b*114))/1000;
                            return $yiq >= 128 ? '#000' : '#fff';
                        }

                        $daysArabic = [
                            'saturday' => 'السبت',
                            'sunday' => 'الأحد',
                            'monday' => 'الإثنين',
                            'tuesday' => 'الثلاثاء',
                            'wednesday' => 'الأربعاء',
                            'thursday' => 'الخميس',
                            'friday' => 'الجمعة',
                        ];

                        $groupedSchedules = $schedules->groupBy('day_of_week');
                    @endphp

                    @foreach($groupedSchedules as $day => $daySchedules)
                        @foreach($daySchedules as $index => $schedule)
                            @php
                                $bgColor = $schedule->color ?? '#423434';
                                $textColor = getContrastColor($bgColor);
                            @endphp
                            <tr>
                                @if($index === 0)
                                    <td rowspan="{{ count($daySchedules) }}" style="background-color: {{ $bgColor }} !important; color: {{ $textColor }}!important;">
                                        {{ $daysArabic[strtolower($day)] ?? $day }}
                                    </td>
                                @endif
                                <td style="background-color: {{ $bgColor }} !important; color: {{ $textColor }}!important;">{{ $schedule->period_number }}</td>
                                <td style="background-color: {{ $bgColor }} !important; color: {{ $textColor }}!important;">{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                <td style="background-color: {{ $bgColor }} !important; color: {{ $textColor }}!important;">{{ $schedule->subject->name ?? 'غير محدد' }}</td>
                                <td style="background-color: {{ $bgColor }} !important; color: {{ $textColor }}!important;">{{ $schedule->teacher->full_name ?? 'غير محدد' }}</td>
                                <td style="background-color: {{ $bgColor }} !important; color: {{ $textColor }}!important;">{{ $schedule->section->name ?? 'غير محدد' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
