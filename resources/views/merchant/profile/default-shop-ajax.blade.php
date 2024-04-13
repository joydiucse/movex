@push('script')
    <script type="text/javascript">
        $(document).ready(function () {

            $('.default-change').on('change', function (e) {
                var shop_id = $(this).val();
                var merchant_id = $(this).attr('data-merchant');
                var token = "{{ csrf_token() }}";


                var url = $(this).attr('data-url');


                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        shop_id: shop_id,
                        merchant_id: merchant_id,
                        _token: token
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    success: function (response) {
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
    </script>
@endpush
