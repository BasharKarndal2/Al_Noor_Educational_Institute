

//جلب بيانات اعتمادا على بيانات سابقة مثلا جلب بيانات المراحل اعتماد على الفوج
function setupDependentSelect(sourceSelectId, targetSelectId, apiUrlTemplate, loadingText = 'جاري التحميل...', defaultOption = '-- اختر --') {
    const sourceSelect = document.getElementById(sourceSelectId);
    const targetSelect = document.getElementById(targetSelectId);

    if (!sourceSelect || !targetSelect) {
      console.error('تعذر العثور على أحد عناصر select.');
      return;
    }

    sourceSelect.addEventListener('change', function () {
      const selectedValue = this.value;
      targetSelect.innerHTML = `<option>${loadingText}</option>`;

      const finalUrl = apiUrlTemplate.replace(':id', selectedValue);
      console.log(finalUrl);
      fetch(finalUrl)
        .then(res => res.json())
        .then(data => {
          let options = `<option value="">${defaultOption}</option>`;
          data.forEach(item => {
            options += `<option value="${item.id}">${item.name}</option>`;
          });
          targetSelect.innerHTML = options;
        })
        .catch(err => {
          console.error('حدث خطأ أثناء جلب البيانات:', err);
          targetSelect.innerHTML = `<option>حدث خطأ في التحميل</option>`;
        });
    });
  }



  
function get_old_data_frome_Eductional(id, name_section_source, name_section_target, url) {
    const working_hour = document.getElementById(id);
    const oldworking_hour = window.oldInputs?.[name_section_source];
    const oldEducation = window.oldInputs?.[name_section_target];
    console.log("الفوج الدراسية:", oldworking_hour);
    
    console.log("المرحلة الدراسية:", oldEducation);
    if (!working_hour || !oldworking_hour) return;

    const finalUrl = url.replace(':id', oldworking_hour);
   

    fetch(finalUrl)
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- اختر المرحلة --</option>';
            data.forEach(stage => {
                const selected = stage.id == oldEducation ? 'selected' : '';
                options += `<option value="${stage.id}" ${selected}>${stage.name}</option>`;
            });
            working_hour.innerHTML = options;
        })
        .catch(err => {
            console.error('حدث خطأ أثناء جلب المراحل الدراسية:', err);
        });
}

function bindSelectWithChild_Classroom({
    parentSelectId,
    childSelectId,
    urlTemplate,
    selectedValue = null,
    defaultOption = '-- اختر --',
    onLoaded = null // ← تابع يُنفذ بعد التحميل
}) {
    const parentSelect = document.getElementById(parentSelectId);
    const childSelect = document.getElementById(childSelectId);

    if (!parentSelect || !childSelect) return;

    const loadData = (parentValue) => {
        if (!parentValue) {
            childSelect.innerHTML = `<option value="">${defaultOption}</option>`;
            return;
        }

        childSelect.innerHTML = '<option>جاري التحميل...</option>';
        const finalUrl = urlTemplate.replace(':id', parentValue);

        fetch(finalUrl)
            .then(res => res.json())
            .then(data => {
                console.log(data)
                let options = `<option value="">${defaultOption}</option>`;
                data.forEach(stage => {
                    const selected = stage.id == selectedValue ? 'selected' : '';
                    options += `<option value="${stage.id}" ${selected}>${stage.name}</option>`;
                });
                childSelect.innerHTML = options;

                if (onLoaded) onLoaded(); // ← بعد تحميل الخيارات
            })
            .catch(err => {
                console.error('حدث خطأ أثناء تحميل البيانات:', err);
                childSelect.innerHTML = `<option value="">حدث خطأ، حاول مرة أخرى</option>`;
            });
    };

    if (parentSelect.value) {
        loadData(parentSelect.value);
    } else {
        const observer = new MutationObserver(() => {
            if (parentSelect.value) {
                loadData(parentSelect.value);
                observer.disconnect();
            }
        });

        observer.observe(parentSelect, { attributes: true, childList: true, subtree: true });
    }

    parentSelect.addEventListener('change', function () {
        loadData(this.value);
    });
}
function setupDependentSelectWithSubject(sourceSelectId, targetSelectId, apiUrlTemplate, getSubjectId, loadingText = 'جاري التحميل...', defaultOption = '-- اختر --') {
    const sourceSelect = document.getElementById(sourceSelectId);
    const targetSelect = document.getElementById(targetSelectId);

    if (!sourceSelect || !targetSelect) {
        console.error('تعذر العثور على أحد عناصر select.');
        return;
    }

    sourceSelect.addEventListener('change', function () {
        const selectedValue = this.value;
        const subjectId = getSubjectId();  // ✅ الحصول على subject_id الحيّ وقت التغيير

        targetSelect.innerHTML = `<option>${loadingText}</option>`;
            const apiUrlTemplateWithQuery = apiUrlTemplate.endsWith('?') ? apiUrlTemplate : apiUrlTemplate + '?';
 console.log(apiUrlTemplateWithQuery)
        const finalUrl = `${apiUrlTemplateWithQuery}section_id_or_classroom_id=${selectedValue}&teacher_id_or_subject=${subjectId}`;
        console.log(finalUrl);

        fetch(finalUrl)
            .then(res => res.json())
            .then(data => {
                let options = `<option value="">${defaultOption}</option>`;
                data.forEach(item => {
                    options += `<option value="${item.id}">${item.name}</option>`;
                });
                targetSelect.innerHTML = options;
            })
            .catch(err => {
                console.error('حدث خطأ أثناء جلب البيانات:', err);
                targetSelect.innerHTML = `<option>حدث خطأ في التحميل</option>`;
            });
    });
}




