<script>
    $(document).ready(function () {

        $('.delivery-reverse').on('click', function (e) {

            e.preventDefault();
            var id = $(this).closest("div.nk-tb-item").find("input").val();
            $('#delivery-reverse-id').val(id);

            var url = $(this).attr('data-url');
            var formData = {
                id : id
            }
            $.ajax({
                type: "GET",
                dataType: 'html',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                success: function (data) {
                    $('#reverse').html(data);
                    getDefaultShop(merchant_id);
                },
                error: function (data) {
                }
            });
        });

    });
    $(document).ready(function () {

        $('.transfer-to-hub').on('click', function (e) {

            e.preventDefault();
            var id = $(this).closest("div.nk-tb-item").find("input").val();
            $('#transfer-to-hub-id').val(id);

            var url = $(this).attr('data-url');
            var formData = {
                id : id
            }
            $.ajax({
                type: "GET",
                dataType: 'html',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                success: function (data) {
                    $('#transfer-hub-options').html(data);
                },
                error: function (data) {
                }
            });
        });

    });
</script>
