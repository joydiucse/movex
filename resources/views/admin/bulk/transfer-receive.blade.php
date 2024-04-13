@extends('master')

@section('title')
    {{__('transfer_receive')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('transfer_receive')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('bulk.transfer.parcel.receive')}}" class="form-validate" id="parcel-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="barcode">{{__('scan_barcode')}} </label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control barcode" id="barcode" data-url="admin/add-receive-parcel-row/" value="{{ old('barcode') }}" name="barcode">
                                                    </div>
                                                    @if($errors->has('barcode'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('barcode') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('delivery_man') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <select id="delivery-man-live-search" name="delivery_man" class="form-control form-control-lg delivery-man-live-search" required> </select>
                                                    </div>
                                                    @if($errors->has('delivery_man'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('delivery_man') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nk-tb-list nk-tb-ulist mt-5" id="parcels" data-val="0">
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                                <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('parcel_id')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('customer_name')}}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('customer_address') }}</strong></span></div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('action')}}</strong></span></div>
                                            </div><!-- .nk-tb-item -->

                                        </div><!-- .nk-tb-list -->

                                        <div class="row">
                                            <div class="col-md-12 text-right mt-4">
                                                <div class="form-group ">
                                                    <button type="submit" class="btn btn-primary d-md-inline-flex resubmit">{{__('print')}}</button>
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
