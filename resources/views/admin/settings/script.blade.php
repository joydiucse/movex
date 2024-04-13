@push('script')
    <script>

        $(document).ready(function () {

            $('#add-row').on('click', function (e) {

                e.preventDefault();
                var url = $('#url').val();
                var add_url = $(this).attr('data-url');
                $.ajax({
                    type: "GET",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url + '/' + add_url,
                    success: function (data) {
                        console.log(data);
                        $('#package-charge').append(data.view);
                    },
                    error: function (data) {
                        console.log(data)
                    }
                });
            });

        });

        $(document).ready(function(){
            $(document).on('click','.delete-btn-remove',function(){

                var token = "{{ csrf_token() }}";
                var row = $(this).attr('data-row');
                var id = $(this).attr('data-id');

                if (id == ''){
                    $('#'+row).remove();
                    Swal.fire(
                        'Success!',
                        '{{ __('deleted_successfully') }}',
                        'success'
                    )
                    return true;
                }

                var url = "{{url('')}}"+'/admin/delete-packaging-charge/'+id;

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        _token: token
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    success: function (response) {
                        console.log(response);
                        if (response.success){
                            $('#'+row).remove();
                            Swal.fire(
                                'Success!',
                                response.message,
                                'success'
                            ).then((confirmed) => {
                                location.reload();
                            });
                        }
                        else{
                            Swal.fire(
                                'Oops..!',
                                response.message,
                                'error'
                            )
                        }
                    },
                    error: function (response) {
                        console.log(response);
                        Swal.fire('Oops..!', response, 'error');
                    }

                });
            });
        });
    </script>
@endpush
