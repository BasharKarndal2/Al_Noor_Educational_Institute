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
      <div class="admin-content">
            <h2 class="page-title m">إدارة الطلبات</h2>

            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#student-orders">طلبات الطلاب</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#teacher-orders">طلبات المعلمين</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="student-orders">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card bg-primary text-white">
                                <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="stat-info">
                                 <h3>{{ $students->count() }}</h3>
                                    <p>إجمالي طلبات الطلاب</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning text-white">
                                <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                                <div class="stat-info">
                                    <h3>{{  $pending_student->count() }}</h3>
                                    <p>طلبات الطلاب المعلقة</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped" id="requeststudent_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>الصف</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>عدد المواد</th>
                                    <th>تاريخ الطلب</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                        @foreach ($students as $student )
                                <tr>
                                    <td>{{ $student->id }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td> {{ $student->classroom->name}}</td>
                                    <td>{{ $student->email}}</td>
                                    <td>{{ $student->subjects->count() }}</td>  
                                    <td>{{ $student->created_at}}</td>
                                    
    @php
    $statusMap = [
        'pending' => ['label' => ' معلق', 'class' => 'bg-warning'],
        'accepted' => ['label' => 'مقبول', 'class' => 'bg-success'],
        'rejected' => ['label' => 'مرفوض', 'class' => 'bg-danger'],
    ];

    $status = $statusMap[$student->request_status] ?? ['label' => 'غير معروف', 'class' => 'bg-secondary'];
@endphp       
                                    
                                    
                                    <td>
    <span class="badge {{ $status['class'] }}">
        {{ $status['label'] }}
    </span>
</td>
                                  
<td>

                                        <button class="btn btn-danger btn-sm action-btn reject-btn" data-type="student" data-id="3">رفض</button>
                                     <a href="{{ route('request.accept', $student->id) }}" class="btn btn-success btn-sm action-btn accept-btn">قبول</a>

                                        
                                        <button class="btn btn-info btn-sm action-btn details-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#detailsModal"
                                                data-type="student"
                                                data-id={{$student->id  }}
                                                data-name={{ $student->name }}
                                                data-class={{ $student->classroom->name}}
                                                data-email={{ $student->email}}
                                                data-phone={{ $student->phone }}
                                                data-address={{ $student->address }}
                                                data-birthdate={{  $student->date_of_birth}}
                                                data-gender={{ $student->gender }}
                                                data-guardian-name={{ $student->parent->name }}
                                                data-guardian-phone={{ $student->parent->phone }}
                                                data-guardian-relation={{ $student->parent->relation }} 
                                                       data-subjects='{{ $student->subjects_json }}'         
                                                data-test-results='{{ $student->test_results_json }}'
                                                data-total={{ $student->total_price }}
                                                data-date={{ $student->created_at }}
                                                data-status="مرفوض">عرض التفاصيل</button>
                                    </td>
                                </tr>
                                   @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="teacher-orders">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="stat-card bg-primary text-white">
                                <div class="stat-icon"><i class="fas fa-clipboard-list"></i></div>
                                <div class="stat-info">
                                    <h3>{{ $teacherres->count() }}</h3>
                                    <p>إجمالي طلبات المعلمين</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning text-white">
                                <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
                                <div class="stat-info">
                                    <h3>{{ $pending_teachers->count() }}</h3>
                                    <p>طلبات المعلمين المعلقة</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped"  id="requestteacher_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المعلم</th>
                                    <th>التخصص</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>تاريخ الطلب</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($teacherres as $teacher)
                                   <tr>
                                    <td>{{  $teacher->id}}</td>
                                    <td>أ. {{ $teacher->full_name }} </td>
                                    <td>{{ $teacher->specialization }}</td>
                                    <td>{{ $teacher->email }}</td>
                                    <td>{{ $teacher->created_at }}</td>
                                     @php
    $statusMap = [
        'pending' => ['label' => ' معلق', 'class' => 'bg-warning'],
        'accepted' => ['label' => 'مقبول', 'class' => 'bg-success'],
        'rejected' => ['label' => 'مرفوض', 'class' => 'bg-danger'],
    ];

    $status = $statusMap[$teacher->request_status] ?? ['label' => 'غير معروف', 'class' => 'bg-secondary'];
@endphp       
                                    
                                    
                                    <td>
    <span class="badge {{ $status['class'] }}">
        {{ $status['label'] }}
    </span>
</td>
                                    <td>
             <a href="{{ route('request.accept_teacher', $teacher->id) }}" class="btn btn-success btn-sm action-btn accept-btn">قبول</a>

                                        <button class="btn btn-danger btn-sm action-btn reject-btn" data-type="teacher" data-id="1">رفض</button>
                                      <button class="btn btn-info btn-sm action-btn details-btn" 
    data-bs-toggle="modal" 
    data-bs-target="#detailsModal"
    data-type="teacher"
    data-id="{{ $teacher->id }}"
    data-name="{{ $teacher->full_name }}" 
    data-specialization="{{ $teacher->specialization }}"
    data-email="{{ $teacher->email }}"
    data-phone="{{ $teacher->phone }}"
    data-id-number="{{ $teacher->identity_number }}"
    data-birthdate="{{ $teacher->birth_date }}"
    data-gender="{{ $teacher->gender }}"
    data-education="{{ $teacher->education }}"
    data-experience="{{ $teacher->experience_years }}"
    data-previous-work="{{ $teacher->previous_work }}"
    data-salary-syp="{{ $teacher->salary_syp }}"
    data-salary-usd="{{ $teacher->salary_usd }}"
    data-address="{{ $teacher->address }}"
    data-date="{{ $teacher->created_at }}"
    data-status= "{{   $status['label'] }}">
    عرض التفاصيل
</button>
                                    </td>
                                </tr>
                                
                                @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Request Details -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">تفاصيل الطلب</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body text-start" id="modalBodyContent">
                    <!-- سيتم تعبئة المحتوى هنا بواسطة JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle Details Button Click
        document.querySelectorAll('.details-btn').forEach(button => {
            button.addEventListener('click', function() {
                const type = this.dataset.type;
                const modalBody = document.getElementById('modalBodyContent');
                let content = '';
                
                if (type === 'student') {
                    // عرض تفاصيل طلب الطالب
                    content = `
                        <div class="detail-item">
                            <strong>رقم الطلب:</strong> ${this.dataset.id}
                        </div>
                        <div class="detail-item">
                            <strong>نوع الطلب:</strong> تسجيل طالب جديد
                        </div>
                        <h4 class="mt-4 mb-3">المعلومات الشخصية</h4>
                        <div class="detail-item">
                            <strong>الاسم الكامل:</strong> ${this.dataset.name}
                        </div>
                        <div class="detail-item">
                            <strong>الصف:</strong> ${this.dataset.class}
                        </div>
                        <div class="detail-item">
                            <strong>تاريخ الميلاد:</strong> ${this.dataset.birthdate}
                        </div>
                        <div class="detail-item">
                            <strong>الجنس:</strong> ${this.dataset.gender}
                        </div>
                        <div class="detail-item">
                            <strong>البريد الإلكتروني:</strong> ${this.dataset.email}
                        </div>
                        <div class="detail-item">
                            <strong>رقم الهاتف:</strong> ${this.dataset.phone}
                        </div>
                        <div class="detail-item">
                            <strong>العنوان:</strong> ${this.dataset.address}
                        </div>
                        
                        <h4 class="mt-4 mb-3">معلومات ولي الأمر</h4>
                        <div class="detail-item">
                            <strong>اسم ولي الأمر:</strong> ${this.dataset.guardianName}
                        </div>
                        <div class="detail-item">
                            <strong>رقم الهاتف:</strong> ${this.dataset.guardianPhone}
                        </div>
                        <div class="detail-item">
                            <strong>صلة القرابة:</strong> ${this.dataset.guardianRelation}
                        </div>
                        
                        <h4 class="mt-4 mb-3">المواد المختارة</h4>
                        <ul class="subject-list">
                            ${JSON.parse(this.dataset.subjects).map(subject => `
                                <li>
                                    <strong>${subject.name}</strong> - ${subject.teacher} (${subject.price} ل.س)
                                </li>
                            `).join('')}
                        </ul>
                        <div class="detail-item">
                            <strong>المبلغ الإجمالي:</strong> ${this.dataset.total} ل.س
                        </div>
                        
                        <h4 class="mt-4 mb-3">نتائج اختبار المستوى</h4>
                        ${Object.entries(JSON.parse(this.dataset.testResults)).map(([subject, questions]) => `
                            <div class="test-results">
                                <h5>${subject}</h5>
                                ${questions.map((q, i) => `
                                    <div class="test-question">
                                        <p><strong>السؤال ${i+1}:</strong> ${q.question}</p>
                                        <p><strong>الإجابة:</strong> ${q.answer} 
                                            <span class="badge ${q.correct ? 'bg-success' : 'bg-danger'}">
                                                ${q.correct ? 'صحيح' : 'خطأ'}
                                            </span>
                                        </p>
                                    </div>
                                `).join('')}
                            </div>
                        `).join('')}
                        
                        <div class="detail-item mt-4">
                            <strong>تاريخ الطلب:</strong> ${this.dataset.date}
                        </div>
                        <div class="detail-item">
                            <strong>الحالة:</strong> <span class="badge ${this.dataset.status === 'مقبول' ? 'bg-success' : this.dataset.status === 'مرفوض' ? 'bg-danger' : 'bg-warning'}">${this.dataset.status}</span>
                        </div>
                    `;
                } else {
                    // عرض تفاصيل طلب المعلم
                    content = `
                        <div class="detail-item">
                            <strong>رقم الطلب:</strong> ${this.dataset.id}
                        </div>
                        <div class="detail-item">
                            <strong>نوع الطلب:</strong> تسجيل معلم جديد
                        </div>
                        <h4 class="mt-4 mb-3">المعلومات الشخصية</h4>
                        <div class="detail-item">
                            <strong>الاسم الكامل:</strong> ${this.dataset.name}
                        </div>
                        <div class="detail-item">
                            <strong>التخصص:</strong> ${this.dataset.specialization}
                        </div>
                        <div class="detail-item">
                            <strong>رقم الهوية:</strong> ${this.dataset.idNumber}
                        </div>
                        <div class="detail-item">
                            <strong>تاريخ الميلاد:</strong> ${this.dataset.birthdate}
                        </div>
                        <div class="detail-item">
                            <strong>الجنس:</strong> ${this.dataset.gender}
                        </div>
                        <div class="detail-item">
                            <strong>البريد الإلكتروني:</strong> ${this.dataset.email}
                        </div>
                        <div class="detail-item">
                            <strong>رقم الهاتف:</strong> ${this.dataset.phone}
                        </div>
                        <div class="detail-item">
                            <strong>العنوان:</strong> ${this.dataset.address}
                        </div>
                        
                        <h4 class="mt-4 mb-3">المعلومات الأكاديمية</h4>
                        <div class="detail-item">
                            <strong>الشهادة العلمية:</strong> ${this.dataset.education}
                        </div>
                        <div class="detail-item">
                            <strong>سنوات الخبرة:</strong> ${this.dataset.experience} سنوات
                        </div>
                        <div class="detail-item">
                            <strong>الأعمال السابقة:</strong> ${this.dataset.previousWork}
                        </div>
                        
                        <h4 class="mt-4 mb-3">الراتب المتوقع</h4>
                        <div class="detail-item">
                            <strong>بالليرة السورية:</strong> ${this.dataset.salarySyp} ل.س
                        </div>
                        <div class="detail-item">
                            <strong>بالدولار:</strong> ${this.dataset.salaryUsd} $
                        </div>
                        
                        <div class="detail-item mt-4">
                            <strong>تاريخ الطلب:</strong> ${this.dataset.date}
                        </div>
                        <div class="detail-item">
                            <strong>الحالة:</strong> <span class="badge ${this.dataset.status === 'مقبول' ? 'bg-success' : this.dataset.status === 'مرفوض' ? 'bg-danger' : 'bg-warning'}">${this.dataset.status}</span>
                        </div>
                    `;
                }
                
                modalBody.innerHTML = content;
            });
        });
        
    </script>
    

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#requeststudent_table').DataTable({
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
        $('select[name="requeststudent_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#requestteacher_table').DataTable({
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
        $('select[name="requestteacher_table_length"]').attr('id', 'show');
    });
});
</script>
@endpush

