@extends('master')

@section('title')
    {{__('sms_setting')}}
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
                                                <h4 class="nk-block-title">{{__('sms_setting')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(hasPermission('sms_settings_update'))
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
                                                                    <label class="form-label">{{ __('sms_provider') }} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <select class="form-select form-control form-control-lg sms-provider" name="sms_provider" required>
                                                                            <option value="">{{ __('select_provider') }}</option>
{{--                                                                            <option value="gp" {{ settingHelper('sms_provider') == 'gp' ? 'selected' : ''}}>{{ __('gp') }}</option>--}}
                                                                            <option value="onnorokom" {{ settingHelper('sms_provider') == 'onnorokom' ? 'selected' : ''}}>{{ __('onnorokom') }}</option>
                                                                            <option value="reve" {{ settingHelper('sms_provider') == 'reve' ? 'selected' : ''}}>{{ __('reve_system') }}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="mask_name">{{__('mask_name')}}</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" id="mask_name" value="{{ settingHelper('mask_name') }}" name="mask_name">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

{{--                                                        <div class="row g-gs">--}}
{{--                                                            <div class="col-md-6">--}}
{{--                                                                <div class="form-group">--}}
{{--                                                                    <label class="form-label" for="gp_sms_url">{{__('gp_sms_url')}} *</label>--}}
{{--                                                                    <div class="form-control-wrap">--}}
{{--                                                                        <input type="text" class="form-control" id="gp_sms_url" value="{{ settingHelper('gp_sms_url') }}" name="gp_sms_url" required>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="row g-gs">--}}
{{--                                                            <div class="col-md-6">--}}
{{--                                                                <div class="form-group">--}}
{{--                                                                    <label class="form-label" for="gp_username">{{__('gp_username')}} *</label>--}}
{{--                                                                    <div class="form-control-wrap">--}}
{{--                                                                        <input type="text" class="form-control" id="gp_username" value="{{ settingHelper('gp_username') }}" name="gp_username" required>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

{{--                                                        <div class="row g-gs">--}}
{{--                                                            <div class="col-md-6">--}}
{{--                                                                <div class="form-group">--}}
{{--                                                                    <label class="form-label" for="gp_password">{{__('gp_password')}} *</label>--}}
{{--                                                                    <div class="form-control-wrap">--}}
{{--                                                                        <input type="password" class="form-control" id="gp_password" value="{{ settingHelper('gp_password') }}" name="gp_password" required>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}

                                                        <div class="onnorokom {{ settingHelper('sms_provider') == 'onnorokom' ? '' : 'd-none'}}">
                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="onnorokom_url">{{__('onnorokom_url')}} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" class="form-control" id="onnorokom_url" value="{{ settingHelper('onnorokom_url') }}" name="onnorokom_url">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="onnorokom_username">{{__('onnorokom_username')}} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" class="form-control" id="onnorokom_username" value="{{ settingHelper('onnorokom_username') }}" name="onnorokom_username">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="onnorokom_password">{{__('onnorokom_password')}} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="password" class="form-control" id="onnorokom_password" value="{{ settingHelper('onnorokom_password') }}" name="onnorokom_password">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="reve {{ settingHelper('sms_provider') == 'reve' ? '' : 'd-none'}}">
                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="reve_url">{{__('reve_url')}} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" class="form-control" id="reve_url" value="{{ settingHelper('reve_url') }}" name="reve_url">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="reve_api_key">{{__('reve_api_key')}} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="password" class="form-control" id="reve_api_key" value="{{ settingHelper('reve_api_key') }}" name="reve_api_key">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="reve_secret">{{__('reve_secret')}} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="password" class="form-control" id="reve_secret" value="{{ settingHelper('reve_secret') }}" name="reve_secret">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(hasPermission('sms_settings_update'))
                                                        <div class="row">
                                                            <div class="col-md-6 text-right mt-4">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-lg btn-primary">{{{__('update')}}}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @if(hasPermission('sms_settings_update'))
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
