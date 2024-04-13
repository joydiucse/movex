@extends('master')

@section('title')
    {{__('charges')}}
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
                                                <h4 class="nk-block-title">{{__('charges')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    @if(hasPermission('charge_setting_update'))
                                    <form action="{{ route('setting.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                                        @csrf
                                    @endif
                                        <div class="card shadow-none">

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card-inner">
                                                        <div class="card-title">
                                                           <h5>{{ __('parcel_return_charges') }}</h5>
                                                        </div>

                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label">{{ __('select').' '.__('return_charge_type') }} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <select class="form-select form-control form-control-lg return-charge-type" name="return_charge_type" required>
                                                                            <option value="">{{ __('select_type') }}</option>
                                                                            <option value="on_demand" {{ settingHelper('return_charge_type') == 'on_demand' ? 'selected' : ''}}>{{ __('on_demand') }}</option>
                                                                            <option value="full_delivery_charge" {{ settingHelper('return_charge_type') == 'full_delivery_charge' ? 'selected' : ''}}>{{ __('full_delivery_charge') }}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="charges {{ settingHelper('return_charge_type') == 'on_demand' ? '' : 'd-none' }}">
                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="return_charge_dhaka">{{ __('dhaka') }} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" class="form-control" id="return_charge_dhaka" value="{{ settingHelper('return_charge_dhaka') }}" name="return_charge_dhaka" required>
                                                                        </div>
                                                                        @if($errors->has('return_charge_dhaka'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('return_charge_dhaka') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="return_charge_sub_city">{{ __('sub_city') }} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" class="form-control" id="return_charge_sub_city" value="{{ settingHelper('return_charge_sub_city') }}" name="return_charge_sub_city" required>
                                                                        </div>
                                                                        @if($errors->has('return_charge_sub_city'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('return_charge_sub_city') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="return_charge_outside_dhaka">{{ __('outside_dhaka') }} *</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" class="form-control" id="return_charge_outside_dhaka" value="{{ settingHelper('return_charge_outside_dhaka') }}" name="return_charge_outside_dhaka" required>
                                                                        </div>
                                                                        @if($errors->has('return_charge_outside_dhaka'))
                                                                            <div class="nk-block-des text-danger">
                                                                                <p>{{ $errors->first('return_charge_outside_dhaka') }}</p>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-title mt-3">
                                                            <h5>{{ __('other_charges') }}</h5>
                                                        </div>
                                                        <div class="row g-gs">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="fragile_charge">{{__('fragile_charge')}} *</label>
                                                                    <div class="form-control-wrap">
                                                                        <input type="text" class="form-control" id="fragile_charge" value="{{ settingHelper('fragile_charge') }}" name="fragile_charge" required>
                                                                    </div>
                                                                    @if($errors->has('fragile_charge'))
                                                                        <div class="nk-block-des text-danger">
                                                                            <p>{{ $errors->first('fragile_charge') }}</p>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @if(hasPermission('charge_setting_update'))
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
                                    @if(hasPermission('charge_setting_update'))
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
