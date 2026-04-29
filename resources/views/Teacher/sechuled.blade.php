@extends('layout.teacher.dashboard')

@section('conten')
<div class="admin-content">
    <h2 class="page-title"><i class="fas fa-calendar-alt"></i> جدولي الدراسي</h2>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary">الجدول الأسبوعي</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اليوم</th>
                                    <th>الحصة</th>
                                    <th>المادة</th>
                                    <th>الصف</th>
                                    <th>الوقت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $days_ar = [
                                        'sunday' => 'الأحد',
                                        'monday' => 'الإثنين',
                                        'tuesday' => 'الثلاثاء',
                                        'wednesday' => 'الأربعاء',
                                        'thursday' => 'الخميس',
                                        'friday' => 'الجمعة',
                                        'saturday' => 'السبت',
                                    ];

                                    $groupedSchedules = $schedules->groupBy('day_of_week');
                                @endphp

                                @foreach($groupedSchedules as $day => $daySchedules)
                                    @foreach($daySchedules as $index => $schedule)
                                        <tr>
                                            @if($index === 0)
                                                <td rowspan="{{ count($daySchedules) }}">{{ $days_ar[$day] }}</td>
                                            @endif
                                            <td>الحصة {{ $schedule->period_number }}</td>
                                            <td>{{ $schedule->subject ? $schedule->subject->name : 'فراغ' }}</td>
                                            <td>  {{ $schedule->section->classroom->name }} {{ $schedule->section->name }}   </td>
                                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach

                                @if($schedules->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">لا يوجد جدول حالياً</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection