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

    @if (session('form_type') === 'create')
        <script>
            function getdataourer(id, name_section, url) {
                const working_hour = document.getElementById(id);
                const oldworking_hour = window.oldInputs?.[name_section];
                console.log("Old value:", oldworking_hour);

                if (oldworking_hour) {
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            let options = '<option value="">-- اختر المرحلة --</option>';
                            data.forEach(stage => {
                                const selected = stage.id == oldworking_hour ? 'selected' : '';
                                options += `<option value="${stage.id}" ${selected}>${stage.name}</option>`;
                            });
                            working_hour.innerHTML = options;
                        })
                        .catch(err => {
                            console.error('حدث خطأ أثناء جلب المراحل الدراسية:', err);
                        });
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                window.oldInputs = @json(old());

                getdataourer('working_hour', 'working_hour_id', '/educational_stage/create');

                // عرض المودال الخاص بإنشاء البيانات
                var myModal = new bootstrap.Modal(document.getElementById("{{ session('id_model') }}"));
                myModal.show();

                // عرض مودال التنبيه بالأخطاء
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
                var myModal = new bootstrap.Modal(document.getElementById('editEducationalStageModal'));
                myModal.show();

                const routeTemplate = "{{ route($routeName, ':id') }}";
                const realUrl = routeTemplate.replace(':id', "{{ session('id') }}");
                document.getElementById('editEducationalStageForm').setAttribute('action', realUrl);

                 loadWorkingHours( "{{ session('working_id') }}",'working_houredit');
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
