@push('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".status-change").on('click', function(){
                var token = "{{ csrf_token() }}";

                var value = $(this).val().split('/');
                var url = "{{url('')}}"+'/admin/'+value[0];

                var change_for = $(this).attr('data-change-for');

                console.log(change_for)

                if($(this).is(':checked')){
                    var status = 1;
                }else{
                    var status = 0;
                }

                var formData = {
                    id : value[1],
                    status : status,
                    change_for : change_for,
                }

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        data: formData,
                        _token: token
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    success: function (response) {
                        console.log(response);
                        Swal.fire(
                            'Success!',
                            response,
                            'success'
                        ).then((confirmed) => {
                            location.reload();
                        });
                    },
                    error: function (response) {

                        Swal.fire('Oops...', response, 'error');
                    }

                });

            });
        });


        // delivery otp permission change

        $(document).ready(function(){
            $('.otp-permission').select2();
        });

        function deliveryOTPPermission(changePermission)
        {
             $.ajax({
                type:'post',
                url: "{{ route('setting.delivery-otp-permission') }}",
                data:{
                    'changePermission': changePermission, '_token': "{{ csrf_token() }}"
                },
                success:function(response){
                    //console.log(response);
                    Swal.fire(
                        'Success!',
                        response,
                        'success'
                    ).then((confirmed) => {
                        location.reload();
                    });
                },
                error: function (response) {
                    Swal.fire('Oops...', response, 'error');
                }
             });
            
        }

    </script>
@endpush
