document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // معالجة تسجيل الدخول
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const remember = document.getElementById('remember').checked;
            
            // هنا يمكنك إضافة التحقق من بيانات الدخول
            console.log('محاولة تسجيل دخول:', { username, password, remember });
            
            // عرض رسالة تحميل
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحقق...';
            btn.disabled = true;
            
            // محاكاة اتصال بالخادم
            setTimeout(function() {
                // في حالة النجاح، توجيه إلى لوحة التحكم
                window.location.href = "dashboard.html";
                
                // في حالة الفشل:
                // btn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>تسجيل الدخول';
                // btn.disabled = false;
                // alert('بيانات الدخول غير صحيحة');
            }, 1500);
        });