@extends('master')

@section('title')
    {{__('add').' '.__('return-batch')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('create_batch')}}</h3>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('create-return-batch-store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-6">

                            <div class="card">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-inner">
                                            <div class="row g-gs">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ __('merchant') }} *</label>
                                                        <div class="form-control-wrap">
                                                            <select id="merchant-live-search" name="merchant" class="form-control form-control-lg merchant-live-search" required> </select>
                                                        </div>


                                                        </span>
                                                        @if($errors->has('merchant'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('merchant') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row g-gs">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">{{ __('delivery_man') }} *</label>
                                                        <div class="form-control-wrap">
                                                            <select id="delivery-man-live-search" name="delivery_man" class="form-control form-control-lg delivery-man-live-search get-delivery-man-balance" required> </select>
                                                        </div>


                                                        </span>
                                                        @if($errors->has('delivery_man'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('delivery_man') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-md-1 text-right mt-4">
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('submit')}}}</button>
                                                    </div>
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
@include('live_search.merchants')

