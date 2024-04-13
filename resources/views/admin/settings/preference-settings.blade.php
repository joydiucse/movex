@extends('master')

@section('title')
    {{__('preference')}}
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
                                                <h4 class="nk-block-title">{{__('preference')}}</h4>
                                            </div>

                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="card shadow-none">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card-inner">
                                                            <div class="card-title mt-3">
                                                                <h5>{{ __('services_and_permissions') }}</h5>
                                                            </div>
                                                            <div class="row g-gs">
                                                                <table class="table table-borderless m-2">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">{{ __('title') }}</th>
                                                                        <th scope="col">{{ __('staff') }}</th>
                                                                        <th scope="col">{{ __('merchant') }}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <div class="g-item">
                                                                        @foreach(settingHelper('preferences')->whereNotIn('title', ['same_day','next_day','sub_city','outside_dhaka'])  as $preference)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ __($preference->title) }}
                                                                                </td>
                                                                                <td>
                                                                                    @if($preference->title != 'read_merchant_api')
                                                                                        <div class="custom-control custom-switch">
                                                                                            <input type="checkbox" class="custom-control-input {{ hasPermission('preference_setting_update') ? 'status-change':'' }}" {{ $preference->staff == true ? 'checked' : '' }} value="preference-status/{{ $preference->id }}" data-change-for="staff" id="customSwitch-{{ $preference->id }}">
                                                                                            <label class="custom-control-label" for="customSwitch-{{ $preference->id }}"></label>
                                                                                        </div>
                                                                                    @endif
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input  {{ hasPermission('preference_setting_update') ? 'status-change':'' }}"  {{ $preference->merchant == true ? 'checked' : '' }} value="preference-status/{{ $preference->id }}+" data-change-for="merchant" id="customSwitchMask-{{ $preference->id }}">
                                                                                        <label class="custom-control-label" for="customSwitchMask-{{ $preference->id }}"></label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </div>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="card-title mt-5">
                                                                <h5>{{ __('parcel_delivery') }}</h5>
                                                            </div>
                                                            <div class="row g-gs">
                                                                <table class="table table-borderless m-2">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">{{ __('title') }}</th>
                                                                        <th scope="col">{{ __('staff') }}</th>
                                                                        <th scope="col">{{ __('merchant') }}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <div class="g-item">
                                                                        @foreach(settingHelper('preferences')->whereIn('title', ['same_day','next_day','sub_city','outside_dhaka'])  as $preference)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ __($preference->title) }}
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input {{ hasPermission('preference_setting_update') ? 'status-change':'' }}" {{ $preference->staff == true ? 'checked' : '' }} value="preference-status/{{ $preference->id }}" data-change-for="staff" id="customSwitch-{{ $preference->id }}">
                                                                                        <label class="custom-control-label" for="customSwitch-{{ $preference->id }}"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input  {{ hasPermission('preference_setting_update') ? 'status-change':'' }}"  {{ $preference->merchant == true ? 'checked' : '' }} value="preference-status/{{ $preference->id }}+" data-change-for="merchant" id="customSwitchMask-{{ $preference->id }}">
                                                                                        <label class="custom-control-label" for="customSwitchMask-{{ $preference->id }}"></label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </div>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group ml-4">
                                                            <label class="form-label">{{ __('delivery_otp_permission') }} *</label>
                                                            <div class="form-control-wrap">
                                                                <select  class="form-control form-control-lg otp-permission" onchange="deliveryOTPPermission(this.value)">
                                                                    @php $otpPermission = settingHelper('delivery_otp'); @endphp
                                                                   
                                                                    <option {{ ($otpPermission->value == 'all') ? 'selected' : '' }} value="all">{{__('all')}} </option>
                                                                    <option {{ ($otpPermission->value == 'conditional') ? 'selected' : '' }} value="conditional">{{__('conditional')}}</option>
                                                                    <option {{ ($otpPermission->value == 'none') ? 'selected' : '' }} value="none">{{__('none')}}</option>
                                                                    
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                </div>

                                @include('admin.settings.sidebar')
                                @include('admin.preference.change-status-ajax')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
