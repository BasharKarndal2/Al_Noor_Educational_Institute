<!-- Modal إدارة مواد المعلم -->
<div class="modal fade" id="manageTeacherSubjectsModal" tabindex="-1" aria-labelledby="manageTeacherSubjectsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="manageTeacherSubjectsModalLabel">
          <i class="fas fa-chalkboard-teacher me-2"></i> إدارة مواد المعلم
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <div id="teacher-subjects-container" class="d-flex flex-wrap">
          <!-- المواد ستعرض هنا -->
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const manageModal = document.getElementById('manageTeacherSubjectsModal');
    const subjectsContainer = document.getElementById('teacher-subjects-container');

    manageModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const teacherID = button.getAttribute('data-id');
        
        // تحميل المواد الخاصة بالمعلم
        loadTeacherSubjects(teacherID);
    });

    function loadTeacherSubjects(teacherID) {
        subjectsContainer.innerHTML = `<span>جاري تحميل المواد...</span>`;
        
        fetch(`/teacher/${teacherID}/subjects`)
            .then(res => res.json())
            .then(data => {
                subjectsContainer.innerHTML = '';
                if (data.length === 0) {
                    subjectsContainer.innerHTML = `<span class="text-muted">لا توجد مواد مرتبطة بهذا المعلم</span>`;
                    return;
                }

                data.forEach(subject => {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-primary me-2 mb-2 p-2';
                    badge.innerHTML = `
                        ${subject.name}
                        <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="حذف"></button>
                    `;

                    // زر الحذف
                    badge.querySelector('button').addEventListener('click', () => {
                        Swal.fire({
                            title: 'هل أنت متأكد؟',
                            text: `هل تريد إزالة المادة (${subject.name}) من قائمة مواد هذا المعلم؟`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'نعم، احذفها',
                            cancelButtonText: 'إلغاء'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                checkAndDeleteTeacherSubject(teacherID, subject.id, badge);
                            }
                        });
                    });

                    subjectsContainer.appendChild(badge);
                });
            })
            .catch(err => {
                console.error('خطأ في تحميل المواد:', err);
                subjectsContainer.innerHTML = `<span class="text-danger">خطأ في تحميل المواد</span>`;
            });
    }

    function checkAndDeleteTeacherSubject(teacherID, subjectID, badgeElement) {
        // أولا نعمل فحص
        fetch(`/teacher/${teacherID}/subject/${subjectID}/check-before-delete`)
            .then(res => res.json())
            .then(data => {
                if (!data.can_delete) {
                    Swal.fire("تنبيه", data.message, "error");
                    return;
                }

                // إذا مسموح بالحذف نكمل
                fetch(`/teacher/${teacherID}/subject/${subjectID}/delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        Swal.fire("تم الحذف!", resp.message, "success");
                        badgeElement.remove();
                    } else {
                        Swal.fire("خطأ", resp.message, "error");
                    }
                })
                .catch(err => {
                    console.error('خطأ في الحذف:', err);
                });

            })
            .catch(err => {
                console.error('خطأ في الفحص:', err);
            });
    }
});
</script>
