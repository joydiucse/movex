@extends('master')

@section('title')
    {{__('assign_return_list') }}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('assign_return_list')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ count($parcels) }} {{__('parcel')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->



                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner position-relative card-tools-toggle">
                                    <div class="card-title-group">
                                        <div class="card-tools">
                                            <div class="d-block">
                                            <div class="alert alert-success  fade show text-dark message-box" style="display: none">
                                                <strong class="response"></strong>
                                           </div>
                                                    <div class="d-inline-flex">
                                                        <input type="text" name="merchant" class="form-control input-parcel" autofocus placeholder="Merchant" id="merchant">
                                                        <div class="form-group">
                                                            <button type="button" onclick="searchMerchantReturn()" class="btn btn-primary ml-2">{{{__('search')}}}</button>
                                                        </div>
                                                    </div>

                                            </div>
                                        </div><!-- .card-tools -->
                                    </div><!-- .card-title-group -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-0">
                                <form action="{{ route('confirm.bulk.return') }}" method="post">
                                    @csrf
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('parcel_id')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('invoice_no')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('assign_person')}}</strong></span></div>

                                        </div><!-- .nk-tb-item -->
                                        @foreach($parcels as $parcel)

                                        <input type="hidden" value="{{ $parcel->id }}" name="parcel_id[]">
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <span>{{ $val++ }}</span>
                                            </div>
                                            <div class="nk-tb-col">

                                                <table>
                                                    <tr>
                                                        <td>  <span>{{ $parcel->company  }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>  <span>{{ $parcel->address  }}</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td>  <span>{{ $parcel->phone_number  }}</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="nk-tb-col">
                                                {{ $parcel->parcel_no  }}
                                            </div>
                                            <div class="nk-tb-col">
                                                {{ $parcel->customer_invoice_no  }}
                                            </div>
                                            <div class="nk-tb-col">
                                                <table>
                                                    <tr>
                                                        <td>  <span> {{ $parcel->first_name  }} {{ $parcel->last_name }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>  <span>{{ $parcel->address  }}</span></td>
                                                    </tr>

                                                    <tr>
                                                        <td>  <span>{{ $parcel->phone_number  }}</span></td>
                                                    </tr>
                                                </table>

                                            </div>

                                        </div><!-- .nk-tb-item -->

                                        @endforeach
                                        <div class="nk-tb-item">

                                        <div class="nk-tb-col"> </div>
                                            <div class="nk-tb-col">
                                                <input type="hidden" value="{{ $parcel->merchant_id }}" name="merchant_id" id="merchant-id">

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <button type="button" class="btn  btn-primary otp-button" onclick="sendOTPCode()">Send OTP</button>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="p-1 text-center countdown text-dark" style="visibility: hidden;">120</div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="nk-tb-col">
                                            <input type="text" class="form-control" name="opt_code" id="opt-code" placeholder="OTP Code">
                                            <span></span>
                                            </div>
                                            <div class="nk-tb-col">
                                            <button type="submit" disabled class="btn btn-primary merchant_return_btn">Submit</button>
                                            </div>


                                        </div><!-- .nk-tb-item -->

                                    </div>

                                    </form>
                                    <!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')
 <script>
    function searchMerchantReturn()
    {
       var merchant_name =  $('#merchant').val();
       $.ajax({
            type: "post",
            url : "{{ route('search.merchant.return') }}",
            data:{
                'merchant_name':  merchant_name,
                "_token": "{{ @csrf_token() }}"
            },
            success:function(data){
                console.log(data);
                $('#page-data').hide();
                $('#search-data').show();
                $('#search-data').html(data);
            }

       });
    }

    $(document).ready(function(){
        $('#merchant').keyup(function(){
            var merchant_name =  $('#merchant').val();
            if(merchant_name == ''){
                $('#page-data').show();
                $('#search-data').hide();
            }
       });
     });

     $('#merchant').keypress(function (e) {
        var key = e.which;
        if(key == 13)  // the enter key code
        {
            searchMerchantReturn()
            return false;
        }
        });
 </script>
 <!-- Otp code script -->
 <script>
    function sendOTPCode(){
       var merchant_id =  $('#merchant-id').val();
        if(merchant_id !=''){
            timecoundown(120);
            $.ajax({
                type: "post",
                data: {merchant_id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-otp-generate') }}",
                success: function (data) {
                   $('.message-box').show();
                   $('.response').html(data)
                   console.log(data);
                },
                error: function (data) {
                }
            });
        }
    }

    $(document).ready(function(){
        $('#opt-code').keyup(function(){

            var otp_code =  $('#opt-code').val();
            if(otp_code.length == 6){
                var merchant_id =  $('#merchant-id').val();
                if(otp_code !=''){
                    $.ajax({
                        type: "post",
                        data: {otp_code, merchant_id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('bulk-otp-generate-check') }}",
                        success: function (data) {
                        if(data == true){
                            $('.merchant_return_btn').prop('disabled', false);
                            $('.message-box').hide();
                        }else{
                            $('.message-box').show();
                            $('.response').html("Sorry !! Otp Code Not match .")
                            $('.merchant_return_btn').prop('disabled', true);
                        }
                        },
                        error: function (data) {
                        }
                    });
                }
            }else{
                $('.merchant_return_btn').prop('disabled', true);
            }
        })
    });

    //timer coundown

        function timecoundown(setTime)
        {
            var counter = setTime;
            var interval = setInterval(function() {
                counter--;
                if (counter <= 0) {
                        clearInterval(interval);
                       $('.otp-button').attr("disabled", false)
                       $('.countdown').css('visibility', 'hide');
                       $('.countdown').html(0);
                    return;
                }else{
                    $('.countdown').css('visibility', 'visible');
                    $('.countdown').html(counter);
                    $('.otp-button').attr("disabled", true)
                   return counter;
                }
            }, 1000);
        }


 </script>
@endpush
@include('live_search.merchants')
@include('admin.withdraws.status-ajax')
