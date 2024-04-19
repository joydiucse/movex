@extends('master')

@section('title')
    {{__('parcel').' '.__('lists')}}
@endsection

@section('style')
    <style>
        .text-fliter-btn{
            color: #8599b1!important;
        }
    </style>
@endsection

@section('mainContent')
    @php
        $pn = isset($_GET['parcel_no']) ? $_GET['parcel_no'] : null;
        $phone_no = isset($_GET['phone_no']) ? $_GET['phone_no'] : null;
    @endphp
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ $parcels->total() }} {{__('parcel')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                @if(hasPermission('parcel_create'))
                                    @if(@settingHelper('preferences')->where('title','create_parcel')->first()->staff)
                                        <a href="{{route('import.csv')}}" class="btn btn-primary d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('import')}}</span></a>
                                    @endif
                                @endif
                                @if(hasPermission('parcel_transfer_to_hub'))
                                    <a href="{{route('bulk.transfer')}}" class="btn btn-primary d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('hub_transfer')}}</span></a>
                                @endif
                                @if(hasPermission('parcel_transfer_receive_to_hub'))
                                    <a href="{{route('bulk.transfer.receive')}}" class="btn btn-primary d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('transfer_receive')}}</span></a>
                                @endif
                                @if(hasPermission('parcel_pickup_assigned'))
                                    <a href="{{route('bulk.pickup.assign')}}" class="btn btn-primary d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('assign_pickup')}}</span></a>
                                @endif
                                @if(hasPermission('parcel_delivery_assigned'))
                                        <a href="{{route('bulk.assigning')}}" class="btn btn-primary d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('assign_delivery')}}</span></a>
                                    @endif

                                @if(hasPermission('parcel_return_assigned_to_merchant'))
                                    <a href="{{route('parcel.return.list')}}" class="btn btn-primary d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('assign_return')}}</span></a>
                                @endif


                                @if(hasPermission('parcel_create'))
                                    @if(@settingHelper('preferences')->where('title','create_parcel')->first()->staff)
                                        <a href="{{route('parcel.create')}}" class="btn btn-primary d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('create').' '.__('parcel')}}</span></a>
                                    @else
                                        <button class="btn btn-danger d-md-inline-flex mt-1"><em class="icon ni ni-plus"></em><span>{{__('create').' '.__('parcel').' ('.__('service_unavailable').')'}}</span></button>
                                    @endif
                                @endif

                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner position-relative card-tools-toggle pb-0">
                                    <div class="row filter-button mb-2">
                                        <div class="col-md-12">
                                            <a href="{{route('parcel')}}" class="badge {{(isset($slug) || request()->has('key')) ? 'text-fliter-btn':'btn-primary'}}"><span>{{__('all')}}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'pending')}}" class="badge {{isset($slug)? ($slug == 'pending'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{__('pending')}}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'pickup-assigned')}}" class="badge {{isset($slug)? ($slug == 'pickup-assigned'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('pickup-assigned') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 're-schedule-pickup')}}" class="badge {{isset($slug)? ($slug == 're-schedule-pickup'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('re-schedule-pickup') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'received-by-pickup-man')}}" class="badge {{isset($slug)? ($slug == 'received-by-pickup-man'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('received-by-pickup-man') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'received')}}" class="badge {{isset($slug)? ($slug == 'received'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('received_by_warehouse') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'transferred-to-hub')}}" class="badge {{isset($slug)? ($slug == 'transferred-to-hub'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('transferred-to-hub') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'transferred-received-by-hub')}}" class="badge {{isset($slug)? ($slug == 'transferred-received-by-hub'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('transferred-received-by-hub') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'delivery-assigned')}}" class="badge {{isset($slug)? ($slug == 'delivery-assigned'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('delivery-assigned') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 're-schedule-delivery')}}" class="badge {{isset($slug)? ($slug == 're-schedule-delivery'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('re-schedule-delivery') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'today-attempt')}}" class="badge {{isset($slug)? ($slug == 'today-attempt'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('today_attempt') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'returned-to-greenx')}}" class="badge {{isset($slug)? ($slug == 'returned-to-greenx'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('returned-to-greenx') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'return-assigned-to-merchant')}}" class="badge {{isset($slug)? ($slug == 'return-assigned-to-merchant'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('return-assigned-to-merchant') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'partially-delivered')}}" class="badge {{isset($slug)? ($slug == 'partially-delivered'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('partially-delivered') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'delivered')}}" class="badge {{isset($slug)? ($slug == 'delivered'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('delivered') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'pending-return')}}" class="badge {{isset($slug)? ($slug == 'pending-return'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('pending-return') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'returned-to-merchant')}}" class="badge {{isset($slug)? ($slug == 'returned-to-merchant'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('returned-to-merchant') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'cancel')}}" class="badge {{isset($slug)? ($slug == 'cancel'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('cancelled') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'deleted')}}" class="badge {{isset($slug)? ($slug == 'deleted'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('deleted') }}</span></a>
                                            <a href="{{route('admin.parcel.filtering', 'edited')}}" class="badge {{isset($slug)? ($slug == 'edited'? 'btn-primary':'text-fliter-btn'):'text-fliter-btn'}}"><span>{{ __('edited') }}</span></a>
                                        </div>
                                    </div>

                                    <div class="card-title-group my-2">
                                        <div class="card-tools">
                                            <div class="d-block">
                                                <!-- <form action="{{route('admin.parcel.filter')}}" method="GET">
                                                  <div class="row g-4">
                                                    <div class="col-lg-6">
                                                      <div class="form-group">
                                                        <label class="form-label" for="full-name-1">Parcel ID</label>
                                                        <div class="form-control-wrap">
                                                          <input type="text" name="parcel_no" class="form-control" autofocus placeholder="AGEWGGWF1215" id="parcel_no">
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                      <div class="form-group">
                                                        <label class="form-label" for="email-address-1">Phone</label>
                                                        <div class="form-control-wrap">
                                                          <input type="text" class="form-control" id="email-address-1" wtx-context="60A82584-985F-41C5-8983-8E3AC12F48DA">
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </form> -->

                                                <form action="{{route('admin.parcel.filter')}}" method="GET" class="form-inline mb-0">
{{--                                                    @csrf--}}
                                                    {{ __('search') }}
                                                    <input type="text" name="parcel_no" class="form-control ml-2" value="{{ $pn }}" autofocus placeholder="GNX93121876914" id="parcel_no">
                                                    <input type="text" name="phone_no" value="{{ $phone_no }}" class="form-control ml-2" placeholder="01711111111" id="phone_no">
                                                    <input type="submit" hidden />
                                                </form>
                                            </div>
                                        </div><!-- .card-tools -->

                                        <div class="card-tools mr-n1 d-flex align-items-center">
                                            @if(hasPermission('read_all_parcel'))
                                                <button type="button" class="btn btn-primary pathao-button mr-2 d-none" id="addToPathaoButton" disabled>
                                                    <i class="fa-solid fa-circle-plus mr-1"></i>
                                                    <span>Add to Pathao</span>
                                                </button>
                                                <div class="dropdown mr-2 custom-dropdown-width">
                                                    <a href="#" class="btn  btn-primary" data-toggle="dropdown">
                                                        <span>Actions</span><em class="icon ni ni-chevron-down"></em>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-auto mt-1">
                                                        <ul class="link-list-plain">
                                                            <li><a href="javascript:void(0)" onclick="submitbulkPrint('sticker')">{{__('print_sticker')}}</a></li>
                                                            <li><a href="javascript:void(0)" onclick="submitbulkPrint('details_print')">{{__('print_sheet')}}</a></li>
                                                            <li><a href="javascript:void(0)" onclick="submitbulkPrint('return_sticker')">{{__('print_return_sticker')}}</a></li>
                                                            <li><a href="javascript:void(0)" onclick="submitbulkPrint('return_sheet_print')">{{__('print_return_sheet')}}</a></li>
                                                            <li><a href="javascript:void(0)" onclick="submitbulkPrint('export_parcel')">{{__('parcel')}}  {{__('export')}}</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif


                                            <ul class="btn-toolbar gx-1">
                                                <li>
                                                    <form action="{{route('admin.parcel.filter')}}" method="GET" class="mb-0">
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
                                                                                        <div class="form-control-wrap">
                                                                                            <select id="merchant-live-search" name="merchant" class="form-control form-control-lg select-merchant merchant merchant-live-search" data-url="{{ route('merchant.change') }}">
                                                                                                <option value="">{{ __('select_merchant') }}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <input type="text" class="form-control" name="phone_number" placeholder="{{__('enter').' '.__('company').' '.__('phone_number')}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <input type="text" class="form-control" name="customer_invoice_no" placeholder="{{__('enter').' '.__('invoice_no')}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <div class="form-control-wrap">
                                                                                            <div class="form-icon form-icon-right">
                                                                                                <em class="icon ni ni-calendar-alt"></em>
                                                                                            </div>
                                                                                            <input type="text" name="created_from" class="form-control date-picker" id="created_from">
                                                                                            <label class="form-label-outlined" for="created_from">{{ __('created_from') }}</label>
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
                                                                                            <label class="form-label-outlined" for="created_to">{{ __('created_to') }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
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
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        {{--                                                                                    <select class="form-select form-select-sm" name="pickup_man">--}}
                                                                                        <select name="pickup_man" class="form-control form-control-lg delivery-man-live-search">
                                                                                            <option value="any">{{__('pickup_man')}}</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        {{--                                                                                    <select class="form-select form-select-sm" name="delivery_man">--}}
                                                                                        <select name="delivery_man" class="form-control form-control-lg delivery-man-live-search">
                                                                                            <option value="any">{{__('delivery_man')}}</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select name="third_party" class="form-select form-select-sm" >
                                                                                            <option value="any">{{__('third_party')}}</option>
                                                                                            @foreach($third_parties as $third_party)
                                                                                                <option value="{{ $third_party->id }}">{{ $third_party->name.' ('.$third_party->address.')' }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-select-sm" name="status">
                                                                                            <option value="any">{{__('any_status')}}</option>
                                                                                            @foreach(\Config::get('greenx.parcel_status') as $parcel_status)
                                                                                                <option value="{{ $parcel_status }}">{{ $parcel_status == 'received' ? __('received_by_warehouse') : __($parcel_status) }}</option>
                                                                                            @endforeach
                                                                                            <option value="pending-return">{{ __('pending-return') }}</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-select-sm" name="weight">
                                                                                            <option value="any">{{__('any_weight')}}</option>
                                                                                            @foreach($charges as $charge)
                                                                                                <option value="{{ $charge->weight }}">{{ $charge->weight }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
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
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-select-sm" name="hub">
                                                                                            <option value="any">{{__('all').' '.__('hub')}}</option>
                                                                                            @if(hasPermission('read_all_parcel'))
                                                                                                @foreach($hubs as $hub)
                                                                                                    <option value="{{ $hub->id }}">{{ $hub->name.' ('.$hub->address.')' }}</option>
                                                                                                @endforeach
                                                                                            @else
                                                                                                @if(!blank(\Sentinel::getUser()->hub) )
                                                                                                    <option value="{{ \Sentinel::getUser()->hub_id }}">{{ \Sentinel::getUser()->hub->name.' ('.\Sentinel::getUser()->hub->address.')' }}</option>
                                                                                                @endif
                                                                                            @endif
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <select class="form-select form-select-sm" name="pickup_hub">
                                                                                            <option value="all">{{__('all').' '.__('pickup_hub')}}</option>
                                                                                            <option value="pending">{{__('pending')}}</option>
                                                                                            @if(hasPermission('read_all_parcel'))
                                                                                                @foreach($hubs as $hub)
                                                                                                    <option value="{{ $hub->id }}">{{ $hub->name.' ('.$hub->address.')' }}</option>
                                                                                                @endforeach
                                                                                            @else
                                                                                                @if(!blank(\Sentinel::getUser()->hub) )
                                                                                                    <option value="{{ \Sentinel::getUser()->hub_id }}">{{ \Sentinel::getUser()->hub->name.' ('.\Sentinel::getUser()->hub->address.')' }}</option>
                                                                                                @endif
                                                                                            @endif
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
                                <form action="/" method="post" id="parcel-form">
                                    @csrf
                                    <div class="card-inner p-0">
                                        <div class="nk-tb-list nk-tb-ulist">
                                            <div class="nk-tb-item nk-tb-head">
                                                <div class="nk-tb-col"><span class="sub-text">
                                                    <div class="custom-control custom-checkbox pt-1">
                                                        <input type="checkbox" class="custom-control-input" id="all">
                                                        <label class="custom-control-label" for="all"></label>
                                                    </div><!-- check box --->
                                                    <strong>#</strong></span>
                                                </div>
                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('no_date')}}</strong></span></div>
                                                <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                                <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('customer')}}</strong></span></div>
                                                <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>

                                                <div class="nk-tb-col"><span class="sub-text"><strong>{{__('actions')}}</strong></span></div>
                                            </div>
                                            <!-- .nk-tb-item -->
                                            @foreach($parcels as $key => $parcel)
                                                <div class="nk-tb-item {{--{{getStatusWiseColor($parcel->status, 'bgShade')}}--}}" id="row_{{$parcel->id}}">
                                                    <input type="hidden" value="{{$parcel->id}}" id="id">

                                                    <div class="nk-tb-col">

                                                        <div class="custom-control custom-checkbox pt-1 ml-3">
                                                            <input type="checkbox" class="custom-control-input" id="{{ $parcel->id }}" name="parcel_id[]" value="{{ $parcel->id }}">
                                                            <label class="custom-control-label" for="{{ $parcel->id }}"></label>
                                                        </div><!-- check box --->

                                                        <span class="pl-3">{{$key + 1}}</span>
                                                    </div>

                                                    <div class="nk-tb-col column-max-width">
                                                        @php $phoneNumber='' @endphp
                                                        <a href="{{ route('admin.parcel.detail',$parcel->parcel_no) }}">
                                                            <table>
                                                                <tr>
                                                                    <td class="d-flex">{{__('id')}}:#
                                                                        <div class="copy-to-clipboard">
                                                                            <input readonly type="text" class="text-primary" id="{{ $parcel->parcel_no }}" data-text="{{ __('copied') }}" value="{{__($parcel->parcel_no)}}">
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr><td>{{__('invno')}}:{{$parcel->customer_invoice_no}}</td></tr>
                                                                @if($parcel->status != 'pending' && $parcel->status
                                                                    != 'pickup-assigned' && $parcel->status != 're-schedule-pickup'
                                                                    && $parcel->status != 'received-by-pickup-man')
                                                                    @if(!blank($parcel->hub))
                                                                        <tr><td>{{__('hub')}}: {{ ($parcel->hub->name).' ('.$parcel->hub->address }} </td></tr>
                                                                    @endif
                                                                @endif
                                                                @if($parcel->status == 'transferred-to-hub')
                                                                    @if(!blank($parcel->transferToHub))
                                                                        <tr><td>{{__('transferring_to')}}: {{ ($parcel->transferToHub->name).' ('.$parcel->transferToHub->address }})</td></tr>
                                                                    @endif
                                                                    @if(!blank($parcel->transferDeliveryMan))
                                                                        <tr><td>{{__('transferring_by')}}: {{$parcel->transferDeliveryMan->user->first_name.' '.$parcel->transferDeliveryMan->user->last_name}}</td></tr>
                                                                            @php $phoneNumber=$parcel->transferDeliveryMan->user->phone_number ?? '' @endphp
                                                                    @endif
                                                                @endif
                                                                @if($parcel->status == 'transferred-received-by-hub')
                                                                    @if(!blank($parcel->transferDeliveryMan))
                                                                        <tr><td>{{__('transferred_by')}}: {{$parcel->transferDeliveryMan->user->first_name.' '.$parcel->transferDeliveryMan->user->last_name}}</td></tr>
                                                                        @php $phoneNumber=$parcel->transferDeliveryMan->user->phone_number ?? '' @endphp
                                                                    @endif
                                                                @endif
                                                                {{--<tr><td>{{$parcel->created_at != ""? date('M d, Y h:i a', strtotime($parcel->created_at)):''}}</td></tr>--}}
                                                                {{--@if($parcel->parcel_type == 'frozen')
                                                                    <tr><td class="text-primary">{{__('pickup')}}: {{$parcel->pickup_date != ""? date('M d, Y', strtotime($parcel->pickup_date)):''}} {{$parcel->pickup_time != ""? date('h:i a', strtotime($parcel->pickup_time)):''}}</td></tr>
                                                                    <tr><td class="text-primary">{{__('delivery')}}: {{$parcel->delivery_date != ""? date('M d, Y', strtotime($parcel->delivery_date)):''}} {{$parcel->pickup_time != ""? date('h:i a', strtotime($parcel->pickup_time)):''}}</td></tr>
                                                                @else
                                                                    <tr><td class="text-primary">{{__('pickup')}}: {{$parcel->pickup_date != ""? date('M d, Y', strtotime($parcel->pickup_date)):''}}</td></tr>
                                                                    <tr><td class="text-primary">{{__('delivery')}}: {{$parcel->delivery_date != ""? date('M d, Y', strtotime($parcel->delivery_date)):''}}</td></tr>
                                                                @endif--}}
                                                                @if($parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified')
                                                                    <tr><td class="text-primary">{{__('delivered_at')}}: {{$parcel->event != ""? date('M d, Y h:i a', strtotime($parcel->event->created_at)):''}}</td></tr>
                                                                    <tr><td class="text-primary">{{__('delivered_by')}}: {{$parcel->deliveryMan->user->first_name.' '.$parcel->deliveryMan->user->last_name}}</td></tr>
                                                                    @php $phoneNumber=$parcel->deliveryMan->user->phone_number ?? '' @endphp
                                                                @endif
                                                                @if($parcel->status == 'received-by-pickup-man')
                                                                    <tr><td class="text-primary">{{__('pickup_by')}}: {{$parcel->pickupMan->user->first_name.' '.$parcel->pickupMan->user->last_name}}</td></tr>
                                                                    @php $phoneNumber=$parcel->pickupMan->user->phone_number ?? '' @endphp
                                                                @endif
                                                                @if($parcel->status == 'received')
                                                                    <tr><td class="text-primary">{{__('pickup_by')}}: {{$parcel->pickupMan->user->first_name.' '.$parcel->pickupMan->user->last_name}}</td></tr>
                                                                    @php $phoneNumber=$parcel->pickupMan->user->phone_number ?? '' @endphp
                                                                @endif
                                                                @if($parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup')
                                                                    {{--                                                                .date('M d, Y g:i A', strtotime($parcel->pickupPerson->created_at))--}}
                                                                    <tr><td class="text-primary">{{__('pickup_man')}}: {{$parcel->pickupMan != ""? ($parcel->pickupMan->user->first_name.' '.$parcel->pickupMan->user->last_name ):''}}</td></tr>
                                                                    @php $phoneNumber=$parcel->pickupMan->user->phone_number ?? '' @endphp
                                                                @endif
                                                                @if($parcel->status == 'return-assigned-to-merchant')
                                                                    <tr><td class="text-primary">{{__('returned_at')}}: {{$parcel->event != ""? date('M d, Y h:i a', strtotime($parcel->event->created_at)):''}}</td></tr>
                                                                    <tr><td class="text-primary">{{__('return_delivery_man')}}: {{$parcel->returnDeliveryMan != ""? ($parcel->returnDeliveryMan->user->first_name.' '.$parcel->returnDeliveryMan->user->last_name ):''}}</td></tr>
                                                                    @php $phoneNumber=$parcel->returnDeliveryMan->user->phone_number ?? '' @endphp
                                                                @endif
                                                                @if($parcel->status == 'returned-to-merchant')
                                                                    <tr><td class="text-primary">{{__('returned_at')}}: {{$parcel->returnEvent != ""? date('M d, Y h:i a', strtotime($parcel->returnEvent->created_at)):''}}</td></tr>
                                                                    <tr><td class="text-primary">{{__('returned_by')}}: {{$parcel->returnDeliveryMan != ""? ($parcel->returnDeliveryMan->user->first_name.' '.$parcel->returnDeliveryMan->user->last_name ):''}}</td></tr>
                                                                    @php $phoneNumber=$parcel->returnDeliveryMan->user->phone_number ?? '' @endphp
                                                                @endif
                                                                @if($parcel->status == 'delivery-assigned' || $parcel->status == 're-schedule-delivery')
                                                                    {{--                                                                .date('M d, Y g:i A', strtotime($parcel->deliveryPerson->created_at))--}}
                                                                    <tr><td class="text-primary">{{__('delivery_man')}}: {{$parcel->deliveryMan != ""? ($parcel->deliveryMan->user->first_name.' '.$parcel->deliveryMan->user->last_name ):''}}</td></tr>
                                                                    @php $phoneNumber=$parcel->deliveryMan->user->phone_number ?? '' @endphp
                                                                @endif
                                                                @if($parcel->status == 'pending' || $parcel->status
                                                                    == 'pickup-assigned' || $parcel->status == 're-schedule-pickup'
                                                                    || $parcel->status == 'received-by-pickup-man')
                                                                    @if(!blank($parcel->pickupHub))
                                                                        <tr><td>{{__('pickup_hub')}}: {{ ($parcel->pickupHub->name).' ('.$parcel->pickupHub->address }})</td></tr>
                                                                    @endif
                                                                @endif
                                                            </table>
                                                        </a>
                                                        @if($phoneNumber!='')
                                                            <tr>
                                                                <td class="">
                                                                    @php $mobileCopyId=$parcel->parcel_no.'_'.$phoneNumber @endphp
                                                                    <span class="text-primary fw-bold">Mobile: <input type="text" class="mobileToCopy text-primary  fw-bold" readonly id="{{$mobileCopyId}}" value="{{$phoneNumber}}" data-original-title="{{__('copy')}}" onclick="copyInput('{{ $mobileCopyId }}')" data-text="Copied Phone Number"></span>
                                                                    <a href="tel:{{ $phoneNumber }}" type="button" class="callButtonRounded" style="padding-top:10px"><em class="fa-solid fa-phone"></em></a>
                                                                </td>
                                                                {{--<td class="text-primary">
                                                                    <div class="copy-to-clipboard">
                                                                        <input readonly type="text" class="text-primary small-device" id="{{ $parcel->percel_id.$phoneNumber }}" data-text="{{ __('copied') }}" value="{{$phoneNumber}}">
                                                                        <a  href="javascript:void(0)" class="btn btn-sm btn-secondary track-url btn-tooltip" data-original-title="{{__('copied')}}"  onclick="compytracking('{{$parcel->percel_id.$phoneNumber}}')" data-text="{{ __('copied') }}"><em class="icon ni ni-copy"></em></a>
                                                                    </div>
                                                                </td>--}}
                                                            </tr>
                                                        @endif
                                                    </div>

                                                    <div class="nk-tb-col  tb-col-lg column-max-width">
                                                        <table width="70%">
                                                            <tr>
                                                                <td>
                                                                    {{ (@$parcel->merchant_id == 1802 && $parcel->user->user_type == 'merchant_staff' ) ? $parcel->merchant->company .' ('.@$parcel->user->first_name.' '.@$parcel->user->last_name.')' : @$parcel->merchant->company }}
                                                                </td>
                                                            </tr>
                                                            <tr><td>{{ @$parcel->pickup_shop_phone_number }}</td></tr>
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
                                                    <div class="nk-tb-col  tb-col-lg column-max-width">
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
                                                        @php $holdNote=''; @endphp
                                                        @if($parcel->status == 'pending')
                                                            <span  class="badge text-warning">{{ __($parcel->status) }}</span><br>
                                                        @elseif($parcel->status == 'deleted')
                                                            <span  class="badge text-danger">{{ __('deleted') }}</span><br>
                                                        @elseif($parcel->status == 'cancel')
                                                            <span  class="statusLabelButton red">{{ __('cancelled') }}</span>
                                                            <div  class="statusLabelNote red mt-1 mb-2"> <b>Reason:</b> {{ $parcel->cancelnote->cancel_note ?? 'N/A' }}</div>
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
                                                            <span  class="statusLabelButton orange">{{ __('delivery-assigned') }}</span><br>
                                                        @elseif($parcel->status == 're-schedule-delivery')
                                                            <span  class="statusLabelButton yellow">{{ __('re-schedule-delivery') }}</span>
                                                            <span  class="badge">{{ ($parcel->delivery_date == date('Y-m-d')) ? __("today") : __('next_delivery') }}  {{ ($parcel->delivery_date)? date('d M Y', strtotime($parcel->delivery_date)):  '' }}</span>
                                                            @php $holdNote=$parcel->holdNote->cancel_note ?? 'N/A'; @endphp
                                                            <div  class="statusLabelNote yellow mt-1 mb-1"> <b>Note:</b> {{ $parcel->holdNote->cancel_note ?? 'N/A' }}</div>
                                                        @elseif($parcel->status == 'returned-to-greenx')
                                                            @if($parcel->is_partially_delivered)
                                                                <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                            @endif
                                                            <span  class="statusLabelButton red">{{ __('returned-to-greenx') }}</span>
                                                            <div  class="statusLabelNote red mt-1 mb-2"> <b>Reason:</b> {{ $parcel->cancelnote->cancel_note ?? 'N/A' }}</div>
                                                        @elseif($parcel->status == 'return-assigned-to-merchant')
                                                            @if($parcel->is_partially_delivered)
                                                                <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                            @endif
                                                            <span  class="statusLabelButton red">{{ __('return-assigned-to-merchant') }}</span><br>
                                                        @elseif($parcel->status == 'partially-delivered')
                                                            <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                        @elseif($parcel->status == 'delivered')
                                                            <span  class="statusLabelButton green">{{ __('delivered') }}</span><br>
                                                        @elseif($parcel->status == 'delivered-and-verified')
                                                            <span  class="badge text-success">{{ __('delivered-and-verified') }}</span><br>
                                                        @elseif($parcel->status == 'returned-to-merchant')
                                                            @if($parcel->is_partially_delivered)
                                                                <span  class="badge text-mint">{{ __('partially-delivered') }}</span><br>
                                                            @endif
                                                            <span  class="statusLabelButton red">{{ __('returned-to-merchant') }}</span>
                                                            <div  class="statusLabelNote red mt-1 mb-2"> <b>Reason:</b> {{ $parcel->cancelnote->cancel_note ?? 'N/A' }}</div>
                                                        @elseif($parcel->status == 're-request')
                                                            <span  class="badge text-warning">{{ __('re-request') }}</span><br>
                                                        @endif
                                                        <div class="mt-6px">
                                                            <span class="statusLabelButton percel-type">{{__($parcel->parcel_type)}}</span><br><br>
                                                        </div>

                                                        @if($parcel->short_url != '')
                                                            <div class="d-flex">
                                                                <label>{{ __('tracking_url').': ' }}</label>
                                                                <div class="copy-to-clipboard">
                                                                    <input readonly type="text" class="text-primary" id="{{ $parcel->short_url }}" data-text="{{ __('copied') }}" value="{{__($parcel->short_url)}}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                        @if($parcel->tracking_number != '')
                                                            <div class="d-flex">
                                                                <label>{{ __('paperfly_track').': ' }}</label>
                                                                <div class="copy-to-clipboard">
                                                                    <input readonly type="text" class="text-primary" id="{{ $parcel->tracking_number }}" data-text="{{ __('copied') }}" value="{{__($parcel->tracking_number)}}">
                                                                </div>
                                                            </div>
                                                                <br> <br>
                                                        @endif

                                                        @if($parcel->location != 'dhaka' && ($parcel->status == 'delivery-assigned' || $parcel->status == 're-schedule-delivery') && $parcel->third_party_id != '')
                                                            <span class="badge text-info">{{__('third_party')}}</span><br><br>
                                                        @endif
                                                        @if($parcel->note !="" && $parcel->note !=NULL)
                                                            <span class="alert alert-danger" style="padding: 0.2rem 1.25rem;">{{ __('note') }}: {{__($parcel->note)}}</span>
                                                        @endif
                                                    </div>

                                                    @include('admin.parcel.parcel-options')

                                                </div><!-- .nk-tb-item -->
                                            @endforeach
                                        </div>
                                        <!-- .nk-tb-list -->
                                    </div><!-- .card-inner -->
                                 </form>
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

    {{-- modal --}}
    <div class="modal fade" tabindex="-1" id="assign-pickup">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('assign_pickup_man')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('assign.pickup.man')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="pickup-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('pickup_man')}} *</label>
                            <div class="form-control-wrap">
                                <select name="pickup_man" class="form-control form-control-lg delivery-man-live-search" required>
                                    {{--                                <select class="form-select form-control form-control-lg" name="pickup_man" required>--}}
                                    <option value="">{{ __('select_pickup_man') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="preview-block">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck1" name="notify_pickup_man" value="notify">
                                    <label class="custom-control-label" for="customCheck1">{{__('notify_pickup_man')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
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
                    <form action="{{route('parcel-cancel')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="cancel-parcel-id">

                        <div class="form-group">
                            <label class="form-label" for="predefined_reason">{{__('predefined_reasons')}} *</label>
                            <div class="form-control-wrap">
                                <select name="predefined_reason" class="form-control" required>
                                    <option value="">{{ __('select_reason') }}</option>
                                    @foreach(\Config::get('greenx.cancel_predefined_reasons') as $reason)
                                        <option value="{{ $reason }}">{{ __($reason) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('predefined_reason'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('predefined_reason') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('cancel_note') }}</label>
                            <textarea name="cancel_note" class="form-control">{{ old('cancel_note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
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
                    <form action="{{route('parcel-delete')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="delete-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('delete_note') }}</label>
                            <textarea name="cancel_note" class="form-control">{{ old('delete_note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-transfer-receive-to-hub">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('transfer_receive_to_hub')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('transfer-receive-to-hub')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="transfer-receive-to-hub-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }} </label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-reverse-from-cancel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('reverse_note')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('reverse-from-cancel')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="reverse-from-cancel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-delivered">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('delivery_parcel')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body parcel-delivered-content">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-delivered-partially">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('parcel').' '.__('partially_delivery')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('partially-delivered')}}" method="POST" class="form-validate is-alter" id="partial-delivery-form">
                        @csrf
                        <input type="hidden" name="id" value="" id="delivery-parcel-partially-id">
                        <div class="form-group">
                            <label class="form-label" for="cod" >{{__('cod')}} *</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control cod" id="cod" value="{{ old('cod') }}" name="cod" placeholder="{{__('cod')}}" required>
                            </div>
                            @if($errors->has('cod'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('cod') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="returned-to-merchant">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('returned_to_merchant')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>

                <div class="modal-body returned-to-merchant-content">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="return-delivery">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('returned_to_greenx')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('parcel-returned-to-greenx')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="delivery-return-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-receive">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('parcel_received_by_warehouse')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('parcel-receive')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="receive-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('hub')}} *</label>
                            <div class="form-control-wrap">
                                <select name="hub" class="form-control form-control-lg" required>
                                    <option value="">{{ __('select_hub') }}</option>
                                    @foreach($hubs as $hub)
                                        <option value="{{ $hub->id }}" {{ $hub->id == Sentinel::getUser()->hub_id ? 'selected' : '' }}>{{ @$hub->name.' ('.@$hub->address }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-receive-by-pickupman">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('parcel_received_by_pickup_man')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('parcel-receive-by-pickupman')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="receive-parcel-pickup-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="delivery-reverse">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('backward')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('delivery-reverse')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="delivery-reverse-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('status')}} *</label>
                            <div class="form-control-wrap">
                                <select name="status" class="form-control form-control-lg" id="reverse" required>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="assign-delivery">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('assign_delivery_man')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('assign.delivery.man')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="assign-delivery-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('delivery_man')}} *</label>
                            <div class="form-control-wrap">
                                <select name="delivery_man" class="form-control form-control-lg delivery-man-live-search" required>
                                    {{--                                <select class="form-select form-control form-control-lg" name="delivery_man" required>--}}
                                    <option value="">{{ __('select_pickup_man') }}</option>
                                </select>
                            </div>
                            @if($errors->has('delivery_man'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('delivery_man') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group d-none third-party">
                            <label class="form-label" for="third_party">{{__('third_party')}}</label>
                            <div class="form-control-wrap">
                                <select name="third_party" class="form-control form-control-lg">
                                    <option value="">{{ __('select_third_party') }}</option>
                                    @foreach($third_parties as $third_party)
                                        <option value="{{ $third_party->id }}">{{ $third_party->name.' ('.$third_party->address.')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('third_party'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('third_party') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="parcel-transfer-to-hub">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('transfer_to_hub')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('transfer-to-hub')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="transfer-to-hub-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('hub')}} *</label>
                            <div class="form-control-wrap">
                                <select name="hub" class="form-control form-control-lg" id="transfer-hub-options" required>

                                </select>
                            </div>
                            @if($errors->has('hub'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('hub') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('shuttle_man')}} *</label>
                            <div class="form-control-wrap">
                                <select id="shuttle-man-live-search" name="delivery_man" class="form-control form-control-lg shuttle-man-live-search" required> </select>
                                {{-- <select name="delivery_man" class="form-control form-control-lg delivery-man-live-search" required>


                                    <option value="">{{ __('select_delivery_man') }}</option>
                                </select> --}}
                            </div>
                            @if($errors->has('delivery_man'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('delivery_man') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="return-assign-tomerchant">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('return_assign_to_merchant')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('return.assign.to.merchant')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="return-merchant-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('delivery_man')}} *</label>
                            <div class="form-control-wrap">
                                <select name="delivery_man" class="form-control form-control-lg delivery-man-live-search" required>
                                    {{--                                <select class="form-select form-control form-control-lg" name="delivery_man" required>--}}
                                    <option value="">{{ __('select_delivery_man') }}</option>
                                </select>
                            </div>
                            @if($errors->has('delivery_man'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('delivery_man') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="re-schedule-pickup">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('re_schedule_pickup')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('re-schedule.pickup')}}" method="POST" class="form-validate is-alter" id="re-schedule-pickup-assign-form">
                        @csrf
                        <input type="hidden" name="id" value="" id="re-schedule-pickup-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('pickup_man')}} *</label>
                            <div class="form-control-wrap">
                                <select id="re-schedule-pickup-assign-man" name="pickup_man" class="form-control form-control-lg delivery-man-live-search" required>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">
                                    <em class="icon ni ni-calendar-alt"></em>
                                </div>
                                <input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="outlined-date-picker" name="date">
                                <label class="form-label-outlined" for="outlined-date-picker">{{ __('date') }}</label>
                            </div>
                        </div>
                        <div class="form-group time">
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">
                                    <em class="icon ni ni-clock"></em>
                                </div>
                                <input type="text" class="form-control form-control-xl form-control-outlined time-picker" id="outlined-time-picker" name="time">
                                <label class="form-label-outlined" for="outlined-time-picker">{{ __('time') }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="predefined_reason">{{__('predefined_reasons')}} *</label>
                            <div class="form-control-wrap">
                                <select name="predefined_reason" class="form-control" required>
                                    <option value="">{{ __('select_reason') }}</option>
                                    @foreach(\Config::get('greenx.pickup_re_schedule_reasons') as $reason)
                                        <option value="{{ $reason }}">{{ __($reason) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('predefined_reason'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('predefined_reason') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" id="re-schedule-pickup-note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group">
                            <div class="preview-block">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="customCheck2" name="notify_pickup_man" value="notify">
                                    <label class="custom-control-label" for="customCheck2">{{__('notify_pickup_man')}}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="re-schedule-delivery">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('re-schedule-delivery')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('re-schedule.delivery')}}" method="POST" class="form-validate is-alter" id="re-schedule-delivery-assign-form">
                        @csrf
                        <input type="hidden" name="id" value="" id="re-schedule-delivery-parcel-id">
                        {{-- <div class="form-group">
                            <label class="form-label" for="area">{{__('delivery_man')}} *</label>
                            <div class="form-control-wrap">
                                <select id="re-schedule-delivery-assign-man" name="delivery_man" class="form-control form-control-lg delivery-man-live-search" required>
                                    <!--  <select class="form-select form-control form-control-lg" name="delivery_man" required  id="re-schedule-delivery-assign-man"> -->
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="form-group d-none third-party">
                            <label class="form-label" for="third_party">{{__('third_party')}}</label>
                            <div class="form-control-wrap">
                                <select name="third_party" id="re-schedule-third-party" class="form-control form-control-lg">
                                    <option value="">{{ __('select_third_party') }}</option>
                                    @foreach($third_parties as $third_party)
                                        <option value="{{ $third_party->id }}">{{ $third_party->name.' ('.$third_party->address.')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('third_party'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('third_party') }}</p>
                                </div>
                            @endif
                        </div> --}}
                        <div class="form-group">
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">
                                    <em class="icon ni ni-calendar-alt"></em>
                                </div>
                                <input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="outlined-date-picker" name="date">
                                <label class="form-label-outlined" for="outlined-date-picker">{{ __('next_delivery_date') }}</label>
                            </div>
                        </div>
                        {{-- <div class="form-group time">
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">
                                    <em class="icon ni ni-clock"></em>
                                </div>
                                <input type="text" class="form-control form-control-xl form-control-outlined time-picker" id="outlined-time-picker" name="time">
                                <label class="form-label-outlined" for="outlined-time-picker">{{ __('time') }}</label>
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <label class="form-label" for="predefined_reason">{{__('predefined_reasons')}} *</label>
                            <div class="form-control-wrap">
                                <select name="predefined_reason" class="form-control" required onchange="deliveryReason(this.value)">
                                    <option value="">{{ __('select_reason') }}</option>
                                    @foreach(\Config::get('greenx.delivery_re_schedule_reasons') as $reason)
                                        <option value="{{ $reason }}">{{ __($reason) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($errors->has('predefined_reason'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('predefined_reason') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group" id="note" style="display: none">
                            <label class="form-label" for="area">{{ __('note') }}</label>
                            <textarea name="note" id="re-schedule-delivery-note" class="form-control">{{ old('note') }}</textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="create-paperflyparcel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Paperfly Parcel Create')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.create-paperfly-parcel')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="create-paperfly-parcel-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{__('district')}} *</label>
                            <div class="form-control-wrap">
                                <select name="district" class="form-control form-control-lg get-thana-union select2" data-url="{{ route('admin.get-thana-union') }}" required>
                                    <option value="">{{ __('select_district') }}</option>
                                </select>
                            </div>
                            @if($errors->has('district'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('district') }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="area">{{__('thana_union')}} *</label>
                            <div class="form-control-wrap">
                                <select name="thana_union" class="form-control form-control-lg thana-union select2" required>
                                    <option value="">{{ __('select_thana_union') }}</option>
                                </select>
                            </div>
                            @if($errors->has('thana_union'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('thana_union') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('submit')}}</button>
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
    @include('live_search.shuttle-man')
    @include('admin.parcel.change-parcel-status')
    @include('admin.parcel.reverse-script')
    @include('admin.parcel.merchant_opt_script')
    @include('admin.parcel.bulk-print-script')
    @include('admin.parcel.delivery_otp_script')
@endpush
@include('live_search.delivery-man')
@include('live_search.merchants')
<script>
    function deliveryReason(reason)
    {
        if(reason == 're_schedule_reasons9'){
            $('#note').show();
        }else{
            $('#note').hide();
        }
    }
</script>
{{--@include('live_search.third-party-live-search')--}}
