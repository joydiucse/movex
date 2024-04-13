@if(Session::has('success'))
    @push('script')
        <script type="text/javascript">
            $(document).ready(function() {
                toastr.clear();
                NioApp.Toast('{{Session::get('success')}}', 'success',{
                  position: 'top-right'
                });
            });
        </script>
    @endpush
@elseif(Session::has('danger'))
    @push('script')
        <script type="text/javascript">
            $(document).ready(function() {
                toastr.clear();
                NioApp.Toast('{{Session::get('danger')}}', 'error', {
                  position: 'top-right'
                });
            });
        </script>
    @endpush
@endif
