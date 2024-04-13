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
                                                <h4 class="nk-block-title">{{__('current_app_info')}}</h4>
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
                                                                    <label class="form-label" for="current_version">{{ __('current_version') }} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" id="current_version" value="{{ settingHelper('current_version') }}" name="current_version"  required>
                                                                    </div>
                                                                    @if($errors->has('current_version'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('current_version') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="update_skipable">{{ __('update_skipable') }} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <select name="update_skipable" class="form-control select2" id="update_skipable">
                                                                            <option {{ (settingHelper('update_skipable') == "true") ? "selected":"" }}  selected value="true">Yes</option>
                                                                            <option {{ (settingHelper('update_skipable') == "false") ? "selected":"" }} value="false">No</option>
                                                                        </select>
                                                                    </div>
                                                                    @if($errors->has('update_skipable'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('update_skipable') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="update_url">{{ __('update_url') }} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" id="update_url" value="{{ settingHelper('update_url') }}" name="update_url"  required>
                                                                    </div>
                                                                    @if($errors->has('update_url'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('update_url') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="phone_visible">{{ __('phone_visible') }} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <select name="phone_visible" class="form-control select2" id="phone_visible">
                                                                            <option {{ (settingHelper('phone_visible') == "true") ? "selected":"" }}  selected value="true">Yes</option>
                                                                            <option {{ (settingHelper('phone_visible') == "false") ? "selected":"" }} value="false">No</option>
                                                                        </select>
                                                                    </div>
                                                                    @if($errors->has('phone_visible'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('phone_visible') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 text-right mt-4">
                                                                <div class="form-group">
                                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('update')}}}</button>
                                                                </div>
                                                            </div>
                                                        </div>

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
@push('script')
<script>
    $('select').select2();
</script>
@endpush
