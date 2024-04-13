@push('script')
<script type="text/javascript">
    $(document).ready(function(){
		$('.cash-collection').keyup(function(e){

            e.preventDefault();


            if($('#fragile').is(':checked')){

                $('.packaging-area').removeClass('d-none');
                var fragile = 1;
                $('.fragile-charge-area').removeClass('d-none');


                if($('.packaging').val() != 'no'){
                    var packaging = $('.packaging').val();
                    $('.packaging-charge-area').removeClass('d-none');
                }else{
                    var packaging = 'no';
                    $('.packaging-charge-area').addClass('d-none');
                }

            }else{

                $('.packaging-area').addClass('d-none');
                var packaging = 'no';
                $('.packaging-charge-area').addClass('d-none');

                var fragile = 0;
                $('.fragile-charge-area').addClass('d-none');

            }

            var url = $('#url').val();
            var formData = {
                merchant     : $('.merchant').val(),
                parcel_type  : $('.parcel_type').val(),
                weight       : $('.weight').val(),
                cod          : $(this).val(),
                packaging    : packaging,
                fragile      : fragile
            }
            $.ajax({
                type: "GET",
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url + '/' + 'charge-details',
                success: function (data) {
                    console.log(data);
                    $('#cash-collection-charge').html(data['cod']);
                    $('#current-payable-charge').html(data['payable']);
                    $('#delivery-charge').html(data['charge']);
                    $('#cod-charge').html(data['cod_charge']);
                    $('#vat-charge').html(data['vat']);
                    $('#total-delivery-charge').html(data['total_delivery_charge']);

                    $('#packaging-charge').html(data['packaging_charge']);
                    $('#frazile-charge').html(data['frazile_charge']);
                },
                error: function (data) {
                }
            });

		});


        $('.merchant, .parcel_type, .weight, .packaging').change(function(e){

            if($('#fragile').is(':checked')){
                $('.packaging-area').removeClass('d-none');
                var fragile = 1;
                $('.fragile-charge-area').removeClass('d-none');


                if($('.packaging').val() != 'no'){
                    var packaging = $('.packaging').val();
                    $('.packaging-charge-area').removeClass('d-none');
                }else{
                    var packaging = 'no';
                    $('.packaging-charge-area').addClass('d-none');
                }

            }else{
                $('.packaging-area').addClass('d-none');
                var packaging = 'no';
                $('.packaging-charge-area').addClass('d-none');

                var fragile = 0;
                $('.fragile-charge-area').addClass('d-none');

            }

            var url = $('#url').val();
            var formData = {
                merchant    : $('.merchant').val(),
                parcel_type : $('.parcel_type').val(),
                weight      : $('.weight').val(),
                cod         : $('.cash-collection').val(),
                packaging   : packaging,
                fragile     : fragile
            }
            console.log(formData)
            $.ajax({
                type: "GET",
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url + '/' + 'charge-details',
                success: function (data) {
                    console.log(data);
                    $('#cash-collection-charge').html(data['cod']);
                    $('#current-payable-charge').html(data['payable']);
                    $('#delivery-charge').html(data['charge']);
                    $('#cod-charge').html(data['cod_charge']);
                    $('#vat-charge').html(data['vat']);
                    $('#total-delivery-charge').html(data['total_delivery_charge']);

                    $('#packaging-charge').html(data['packaging_charge']);
                    $('#fragile-charge').html(data['fragile_charge']);
                },
                error: function (data) {
                }
            });

        });

        $('#fragile').click(function(e){

            if($('#fragile').is(':checked')){

                $('.packaging-area').removeClass('d-none');
                var fragile = 1;
                $('.fragile-charge-area').removeClass('d-none');

                if($('.packaging').val() != 'no'){
                    var packaging = $('.packaging').val();
                    $('.packaging-charge-area').removeClass('d-none');
                }else{
                    var packaging = 'no';
                    $('.packaging-charge-area').addClass('d-none');
                }

            }else{
                $('.packaging-area').addClass('d-none');
                var packaging = 'no';
                $('.packaging-charge-area').addClass('d-none');

                var fragile = 0;
                $('.fragile-charge-area').addClass('d-none');

            }

            var url = $('#url').val();
            var formData = {
                merchant    : $('.merchant').val(),
                parcel_type : $('.parcel_type').val(),
                weight      : $('.weight').val(),
                cod         : $('.cash-collection').val(),
                packaging   : packaging,
                fragile     : fragile
            }

            console.log(formData);

            $.ajax({
                type: "GET",
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url + '/' + 'charge-details',
                success: function (data) {
                    console.log(data);

                    $('#cash-collection-charge').html(data['cod']);
                    $('#current-payable-charge').html(data['payable']);
                    $('#delivery-charge').html(data['charge']);
                    $('#cod-charge').html(data['cod_charge']);
                    $('#vat-charge').html(data['vat']);
                    $('#total-delivery-charge').html(data['total_delivery_charge']);

                    $('#packaging-charge').html(data['packaging_charge']);
                    $('#fragile-charge').html(data['fragile_charge']);
                },
                error: function (data) {
                }
            });

        });


    });

</script>
@endpush
