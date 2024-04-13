@extends('master')

@section('title')
    {{__('sms_preference_setting')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('sms_preference_setting')}}</h3>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">

                        <div class="card">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="card-aside-wrap">

                                                    <div class="card-inner card-inner-lg">
                                                        <div class="nk-block-head nk-block-head-sm">
                                                            <div class="nk-block-head-content">
                                                                <h6>{{ __('parcel').' '.__('status').' '.__('sms_to_merchant') }}</h6>
                                                                <p>{{ __('merchant_will_receive_parcel_event_sms') }}</p>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block-content">
                                                            <div class="gy-3">
                                                                <table class="table table-borderless">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">{{ __('event') }}</th>
                                                                        <th scope="col">{{ __('status') }}</th>
                                                                        <th scope="col">{{ __('masking') }}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <div class="g-item">
                                                                            @foreach($sms_templates as $event)
                                                                                <tr>
                                                                                    <td>
                                                                                        {{ __($event->subject) }}
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="custom-control custom-switch">
                                                                                            <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}" {{ $event->sms_to_merchant ? 'checked' :'' }} value="sms-status/{{$event->id}}" data-change-for="sms_to_merchant" id="customSwitch-{{$event->id}}">
                                                                                            <label class="custom-control-label" for="customSwitch-{{$event->id}}"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="custom-control custom-switch">
                                                                                            <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}"  {{ $event->masking ? 'checked' :'' }} value="sms-masking-status/{{$event->id}}" data-change-for="sms_to_merchant" id="customSwitchMask-{{$event->id}}">
                                                                                            <label class="custom-control-label" for="customSwitchMask-{{$event->id}}"></label>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </div>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div><!-- .nk-block-content -->
                                                    </div>

                                                </div><!-- .card-aside-wrap -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card-aside-wrap">

                                                    <div class="card-inner card-inner-lg">
                                                        <div class="nk-block-head nk-block-head-sm">
                                                            <div class="nk-block-head-content">
                                                                <h6>{{ __('parcel').' '.__('status').' '.__('sms_to_customer') }}</h6>
                                                                <p>{{ __('customer_will_receive_parcel_event_sms') }}</p>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block-content">
                                                            <div class="gy-3">
                                                                <table class="table table-borderless">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">{{ __('event') }}</th>
                                                                        <th scope="col">{{ __('status') }}</th>
                                                                        <th scope="col">{{ __('masking') }}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <div class="g-item">
                                                                        @foreach($customer_sms_templates as $event)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ __($event->subject) }}
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}" {{ $event->sms_to_customer ? 'checked' :'' }} value="sms-status/{{$event->id}}" data-change-for="sms_to_customer" id="customSwitch2-{{$event->id}}">
                                                                                        <label class="custom-control-label" for="customSwitch2-{{$event->id}}"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}" {{ $event->masking ? 'checked' :'' }} value="sms-masking-status/{{$event->id}}" data-change-for="sms_to_customer" id="customSwitchMask2-{{$event->id}}">
                                                                                        <label class="custom-control-label" for="customSwitchMask2-{{$event->id}}"></label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="g-item">
                                                                        @foreach($otp_sms_preference as $event)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ __($event->subject) }}
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input disabled type="checkbox" class="custom-control-input" {{ $event->sms_to_customer ? 'checked' :'' }}  id="customSwitch2-{{$event->id}}">
                                                                                        <label class="custom-control-label" for="customSwitch2-{{$event->id}}"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}" {{ $event->masking ? 'checked' :'' }} value="sms-masking-status/{{$event->id}}" data-change-for="sms_to_customer" id="customSwitchMask2-{{$event->id}}">
                                                                                        <label class="custom-control-label" for="customSwitchMask2-{{$event->id}}"></label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </div>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div><!-- .nk-block-content -->
                                                    </div>

                                                </div><!-- .card-aside-wrap -->
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card-aside-wrap">

                                                    <div class="card-inner card-inner-lg">
                                                        <div class="nk-block-head nk-block-head-sm">
                                                            <div class="nk-block-head-content">
                                                                <h6>{{ __('payment').' '.__('status').' '.__('sms_to_merchant') }}</h6>
                                                                <p>{{ __('merchant_will_receive_withdraw_event_sms') }}</p>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block-content">
                                                            <div class="gy-3">
                                                                <table class="table table-borderless">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">{{ __('event') }}</th>
                                                                        <th scope="col">{{ __('status') }}</th>
                                                                        <th scope="col">{{ __('masking') }}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <div class="g-item">
                                                                        @foreach($withdraw_sms_templates as $event)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ __($event->subject) }}
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}" {{ $event->sms_to_merchant ? 'checked' :'' }} value="sms-status/{{$event->id}}" data-change-for="withdraw_sms" id="customSwitch3-{{$event->id}}">
                                                                                        <label class="custom-control-label" for="customSwitch3-{{$event->id}}"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}" {{ $event->masking ? 'checked' :'' }} value="sms-masking-status/{{$event->id}}" data-change-for="withdraw_sms" id="customSwitchMask3-{{$event->id}}">
                                                                                        <label class="custom-control-label" for="customSwitchMask3-{{$event->id}}"></label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </div>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div><!-- .nk-block-content -->
                                                    </div>

                                                </div><!-- .card-aside-wrap -->
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card-aside-wrap">

                                                    <div class="card-inner card-inner-lg">
                                                        <div class="nk-block-head nk-block-head-sm">
                                                            <div class="nk-block-head-content">
                                                                <h6>{{ __('name_changes').' '.__('status').' '.__('sms_to_merchant') }}</h6>
                                                                <p>{{ __('merchant_will_receive_name_change_event_sms') }}</p>
                                                            </div>
                                                        </div><!-- .nk-block-head -->
                                                        <div class="nk-block-content">
                                                            <div class="gy-3">
                                                                <table class="table table-borderless">
                                                                    <thead>
                                                                    <tr>
                                                                        <th scope="col">{{ __('event') }}</th>
                                                                        <th scope="col">{{ __('status') }}</th>
                                                                        <th scope="col">{{ __('masking') }}</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <div class="g-item">
                                                                        @foreach($name_sms_template as $event)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ __($event->subject) }}
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}" {{ $event->sms_to_merchant ? 'checked' :'' }} value="sms-status/{{$event->id}}" data-change-for="name_sms" id="customSwitch4-{{$event->id}}">
                                                                                        <label class="custom-control-label" for="customSwitch4-{{$event->id}}"></label>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div class="custom-control custom-switch">
                                                                                        <input type="checkbox" class="custom-control-input {{ hasPermission('sms_setting_update') ? 'status-change':'' }}" {{ $event->masking ? 'checked' :'' }} value="sms-masking-status/{{$event->id}}" data-change-for="name_sms" id="customSwitchMask4-{{$event->id}}">
                                                                                        <label class="custom-control-label" for="customSwitchMask4-{{$event->id}}"></label>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </div>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div><!-- .nk-block-content -->
                                                    </div>

                                                </div><!-- .card-aside-wrap -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.preference.change-status-ajax')
