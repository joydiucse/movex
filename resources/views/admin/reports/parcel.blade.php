@extends('master')

@section('title')
    {{__('parcels_summary')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('parcels_summary')}}</h3>

                            </div><!-- .nk-block-head-content -->

                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.search.parcels')}}" class="form-validate" method="GET">
{{--                        @csrf--}}
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('start_date')}} *</label>
                                                    <div class="form-control-wrap focused">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-calendar-alt"></em>
                                                        </div>
                                                        <input type="text" class="form-control date-picker" name="start_date" autocomplete="off" required placeholder="{{__('start_date')}}">
                                                    </div>
                                                    @if($errors->has('start_date'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('start_date') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('end_date')}} *</label>
                                                    <div class="form-control-wrap focused">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-calendar-alt"></em>
                                                        </div>
                                                        <input type="text" class="form-control date-picker" name="end_date" autocomplete="off" required placeholder="{{__('end_date')}}">
                                                    </div>
                                                    @if($errors->has('end_date'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('end_date') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('select_status')}} </label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg search-type" name="status">
                                                            <option value="">{{ __('select_type') }}</option>
                                                            <option value="pending">{{__('pending')}}</option>
                                                            <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
                                                            <option value="re-schedule-pickup">{{__('re-schedule-pickup')}}</option>
                                                            <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
                                                            <option value="received">{{__('received_by_warehouse')}}</option>
                                                            <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
                                                            <option value="re-schedule-delivery">{{__('re-schedule-delivery')}}</option>
                                                            <option value="returned-to-greenx">{{__('returned-to-greenx')}}</option>
                                                            <option value="return-assigned-to-merchant">{{__('return-assigned-to-merchant')}}</option>
                                                            <option value="returned-to-merchant">{{__('returned-to-merchant')}}</option>
                                                            <option value="delivered">{{__('delivered')}}</option>
                                                            <option value="partially-delivered">{{__('partially-delivered')}}</option>
                                                            <option value="cancelled">{{__('cancelled')}}</option>
                                                            <option value="deleted">{{__('deleted')}}</option>
                                                            <option value="re-request">{{__('re-request')}}</option>
                                                        </select>
                                                    </div>
                                                    @if($errors->has('status'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('status') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('merchant')}}</label>
                                                    <div class="form-control-wrap">
                                                        <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search">
{{--                                                        <select class="form-select form-control form-control-lg" name="merchant">--}}
                                                            <option value="">{{ __('select_merchant') }}</option>
{{--                                                            @foreach($merchants as $merchant)--}}
{{--                                                                <option value="{{ $merchant->id }}">{{ $merchant->user->first_name.' '.$merchant->user->last_name }} ({{$merchant->company}})</option>--}}
{{--                                                            @endforeach--}}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-sm-12 text-right">
                                                <div class="form-group">
                                                    <button type="submit" class="btn d-md-inline-flex btn-primary">{{{__('search')}}}</button>
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

    @if(isset($data))
        <div class="nk-content ">
            <div class="container-fluid">
                <div class="nk-content-inner">
                    <div class="nk-content-body">
                        <div class="nk-block-head nk-block-head-sm">
                            <div class="nk-block-between">
                                <div class="nk-block-head-content">
                                    <h3 class="nk-block-title page-title">{{__('parcel').' '.__('summery_between')}} {{$date['start_date'] != ""? date('M d, Y', strtotime($date['start_date'])):''}} - {{$date['end_date'] != ""? date('M d, Y', strtotime($date['end_date'])):''}}</h3>
                                    <div class="nk-block-des text-soft">
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

                                            </div><!-- .card-tools -->
                                        </div><!-- .card-title-group -->
                                    </div><!-- .card-inner -->
                                    <div class="card-inner p-0">
                                        <div class="nk-tb-list nk-tb-ulist">
                                            @foreach($data as $event => $count)
                                                <div class="nk-tb-item">
                                                    <div class="nk-tb-col tb-col-md">
                                                        <span class="sub-text"><strong>{{ $event }}</strong></span>
                                                    </div>
                                                    <div class="nk-tb-col tb-col-md">
                                                        <span class="sub-text"><strong> {{ $count  }} {{__('pcs')}}</strong></span>
                                                    </div>
                                                </div><!-- .nk-tb-item -->
                                            @endforeach

                                        </div><!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                </div><!-- .card-inner-group -->
                            </div><!-- .card -->
                        </div><!-- .nk-block -->
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
@include('live_search.merchants')
