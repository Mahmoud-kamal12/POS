@if (session('success'))
    <script>
        new Noty({
            type: 'success',
            layout: 'bottomRight',
            text: "{{ session('success') }}",
            timeout: 2000,
            killer: true
        }).show();
    </script>

@endif
