<!-- resources/views/admin/aleat_delet.blade.php -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title w-100" id="confirmDeleteModalLabel">تأكيد الحذف</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="إغلاق"></button>
      </div>
      <div class="modal-body fw-bold fs-5">
        هل أنت متأكد أنك تريد حذف هذا السجل؟
      </div>
      <div class="modal-footer justify-content-center">
        <form id="deleteForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger px-4">نعم، حذف</button>
        </form>
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">إلغاء</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('confirmDeleteModal');
    const deleteForm = document.getElementById('deleteForm');

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const routeTemplate = button.getAttribute('data-route');
        const action = routeTemplate.replace(':id', id);
        deleteForm.setAttribute('action', action);
    });
});
</script>
