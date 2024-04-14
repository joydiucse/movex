{{--<script src="{{asset('admin/js/tailwind-3.4.3.js')}}"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    clifford: '#da373d',
                }
            }
        },
        darkMode: 'class',
    }
</script>--}}
<script id="fa" src="{{ asset('admin/')}}/js/bundle.js?ver=2.3.0"></script>
<script src="{{ asset('admin/')}}/js/scripts.js?ver=2.3.0"></script>
<script src="{{ asset('admin/')}}/js/custom.js"></script>
<link rel="stylesheet" href="{{ asset('admin/')}}/css/tagsinput.css">
{{--  success message hide --}}
@stack('script')
