@extends('master')

@section('title')
    {{__('assign_return')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Return Parcels</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ count($return_list) }} {{__('return_merrchant')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
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
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col tb-col-lg">
                                                    <span class="sub-text"><strong class="text-info">Merchant : {{ $merchant->company }} </strong></span>
                                                    <span class="sub-text"><strong class="text-success">Return Man : {{ $delivery_man->user->first_name }} </strong></span>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <span class="sub-text"><strong class="text-info">{{__('phone_number')}}: {{ $merchant->phone_number }}</strong></span>
                                                    <span class="sub-text"><strong class="text-success">Return Man Phone : {{ $delivery_man->phone_number }}</strong></span>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <span class="sub-text"><strong class="text-info">{{__('address')}} :  {{ $merchant->address }}</strong></span>
                                                    <span class="sub-text"><strong class="text-success">Return Man Address :  {{ $delivery_man->address }}</strong></span>
                                                </div>

                                                <div class="nk-tb-col">
                                                    <span class="sub-text"><strong class="text-info">{{__('create_by')  }} : {{ $bulk_return->returnAssignMan->first_name }} {{ $bulk_return->returnAssignMan->last_name }}</strong></span>
                                                    <span class="sub-text"><strong class="text-success">{{__('process_by')}} : {{ ($bulk_return->processed_by) ? $bulk_return->precessMan->first_name." ".$bulk_return->precessMan->last_name : '' }}</strong></span>
                                                </div>

                                                <div class="nk-tb-col">
                                                    <span class="sub-text"><strong class="text-info">{{__('create_date')}} : {{ $bulk_return->created_at->format('Y-m-d') }}</strong></span>
                                                    <span class="sub-text"><strong class="text-success">{{__('process_date')}} : {{($bulk_return->status =='processed') ? $bulk_return->updated_at->format('Y-m-d'): '' }}</strong></span>
                                                </div>

                                            </div><!-- .nk-tb-item -->

                                        </div>

                                        {{--@if($bulk_return->status =='pending')
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('merchant') }} *</label>
                                                    <div class="form-control-wrap">
                                                        --}}{{-- <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search" required> </select> --}}{{--
                                                        <input type="hidden" id="merchant-live-search" name="merchant" value="{{ $id }}">
                                                        <span class="form-control">{{ $merchant_name  }}</span>
                                                    </div>
                                                    @if($errors->has('merchant'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('merchant') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
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
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('return_man') }} *</label>
                                                    <div class="form-control-wrap">
                                                        --}}{{-- <span>{{ $delivery_man->user->first_name }} {{ $delivery_man->user->last_name }}</span> --}}{{--
                                                        <span class="form-control delivery-man-name" onclick="changeDeliveryMan()">{{ $delivery_man->user->first_name }} {{ $delivery_man->user->last_name }}</span>
                                                        <input type="hidden" value="{{ $delivery_man->id }}" name="return_man">
                                                        <select  id="delivery-man-live-search" name="return_man" class="form-control form-control-lg live-search delivery-man-live-search"> </select>
                                                    </div>
                                                    @if($errors->has('return_man'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('return_man') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif--}}
                                    </div>
                                    <div class="nk-tb-list nk-tb-ulist mt-5" id="parcels" data-val="{{ count($return_list) }}">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('parcel_id')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('invoice_no')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('customer') }}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status') }}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('action') }}</strong></span></div>
                                        </div><!-- .nk-tb-item -->
                                        @foreach($return_list as $parcel)

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
                                                    {{$parcel->customer_invoice_no}}
                                                </div>
                                                <div class="nk-tb-col">
                                                    <p> {{__('name')}} : {{ $parcel->company  }}</p>
                                                    <p>{{__('address') }}: {{ $parcel->address  }}</p>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <p> {{__('name')}} : {{ $parcel->customer_name  }}</p>
                                                    <p>{{__('phone') }}: {{ $parcel->customer_phone_number  }}</p>
                                                </div>
                                                <div class="nk-tb-col">
                                                    {{ $parcel->status  }}
                                                </div>
                                                <div class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                         <li><a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.detail',$parcel->parcel_no) : route('merchant.staff.parcel.detail',$parcel->parcel_no) }}"   class="btn btn-sm btn-primary"><em class="icon ni ni-eye-alt"></em></a></li>
                                                    </ul>
                                                </div>

                                            </div><!-- .nk-tb-item -->

                                        @endforeach


                                    </div><!-- .nk-tb-list -->
                                    {{--@if($bulk_return->status =='pending')
                                        <div class="row">
                                            <input type="hidden" value="{{ $id }}" name="merchant_id" id="merchant-id">
                                            @if($return_sms->sms_to_merchant == 1)
                                                <div class="col-md-2 mt-4 mw-10" style="max-width: 11.56667%;">
                                                    <div class="nk-tb-col">
                                                        @if(count($return_list) > 0)
                                                            <button type="button" class="btn  btn-primary otp-button" onclick="sendOTPCode()">Send OTP</button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mt-4">
                                                    @if(count($return_list) > 0)
                                                        <input type="text" class="form-control mt-3" name="otp_code" id="opt-code" placeholder="OTP Code">
                                                    @endif
                                                </div>

                                                <div class="col-md-1 mt-4">
                                                    <div class="nk-tb-col w-50">
                                                        <div class="p-1 text-center countdown text-white bg-success font-weight-bold" style="visibility: hidden;">120</div>
                                                    </div>
                                                </div>
                                            @endif


                                            @if($return_sms->sms_to_merchant == 1)
                                                <div class="col-md-6 text-right mt-4">
                                                    <div class="form-group ">
                                                        <button onclick="formActionChange('return')" type="button" disabled class="btn btn-danger merchant_return_btn mr-2">Confirm Return</button>

                                                        <div class="custom-control custom-checkbox mr-3">
                                                            <input type="checkbox" class="custom-control-input" id="printCheck" name="print_list" value="print">
                                                            <label class="custom-control-label" for="printCheck">{{__('Print Return List')}}</label>
                                                        </div>
                                                        <input type="hidden" id="batch_no" name="batch_no" value="{{ $batch_no }}">
                                                        <button onclick="formActionChange('update')" type="button" class="btn btn-primary  resubmit">{{__('Update')}}</button>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-12 text-right mt-4">
                                                    <div class="form-group ">
                                                        <div class="custom-control custom-checkbox mr-3">
                                                            <input type="checkbox" class="custom-control-input" id="printCheck" name="print_list" value="print">
                                                            <label class="custom-control-label" for="printCheck">{{__('Print Return List')}}</label>
                                                        </div>
                                                        <input type="hidden" id="batch_no" name="batch_no" value="{{ $batch_no }}">
                                                        <button onclick="formActionChange('update')" type="button" class="btn btn-primary  resubmit">{{__('Update')}}</button>

                                                        <button onclick="formActionChange('return')" type="button"  class="btn btn-danger  mr-2">Confirm Return</button>
                                                    </div>
                                                </div>

                                            @endif

                                        </div>
                                    @endif--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
{{--@push('script')
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
                document.getElementById("parcel-form").action = "{{ route('bulk.return.parcel.update') }}";
                $('#parcel-form').submit();
           }else if(action_name == 'return'){
                document.getElementById("parcel-form").action = "{{ route('confirm.bulk.return') }}";
                $('#parcel-form').submit();
           }
        }

 </script>
@endpush--}}
{{--@include('live_search.delivery-man')
@include('admin.bulk.bulk-script')
@include('live_search.merchants')--}}
