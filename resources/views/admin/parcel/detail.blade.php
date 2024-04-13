@extends('master')

@section('title')
    {{__('parcel').' '.__('details')}}
@endsection
@section('style')
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/')}}/css/timeline.css">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/')}}/css/material-design-iconic-font.min.css">
@endsection
@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('parcel_detail')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="row g-gs">
                            <div class="col-md-12 col-sm-12  mb-4">
                                <div class="card">
                                    <div class="nk-ecwg nk-ecwg6">
                                        <div class="card-inner">
                                            <div class="card-title-group">
                                                <div class="card-title">
                                                    <h6 class="title d-flex">#
                                                        <div class="copy-to-clipboard">
                                                            <input readonly type="text" data-text="{{ __('copied') }}" class="text-info" value="{{ $parcel->parcel_no }}">
                                                        </div>
                                                    </h6>
                                                </div>
                                                <div class="card-title">
                                                    <h6 class="title">{{ __($parcel->status) }}{{ $parcel->is_partially_delivered ? ('('.__('partially-delivered').')') : '' }}</h6>
                                                </div>
                                                <div class="card-title">
                                                    <a href="{{ route('admin.parcel.print',$parcel->parcel_no) }}" target="_blank"
                                                       class="btn btn-icon btn-warning"> <em class="icon ni ni-printer"></em> </a>
                                                </div>
                                            </div>
                                        </div><!-- .card-inner -->
                                    </div><!-- .nk-ecwg -->
                                </div><!-- .card -->
                            </div><!-- .col -->
                        </div>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card wave">
                                    <div class="card-header">{{ __('Merchant Details') }}</div>
                                    <div class="card-body">
                                        <span>{{ __('merchant_name') }}:&nbsp;</span> <span
                                            class="text">
                                            {{ (@$parcel->merchant_id == 1802 && $parcel->user->user_type == 'merchant_staff' ) ?@$parcel->user->first_name.' '.@$parcel->user->last_name : $parcel->merchant->user->first_name.' '.$parcel->merchant->user->last_name }}
                                        </span><br>
                                        <span>{{ __('company_name') }}:&nbsp;</span> <span
                                            class="text">{{ $parcel->merchant->company }}</span><br>
                                        <span>{{ __('pickup_number') }}:&nbsp;</span>

                                        <span  class="text">{{ (hasPermission('merchant_read_phone')) ?  $parcel->pickup_shop_phone_number :  Str::mask($parcel->pickup_shop_phone_number, "*", 3).Str::substr($parcel->pickup_shop_phone_number, -2) }}</span>
                                         <br>

                                        <span>{{ __('pickup_address') }}:&nbsp;</span>
                                         <span class="text">{{  (hasPermission('merchant_read_address')) ?  $parcel->pickup_address : Str::mask($parcel->pickup_address, "*", 3)  }}</span><br>

                                        <span>{{ __('email') }}:&nbsp;&nbsp;</span>
                                            <span  class="text">{{ (hasPermission('merchant_read_email'))?  $parcel->merchant->user->email : Str::mask($parcel->merchant->user->email, "*", 3) }}</span><br>

                                        @if($parcel->status != 'pending' && $parcel->status
                                          != 'pickup-assigned' && $parcel->status != 're-schedule-pickup'
                                          && $parcel->status != 'received-by-pickup-man')
                                            @if(!blank($parcel->hub))
                                                <span>{{ __('hub') }}:&nbsp;&nbsp;</span> <span
                                                    class="text">{{ ($parcel->hub->name).' ('.$parcel->hub->address }})</span><br>
                                            @endif
                                        @endif
                                        @if($parcel->status == 'transferred-to-hub')
                                            @if(!blank($parcel->transferToHub))
                                                <span>{{ __('transferring_to') }}:&nbsp;&nbsp;</span> <span
                                                    class="text">{{ ($parcel->transferToHub->name).' ('.$parcel->transferToHub->address }})</span><br>
                                            @endif
                                            @if(!blank($parcel->transferDeliveryMan))
                                                    <span>{{ __('transferring_by') }}:&nbsp;&nbsp;</span> <span
                                                        class="text">{{$parcel->transferDeliveryMan->user->first_name.' '.$parcel->transferDeliveryMan->user->last_name}}</span><br>
                                            @endif
                                        @endif
                                        @if($parcel->status == 'transferred-received-by-hub')
                                            @if(!blank($parcel->transferDeliveryMan))
                                                <span>{{ __('transferred_by') }}:&nbsp;&nbsp;</span> <span
                                                    class="text">{{$parcel->transferDeliveryMan->user->first_name.' '.$parcel->transferDeliveryMan->user->last_name}}</span><br>
                                            @endif
                                        @endif
                                        <span>{{ __('created_at') }}:&nbsp;</span> <span
                                            class="text">{{ date('M d, Y h:i a', strtotime($parcel->created_at)) }}</span><br>
                                        @if($parcel->parcel_type == 'frozen')
                                            <span>{{__('pickup')}}: </span> <span
                                            class="text">{{$parcel->pickup_date != ""? date('M d, Y', strtotime($parcel->pickup_date)):''}} {{$parcel->pickup_time != ""? date('h:i a', strtotime($parcel->pickup_time)):''}}</span>
                                            <span>{{__('delivery')}}: </span> <span
                                            class="text">{{$parcel->delivery_date != ""? date('M d, Y', strtotime($parcel->delivery_date)):''}} {{$parcel->pickup_time != ""? date('h:i a', strtotime($parcel->pickup_time)):''}}</span><br>
                                        @else
                                            <span>{{__('pickup')}}: </span> <span
                                            class="text">{{$parcel->pickup_date != ""? date('M d, Y', strtotime($parcel->pickup_date)):''}}</span><br>
                                            <span>{{__('delivery')}}: </span> <span
                                            class="text">{{$parcel->delivery_date != ""? date('M d, Y', strtotime($parcel->delivery_date)):''}}</span><br>
                                        @endif
                                        @if($parcel->status == 'delivered' || $parcel->status == 'delivered-and-verified')
                                            <span>{{__('delivered_at')}}: </span> <span
                                                class="text">{{$parcel->event != ""? date('M d, Y g:i A', strtotime($parcel->event->created_at)):''}}</span><br>
                                            <span>{{__('delivered_by')}}: </span> <span
                                                class="text">{{$parcel->deliveryMan->user->first_name.' '.$parcel->deliveryMan->user->last_name}}</span><br>
                                        @endif
                                        @if($parcel->status == 'received-by-pickup-man')
                                            <span>{{__('pickup_by')}}: </span> <span
                                                class="text">{{$parcel->pickupMan->user->first_name.' '.$parcel->pickupMan->user->last_name}}</span><br>
                                        @endif
                                        @if($parcel->status == 'received')
                                            <span>{{__('pickup_by')}}: </span> <span
                                                class="text">{{$parcel->pickupMan->user->first_name.' '.$parcel->pickupMan->user->last_name}}</span><br>
                                        @endif
                                        @if($parcel->status == 'pickup-assigned' || $parcel->status == 're-schedule-pickup')
                                            <span>{{__('pickup_man')}}: </span> <span
                                                class="text">{{$parcel->pickupMan != ""? ($parcel->pickupMan->user->first_name.' '.$parcel->pickupMan->user->last_name ):''}}</span><br>
                                        @endif
                                        @if($parcel->status == 'delivery-assigned' || $parcel->status == 're-schedule-delivery')
                                            <span>{{__('delivery_man')}}: </span> <span
                                                class="text">{{$parcel->deliveryMan != ""? ($parcel->deliveryMan->user->first_name.' '.$parcel->deliveryMan->user->last_name ):''}}</span><br>
                                        @endif
                                        <div class="d-flex mt-2">
                                            <span class="btn shadow-lg">{{__('parcel_type')}}:&nbsp;&nbsp;<p
                                                    class="text">{{ __($parcel->parcel_type) }} </p></span>
                                            <span class="btn shadow-lg">{{ __('total_charge') }}:&nbsp;&nbsp;<p
                                                    class="text">{{ number_format($parcel->total_delivery_charge,2)}} </p>{{ __('tk')}} </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <tr>
                                <div class="col-xl-6">
                                    <div class="card wave">
                                        <div class="card-header">{{ __('Customer Details') }}</div>
                                        <div class="card-body">
                                            <span>{{ __('id') }}:&nbsp;&nbsp;</span> <span
                                                class="text">#{{ $parcel->parcel_no }}</span><br>
                                            <span>{{ __('invno') }}:&nbsp;&nbsp;</span> <span
                                                class="text">{{ $parcel->customer_invoice_no }}</span><br>
                                            <span>{{ __('customer_name') }}:&nbsp;&nbsp;</span> <span
                                                class="text">{{ $parcel->customer_name}}</span><br>
                                            <span>{{ __('customer').' '.__('phone') }}:&nbsp;&nbsp;</span> <span
                                                class="text">{{ $parcel->customer_phone_number }}</span><br>

                                            <span>{{ __('customer').' '.__('address') }}:&nbsp;&nbsp;</span> <span
                                                class="text">{{ $parcel->customer_address }}</span><br>
                                            <span>{{ __('location') }}:&nbsp;&nbsp;</span> <span
                                                class="text">{{ __($parcel->location) }}</span><br>

                                            @if($parcel->note !="" && $parcel->note !=NULL)
                                                <span>{{ __('note') }}:&nbsp;&nbsp;</span> <span
                                                    class="text">{{ $parcel->note }}</span><br>
                                            @endif
                                            <div class="d-flex mt-2">
                                                <span class="btn shadow-lg">{{__('weight')}}:&nbsp;&nbsp;<p
                                                        class="text">{{ $parcel->weight.' '.__('kg') }} </p></span>
                                                <span class="btn shadow-lg">{{ __('COD') }}:&nbsp;&nbsp;<p
                                                        class="text">{{ number_format($parcel->price,2)}} </p>{{ __('tk')}} </span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </tr>
                        </div>
                        @if($parcel->events())
                            <div class="card history mt-5">
                                <div class="card-body">
                                    <div class="row history-row">
                                        <div class="col-md-10">
                                            <ul class="cbp_tmtimeline">
                                                @foreach($parcel->events as $event)
                                                    <li>
                                                        <time class="cbp_tmtime" datetime="2017-11-04T18:30"><span>{{ date('M d, Y', strtotime($event->created_at)) }}</span> <span>{{ date('h:i a', strtotime($event->created_at)) }}</span></time>
                                                        @if($event->title == 'parcel_cancel_event')
                                                            <div class="cbp_tmicon bg-blush"><i class="zmdi zmdi-close"></i></div>
                                                            <div class="cbp_tmlabel empty">
                                                                <p>{{ __($event->title)}}</p>
                                                                <p>{{ $event->cancel_note !='' ? __('reason').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'assign_pickup_man_event')
                                                            <div class="cbp_tmicon bg-info"><i class="zmdi zmdi-label"></i></div>
                                                            <div class="cbp_tmlabel">

                                                                @if(@$event->pickupPerson)
                                                                    <p> <strong>{{$event->pickupPerson->user->first_name.' '.$event->pickupPerson->user->last_name}}</strong> {{__('is Assigned for Pickup')}}</p>
                                                                    <p>{{__('pickup_man')}}: <strong><span class="text">{{$event->pickupPerson->user->first_name.' '.$event->pickupPerson->user->last_name}}</span></strong></p>
                                                                    <p>{{__('phone_number')}}: <strong><span class="text">{{ $event->pickupPerson->phone_number}}</span></strong></p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_re_schedule_pickup_event')
                                                            <div class="cbp_tmicon bg-orange"><i class="zmdi zmdi-time-restore"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                @if(@$event->pickupPerson)
                                                                    <p> Reassigned:: <strong>{{$event->pickupPerson->user->first_name.' '.$event->pickupPerson->user->last_name}}</strong> {{__('is Assigned for Pickup')}}</p>
                                                                    <p>{{__('pickup_man')}}: <strong>{{$event->pickupPerson->user->first_name.' '.$event->pickupPerson->user->last_name}}</strong></p>
                                                                    <p>{{__('phone_number')}}: <strong><span class="text">{{ $event->pickupPerson->phone_number}}</strong></p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'assign_delivery_man_event')
                                                            <div class="cbp_tmicon bg-info"><i class="zmdi zmdi-label"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p><strong>"{{$event->deliveryPerson->user->first_name.' '.$event->deliveryPerson->user->last_name}}"</strong> Out for Delivery</p>
                                                                <p>{{__('delivery_man')}}: <strong>{{$event->deliveryPerson->user->first_name.' '.$event->deliveryPerson->user->last_name}}</strong></p>
                                                                <p>{{__('phone_number')}}: <strong>{{ $event->deliveryPerson->phone_number}}</strong></p>
                                                                @if($event->thirdParty)
                                                                    <p>{{ __('third_party') }}: <strong> {{ $event->thirdParty->name.' ('.$event->thirdParty->address.')' }}</strong></p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_re_schedule_delivery_event')
                                                            <div class="cbp_tmicon bg-orange"><i class="zmdi zmdi-time-restore"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p>{{ __($event->title) }}</p>
                                                                <p>{{__('re_schedule_date')}}: <strong>{{ ($event->parcel->delivery_date)? date('d M Y', strtotime($event->parcel->delivery_date)):  '' }}</strong></p>
                                                                <p>{{__('delivery_man')}}: <strong>{{$event->deliveryPerson->user->first_name.' '.$event->deliveryPerson->user->last_name}}</strong></p>
                                                                <p>{{__('phone_number')}}: <strong>{{ $event->deliveryPerson->phone_number}}</strong></p>
                                                                @if($event->thirdParty)
                                                                    <p>{{ __('third_party') }}: <strong> {{ $event->thirdParty->name.' ('.$event->thirdParty->address.')' }}</strong></p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_return_to_greenx_event')
                                                            <div class="cbp_tmicon bg-orange"><em class="icon ni ni-undo"></em> </div>
                                                            <div class="cbp_tmlabel">
                                                                <p>{{ __($event->title) }}</p>
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_return_assign_to_merchant_event')
                                                            <div class="cbp_tmicon bg-info"><i class="zmdi zmdi-label"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p>{{ __($event->title) }}</p>

                                                                @if(@$event->returnPerson)
                                                                    <p>{{__('delivery_man')}}: <strong>{{$event->returnPerson->user->first_name.' '.$event->returnPerson->user->last_name}}</strong></p>
                                                                    <p>{{__('phone_number')}}: <strong>{{ $event->returnPerson->phone_number}}</strong></p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_partial_delivered_event')
                                                            <div class="cbp_tmicon bg-green"><i class="zmdi zmdi-check"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p>{{ __($event->title) }} </p>
                                                                @if(@$event->deliveryPerson)
                                                                    <p>{{__('delivery_man')}}: <strong>{{$event->deliveryPerson->user->first_name.' '.$event->deliveryPerson->user->last_name}}</strong></p>
                                                                    <p>{{__('phone_number')}}: <strong>{{ $event->deliveryPerson->phone_number}}</strong></p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_delivered_event')
                                                            <div class="cbp_tmicon bg-green"><i class="zmdi zmdi-check"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p>{{'Yes!! '}} <strong>{{$event->deliveryPerson->user->first_name.' '.$event->deliveryPerson->user->last_name}}</strong> {{ __($event->title) }} </p>
                                                                @if(@$event->deliveryPerson)
                                                                    <p>{{__('delivery_man')}}: <strong>{{$event->deliveryPerson->user->first_name.' '.$event->deliveryPerson->user->last_name}}</strong></p>
                                                                    <p>{{__('phone_number')}}: <strong>{{ $event->deliveryPerson->phone_number}}</strong></p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_received_event')
                                                            <div class="cbp_tmicon bg-green"><i class="zmdi zmdi-store"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p>{{ __($event->title) }}</p>
                                                                @if(!blank($event->hub))
                                                                    <p>{{ __('hub').': '.$event->hub->name.' ('.$event->hub->address.')' }}</p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_received_by_pickup_man_event')
                                                            <div class="cbp_tmicon bg-green"><i class="zmdi zmdi-check"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p>{{ __($event->title) }}</p>
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_update_event')
                                                                <div class="cbp_tmicon bg-orange"><i class="zmdi zmdi-edit"></i></div>
                                                                <div class="cbp_tmlabel">
                                                                    <p> {{ __($event->title) }}</p>
                                                                    <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                                    @if(!empty($event->additional_info))
                                                                            @php
                                                                                $additional_info    = json_decode($event->additional_info);
                                                                                //dd($event->additional_info);
                                                                                $old_data           = property_exists($additional_info,'parcel_old_data') ? (array)$additional_info->parcel_old_data : [];
                                                                                $new_data           = property_exists($additional_info,'parcel_new_data') ? (array)$additional_info->parcel_new_data : [];
                                                                                //dd((array)$old_data);
                                                                            @endphp
                                                                        <table class="table">
                                                                            <thead><tr><th>Title</th><th>Previus Data</th><th>New Data</th></tr></thead>
                                                                            @foreach ($old_data as $key => $old)
                                                                                @if($old != $new_data[$key] && $key !=  'updated_at' && $key !=  'payable' && $old && $new_data[$key])
                                                                                    <tr>
                                                                                        <td>{{__($key)}}</td>
                                                                                        <td>{{$old}}</td>
                                                                                        <td>{{$new_data[$key]}}</td>
                                                                                    </tr>
                                                                                @endif

                                                                            @endforeach
                                                                        </table>
                                                                    @endif
                                                                </div>
                                                        @elseif($event->title == 'parcel_re_request_event')
                                                                <div class="cbp_tmicon bg-orange"><i class="zmdi zmdi-edit"></i></div>
                                                                <div class="cbp_tmlabel">
                                                                    <p> {{ __($event->title) }}</p>
                                                                    <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                    <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                                </div>
                                                        @elseif($event->title == 'parcel_returned_to_merchant_event')
                                                                <div class="cbp_tmicon bg-green"><i class="zmdi zmdi-check"></i></div>
                                                                <div class="cbp_tmlabel"><p> {{ __($event->title) }}
                                                                    <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                    <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                                </div>
                                                        @elseif($event->title == 'parcel_transferred_to_hub_assigned_event')
                                                            <div class="cbp_tmicon bg-green"><i class="zmdi zmdi-local-shipping"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p>Parcel Transferred to <strong>{{ $event->hub->name }}</strong></p>
                                                                @if(!blank($event->hub))
                                                                    <p>{{ __('transferring_to').': '.$event->hub->name.' ('.$event->hub->address.')' }}</p>
                                                                @endif
                                                                @if(!blank($event->transferPerson))
                                                                    <p>{{ __('transferring_by').': '.$event->transferPerson->user->first_name.' '.$event->transferPerson->user->last_name }}</p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @elseif($event->title == 'parcel_transferred_to_hub_event')
                                                            <div class="cbp_tmicon bg-green"><i class="zmdi zmdi-local-store"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p><strong>{{ $event->hub->name }}</strong> Received Parcel in Good Condition.</p>
                                                                @if(!blank($event->hub))
                                                                    <p>{{ __('hub').': '.$event->hub->name.' ('.$event->hub->address.')' }}</p>
                                                                @endif
                                                                @if(!blank($event->transferPerson))
                                                                    <p>{{ __('transferred_by').': '.$event->transferPerson->user->first_name.' '.$event->transferPerson->user->last_name }}</p>
                                                                @endif
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>

                                                        @else
                                                            <div class="cbp_tmicon"><i class="zmdi zmdi-check"></i></div>
                                                            <div class="cbp_tmlabel">
                                                                <p>{{ __($event->title) }}</p>
                                                                <p>{{ $event->cancel_note !='' ? __('note').': '. $event->cancel_note : '' }}</p>
                                                                <p>{{ ' '.__('processed_by').': '}}{{$event->user->first_name.' '.$event->user->last_name.' '}} ({{ $event->user->user_type == 'delivery' ? __('delivery_man') :  __($event->user->user_type)}})</p>
                                                            </div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
