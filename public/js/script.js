// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 70,
                    behavior: 'smooth'
                });
                
                // Close mobile menu if open
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    navbarCollapse.classList.remove('show');
                }
            }
        });
    });
    
    // Back to top button
    const backToTopButton = document.querySelector('.back-to-top');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('active');
        } else {
            backToTopButton.classList.remove('active');
        }
    });
    
    backToTopButton.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Counter animation
    const counters = document.querySelectorAll('.counter');
    const speed = 200;
    
    function animateCounters() {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const increment = target / speed;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(animateCounters, 1);
            } else {
                counter.innerText = target;
            }
        });
    }
    
    // Initialize counters when section is in view
    const counterSection = document.querySelector('.stats');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    if (counterSection) {
        observer.observe(counterSection);
    }
    
    
    // Lightbox for facilities images
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'showImageNumberLabel': false
    });
    
    // Form submission
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            // Here you would typically send the form data to a server
            // For demonstration, we'll just show an alert
            alert(`شكراً ${name}، تم استلام رسالتك بنجاح وسنتواصل معك قريباً.`);
            
            // Reset form
            contactForm.reset();
        });
    }
    
    // Newsletter form
    const newsletterForm = document.querySelector('.newsletter-form');
    
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value;
            
            // Here you would typically send the email to a server
            // For demonstration, we'll just show an alert
            alert(`شكراً على اشتراكك في نشرتنا البريدية باستخدام ${email}`);
            
            // Reset form
            this.reset();
        });
    }
    
    // Add animation classes when elements come into view
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('.feature-card, .department-card, .course-card, .facility-card, .teacher-card, .testimonial-card');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;
            
            if (elementPosition < screenPosition) {
                element.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    };
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Run once on page load
});





//قسم الدورات التدريبية
$(document).ready(function() {
        // Handle toggle courses button
        $('#toggle-courses').click(function() {
            $('#all-courses').slideToggle();
            
            if ($('#toggle-courses').text() === 'عرض جميع الدورات') {
                $('#toggle-courses').text('إخفاء الدورات الإضافية');
            } else {
                $('#toggle-courses').text('عرض جميع الدورات');
            }
        });
        
        // Handle registration button click (from previous code)
        $('.register-btn').click(function() {
            var courseName = $(this).data('course');
            $('#courseTitle').text(courseName);
            $('#selectedCourse').val(courseName);
            $('#registrationModal').modal('show');
        });
        
        // Handle form submission (from previous code)
        $('#registrationForm').submit(function(e) {
            e.preventDefault();
            $('#registrationModal').modal('hide');
            $('#successToast').toast('show');
            this.reset();
        });
    });
    $(document).ready(function() {
    
    
    // معالجة إرسال النموذج
    $('#registrationForm').submit(function(e) {
        e.preventDefault();
        
        // جمع بيانات النموذج
        var formData = {
            course_name: $('#selectedCourse').val(),
            fullname: $('#fullname').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            age: $('#age').val(),
            education: $('#education').val(),
            notes: $('#notes').val()
        };
        
        // هنا يمكنك إضافة كود AJAX لإرسال البيانات
        console.log('بيانات النموذج:', formData);
        
        // عرض رسالة نجاح
        alert('تم إرسال طلب التسجيل بنجاح!');
        
        // إغلاق النموذج بعد الإرسال
        $('#registrationModal').modal('hide');
        
        // إعادة تعيين النموذج
        this.reset();
    });
    
    // تأكد من أن زر الإغلاق يعمل
    $('.close').click(function() {
        $('#registrationModal').modal('hide');
    });
    
    // يمكنك إضافة هذا الحدث لمعرفة ما إذا كان النموذج يغلق
    $('#registrationModal').on('hidden.bs.modal', function () {
        console.log('تم إغلاق النموذج');
    });
});





//slider



 document.addEventListener('DOMContentLoaded', function() {
        var swiper = new Swiper('.swiper-container', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            effect: 'fade', // تأثير التلاشي بين الشرائح
            fadeEffect: {
                crossFade: true
            },
        });
    });














