<script>

    function parcelDelivery(id)
    {
        var parcel_id =  id;
        if(parcel_id !=''){
            $.ajax({
                type: "post",
                data: {parcel_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('parcel-delivered-modal') }}",
                success: function (data) {

                    $('.parcel-delivered-content').html(data);
                },
                error: function (data) {
                }
            });

        }
    }


    function DeliveryOTPCode(){
        timecoundown(120)
       var parcel_id =  $('#delivery-parcel-id').val();
        if(parcel_id !=''){
            $.ajax({
                type: "post",
                data: {parcel_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('delivery-otp-generate') }}",
                success: function (data) {
                    $('.response_'+parcel_id).html(data);
                    //console.log(data)
                },
                error: function (data) {
                }
            });

        }
    }

     //timer coundown

     function timecoundown(setTime)
        {
            var counter = setTime;
            var parcel_id =  $('#delivery-parcel-id').val();
            var interval = setInterval(function() {
                counter--;
                if (counter <= 0) {
                        clearInterval(interval);
                       $('.otp-button_'+parcel_id).attr("disabled", false)
                       $('.countdown_'+parcel_id).css('visibility', 'hide');
                       $('.countdown_'+parcel_id).html(0);
                    return;
                }else{
                    $('.countdown_'+parcel_id).css('visibility', 'visible');
                    $('.countdown_'+parcel_id).html(counter);
                    $('.otp-button_'+parcel_id).attr("disabled", true);
                    //console.log(parcel_id)
                   return counter;
                }
            }, 1000);
        }


        function parcelOTPCheck()
        {
                var otp_code =  $('#opt-code').val();
                var parcel_id =  $('#delivery-parcel-id').val();
                if(otp_code !=''){
                    $.ajax({
                        type: "post",
                        data: {otp_code, parcel_id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('delivery-otp-check') }}",
                        success: function (data) {
                            console.log(data);
                            if(data == true){
                                $('.otp-veryfi-response_'+parcel_id).hide();
                                $('.countdown_'+parcel_id).hide();
                                $('.otp-button_'+parcel_id).hide();
                                $('.send-response_'+parcel_id).hide();
                            }else{
                                $('.send-response_'+parcel_id).hide()
                                $('.otp-veryfi-response_'+parcel_id).html("Sorry !! Otp Code Not match .")
                            }
                        },
                        error: function (data) {
                        }
                    });
                }
        }
</script>
