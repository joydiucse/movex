@extends('master')

@section('title')
    {{__('return').' '.__('lists')}}
@endsection
@section('style')
    <style>
        .text-fliter-btn{
            color: #8599b1!important;
        }
    </style>
@endsection

@php
    $pn = isset($_GET['pn']) ? $_GET['pn'] : null;
    $phone_no = isset($_GET['phone_no']) ? $_GET['phone_no'] : null;
@endphp

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Returned Parcels List</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('found')}} {{ $parcels->total() }} {{__('parcel')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            {{--<div class="nk-block-head-content">
                                @if (@settingHelper('preferences')->where('title','create_parcel')->first()->merchant)
                                    <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.import.csv') : route('merchant.staff.import.csv') }}" class="btn btn-primary btn-custom-action d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('import')}}</span></a>
                                    <a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.create') : route('merchant.staff.parcel.create')}}" class="btn btn-primary btn-custom-action  d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('create_parcel')}}</span></a>
                                @else
                                    <button class="btn btn-danger d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('create').' '.__('parcel').' ('.__('service_unavailable').')'}}</span></button>
                                @endif
                            </div>--}}<!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner position-relative card-tools-toggle">
                                    {{--<div class="row filter-button mb-2">
                                        <div class="col-md-12 text-center">
                                            <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel') : route('merchant.staff.parcel') }}" class="badge {{isset($slug)? 'text-fliter-btn':'btn-primary'}}"><span>{{__('all')}}</span></a>

                                            <a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.filtering', 'pending') : route('merchant.staff.parcel.filtering', 'pending')}}" class="badge {{isset($slug)? ($slug == 'pending'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{__('pending')}}</span></a>
                                            --}}{{--                                                    <a href="{{route('merchant.parcel.filtering', 'pickup-assigned')}}" class="badge {{isset($slug)? ($slug == 'pickup-assigned'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('pickup-assigned') }}</span></a>--}}{{--
                                            --}}{{--                                                    <a href="{{route('merchant.parcel.filtering', 're-schedule-pickup')}}" class="badge {{isset($slug)? ($slug == 're-schedule-pickup'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('re-schedule-pickup') }}</span></a>--}}{{--
                                            <a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.filtering', 'received') : route('merchant.staff.parcel.filtering', 'received')}}" class="badge {{isset($slug)? ($slug == 'received'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('received_by_warehouse') }}</span></a>
                                            --}}{{--                                                    <a href="{{route('merchant.parcel.filtering', 'delivery-assigned')}}" class="badge {{isset($slug)? ($slug == 'delivery-assigned'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('delivery-assigned') }}</span></a>--}}{{--
                                            --}}{{--        --}}{{--
                                            --}}{{--                                                    <a href="{{route('merchant.parcel.filtering', 're-schedule-delivery')}}" class="badge {{isset($slug)? ($slug == 're-schedule-delivery'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('re-schedule-delivery') }}</span></a>--}}{{--
                                            --}}{{--                                                    <a href="{{route('merchant.parcel.filtering', 'returned-to-greenx')}}" class="badge {{isset($slug)? ($slug == 'returned-to-greenx'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('returned-to-greenx') }}</span></a>--}}{{--
                                            --}}{{--                                                    <a href="{{route('merchant.parcel.filtering', 'return-assigned-to-merchant')}}" class="badge {{isset($slug)? ($slug == 'return-assigned-to-merchant'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('return-assigned-to-merchant') }}</span></a>--}}{{--
                                            <a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.filtering', 'delivered') : route('merchant.staff.parcel.filtering', 'delivered')}}" class="badge {{isset($slug)? ($slug == 'delivered'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('delivered') }}</span></a>
                                            --}}{{--                                                    <a href="{{route('merchant.parcel.filtering', 'returned-to-merchant')}}" class="badge {{isset($slug)? ($slug == 'returned-to-merchant'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('returned-to-merchant') }}</span></a>--}}{{--
                                            <a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.filtering', 'cancel') : route('merchant.staff.parcel.filtering', 'cancel')}}" class="badge {{isset($slug)? ($slug == 'cancel'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('cancelled') }}</span></a>
                                            <a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.filtering', 'deleted') :  route('merchant.staff.parcel.filtering', 'deleted')}}" class="badge {{isset($slug)? ($slug == 'deleted'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('deleted') }}</span></a>
                                        </div>
                                    </div>--}}
                                    <div class="card-title-group">
                                        <div class="card-tools">
                                            <div class="d-block">
                                                <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.return-list') : route('merchant.staff.parcel') }}">
                                                    {{--                                                    @csrf--}}
                                                    {{ __('search') }}
                                                    <div class="mulipul-src-field d-flex">
                                                        <input type="text" name="pn" class="form-control input-parcel" value="{{ $pn }}" autofocus placeholder="AGEWGGWF1215" id="parcel_no">
                                                        <input type="text" name="phone_no" value="{{ $phone_no }}" class="form-control ml-2" placeholder="01711111111" id="phone_no">
                                                        <input type="submit" class="btn btn-primary" value="{{__('search') }}" />

                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- .card-tools -->
                                        <div class="card-tools mr-n1">
                                            <ul class="btn-toolbar gx-1">
                                                <li>
                                                    <form action="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.return-list') : route('merchant.staff.parcel.filter')}}" method="GET">
{{--                                                        @csrf--}}
                                                        <ul class="btn-toolbar gx-1">
                                                            <li>
                                                                <div class="dropdown">
                                                                    <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                                                        <div class="dot dot-primary"></div>
                                                                        <em class="icon ni ni-filter-alt"></em>
                                                                    </a>
                                                                    <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                                                        <div class="dropdown-head">
                                                                            <span class="sub-title dropdown-title">{{__('filter')}}</span>

                                                                        </div>
                                                                        <div class="dropdown-body dropdown-body-rg">
                                                                            <div class="row gx-6 gy-3">
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <input type="text" class="form-control" name="customer_name" placeholder="{{__('enter').' '.__('customer_name')}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <input type="text" class="form-control" name="customer_invoice_no" placeholder="{{__('enter').' '.__('invoice_no')}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <input type="text" class="form-control" name="phone_number" placeholder="{{__('enter').' '.__('phone_number')}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <div class="form-control-wrap">
                                                                                            <div class="form-icon form-icon-right">
                                                                                                <em class="icon ni ni-calendar-alt"></em>
                                                                                            </div>
                                                                                            <input type="text" name="created_from" class="form-control date-picker" id="created_from">
                                                                                            <label class="form-label-outlined" for="created_from">{{ __('from') }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <div class="form-control-wrap">
                                                                                            <div class="form-icon form-icon-right">
                                                                                                <em class="icon ni ni-calendar-alt"></em>
                                                                                            </div>
                                                                                            <input type="text" name="created_to" class="form-control date-picker" id="created_to">
                                                                                            <label class="form-label-outlined" for="created_to">{{ __('to') }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                {{-- <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <div class="form-control-wrap">
                                                                                            <div class="form-icon form-icon-right">
                                                                                                <em class="icon ni ni-calendar-alt"></em>
                                                                                            </div>
                                                                                            <input type="text" name="pickup_date" class="form-control date-picker" id="pickup_date">
                                                                                            <label class="form-label-outlined" for="pickup_date">{{ __('pickup_date') }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <div class="form-control-wrap">
                                                                                            <div class="form-icon form-icon-right">
                                                                                                <em class="icon ni ni-calendar-alt"></em>
                                                                                            </div>
                                                                                            <input type="text" name="delivery_date" class="form-control date-picker" id="delivery_date">
                                                                                            <label class="form-label-outlined" for="delivery_date">{{ __('delivery_date') }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <div class="form-control-wrap">
                                                                                            <div class="form-icon form-icon-right">
                                                                                                <em class="icon ni ni-calendar-alt"></em>
                                                                                            </div>
                                                                                            <input type="text" name="delivered_date" class="form-control date-picker" id="delivered_date">
                                                                                            <label class="form-label-outlined" for="delivered_date">{{ __('delivered_date') }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div> --}}
                                                                                {{--<div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-select-sm" name="status">
                                                                                            <option value="any">{{__('any_status')}}</option>
                                                                                            @foreach(\Config::get('greenx.parcel_status') as $parcel_status)
                                                                                                <option value="{{ $parcel_status }}">{{ __($parcel_status) }}</option>
                                                                                            @endforeach
                                                                                            <option value="pending-return">{{ __('pending-return') }}</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>--}}
                                                                                {{-- <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-select-sm" name="weight">
                                                                                            <option value="any">{{__('any_weight')}}</option>
                                                                                            @foreach($charges as $charge)
                                                                                                <option value="{{ $charge->weight }}">{{ $charge->weight }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div> --}}
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-select-sm" name="parcel_type">
                                                                                            <option value="any">{{__('any_type')}}</option>
                                                                                            <option value="same_day">{{ __('same_day') }}</option>
                                                                                            <option value="next_day">{{ __('next_day') }}</option>
                                                                                            <option value="sub_city">{{ __('sub_city') }}</option>
                                                                                            <option value="outside_dhaka">{{ __('outside_dhaka') }}</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-select-sm" name="location">
                                                                                            <option value="any">{{__('any_location')}}</option>
                                                                                            @foreach($cod_charges as $cod_charge)
                                                                                                <option value="{{ $cod_charge->location }}">{{ __($cod_charge->location) }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 text-right">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <div class="form-group">
                                                                                            <button type="submit" name="download" value="1" class="btn btn-primary">{{__('download')}}</button>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <button type="submit" class="btn btn-primary">{{__('filter')}}</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .filter-wg -->
                                                                </div><!-- .dropdown -->
                                                            </li><!-- li -->
                                                        </ul><!-- .btn-toolbar -->
                                                    </form>
                                                </li><!-- li -->
                                            </ul><!-- .btn-toolbar -->
                                        </div><!-- .card-tools -->
                                    </div><!-- .card-title-group -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-0">
                                    <div class="nk-tb-list nk-tb-ulist custom-font-size">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('no')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('customer')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>

                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                        </div>
                                        <!-- .nk-tb-item -->
                                        @php
                                                $currentPage = $parcels->currentPage();
                                                $paginationSize = $parcels->perPage();
                                        @endphp
                                        @foreach($parcels as $key => $parcel)
                                            <div class="nk-tb-item" id="row_{{$parcel->id}}">
                                                <input type="hidden" value="{{$parcel->id}}" id="id">
                                                <div class="nk-tb-col">
                                                    <span class="pl-3">{{($currentPage-1)*$paginationSize + $key + 1}}</span>
                                                </div>

                                                <div class="nk-tb-col">
                                                    <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.detail',$parcel->parcel_no) : route('merchant.staff.parcel.detail',$parcel->parcel_no) }}">
                                                        <table>
                                                            <tr><td>{{__('id')}}:#{{ $parcel->parcel_no }}</td></tr>
                                                            <tr><td>{{__('invno')}}:{{$parcel->customer_invoice_no}}</td></tr>
                                                            {{--<tr><td>{{$parcel->created_at != ""? date('M d, Y h:i a', strtotime($parcel->created_at)):''}}</td></tr>--}}
                                                            {{--@if($parcel->parcel_type == 'frozen')
                                                                <tr><td class="text-primary">{{__('pickup')}}: {{$parcel->pickup_date != ""? date('M d, Y', strtotime($parcel->pickup_date)):''}} {{$parcel->pickup_time != ""? date('h:i a', strtotime($parcel->pickup_time)):''}}</td></tr>
                                                                <tr><td class="text-primary">{{__('delivery')}}: {{$parcel->delivery_date != ""? date('M d, Y', strtotime($parcel->delivery_date)):''}} {{$parcel->pickup_time != ""? date('h:i a', strtotime($parcel->pickup_time)):''}}</td></tr>
                                                            @else
                                                                <tr><td class="text-primary">{{__('pickup')}}: {{$parcel->pickup_date != ""? date('M d, Y', strtotime($parcel->pickup_date)):''}}</td></tr>
                                                                <tr><td class="text-primary">{{__('delivery')}}: {{$parcel->delivery_date != ""? date('M d, Y', strtotime($parcel->delivery_date)):''}}</td></tr>
                                                            @endif
                                                            @if($parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified')
                                                                <tr><td class="text-primary">{{__('delivered_at')}}: {{$parcel->event != ""? date('M d, Y g:i A', strtotime($parcel->event->created_at)):''}}</td></tr>
                                                                <tr><td class="text-primary">{{__('delivered_by')}}: {{$parcel->deliveryMan->user->first_name.' '.$parcel->deliveryMan->user->last_name}}</td></tr>
                                                            @endif
                                                            @if($parcel->status == 'received')
                                                                <tr><td class="text-primary">{{__('pickup_by')}}: {{$parcel->pickupMan->user->first_name.' '.$parcel->pickupMan->user->last_name}}</td></tr>
                                                            @endif
                                                            @if($parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup')
                                                                --}}{{--                                                                .date('M d, Y g:i A', strtotime($parcel->pickupPerson->created_at))--}}{{--
                                                                <tr><td class="text-primary">{{__('pickup_man')}}: {{$parcel->pickupMan != ""? ($parcel->pickupMan->user->first_name.' '.$parcel->pickupMan->user->last_name ):''}}</td></tr>
                                                            @endif
                                                            @if($parcel->status == 'return-assigned-to-merchant')
                                                                <tr><td class="text-primary">{{__('returned_at')}}: {{$parcel->event != ""? date('M d, Y h:i a', strtotime($parcel->event->created_at)):''}}</td></tr>
                                                                <tr><td class="text-primary">{{__('return_delivery_man')}}: {{$parcel->returnDeliveryMan != ""? ($parcel->returnDeliveryMan->user->first_name.' '.$parcel->returnDeliveryMan->user->last_name ):''}}</td></tr>
                                                            @endif--}}
                                                            @if($parcel->status == 'returned-to-merchant')
                                                                <tr><td class="text-primary">{{__('returned_at')}}: {{$parcel->returnEvent != ""? date('M d, Y h:i a', strtotime($parcel->returnEvent->created_at)):''}}</td></tr>
                                                                <tr><td class="text-primary">{{__('returned_by')}}: {{$parcel->returnDeliveryMan != ""? ($parcel->returnDeliveryMan->user->first_name.' '.$parcel->returnDeliveryMan->user->last_name ):''}}</td></tr>
                                                                <tr><td class="text-primary">{{__('Mobile')}}: {{$parcel->returnDeliveryMan != ""? $parcel->returnDeliveryMan->user->phone_number :''}}</td></tr>
                                                            @endif
                                                            {{--@if(($parcel->status == 'delivery-assigned' || $parcel->status == 're-schedule-delivery') && $parcel->location == 'dhaka')
                                                                <tr><td class="text-primary">{{__('delivery_man')}}: {{$parcel->deliveryMan != ""? ($parcel->deliveryMan->user->first_name.' '.$parcel->deliveryMan->user->last_name ):''}}</td></tr>
                                                            @endif--}}
                                                        </table>
                                                    </a>
                                                </div>

                                                <div class="nk-tb-col tb-col-lg column-max-width">
                                                    <table width="70%">
                                                        <tr><td>{{ (@$parcel->merchant_id == 1802 && $parcel->user->user_type == 'merchant_staff' ) ? @$parcel->user->first_name.' '.@$parcel->user->last_name : @$parcel->merchant->company }}</td></tr>
                                                        <tr><td>{{ (@$parcel->merchant_id == 1802 && $parcel->user->user_type == 'merchant_staff' ) ? $parcel->user->phone_number : $parcel->merchant->phone_number}}</td></tr>
                                                        <tr><td>{{ @$parcel->pickup_address }}</td></tr>
                                                        <tr>
                                                            <td>
                                                                <table class="text-primary" width="100%">
                                                                    <tr>
                                                                        <td width="50%">{{__('weight').': '. $parcel->weight . __('kg')}}</td>
                                                                        <td width="50%">{{__('charge').': '. number_format($parcel->total_delivery_charge,2).__('tk')}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="50%">{{__('COD').': '. number_format($parcel->price,2) . __('tk')}}</td>
                                                                        <td width="50%">{{__('payable').': '. number_format($parcel->payable,2).__('tk')}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td width="50%">{{__('selling_price').': '. number_format($parcel->selling_price,2).__('tk')}}</td>
                                                                    </tr>
                                                                </table>

                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg column-max-width">
                                                    <table width="70%">
                                                        <tr><td>{{ @$parcel->customer_name }}</td></tr>
                                                        <tr><td>{{ @$parcel->customer_phone_number }}</td></tr>
                                                        <tr><td>{{ @$parcel->customer_address }}</td></tr>
                                                        <tr>
                                                            <td>
                                                                <table class="text-primary" width="100%">
                                                                    <tr>
                                                                        <td width="50%">{{__('location').': '. __($parcel->location)}}</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="nk-tb-col column-max-width">
                                                    {{--@if($parcel->status == 'pending')
                                                        <span  class="badge text-warning">{{ __($parcel->status) }}</span><br>
                                                    @elseif($parcel->status == 'deleted')
                                                        <span  class="badge text-danger">{{ __('deleted') }}</span><br>
                                                    @elseif($parcel->status == 'cancel')
                                                        <span  class="badge text-danger">{{ __('cancelled') }}</span><br>
                                                    @elseif($parcel->status == 'pickup-assigned')
                                                        <span  class="badge text-pink">{{ __('pickup-assigned') }}</span><br>
                                                    @elseif($parcel->status == 're-schedule-pickup')
                                                        <span  class="badge text-purple">{{ __('re-schedule-pickup') }}</span><br>
                                                    @elseif($parcel->status == 'received-by-pickup-man')
                                                        <span  class="badge text-yale">{{ __('received-by-pickup-man') }}</span><br>
                                                    @elseif($parcel->status == 'received')
                                                        <span  class="badge text-blue">{{ __('received_by_warehouse') }}</span><br>
                                                    @elseif($parcel->status == 'transferred-to-hub')
                                                        <span  class="badge text-pigeon">{{ __('transferred-to-hub') }}</span><br>
                                                    @elseif($parcel->status == 'transferred-received-by-hub')
                                                        <span  class="badge text-prussian">{{ __('transferred-received-by-hub') }}</span><br>
                                                    @elseif($parcel->status == 'delivery-assigned')
                                                        @if($parcel->location == 'dhaka')
                                                            <span  class="badge text-pear">{{ __('delivery-assigned') }}</span><br>
                                                        @else
                                                            <span  class="badge text-pear">{{ __('in_transit') }}</span><br>
                                                        @endif
                                                    @elseif($parcel->status == 're-schedule-delivery')
                                                        <span  class="badge text-brown">{{ __('re-schedule-delivery') }}</span><br>
                                                    @elseif($parcel->status == 'returned-to-greenx')
                                                        @if($parcel->is_partially_delivered)
                                                            <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                        @endif
                                                        <span  class="badge text-warning">{{ __('returned-to-greenx') }}</span><br>
                                                    @elseif($parcel->status == 'return-assigned-to-merchant')
                                                        @if($parcel->is_partially_delivered)
                                                            <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                        @endif
                                                        <span  class="badge text-indigo">{{ __('return-assigned-to-merchant') }}</span><br>
                                                    @elseif($parcel->status == 'partially-delivered')
                                                        <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                    @elseif($parcel->status == 'delivered')
                                                        <span  class="badge text-success">{{ __('delivered') }}</span><br>
                                                    @elseif($parcel->status == 'delivered-and-verified')
                                                        <span  class="badge text-success">{{ __('delivered-and-verified') }}</span><br>
                                                    @elseif($parcel->status == 'returned-to-merchant')
                                                        @if($parcel->is_partially_delivered)
                                                            <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                        @endif
                                                        <span  class="badge text-success">{{ __('returned-to-merchant') }}</span><br>
                                                    @elseif($parcel->status == 're-request')
                                                        <span  class="badge text-warning">{{ __('re-request') }}</span><br>
                                                    @endif--}}
                                                    @if($parcel->is_partially_delivered)
                                                        <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                    @endif
                                                    <span  class="statusLabelButton red">{{ __('returned-to-merchant') }}</span><br>
                                                    <div  class="statusLabelNote red mt-1"> <b>Reason:</b> {{ $parcel->cancelnote->cancel_note ?? 'N/A' }}</div>
                                                    <span class="badge text-info mt-1">{{__($parcel->parcel_type)}}</span><br><br>

                                                    @if($parcel->short_url != '')
                                                            <div class="d-flex">
                                                                <label>{{ __('tracking_url').': ' }}</label>
                                                                <div class="copy-to-clipboard">
                                                                    <input readonly type="text" class="text-primary small-device" id="{{ $parcel->short_url }}" data-text="{{ __('copied') }}" value="{{__($parcel->short_url)}}">
                                                                    <a  href="javascript:void(0)" class="btn btn-sm btn-secondary track-url btn-tooltip" data-original-title="{{__('copied')}}"  onclick="compytracking('{{__($parcel->short_url)}}')" data-text="{{ __('copied') }}"><em class="icon ni ni-copy"></em></a>
                                                                </div>
                                                            </div> <br>
                                                    @endif
                                                    @if($parcel->note !="" && $parcel->note !=NULL)
                                                        <span>{{ __('note') }}: {{__($parcel->note)}}</span>
                                                    @endif

                                                    <div class="d-lg-none d-flex">
                                                        <div class="tb-odr-btns d-md-inline mr-1">
                                                            <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.detail',$parcel->parcel_no) : route('merchant.staff.parcel.detail',$parcel->parcel_no) }}" class="btn btn-sm btn-primary btn-tooltip" data-original-title="{{__('details')}}"><em class="icon ni ni-eye-alt"></em></a>
                                                        </div>
                                                        <div class="tb-odr-btns d-md-inline mr-1">
                                                            <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.print',$parcel->parcel_no) : route('merchant.staff.parcel.print',$parcel->parcel_no) }}" target="_blank" class="btn btn-sm btn-warning btn-tooltip" data-original-title="{{__('print')}}"><em class="icon ni ni-printer"></em></a>
                                                        </div>
                                                        @if($parcel->status == 'pending' || $parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup')
                                                            <div class="tb-odr-btns d-md-inline mr-1">
                                                                <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.edit', $parcel->parcel_no) : route('merchant.staff.parcel.edit', $parcel->parcel_no)}}" class="btn btn-sm btn-info btn-tooltip" data-original-title="{{__('edit')}}"><em class="icon ni ni-edit"></em></a>
                                                            </div>
                                                            <div class="tb-odr-btns d-md-inline mr-1">
                                                                <a href="javascript:void(0);" class="delete-parcel btn btn-sm btn-danger btn-tooltip" data-original-title="{{__('deleted')}}" id="delete-parcel" data-toggle="modal" data-target="#parcel-delete"><em class="icon ni ni-trash"></em></a>
                                                            </div>
                                                        @endif
                                                        @if($parcel->status == 'received-by-pickup-man' ||
                                                        $parcel->status == 'received' ||
                                                        $parcel->status == 'transferred-to-hub' ||
                                                        $parcel->status == 'transferred-received-by-hub')
                                                            <div class="tb-odr-btns d-md-inline mr-1">
                                                                <a href="javascript:void(0);" class="cancel-parcel btn btn-sm btn-danger btn-tooltip" data-original-title="{{__('cancel')}}" id="cancel-parcel" data-toggle="modal" data-target="#parcel-cancel"><em class="icon ni ni-cross"></em></a>
                                                            </div>
                                                        @endif

                                                        @if($parcel->status == 'cancel')
                                                            <div class="tb-odr-btns d-md-inline mr-1">
                                                                <a href="javascript:void(0);" class="parcel-re-request btn btn-sm btn-success btn-tooltip" data-original-title="{{__('re_request')}}" id="parcel-re-request" data-toggle="modal" data-target="#re-request-parcel"><em class="icon ni ni-arrow-left-circle"></em></a>
                                                            </div>
                                                        @endif
                                                        <div class="tb-odr-btns d-md-inline mr-1">
                                                            <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.duplicate',$parcel->parcel_no) : route('merchant.staff.parcel.duplicate',$parcel->parcel_no) }}" class="btn btn-sm btn-secondary btn-tooltip" data-original-title="{{__('duplicate_parcel')}}"><em class="icon ni ni-copy"></em></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    <div class="tb-odr-btns d-md-inline mr-1">
                                                        <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.detail',$parcel->parcel_no) : route('merchant.staff.parcel.detail',$parcel->parcel_no) }}" class="btn btn-sm btn-primary btn-tooltip" data-original-title="{{__('details')}}"><em class="icon ni ni-eye-alt"></em></a>
                                                    </div>
                                                    <div class="tb-odr-btns d-md-inline mr-1">
                                                        <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.print',$parcel->parcel_no) : route('merchant.staff.parcel.print',$parcel->parcel_no) }}" target="_blank" class="btn btn-sm btn-warning btn-tooltip" data-original-title="{{__('print')}}"><em class="icon ni ni-printer"></em></a>
                                                    </div>
                                                    @if($parcel->status == 'pending' || $parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup')
                                                        <div class="tb-odr-btns d-md-inline mr-1">
                                                            <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.edit', $parcel->parcel_no) : route('merchant.staff.parcel.edit', $parcel->parcel_no)}}" class="btn btn-sm btn-info btn-tooltip" data-original-title="{{__('edit')}}"><em class="icon ni ni-edit"></em></a>
                                                        </div>
                                                        <div class="tb-odr-btns d-md-inline mr-1">
                                                            <a href="javascript:void(0);" class="delete-parcel btn btn-sm btn-danger btn-tooltip" data-original-title="{{__('deleted')}}" id="delete-parcel" data-toggle="modal" data-target="#parcel-delete"><em class="icon ni ni-trash"></em></a>
                                                        </div>
                                                    @endif
                                                    @if($parcel->status == 'received-by-pickup-man' ||
                                                    $parcel->status == 'received' ||
                                                    $parcel->status == 'transferred-to-hub' ||
                                                    $parcel->status == 'transferred-received-by-hub')
                                                    <div class="tb-odr-btns d-md-inline mr-1">
                                                        <a href="javascript:void(0);" class="cancel-parcel btn btn-sm btn-danger btn-tooltip" data-original-title="{{__('cancel')}}" id="cancel-parcel" data-toggle="modal" data-target="#parcel-cancel"><em class="icon ni ni-cross"></em></a>
                                                    </div>
                                                    @endif

                                                    @if($parcel->status == 'cancel')
                                                        <div class="tb-odr-btns d-md-inline mr-1">
                                                            <a href="javascript:void(0);" class="parcel-re-request btn btn-sm btn-success btn-tooltip" data-original-title="{{__('re_request')}}" id="parcel-re-request" data-toggle="modal" data-target="#re-request-parcel"><em class="icon ni ni-arrow-left-circle"></em></a>
                                                        </div>
                                                    @endif
                                                    {{--<div class="tb-odr-btns d-md-inline mr-1">
                                                        <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.duplicate',$parcel->parcel_no) : route('merchant.staff.parcel.duplicate',$parcel->parcel_no) }}" class="btn btn-sm btn-secondary btn-tooltip" data-original-title="{{__('duplicate_parcel')}}"><em class="icon ni ni-copy"></em></a>
                                                    </div>--}}
                                                </div>
                                            </div><!-- .nk-tb-item -->
                                        @endforeach
                                    </div>
                                    <!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-2">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {!! $parcels->appends(Request::except('page'))->links() !!}
                                        </div>
                                    </div><!-- .nk-block-between -->
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-cancel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('cancel_parcel')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel-cancel') : route('merchant.staff.parcel-cancel')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="cancel-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('cancel_note') }} *</label>
                            <textarea name="cancel_note" class="form-control" required>{{ old('cancel_note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-delete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('delete_parcel')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel-delete') : route('merchant.staff.parcel-delete')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="delete-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('delete_note') }}</label>
                            <textarea name="cancel_note" class="form-control">{{ old('delete_note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="re-request-parcel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('re_request')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.re-request') : route('merchant.staff.parcel.re-request')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="re-request-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }} </label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @include('common.delete-ajax')
    @include('common.change-status-ajax')
    @include('merchant.parcel.change-parcel-status')

    <script>
        function compytracking(track_url)
        {
            var copyText = document.getElementById(track_url);
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            navigator.clipboard.writeText(copyText.value);
        }
    </script>
@endpush
