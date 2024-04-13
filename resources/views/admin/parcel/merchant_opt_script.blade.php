<script>
    function parcelReturnToMerchant(id)
    {   
        var parcel_id =  id;
        if(parcel_id !=''){
            $.ajax({
                type: "post",
                data: {parcel_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('parcel-returned-to-merchant-modal') }}",
                success: function (data) {
                    $('.returned-to-merchant-content').html(data);
                    //console.log(data)
                },
                error: function (data) {
                }
            });
            
        }  
    }


    function sendOTPCode(){
        merchanttimecoundown(120)
       var parcel_id =  $('#returned-to-merchant-id').val();
        if(parcel_id !=''){
            $.ajax({
                type: "post",
                data: {parcel_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('parcel-otp-generate') }}",
                success: function (data) {
                    $('.message-box_'+parcel_id).show();
                   $('.response_'+parcel_id).html(data)
                },
                error: function (data) {
                }
            });
        }
    }
   
    function merchantOTPCheck()
    {
        var otp_code =  $('#opt-code').val();
            var parcel_id =  $('#returned-to-merchant-id').val();
            if(otp_code !=''){
                $.ajax({
                    type: "post",
                    data: {otp_code, parcel_id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('parcel-otp-generate-check') }}",
                    success: function (data) {
                     if(data == true){
                        $('.merchant_return_btn_'+parcel_id).prop('disabled', false);
                        $('.message-box_'+parcel_id).hide();
                     }else{
                        $('.message-box_'+parcel_id).show();
                        $('.response_'+parcel_id).html("Sorry !! Otp Code Not match .")
                        $('.merchant_return_btn_'+parcel_id).prop('disabled', true);
                     }
                    },
                    error: function (data) {
                    }
                });
            }
    }


     //timer coundown

     function merchanttimecoundown(setTime)
        {
            var counter = setTime;
            var parcel_id =  $('#returned-to-merchant-id').val();
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
                    $('.otp-button_'+parcel_id).attr("disabled", true)
                   return counter;
                }
            }, 1000);
        }
</script>
