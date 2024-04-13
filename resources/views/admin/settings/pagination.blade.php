@extends('master')

@section('title')
    {{__('pagination')}}
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
                                                <h4 class="nk-block-title">{{__('item_per_page')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(hasPermission('pagination_setting_update'))
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
                                                                    <label class="form-label" for="paginate_all_list">{{__('web_list')}}({{ __('except_parcel_and_merchant') }}) *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="number" class="form-control" id="paginate_all_list" value="{{ settingHelper('paginate_all_list') }}" name="paginate_all_list" min="1" required>
                                                                    </div>
                                                                    @if($errors->has('paginate_all_list'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('paginate_all_list') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="paginate_parcel_merchant_list">{{__('web_parcel_merchant_list')}} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="number" class="form-control" id="paginate_parcel_merchant_list" value="{{ settingHelper('paginate_parcel_merchant_list') }}" name="paginate_parcel_merchant_list" min="1" required>
                                                                    </div>
                                                                    @if($errors->has('paginate_parcel_merchant_list'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('paginate_parcel_merchant_list') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="paginate_api_list">{{__('mobile_app_list')}} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="number" class="form-control" id="paginate_api_list" value="{{ settingHelper('paginate_api_list') }}" name="paginate_api_list" min="1" required>
                                                                    </div>
                                                                    @if($errors->has('paginate_api_list'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('paginate_api_list') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(hasPermission('pagination_setting_update'))
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
                                    @if(hasPermission('pagination_setting_update'))
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
