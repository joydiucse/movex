@extends('master')

@section('title')
    {{__('send_bulk_sms')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('send_bulk_sms')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('sms.campaign.post') }}" class="form-validate" method="POST">
                        @csrf
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">

                                        <div class="row g-gs">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('sms_to')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg sms-to" name="sms_to"  required>
                                                            <option value="">{{ __('select') }}</option>
                                                            <option value="merchant">{{__('merchant')}}</option>
                                                            <option value="delivery_man">{{__('delivery_man')}}</option>
                                                        </select>
                                                    </div>
                                                    @if($errors->has('sms_to'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('sms_to') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div id="merchant-section" class="d-none">
                                            <div class="row g-gs">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">{{__('merchants')}} *</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select form-control form-control-lg" id="merchant" name="merchant">
                                                                <option value="">{{ __('select') }}</option>
                                                                <option value="all_merchant">{{__('all_merchant')}}</option>
                                                                <option value="selected_merchants">{{__('selected_merchants')}}</option>
                                                            </select>
                                                        </div>
                                                        @if($errors->has('merchant'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('merchant') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-gs d-none selected-merchant-section">

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ __('select_merchants') }}</label>
                                                        <div class="form-control-wrap">
                                                            <select name="merchants[]" id="selected_merchants" class="form-control form-control-lg merchant-live-search" multiple="multiple"> </select>
                                                        </div>
                                                        @if($errors->has('merchants'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('merchants') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="delivery-man-section" class="d-none">
                                            <div class="row g-gs">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">{{__('delivery_man')}} *</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-select form-control form-control-lg" id="delivery_men" name="delivery_man">
                                                                <option value="">{{ __('select') }}</option>
                                                                <option value="all_delivery_men">{{__('all_delivery_men')}}</option>
                                                                <option value="selected_delivery_men">{{__('selected_delivery_men')}}</option>
                                                            </select>
                                                        </div>
                                                        @if($errors->has('delivery_man'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('delivery_man') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-gs d-none selected-delivery-men-section">

                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ __('select_delivery_men') }}</label>
                                                        <div class="form-control-wrap">
                                                            <select name="delivery_men[]" id="selected_delivery_men" class="form-control form-control-lg delivery-man-live-search" multiple="multiple"> </select>
                                                        </div>
                                                        @if($errors->has('merchants'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('merchants') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('sms_body') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" name="sms_body" rows="7"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-6">
                                            <div class="col-sm-12 text-right">
                                                <div class="form-group">
                                                    <button type="submit" class="btn d-md-inline-flex btn-primary resubmit"><em class="icon ni ni-send"></em></button>
                                                </div>
                                            </div>
                                        </div>
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
            $('.sms-to').on('change', function(){
                if($(this).val() == 'merchant'){
                    $('#merchant-section').removeClass('d-none');
                    $('#delivery-man-section').addClass('d-none');
                    $('#merchant').prop('required',true);
                }
                else if($(this).val() == 'delivery_man'){
                    $('#delivery-man-section').removeClass('d-none');
                    $('#merchant-section').addClass('d-none');
                }else{
                    $('#merchant-section').addClass('d-none');
                    $('#delivery-man-section').addClass('d-none');
                }
            })
            $('#merchant').on('change', function(){
                if($(this).val() == 'selected_merchants'){
                    $('.selected-merchant-section').removeClass('d-none');
                    $('#selected_merchants').prop('required',true);
                }
                else{
                    $('.selected-merchant-section').addClass('d-none');
                }
            })
            $('#delivery_men').on('change', function(){
                if($(this).val() == 'selected_delivery_men'){
                    $('.selected-delivery-men-section').removeClass('d-none');
                    $('#selected_delivery_men').prop('required',true);
                }
                else{
                    $('.selected-delivery-men-section').addClass('d-none');
                }
            })
        });
    </script>
@endpush
@include('live_search.merchants')
@include('live_search.delivery-man')
