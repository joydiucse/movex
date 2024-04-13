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
                                <h3 class="nk-block-title page-title">{{__('assign_return')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('bulk.return.parcel.save') }}" class="form-validate" id="parcel-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('merchant') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search" required> </select>
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
                                                        <select id="delivery-man-live-search" name="return_man" class="form-control form-control-lg delivery-man-live-search" required> </select>
                                                    </div>
                                                    @if($errors->has('return_man'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('return_man') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-tb-list nk-tb-ulist mt-5" id="parcels" data-val="0">
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                                <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('parcel_id')}}</strong></span></div>
                                                <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('invoice_no')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant_name')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant_address') }}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status') }}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('action')}}</strong></span></div>
                                            </div><!-- .nk-tb-item -->

                                        </div><!-- .nk-tb-list -->

                                        <div class="row">
                                            <div class="col-md-12 text-right mt-4">
                                                <div class="form-group ">
                                                        {{-- <div class="custom-control custom-checkbox mr-3">
                                                            <input type="checkbox" class="custom-control-input" id="customCheck1" name="notify_customer" value="notify">
                                                            <label class="custom-control-label" for="customCheck1">{{__('notify_customer')}}</label>
                                                        </div> --}}
                                                    <button type="submit" class="btn btn-primary d-md-inline-flex resubmit">{{__('Save & Print')}}</button>
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

@include('live_search.delivery-man')
@include('admin.bulk.bulk-script')
@include('live_search.merchants')
