@props(['type'])

@if (session()->has($type))
      <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof notify === 'function') {
                notify('{{ $type }}', '{{ session($type) }}');
            }
        });
    </script>
@endif