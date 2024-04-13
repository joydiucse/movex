@extends('master')

@section('title')
    {{__('pickup_time_delivery_days')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-aside-wrap">

                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head nk-block-head-lg pb-2">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">{{__('pickup_time_delivery_days')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(hasPermission('pickup_and_delivery_time_setting_update'))
                                    <form action="{{ route('setting.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                                        @csrf
                                    @endif
                                        <div class="card shadow-none">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card-inner">

                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="pickup_accept_start">{{ __('pickup_accept_start') }}({{ __('0_24') }}) *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" id="pickup_accept_start" value="{{ settingHelper('pickup_accept_start') }}" name="pickup_accept_start" min="0" max="24" required>
                                                                    </div>
                                                                    @if($errors->has('pickup_accept_start'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('pickup_accept_start') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="pickup_accept_end">{{ __('pickup_accept_end') }}({{ __('0_24') }}) *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" id="pickup_accept_end" value="{{ settingHelper('pickup_accept_end') }}" name="pickup_accept_end" min="0" max="24" required>
                                                                    </div>
                                                                    @if($errors->has('pickup_accept_end'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('pickup_accept_end') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="outside_dhaka_days">{{__('outside_dhaka_delivery_days')}} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" id="outside_dhaka_days" value="{{ settingHelper('outside_dhaka_days') }}" min="0" name="outside_dhaka_days" required>
                                                                    </div>
                                                                    @if($errors->has('outside_dhaka_days'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('outside_dhaka_days') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(hasPermission('pickup_and_delivery_time_setting_update'))
                                                        <div class="row">
                                                            <div class="col-md-6 text-right mt-4">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('update')}}}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @if(hasPermission('pickup_and_delivery_time_setting_update'))
                                    </form>
                                    @endif
                                </div>

                                @include('admin.settings.sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
