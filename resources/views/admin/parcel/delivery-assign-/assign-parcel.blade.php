@extends('master')

@section('title')
    {{__('assign_delivery')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('assign_delivery')}} {{__('list')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ count($assign_parcel) }} {{__('assign_delivery')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{-- route('bulk.return.parcel.update') --}}/" class="form-validate" id="parcel-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="alert   fade show text-dark message-box" style="display: none">
                                                <strong class="response"></strong>
                                           </div>
                                            {{-- merchant --}}
                                            <div class="nk-tb-list nk-tb-ulist">
                                                <div class="nk-tb-item nk-tb-head ">
                                                    <div class="nk-tb-col tb-col-lg">
                                                        <span class="sub-text"><strong class="text-info">Delivery Man : {{ $delivery_man->user->first_name }} {{ $delivery_man->user->last_name }} </strong></span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span class="sub-text"><strong class="text-info">Delivery Man Phone : {{ $delivery_man->phone_number }}</strong></span>
                                                    </div>
                                                    <div class="nk-tb-col">
                                                        <span class="sub-text"><strong class="text-info">Delivery Man Address :  {{ $delivery_man->address }}</strong></span>
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <span class="sub-text"><strong class="text-info">{{__('create_by')  }} : {{ $bulk_delivery->returnAssignMan->first_name }} {{ $bulk_delivery->returnAssignMan->last_name }}</strong></span>
                                                        <span class="sub-text"><strong class="text-info">{{__('process_by')}} : {{ ($bulk_delivery->processed_by) ? $bulk_delivery->precessMan->first_name." ".$bulk_delivery->precessMan->last_name : '' }}</strong></span>
                                                    </div>

                                                    <div class="nk-tb-col">
                                                        <span class="sub-text"><strong class="text-info">{{__('create_date')}} : {{ $bulk_delivery->created_at->format('Y-m-d') }}</strong></span>
                                                        <span class="sub-text"><strong class="text-info">{{__('process_date')}} : {{ $bulk_delivery->updated_at->format('Y-m-d') }}</strong></span>
                                                    </div>

                                                </div><!-- .nk-tb-item -->

                                            </div>

                                            @if($bulk_delivery->status =='pending')
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="barcode">{{__('scan_barcode')}} </label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control barcode-return" id="barcode" data-url="admin/add-parcel-row/" value="{{ old('barcode') }}" name="barcode">
                                                    </div>
                                                    @if($errors->has('barcode'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('barcode') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="nk-tb-list nk-tb-ulist mt-5" id="parcels" data-val="{{ count($assign_parcel) }}">
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                                <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('parcel_id')}}</strong></span></div>
                                                <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('invoice_no')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status') }}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('action')}}</strong></span></div>
                                            </div><!-- .nk-tb-item -->
                                            @foreach($assign_parcel as $parcel)

                                            <div class="nk-tb-item" id="row_{{$parcel->parcel_no}}">
                                                <input type="hidden" name="parcel_list[]"  value="{{$parcel->id}}">
                                                <input type="hidden" value="{{ $parcel->id }}" name="parcel_id[]">
                                                <div class="nk-tb-col">
                                                    <span>{{ $val++ }}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    {{$parcel->parcel_no}}
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    {{$parcel->parcel->customer_invoice_no}}
                                                </div>
                                                <div class="nk-tb-col">
                                                    {{ $parcel->status  }}
                                                </div>

                                                <div class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                        @if($bulk_delivery->status =='pending')
                                                        <li><button type="button" class="btn btn-sm btn-danger" onclick="returnParcelReverse('{{ $parcel->parcel_no }}')"><em class="icon ni ni-trash"></em></button></li>
                                                        @else
                                                        <li><a href="{{ route('admin.parcel.detail',$parcel->parcel_no) }}"   class="btn btn-sm btn-primary"><em class="icon ni ni-eye-alt"></em></a></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div><!-- .nk-tb-item -->

                                            @endforeach


                                        </div><!-- .nk-tb-list -->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function(){
            $(".select2-container").hide();
            $('.merchant_return_btn').hide();

        });
        function changeDeliveryMan()
        {
            $('.delivery-man-name').hide();
            $(".select2-container").show();
        }

        function returnParcelReverse(id)
        {
                   if(id !=''){
                        $.ajax({
                            type: "post",
                            data: {id},
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('merchant.return.reverse') }}",
                            success: function (data) {
                               // Swal.fire('Oops...', data, 'error');
                                 $('#row_'+id).remove();
                                console.log(data);
                            },
                            error: function (data) {
                            }
                        });
                    }


        }


    </script>
@endpush
@push('script')
<!-- Otp code script -->
<script>
    function sendOTPCode(){
       var merchant_id =  $('#merchant-id').val();
       var batch_no =  $('#batch_no').val();
        if(merchant_id !=''){

            Swal.fire({
            title: 'Are you sure?',
            text: "You want send OTP code to the merchant!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
            }).then((result) => {
            if (result.isConfirmed) {

                    $.ajax({
                        type: "post",
                        data: {merchant_id, batch_no},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('bulk-otp-generate') }}",
                        success: function (data) {
                        $('.message-box').show();
                        $('.response').html(data)
                        timecoundown(120);
                        console.log(data);
                        },
                        error: function (data) {
                        }
                    });


                }else{
                    return false;
                }
            })
        }
    }

    $(document).ready(function(){
        $('#opt-code').keyup(function(){
            var otp_code =  $('#opt-code').val();
            if(otp_code.length == 4 || otp_code.length == 5 ){
                var merchant_id =  $('#merchant-id').val();
                var batch_no =  $('#batch_no').val();
                if(otp_code !=''){
                    $.ajax({
                        type: "post",
                        data: {otp_code, merchant_id, batch_no},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('bulk-otp-generate-check') }}",
                        success: function (data) {
                        if(data == true){
                            $('.merchant_return_btn').prop('disabled', false);
                            $('.message-box').hide();
                            $('.merchant_return_btn').show();
                            $('.resubmit').hide();
                            $('.custom-checkbox').hide();
                        }else{
                            $('.message-box').show();
                            $('.response').html("Sorry !! Otp Code Not match .")
                            $('.merchant_return_btn').hide();
                            $('.resubmit').show();
                            $('.custom-checkbox').show();
                            $('.merchant_return_btn').prop('disabled', true);

                        }
                        },
                        error: function (data) {
                        }
                    });
                }
            }else{
                $('.merchant_return_btn').prop('disabled', true);
                $('.custom-checkbox').show();
                $('.resubmit').show();
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


        //from acction page change

        function formActionChange(action_name)
        {
           if(action_name =='update'){
                //$("#parcel-form").action("{{ route('bulk.return.parcel.update') }}");
                document.getElementById("parcel-form").action = "{{ route('bulk.return.parcel.update') }}";
                $('#parcel-form').submit();
           }else if(action_name == 'return'){
                document.getElementById("parcel-form").action = "{{ route('confirm.bulk.return') }}";
                $('#parcel-form').submit();
           }
        }

 </script>
@endpush
@include('live_search.delivery-man')
@include('admin.bulk.bulk-script')
@include('live_search.merchants')
