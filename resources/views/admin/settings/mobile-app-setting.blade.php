@extends('master')

@section('title')
    {{__('mobile_app_setting')}}
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
                                                <h4 class="nk-block-title">{{__('mobile_app_setting')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(hasPermission('mobile_app_setting_update'))
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
                                                                        <label class="form-label" for="api_key">{{ __('api_key') }} *</label>
                                                                        <div class="form-control-wrap d-flex">
                                                                            <input type="text" class="form-control api-key" id="api_key" data-text="{{__('copied')}}" value="{{ settingHelper('api_key') }}" name="api_key" required>
                                                                            <span type="button" class="btn btn-icon btn-trigger btn-tooltip {{ hasPermission('mobile_app_setting_update') ? '' : 'd-none' }}" onclick="getKey(16, 'api_key')" data-original-title="{{__('change')}}" onclick="copyInput('api_key')"><em class="icon ni ni-repeat"></em></span>
                                                                            <span type="button" class="btn btn-icon btn-trigger btn-tooltip" data-original-title="{{__('copy')}}" onclick="copyInput('api_key')"><em class="icon ni ni-copy"></em></span>
                                                                        </div>
                                                                        @if($errors->has('api_key'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('api_key') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(hasPermission('mobile_app_setting_update'))
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
                                            @if(hasPermission('mobile_app_setting_update'))
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
