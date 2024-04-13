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
                                                <h4 class="nk-block-title">{{__('shops')}}</h4>
                                                <div class="nk-block-des">
                                                </div>
                                            </div>
                                            @if(hasPermission('merchant_shop_create'))
                                                <div class="d-flex">
                                                    <a href="{{ route('detail.merchant.staff.create', $merchant->id) }}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
                                                    <div class="nk-block-head-content align-self-start d-lg-none">
                                                        <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block card">
                                        <table class="table table-ulogs">
                                            <thead class="thead-light">
                                            <tr class="shops-profile">
                                                <th class="tb-col-os"><span class="overline-title">#</span></th>
                                                <th class="tb-col-os"><span class="overline-title">{{__('staff')}}</span></th>
                                                <th class="tb-col-os tb-col-lg"><span class="overline-title">{{__('current_balance')}}</span></th>
                                                <th class="tb-col-os"><span class="overline-title">{{__('phone_number')}}</span></th>
                                                <th class="tb-col-os tb-col-lg"><span class="overline-title">{{__('last_login')}}</span></th>
                                                <th class="tb-col-os"><span class="overline-title">{{__('status')}}</span></th>
                                                @if(hasPermission('merchant_staff_update'))
                                                    <th class="tb-col-os"><span class="overline-title">{{__('options')}}</span></th>
                                                @endif
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($staffs as $key => $staff)
                                                <tr id="row_{{$staff->id}}" class="shops-profile">
                                                    <td class="tb-col-os"><span class="text-capitalize">{{$key + 1}}</span></td>
                                                    <td class="tb-col-os">
                                                        <a href="{{ route('detail.merchant.staff.personal.info', $staff->id) }}">
                                                            <div class="user-card">
                                                                <div class="user-avatar sm bg-primary">
                                                                    @if(!blank($staff->image) && file_exists($staff->image->image_small_one))
                                                                        <img src="{{asset($staff->image->image_small_one)}}" alt="{{$staff->first_name}}">
                                                                    @else
                                                                        <img src="{{asset('admin/images/default/user32x32.jpg')}}" alt="{{$staff->first_name}}">
                                                                    @endif
                                                                </div>
                                                                <div class="user-info d-block">
                                                                    <span class="tb-lead">{{$staff->first_name.' '.$staff->last_name}}</span> <br>
                                                                    <span>{{ $staff->email }}</span>
                                                                </div>
                                                            </div>
                                                        </a>
                                                    <td class="tb-col-os tb-col-lg"><span>{{ number_format($staff->balance($staff->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small> </span></td>
                                                    <td class="tb-col-os"><span>{{ $staff->phone_number }} </span></td>
                                                    <td class="tb-col-os tb-col-lg"><span>{{$staff->last_login != ""? date('M d, Y h:i a', strtotime($staff->last_login)):''}}</span></td>

                                                    <td class="tb-col-os">
                                                        <span>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox" class="custom-control-input {{ hasPermission('merchant_staff_update') ? 'status-change': ''}}" {{ $staff->status ? 'checked' :'' }} value="user-status/{{$staff->id}}" id="customSwitch2-{{$staff->id}}">
                                                                <label class="custom-control-label" for="customSwitch2-{{$staff->id}}"></label>
                                                            </div>
                                                        </span>
                                                    </td>
                                                    <td class="tb-col-os">
                                                        @if(hasPermission('merchant_staff_update'))
                                                            <div class="tb-odr-btns d-md-inline">
                                                                <a href="{{route('detail.merchant.staff.edit', $staff->id)}}"  class="btn btn-sm btn-info" data-id="{{ $staff->id }}"><em class="icon ni ni-edit"></em></a>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @include('admin.merchants.details.sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
@include('common.change-status-ajax')
