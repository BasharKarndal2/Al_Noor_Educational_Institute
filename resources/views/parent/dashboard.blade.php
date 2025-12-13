@extends('layout.parent.dashboard')

@section('conten')
<div class="container-fluid py-4">
    <h2 class="page-title"><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</h2>

    <!-- بطاقات الإحصائيات العامة -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="stat-card bg-primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $children->count() }}</h3>
                    <p>عدد الأبناء</p>
                    <a href="{{ route('parent.get_chiled') }}">عرض الكل <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقات لكل ابن (الحضور + المعدل) -->
    <div class="row g-4 mb-4">
        @foreach($childrenData as $child)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>{{ $child['name'] }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="stat-icon bg-light text-success me-3">
                                <i class="fas fa-calendar-check fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $child['attendance'] }}%</h4>
                                <small class="text-muted">نسبة الحضور</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-light text-info me-3">
                                <i class="fas fa-star fa-lg"></i>
                            </div>
                            <div>
                                <h4 class="mb-0">{{ $child['average'] }}</h4>
                                <small class="text-muted">المعدل العام</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection
