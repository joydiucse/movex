@extends('master')

@section('title')
{{__('delivery_man')}} {{__('lists')}}
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
                                <p>{{__('you_have_total')}} {{ !blank($delivery_men)? $delivery_men->total():'0' }} {{__('delivery_man')}}.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            @if(hasPermission('deliveryman_create'))
                                <a href="{{route('delivery.man.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
                            @endif
                        </div><!-- .nk-block-head-content -->
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
                                                        <form action="{{route('delivery.man.filter')}}" method="GET">
{{--                                                            @csrf--}}
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
                                                                                        <input type="text" class="form-control" name="name" placeholder="{{__('name')}}">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <input type="text" class="form-control" name="email" placeholder="{{__('email')}}">
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
                                                                                        <select class="form-select form-select-sm" name="hub">
                                                                                            <option value="all">{{__('all').' '.__('hub')}}</option>
                                                                                            <option value="pending">{{__('pending')}}</option>
                                                                                            @if(hasPermission('read_all_delivery_man'))
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
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('name').'/'.__('email')}}</strong></span></div>
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('phone')}}</strong></span></div>
                                        <div class="nk-tb-col tb-col-lg column-max-width"><span class="sub-text"><strong>{{__('hub')}}</strong></span></div>
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('address')}}</strong></span></div>
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('driving_license')}}</strong></span></div>
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('last_login')}}</strong></span></div>
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('pick_up_fee')}}</strong></span></div>
                                        <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('delivery_fee')}}</strong></span></div>
                                        <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('return_fee')}}</strong></span></div>

                                        @if(hasPermission('deliveryman_update'))
                                             <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('vertual')}}</strong></span></div>
                                             <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('shuttel')}}</strong></span></div>
                                            <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                        @endif
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_amount')}}</strong></span></div>
                                        @if(hasPermission('deliveryman_update') || hasPermission('deliveryman_delete'))
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                        @endif
                                    </div><!-- .nk-tb-item -->
                                    @foreach($delivery_men as $key => $delivery_man)
                                    <div class="nk-tb-item" id="row_{{$delivery_man->id}}">
                                        <div class="nk-tb-col tb-col-md">
                                            <span>{{$key + 1}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <a href="{{route('detail.delivery.man.personal.info', $delivery_man->id)}}">
                                                <div class="user-card">
                                                    <div class="user-avatar bg-primary">
                                                        @if(!blank($delivery_man->user->image) && file_exists($delivery_man->user->image->image_small_two))
                                                            <img src="{{asset($delivery_man->user->image->image_small_two)}}" alt="{{$delivery_man->user->first_name}}">
                                                        @else
                                                        <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$delivery_man->user->first_name}}">
                                                        @endif
                                                    </div>
                                                    <div class="user-info">
                                                        <span class="tb-lead">{{$delivery_man->user->first_name.' '.$delivery_man->user->last_name}} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                        <span>{{$delivery_man->user->email}}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="nk-tb-col  tb-col-lg">
                                            {{$delivery_man->phone_number}}
                                        </div>
                                        <div class="nk-tb-col tb-col-lg column-max-width">
                                                    <span>
                                                       @if(!blank($delivery_man->user->hub))
                                                            {{ $delivery_man->user->hub->name.' ('.$delivery_man->user->hub->address.')' }}
                                                        @else
                                                            {{ __('not_available') }}
                                                        @endif
                                                    </span>
                                        </div>
                                        <div class="nk-tb-col  tb-col-lg">
                                            {{$delivery_man->address}}
                                        </div>
                                        <div class="nk-tb-col  tb-col-lg">
                                            <span>
                                                @if(!blank($delivery_man->driving_license) && file_exists($delivery_man->driving_license))
                                                    <a href="{{ asset($delivery_man->driving_license) }}" target="_blank"> <em class="icon ni ni-link-alt"></em> {{ __('driving_license') }}</a>
                                                @else
                                                    {{ __('not_available') }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="nk-tb-col  tb-col-lg">
                                            {{$delivery_man->user->last_login != ""? date('M y, Y h:i a', strtotime($delivery_man->user->last_login)):''}}
                                        </div>


                                        <div class="nk-tb-col  tb-col-lg">
                                            {{$delivery_man->pick_up_fee == ""? '0.00':number_format($delivery_man->pick_up_fee,2)}}{{' '.__('tk')}}
                                        </div>
                                        <div class="nk-tb-col  tb-col-lg">
                                            {{$delivery_man->delivery_fee  == ""? '0.00':number_format($delivery_man->delivery_fee,2)}}{{' '.__('tk')}}
                                        </div>
                                        <div class="nk-tb-col  tb-col-lg">
                                            {{$delivery_man->return_fee  == ""? '0.00':number_format($delivery_man->return_fee,2)}}{{' '.__('tk')}}
                                        </div>

                                        @if(hasPermission('deliveryman_update'))
                                        <div class="nk-tb-col  tb-col-lg">
                                            <div class="preview-block">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input status-change" {{ $delivery_man->is_vertual ? 'checked' :'' }} value="delivery-man-vertual/{{$delivery_man->id}}" id="customSwitch3-{{$delivery_man->id}}">
                                                    <label class="custom-control-label" for="customSwitch3-{{$delivery_man->id}}"></label>
                                                </div>
                                            </div>
                                        </div> <!-- Vertual delivery man -->

                                        <div class="nk-tb-col  tb-col-lg">
                                            <div class="preview-block">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input status-change" {{ $delivery_man->is_shuttle ? 'checked' :'' }} value="delivery-man-shuttle/{{$delivery_man->id}}" id="customSwitch4-{{$delivery_man->id}}">
                                                    <label class="custom-control-label" for="customSwitch4-{{$delivery_man->id}}"></label>
                                                </div>
                                            </div>
                                        </div> <!-- shuttle delivery man -->

                                        <div class="nk-tb-col  tb-col-lg">
                                            <div class="preview-block">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input status-change" {{ $delivery_man->user->status ? 'checked' :'' }} value="delivery-man-status/{{$delivery_man->user_id}}" id="customSwitch2-{{$delivery_man->id}}">
                                                    <label class="custom-control-label" for="customSwitch2-{{$delivery_man->id}}"></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="nk-tb-col">
                                            {{number_format($delivery_man->balance($delivery_man->id),2) .' '.__('tk')}}
                                        </div>
                                        @if(hasPermission('deliveryman_update') || hasPermission('deliveryman_delete'))
                                        <div class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <ul class="link-list-opt no-bdr">
                                                                @if(hasPermission('deliveryman_update'))
                                                                <li><a href="{{route('delivery.man.edit', $delivery_man->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                @endif
                                                                @if(hasPermission('deliveryman_delete'))
                                                                <li><a href="javascript:void(0);" onclick="delete_row('delivery-man-delete/', {{$delivery_man->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                                                                @endif
                                                                @if(hasPermission('deliveryman_read'))
                                                                    <li><a href="{{ route('admin.delivery.edit.log', $delivery_man->id) }}"><em class="icon ni ni-arrow-right"></em> <span> {{__('history')}} </span></a></li>
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
                                        {!! !blank($delivery_men)? $delivery_men->links():'' !!}
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

@push('script')
@include('common.delete-ajax')
@include('common.change-status-ajax')
@endpush
