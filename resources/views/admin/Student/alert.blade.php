<!-- Modal التنبيه -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-body p-0">
        <div class="custom-alert-container text-center">
          <div class="icon-circle">
            <ion-icon name="alert-circle-outline" class="icon"></ion-icon>
          </div>
          <h5 class="title mt-4 text-danger">حدث خطأ!</h5>
          <ul id="alertMessages" class="error-list list-unstyled text-muted small px-4 mb-4"></ul>
          <button class="btn btn-danger w-50 mb-4" data-bs-dismiss="modal">إعادة المحاولة</button>
        </div>
      </div>
    </div>
  </div>
</div>

@if ($errors->any())


@if ($errors->has('section'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    window.oldInputs = @json(old());

    const alertMessages = @json($errors->all());
    let listHtml = "";
    alertMessages.forEach(msg => {
        listHtml += `<li>${msg}</li>`;
    });

    const alertList = document.getElementById("alertMessages");
    if (alertList) {
        alertList.innerHTML = listHtml;
    }

    // عرض مودال التنبيه أولاً
    const alertModalEl = document.getElementById('alertModal');
    if (alertModalEl) {
        const alertModal = new bootstrap.Modal(alertModalEl);
        alertModal.show();

        // عرض مودال الإدخال بعد إغلاق التنبيه
        const inputModalId = "addStudentModal";
        if (inputModalId) {
            const inputModalEl = document.getElementById(inputModalId);
            const inputModal = new bootstrap.Modal(inputModalEl);

            alertModalEl.addEventListener('hidden.bs.modal', function () {
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                inputModal.show();
            });
        }
    }
});
</script>
@endif

    @if (session('form_type') === 'create')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.oldInputs = @json(old());

                const alertMessages = @json($errors->all());
                let listHtml = "";
                alertMessages.forEach(msg => {
                    listHtml += `<li>${msg}</li>`;
                });
                document.getElementById("alertMessages").innerHTML = listHtml;

                // عرض مودال التنبيه أولاً
                const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                alertModal.show();

                // // عرض مودال الإدخال بعد عرض التنبيه
               
                     const inputModal = new bootstrap.Modal(document.getElementById("{{ session('id_model') }}"));

                // عند إغلاق مودال التنبيه، إعادة تفعيل مودال الإدخال
                document.getElementById('alertModal').addEventListener('hidden.bs.modal', function () {
              
                    document.body.classList.remove('modal-open');
     
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                          inputModal.show();
                });
            });
        </script>
    @endif

    @if (session('form_type') === 'edit')
        @php
            $routeName = session('url');
        @endphp
      
           <script>
            document.addEventListener('DOMContentLoaded', function () {
                window.oldInputs = @json(old());

                const alertMessages = @json($errors->all());
                let listHtml = "";
                alertMessages.forEach(msg => {
                    listHtml += `<li>${msg}</li>`;
                });
                document.getElementById("alertMessages").innerHTML = listHtml;
                const routeTemplate = "{{ route(session('url'), ':id') }}";
console.log(routeTemplate);
                const realUrl = routeTemplate.replace(':id', "{{ session('id') }}");
                document.getElementById('editstudentForm').setAttribute('action', realUrl);
                // عرض مودال التنبيه أولاً
                const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                alertModal.show();

                // // عرض مودال الإدخال بعد عرض التنبيه
               
                     const editModal = new bootstrap.Modal(document.getElementById("{{ session('id_model') }}"));

                // عند إغلاق مودال التنبيه، إعادة تفعيل مودال الإدخال
                document.getElementById('alertModal').addEventListener('hidden.bs.modal', function () {
              
                    document.body.classList.remove('modal-open');
     
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                          editModal.show();
                });
            });
        </script>
    @endif
@endif  


