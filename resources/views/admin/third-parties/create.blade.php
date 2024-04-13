@extends('master')

@section('title')
    {{__('add').' '.__('third_party')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('add')}} {{__('third_party')}}</h3>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.third-party.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">{{__('name')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="name" value="{{ old('name') }}" name="name" required>
                                                    </div>
                                                    @if($errors->has('name'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('name') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="phone_number">{{__('phone_number')}}</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="phone_number" value="{{ old('phone_number') }}" name="phone_number">
                                                    </div>
                                                    @if($errors->has('phone_number'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('phone_number') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="address">{{__('address')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="address" placeholder="{{__('address')}}" name="address" required>{{ old('details')}}</textarea>
                                                    </div>
                                                    @if($errors->has('address'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('address') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 text-right mt-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('submit')}}}</button>
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


