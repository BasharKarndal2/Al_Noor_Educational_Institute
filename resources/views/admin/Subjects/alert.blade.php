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

@if ($errors->has('duplicate'))
  
  document.addEventListener('DOMContentLoaded', function () {
                // عرض المودال الخاص بإنشاء البيانات
                var myModal = new bootstrap.Modal(document.getElementById("addSubjectModal"));
                myModal.show();
                const alertMessages = @json($errors->all());
                let listHtml = "";
                alertMessages.forEach(msg => {
                    listHtml += `<li>${msg}</li>`;
                });
                document.getElementById("alertMessages").innerHTML = listHtml;
                let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                alertModal.show();
            });
        </script>




@endif

    @if (session('form_type') === 'create')
        <script>
        

            document.addEventListener('DOMContentLoaded', function () {
                // عرض المودال الخاص بإنشاء البيانات
                var myModal = new bootstrap.Modal(document.getElementById("addSubjectModal"));
                myModal.show();
                const alertMessages = @json($errors->all());
                let listHtml = "";
                alertMessages.forEach(msg => {
                    listHtml += `<li>${msg}</li>`;
                });
                document.getElementById("alertMessages").innerHTML = listHtml;
                let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                alertModal.show();
            });
        </script>
    @endif

    @if (session('form_type') === 'edit')
        @php
            $routeName = session('url'); // مثل: 'workeing_hours.update'
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var myModal = new bootstrap.Modal(document.getElementById('editSubjectModal'));
                myModal.show();

                const routeTemplate = "{{ route($routeName, ':id') }}";
                const realUrl = routeTemplate.replace(':id', "{{ session('id') }}");
                document.getElementById('editSubjectForm').setAttribute('action', realUrl);
                const alertMessages = @json($errors->all());
                let listHtml = "";
                alertMessages.forEach(msg => {
                    listHtml += `<li>${msg}</li>`;
                });
                document.getElementById("alertMessages").innerHTML = listHtml;

                let alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
                alertModal.show();
            });
        </script>
    @endif




@endif
