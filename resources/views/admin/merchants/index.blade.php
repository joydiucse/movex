@extends('master')

@section('title')
    {{__('merchant').' '.__('lists')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                                <div class="nk-block-des text-soft">
                                    <p>{{__('you_have_total')}} {{ $merchants->total() }} {{__('merchants')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            @if(hasPermission('merchant_create'))
                            <div class="nk-block-head-content">
                                <a href="{{route('merchant.create')}}" class="btn btn-primary  d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                            @endif
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner position-relative card-tools-toggle">
                                    <div class="card-title-group">
                                        <div class="card-tools">

                                        </div><!-- .card-tools -->
                                        <div class="card-tools mr-n1">
                                            <ul class="btn-toolbar gx-1">
                                                <li>
                                                    <div class="toggle-wrap">
                                                        <a href="#" class="btn btn-icon btn-trigger toggle" data-target="cardTools"><em class="icon ni ni-menu-right"></em></a>
                                                        <div class="toggle-content" data-content="cardTools">
                                                            <form action="{{route('merchant.filter')}}" method="GET">
{{--                                                                @csrf--}}
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
                                                                                            <input type="text" class="form-control" name="company_name" placeholder="{{__('enter_company_name')}}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <select class="form-select form-select-sm" name="status">
                                                                                                <option value="any">{{__('any_status')}}</option>
                                                                                                <option value="1">{{__('active')}}</option>
                                                                                                <option value="0">{{__('inactive')}}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <select class="form-select form-select-sm" name="approval_status">
                                                                                                <option value="any">{{__('any_approval_status')}}</option>
                                                                                                <option value="1">{{__('approved')}}</option>
                                                                                                <option value="0">{{__('pending')}}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <select class="form-select form-select-sm" name="sort_by">
                                                                                                <option value="any">{{__('sort_by')}}</option>
                                                                                                <option value="rank">{{__('rank')}}</option>
                                                                                                <option value="oldest_on_top">{{__('oldest_on_top')}}</option>
                                                                                                <option value="newest_on_top">{{__('newest_on_top')}}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <div class="form-group">
                                                                                            <select class="form-select form-select-sm" name="hub">
                                                                                                <option value="all">{{__('all').' '.__('hub')}}</option>
                                                                                                <option value="pending">{{__('pending')}}</option>
                                                                                                @if(hasPermission('read_all_merchant'))
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
                                                                                        <div class="form-group">
                                                                                            <button type="submit" class="btn btn-primary">{{__('filter')}}</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div><!-- .filter-wg -->
                                                                    </div><!-- .dropdown -->
                                                                </li><!-- li -->
                                                            </ul><!-- .btn-toolbar -->
                                                            </form>
                                                        </div><!-- .toggle-content -->
                                                    </div><!-- .toggle-wrap -->
                                                </li><!-- li -->
                                            </ul><!-- .btn-toolbar -->
                                        </div><!-- .card-tools -->
                                    </div><!-- .card-title-group -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-0">
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col column-max-width"><span class="sub-text"><strong>{{__('company_user')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('trade_license')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('parcels')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg column-max-width"><span class="sub-text"><strong>{{__('parcel').' '.__('pickup_hub')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg column-max-width"><span class="sub-text"><strong>{{__('website')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('phone')}}</strong></span></div>
                                            @if(hasPermission('merchant_update'))
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                            @endif
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('unpaid_amount')}}</strong></span></div>
                                            @if(hasPermission('merchant_update') || hasPermission('merchant_delete') || hasPermission('download_closing_report'))
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                            @endif
                                        </div><!-- .nk-tb-item -->
                                        @foreach($merchants as $key => $merchant)
                                            <div class="nk-tb-item" id="row_{{$merchant->id}}">
                                                <div class="nk-tb-col">
                                                    <span>{{$key + 1}}</span>
                                                </div>
                                                <div class="nk-tb-col column-max-width">
                                                    <a href="{{route('detail.merchant.personal.info', $merchant->id)}}">
                                                        <div class="user-card">
                                                            <div class="user-avatar bg-primary">
                                                                @if(!blank($merchant->user->image) && file_exists($merchant->user->image->image_small_two))
                                                                    <img src="{{asset($merchant->user->image->image_small_two)}}" alt="{{$merchant->user->first_name}}">
                                                                @else
                                                                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$merchant->user->first_name}}">
                                                                @endif
                                                            </div>
                                                            <div class="user-info">
                                                                <span class="tb-lead">{{$merchant->user->first_name.' '.$merchant->user->last_name .'('.$merchant->company.')'}} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                                <span>{{$merchant->user->email}}</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="nk-tb-col tb-col-lg">
                                                    <span>
                                                        @if(!blank($merchant->trade_license) && file_exists($merchant->trade_license))
                                                            <a href="{{ asset($merchant->trade_license) }}" target="_blank"> <em class="icon ni ni-link-alt"></em> {{ __('trade_license') }}</a>
                                                        @else
                                                            {{ __('not_available') }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    <span  class=" text-info">{{__('total_parcels').': '. $merchant->parcels->count() }}</span><br>
                                                    <span  class=" text-success">{{__('total_delivered').': '. $merchant->parcels->whereIn('status', ['delivered','delivered-and-verified'])->count() }}</span><br>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg column-max-width">
                                                    <span>
                                                       @if(!blank($merchant->user->hub))
                                                            {{ $merchant->user->hub->name.' ('.$merchant->user->hub->address.')' }}
                                                        @else
                                                            {{ __('not_available') }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg column-max-width">
                                                    <span>
                                                       @if(!blank($merchant->website))
                                                            <a href="{{ $merchant->website }}" target="_blank"> <em class="icon ni ni-link-alt"></em> {{ $merchant->website }}</a>
                                                        @else
                                                            {{ __('not_available') }}
                                                        @endif
                                                    </span>
                                                </div>

                                                <div class="nk-tb-col tb-col-lg">
                                                    <span>{{ (hasPermission('merchant_read_phone')) ?  $merchant->phone_number : Str::mask($merchant->phone_number, "*", 3).Str::substr($merchant->phone_number, -2)}}</span>
                                                </div>
                                                @if(hasPermission('merchant_update'))
                                                <div class="nk-tb-col tb-col-lg">
                                                    <div class="preview-block">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input status-change" {{ $merchant->status ? 'checked' :'' }} value="merchant-status/{{$merchant->id}}" id="customSwitch2-{{$merchant->id}}">
                                                            <label class="custom-control-label" for="customSwitch2-{{$merchant->id}}"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <div class="nk-tb-col tb-col-lg">
                                                    {{number_format($merchant->balance($merchant->id),2) .' '.__('tk')}}
                                                </div>
                                                @if(hasPermission('merchant_update') || hasPermission('merchant_delete') || hasPermission('download_closing_report'))
                                                <div class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                        <li>
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        @if(hasPermission('merchant_update'))
                                                                            <li><a href="{{route('merchant.edit', $merchant->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                        @endif
                                                                        @if(hasPermission('merchant_delete'))
                                                                            <li><a href="javascript:void(0);" onclick="delete_row('merchant/delete/', {{$merchant->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                                                                        @endif
                                                                        @if(hasPermission('download_closing_report'))
                                                                            <li><a href="{{ route('admin.merchant.closing.report', $merchant->id) }}"><em class="icon ni ni-download"></em> <span> {{__('closing_report')}} </span></a></li>
                                                                        @endif
                                                                        @if(hasPermission('merchant_read'))
                                                                        <li><a href="{{ route('admin.merchant.edit.log', $merchant->id) }}"><em class="icon ni ni-arrow-right"></em> <span> {{__('history')}} </span></a></li>
                                                                    @endif
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                @endif

                                            </div><!-- .nk-tb-item -->
                                        @endforeach
                                    </div><!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-2">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {!! $merchants->appends(Request::except('page'))->links() !!}
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
@endsection

@include('common.delete-ajax')
@include('common.change-status-ajax')
