<script type="text/javascript">
    $(document).ready(function(){
        $(".request-otp").on('click', function(){
            var token = "{{ csrf_token() }}";

            var id = $(this).attr('data-request-id');
            var count = parseInt($(this).attr('data-request'));

            count = parseInt(count);
            var url = "{{url('')}}"+'/request-otp/'+ id

            $.ajax({
                type: 'GET',
                dataType: 'json',
                data: {
                    id: id,
                    _token: token
                },
                url: url,
                success: function (response) {
                    console.log(response);
                    toastr.clear();
                    NioApp.Toast(response, 'success', {
                        position: 'top-right'
                    });
                    $('.request-otp').attr('data-request',count+1);

                    var loading = 60 * 5;
                    startTimer(loading, count);
                },
                error: function (response) {
                    toastr.clear();
                    NioApp.Toast(response, 'error', {
                        position: 'top-right'
                    });
                }

            });

        });
    });

</script>

<script>
    function startTimer(duration , count) {
        var timer = duration, minutes, seconds;
        var trigger =  setInterval(function () {
            minutes = parseInt(timer / 60, 10)
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            if (timer >= 0) {
                --timer;
                $('.time').removeClass('d-none');
                $('.request-otp').addClass('d-none');
                $("#time").text(minutes + ":" + seconds);
            }
            else if(timer < 0){
                $('.request-otp').removeClass('d-none');
                $('.time').addClass('d-none');

                clearInterval(trigger);
            }
        }, 1000);
        if(count==5){
            $('.request-otp').remove();
            $('.time').remove();
        }
    }
</script>
