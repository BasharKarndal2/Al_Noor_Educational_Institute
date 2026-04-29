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


    @if (session('form_type') === 'edit')
        @php
            $routeName = session('url'); // مثل: 'workeing_hours.update'
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                 window.oldInputs = @json(old());
                var myModal = new bootstrap.Modal(document.getElementById('editClassroomModal'));
                myModal.show();

                const routeTemplate = "{{ route($routeName, ':id') }}";
                const realUrl = routeTemplate.replace(':id', "{{ session('id') }}");
                document.getElementById('editClassroomForm').setAttribute('action', realUrl);

                get_old_data_frome_workinhour('working_houredit', 'working_hour_id','/educational_stage/create');

bindSelectWithChild_Classroom({
    parentSelectId: 'working_houredit',
    childSelectId: 'education_stageedit',
    urlTemplate: '/educational_stage/get_based_on_working/:id',
    selectedValue:@json(old('education_stage_id')) // المرحلة الدراسية التي كانت مخزنة مسبقًا
});
            
               
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
