<!-- Modal إدارة المواد -->
<div class="modal fade" id="manageSubjectsModal" tabindex="-1" aria-labelledby="manageSubjectsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="manageSubjectsModalLabel">
          <i class="fas fa-book me-2"></i> إدارة مواد الشعبة
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      
      <div class="modal-body">
        <div id="current-subjects-container" class="d-flex flex-wrap">
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
    const manageModal = document.getElementById('manageSubjectsModal');
    const subjectsContainer = document.getElementById('current-subjects-container');

    manageModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const sectionID = button.getAttribute('data-id');
        
        // تحميل المواد الخاصة بالشعبة
        loadCurrentSubjects(sectionID);
    });

    function loadCurrentSubjects(sectionID) {
        subjectsContainer.innerHTML = `<span>جاري تحميل المواد...</span>`;
        
        fetch(`/subject/get_in_section/${sectionID}`)
            .then(res => res.json())
            .then(data => {
                subjectsContainer.innerHTML = '';
                if (data.length === 0) {
                    subjectsContainer.innerHTML = `<span class="text-muted">لا توجد مواد في هذه الشعبة</span>`;
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
    text: ` سيتم حذف المادة (${subject.name}) من هذه الشعبة! في  حال قمت بحذفها سوف يتم حذف تقييمات الطلاب وعلامات اختباراتهم ايضا الأفضل تغيير اسم المادة اذا اردت`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'نعم، احذفها',
    cancelButtonText: 'إلغاء'
}).then((result) => {
    if (result.isConfirmed) {
        deleteSubjectFromSection(sectionID, subject.id, badge);
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

    function deleteSubjectFromSection(sectionID, subjectID, badgeElement) {
        fetch(`/section/${sectionID}/subject/${subjectID}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                 Swal.fire("تم الحذف!", data.message, "success")
                badgeElement.remove();

            } else {
                alert('فشل الحذف: ' + data.message);
            }
        })
        .catch(err => {
            console.error('خطأ في الحذف:', err);
        });
    }
});

</script>