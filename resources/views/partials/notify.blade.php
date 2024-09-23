<link rel="stylesheet" href="{{ asset('assets/css/iziToast.min.css') }}">
<script src="{{ asset('assets/js/iziToast.min.js') }}"></script>

@if(session()->has('success'))
<script type="text/javascript">
    (function($) {
        "use strict";
        iziToast.success({
            message: "{{ session('success') }}",
            position: "topRight"
        });
    })(jQuery);
</script>
@endif

@if(session()->has('error'))
<script type="text/javascript">
    (function($) {
        "use strict";
        iziToast.error({
            message: "{{ session('error') }}",
            position: "topRight"
        });
    })(jQuery);
</script>
@endif

@if ($errors->any())
@php
$collection = collect($errors->all());
$errors = $collection->unique();
@endphp

<script>
    (function($) {
        "use strict";
        @foreach($errors as $error)
        iziToast.error({
            message: '{{ trans($error) }}',
            position: "topRight"
        });
        @endforeach
    })(jQuery);
</script>
@endif
<script>
    "use strict";

    function notify(status, message) {
        iziToast[status]({
            message: message,
            position: "topRight"
        });
    }
</script>