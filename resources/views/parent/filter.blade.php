
{{-- 

@push('stack')
<script>
$(document).ready(function() {

    function loadChildren() {
        $.ajax({
            url: "{{ route('get.childrenselect') }}",
            type: "GET",
            success: function(data) {
                $("#chiled").empty().append('<option value="">اختر الطالب...</option>');
                $.each(data, function(index, child) {
                    $("#chiled").append('<option value="'+ child.id +'">'+ child.name +'</option>');
                });
            },
            error: function() {
                alert("حصل خطأ أثناء جلب البيانات");
            }
        });
    }

    // تحميل الأبناء عند فتح الصفحة
    loadChildren();

    // إعادة التحميل عند الضغط على زر إعادة التعيين
    $("#reloadChildren").click(function() {
        loadChildren();
    });

});
</script>

@endpush --}}