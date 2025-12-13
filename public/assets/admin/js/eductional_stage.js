function loadWorkingHours(selectedId,id) {

    //هذه الدالة تعمل في التعديل حيث تقوم بجلب بيانات الفوج وتعبئة 
// select
    const working_hour = document.getElementById(id);

    // console.log(working_hour)

    fetch('/educational_stage/create') // عدّل حسب الحاجة
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">-- اختر الفوج الدراسي --</option>';
            data.forEach(stage => {
                // أضف selected عند المطابقة
                const selected = stage.id == selectedId ? 'selected' : '';
                options += `<option value="${stage.id}" ${selected}>${stage.name}</option>`;
            });
            working_hour.innerHTML = options;
        })
        .catch(err => {
            console.error('حدث خطأ أثناء جلب المراحل الدراسية:', err);
        });
}

function setupStageLoader(buttonId, selectId, apiUrl) {
  const addStageButton = document.getElementById(buttonId);
  const selectElement = document.getElementById(selectId);

  if (!addStageButton || !selectElement) {
    console.error("تعذر العثور على العناصر المحددة.");
    return;
  }

  addStageButton.addEventListener('click', () => {
    console.log(buttonId);
    fetch(apiUrl)
      .then(res => {
        if (!res.ok) {
          throw new Error('Network response was not ok');
        }
        return res.json();
      })
      .then(data => {
        let options = '<option value="">-- اختر الفوج الدراسي --</option>';
        data.forEach(stage => {
          options += `<option value="${stage.id}">${stage.name}</option>`;
        });
        selectElement.innerHTML = options;
      })
      .catch(err => {
        console.error('حدث خطأ أثناء جلب المراحل الدراسية:', err);
      });
  });
}

function loadOptionsIntoSelect(selectId, apiUrl, placeholder = '-- اختر --') {
  const selectElement = document.getElementById(selectId);
  if (!selectElement) {
    console.error(`لم يتم العثور على العنصر select بالمعرف: ${selectId}`);
    return;
  }

  // إظهار مؤقت (اختياري)
  selectElement.innerHTML = `<option value="">جاري التحميل...</option>`;

  fetch(apiUrl)
    .then(res => {
      if (!res.ok) {
        throw new Error('Network response was not ok');
      }
      return res.json();
    })
    .then(data => {
      let options = `<option value="">${placeholder}</option>`;
      data.forEach(item => {
        options += `<option value="${item.id}">${item.name}</option>`;
      });
      selectElement.innerHTML = options;
    })
    .catch(err => {
      console.error(`خطأ أثناء تحميل البيانات من ${apiUrl}:`, err);
      selectElement.innerHTML = `<option value="">فشل التحميل</option>`;
    });
}




  // تقوم بجيب البيانات القديمة الخاصة ب الأفواج وجلب الأفواج ايضا

   function get_old_data_frome_workinhour(id, name_section, url) {
                const working_hour = document.getElementById(id);
                const oldworking_hour = window.oldInputs?.[name_section];
                // console.log("Old value:", oldworking_hour);
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
function loadOptionsIntoSelectsection(selectId, apiUrl, placeholder = '-- اختر --') {
  const selectElement = document.getElementById(selectId);

  if (!selectElement) {
     
    console.error(`لم يتم العثور على العنصر select بالمعرف: ${selectId}`);
    return;
  }

  // إظهار مؤقت (اختياري)
  selectElement.innerHTML = `<option value="">جاري التحميل...</option>`;

  fetch(apiUrl)
    .then(res => {
      if (!res.ok) {
        throw new Error('Network response was not ok');
      }
      return res.json();
    })
    .then(data => {
       console.log(data);
      let options = `<option value="">${placeholder}</option>`;
      data.forEach(item => {
        options += `<option value="${item.id}">  ${item.classroom.name}   ${item.name}</option>`;
      });
      selectElement.innerHTML = options;
    })
    .catch(err => {
      console.error(`خطأ أثناء تحميل البيانات من ${apiUrl}:`, err);
      selectElement.innerHTML = `<option value="">فشل التحميل</option>`;
    });
}


function loadsectioninteacher(selectedId,id) {

    //هذه الدالة تعمل في التعديل حيث تقوم بجلب بيانات الفوج وتعبئة 
// select
    const section = document.getElementById(id);

    console.log(section)

    fetch('/teacher/getclassroom') // عدّل حسب الحاجة
        .then(res => res.json())
        .then(data => {
            let options = '<option value="">--   أختر الصف الدراسي --</option>';
            data.forEach(stage => {
                // أضف selected عند المطابقة
                const selected = stage.id == selectedId ? 'selected' : '';
                options += `<option value="${stage.id}" ${selected}>${stage.name} : ${stage.classroom.name}</option>`;
            });
            section.innerHTML = options;
        })
        .catch(err => {
            console.error('حدث خطأ أثناء جلب المراحل الدراسية:', err);
        });
}
