@extends('master')

@section('title')
    {{__('shops').' '.__('list')}}
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
                                    <div class="nk-block-head nk-block-head-lg">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">{{__('api_credentials')}}</h4>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(!hasPermission('read_api_credentials'))
                                        <div class="card shadow-none">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card-inner">
                                                        <div class="row g-gs">
                                                            {{ __('contact_with_greenx_admin_to_get_your_api_credentials') }}
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @else
                                        @if(Sentinel::getUser()->user_type == 'merchant' && @settingHelper('preferences')->where('title','merchant_api_update')->first()->merchant
                                                && hasPermission('update_api_credentials'))
                                            <form action="{{ route('merchant.api.credentials.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                                                @csrf
                                        @endif
                                            <div class="card shadow-none">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card-inner">
                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="api_key">{{ __('api_key') }} {{ (settingHelper('preferences')->where('title','merchant_api_update')->first()->merchant && hasPermission('update_api_credentials')) ? '*' : '' }}</label>
                                                                        <div class="form-control-wrap d-flex">
                                                                            <input type="text" class="form-control" hidden id="merchant" value="{{ \Sentinel::getUser()->merchant->id }}" name="id">
                                                                            <input type="text" class="form-control api-key" id="api_key" data-text="{{__('copied')}}" value="{{ \Sentinel::getUser()->merchant->api_key }}" name="api_key" required>
                                                                            @if(Sentinel::getUser()->user_type == 'merchant' && @settingHelper('preferences')->where('title','merchant_api_update')->first()->merchant && hasPermission('update_api_credentials'))
                                                                                <span type="button" class="btn btn-icon btn-trigger btn-tooltip" onclick="getKey(15, 'api_key')" data-original-title="{{__('change')}}"><em class="icon ni ni-repeat"></em></span>
                                                                            @endif
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
                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="secret_key">{{ __('secret_key') }} {{ (settingHelper('preferences')->where('title','merchant_api_update')->first()->merchant && hasPermission('update_api_credentials')) ? '*' : '' }}</label>
                                                                        <div class="form-control-wrap d-flex">
                                                                            <input type="text" class="form-control secret-key" id="secret_key" data-text="{{__('copied')}}" value="{{ \Sentinel::getUser()->merchant->secret_key }}" name="secret_key" required>
                                                                            @if(Sentinel::getUser()->user_type == 'merchant' && @settingHelper('preferences')->where('title','merchant_api_update')->first()->merchant
                                                                                    && hasPermission('update_api_credentials'))
                                                                                <span type="button" class="btn btn-icon btn-trigger btn-tooltip" onclick="getKey(30, 'secret_key')" data-original-title="{{__('change')}}" ><em class="icon ni ni-repeat"></em></span>
                                                                            @endif
                                                                            <span type="button" class="btn btn-icon btn-trigger btn-tooltip" data-original-title="{{__('copy')}}" onclick="copyInput('secret_key')"><em class="icon ni ni-copy"></em></span>
                                                                        </div>
                                                                        @if($errors->has('secret_key'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('secret_key') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(Sentinel::getUser()->user_type == 'merchant' && @settingHelper('preferences')->where('title','merchant_api_update')->first()->merchant
                                                                && hasPermission('update_api_credentials'))
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
                                        @if(Sentinel::getUser()->user_type == 'merchant' && @settingHelper('preferences')->where('title','merchant_api_update')->first()->merchant
                                            && hasPermission('update_api_credentials'))
                                            </form>
                                        @endif
                                    @endif
                                </div>

                                @include('merchant.profile.profile-sidebar')
                            </div><!-- .card-aside-wrap -->


                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection

