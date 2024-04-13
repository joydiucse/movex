<div class="nk-tb-col nk-tb-col-tools">
    <ul class="nk-tb-actions gx-1">
        <li>
            <span type="button" class="btn btn-icon btn-trigger btn-tooltip" data-original-title="{{__('copy')}}" onclick="copyInput('{{ $parcel->parcel_no }}')"><em class="icon ni ni-copy"></em></span>
        </li>
        <li>
            <div class="dropdown">
                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger btn-tooltip" data-original-title="{{__('options')}}" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <ul class="link-list-opt no-bdr">
                        @if(hasPermission('parcel_update'))
                            @if($parcel->status == 'pending'
                            || $parcel->status == 'pickup-assigned'
                            || $parcel->status == 're-schedule-pickup'
                            || $parcel->status == 'received-by-pickup-man'
                            || $parcel->status == "received"
                            || $parcel->status == "transferred-to-hub"
                            || $parcel->status == "delivery-assigned"
                            || $parcel->status == "re-schedule-delivery"
                            || ($parcel->status == "returned-to-greenx" && $parcel->is_partially_delivered == false)
                            || ($parcel->status == "return-assigned-to-merchant" && $parcel->is_partially_delivered == false
                            || hasPermission('send_to_paperfly'))
                            )
                                <li><a href="{{route('parcel.edit', $parcel->parcel_no)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                            @endif
                        @endif
                        @if(($parcel->status != "pending" && $parcel->status != 'delivered-and-verified') && hasPermission('parcel_backward'))
                            @if($parcel->status == 'cancel' || $parcel->status == 'deleted')
                                <li><a href="javascript:void(0);" class="reverse-from-cancel" id="reverse-from-cancel" data-toggle="modal" data-target="#parcel-reverse-from-cancel"><em class="icon ni ni-arrow-left"></em> <span> {{__('backward').' ('.__($parcel->status_before_cancel)}}) </span></a></li>
                            @else
                                <li><a href="javascript:void(0);" class="delivery-reverse" id="reverse-delivery" data-toggle="modal" data-target="#delivery-reverse" data-url="{{ route('parcel.reverse.options') }}"><em class="icon ni ni-arrow-left"></em> <span> {{__('backward')}} </span></a></li>
                            @endif
                        @endif

                        @if(($parcel->status == "cancel") &&
                        ($parcel->status_before_cancel == 'received-by-pickup-man' ||
                        $parcel->status_before_cancel == 'received' ||
                        $parcel->status_before_cancel == 'transferred-to-hub' ||
                        $parcel->status_before_cancel == 'transferred-received-by-hub' ||
                        $parcel->status_before_cancel == 'delivery-assigned' ||
                        $parcel->status_before_cancel == 're-schedule-delivery'))

                            @if(hasPermission('parcel_returned_to_greenx'))
                                <li><a href="javascript:void(0);" class="delivery-return" id="delivery-return" data-toggle="modal" data-target="#return-delivery"><em class="icon ni ni-plus"></em> <span> {{__('returned_to_greenx')}} </span></a></li>
                            @endif

                        @endif


                        @if($parcel->status == "pending")
                            @if(hasPermission('parcel_pickup_assigned'))
                                <li><a href="javascript:void(0);" class="assign-pickup-man" id="assign-pickup-man" data-toggle="modal" data-target="#assign-pickup"><em class="icon ni ni-plus"></em> <span> {{__('assign_pickup_man')}} </span></a></li>
                            @endif
                            {{-- parcel delete --}}
                            @if(hasPermission('parcel_delete'))
                                <li><a href="javascript:void(0);" class="delete-parcel" id="delete-parcel" data-toggle="modal" data-target="#parcel-delete"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                            @endif
                                {{-- parcel delete --}}
                        @elseif($parcel->status == "pickup-assigned" || $parcel->status == "re-schedule-pickup")
                            @if(hasPermission('parcel_reschedule_pickup'))
                                <li><a href="javascript:void(0);" class="reschedule-pickup" id="reschedule-pickup" data-toggle="modal" data-target="#re-schedule-pickup"><em class="icon ni ni-plus"></em> <span> {{__('re_schedule_pickup')}} </span></a></li>
                            @endif

                            @if(hasPermission('parcel_received'))
                                <li><a href="javascript:void(0);" class="receive-parcel-pickup" id="receive-parcel-pickup" data-toggle="modal" data-target="#parcel-receive-by-pickupman"><em class="icon ni ni-plus"></em> {{__('received_by_pickup_man')}} </span></a></li>
                            @endif

                            @if(hasPermission('parcel_received'))
                                <li><a href="javascript:void(0);" class="receive-parcel" id="receive-parcel" data-toggle="modal" data-target="#parcel-receive"><em class="icon ni ni-plus"></em> {{__('received_by_warehouse')}} </span></a></li>
                            @endif
                            {{-- parcel delete --}}
                            @if(hasPermission('parcel_delete'))
                            <li><a href="javascript:void(0);" class="delete-parcel" id="delete-parcel" data-toggle="modal" data-target="#parcel-delete"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                            @endif
                            {{-- parcel delete --}}

                        @elseif($parcel->status == "received-by-pickup-man")

                            @if(hasPermission('parcel_received'))
                                <li><a href="javascript:void(0);" class="receive-parcel" id="receive-parcel" data-toggle="modal" data-target="#parcel-receive"><em class="icon ni ni-plus"></em> {{__('received_by_warehouse')}} </span></a></li>
                            @endif
                            @if(hasPermission('parcel_cancel'))
                                <li><a href="javascript:void(0);" class="cancel-parcel" id="cancel-parcel" data-toggle="modal" data-target="#parcel-cancel"><em class="icon ni ni-cross"></em> <span> {{__('cancel')}} </span></a></li>
                            @endif

                        @elseif($parcel->status == "received" || $parcel->status == 'transferred-received-by-hub' || $parcel->status == 're-schedule-delivery')
                            @if(hasPermission('parcel_transfer_to_hub'))
                                <li><a href="javascript:void(0);" class="transfer-to-hub" id="transfer-to-hub" data-url="{{ route('parcel.transfer.options') }}"  data-toggle="modal" data-target="#parcel-transfer-to-hub"><em class="icon ni ni-plus"></em> <span> {{__('transfer_to_hub')}} </span></a></li>
                            @endif
                            @if(hasPermission('parcel_delivery_assigned'))
                                <li><a href="javascript:void(0);" class="assign-delivery-man" id="assign-delivery-man" data-toggle="modal" data-target="#assign-delivery"><em class="icon ni ni-plus"></em> <span> {{__('assign_delivery_man')}} </span></a></li>
                            @endif
                            @if(hasPermission('parcel_cancel'))
                                <li><a href="javascript:void(0);" class="cancel-parcel" id="cancel-parcel" data-toggle="modal" data-target="#parcel-cancel"><em class="icon ni ni-cross"></em> <span> {{__('cancel')}} </span></a></li>
                            @endif

                        @elseif($parcel->status == "transferred-to-hub")
                            @if(hasPermission('parcel_transfer_receive_to_hub'))
                                <li><a href="javascript:void(0);" class="transfer-receive-to-hub" id="transfer-receive-to-hub" data-toggle="modal" data-target="#parcel-transfer-receive-to-hub"><em class="icon ni ni-plus"></em> <span> {{__('transfer_receive_to_hub')}} </span></a></li>
                            @endif
                            @if(hasPermission('parcel_cancel'))
                                <li><a href="javascript:void(0);" class="cancel-parcel" id="cancel-parcel" data-toggle="modal" data-target="#parcel-cancel"><em class="icon ni ni-cross"></em> <span> {{__('cancel')}} </span></a></li>
                            @endif
                        @elseif($parcel->status == "delivery-assigned" || $parcel->status == "re-schedule-delivery")
                            @if(hasPermission('parcel_reschedule_delivery'))
                                <li><a href="javascript:void(0);" class="reschedule-delivery" id="reschedule-delivery" data-toggle="modal" data-target="#re-schedule-delivery"><em class="icon ni ni-plus"></em> <span> {{__('re-schedule-delivery')}}</span></a></li>
                            @endif
                            @if(hasPermission('parcel_returned_to_greenx'))
                                <li><a href="javascript:void(0);" class="delivery-return" id="delivery-return" data-toggle="modal" data-target="#return-delivery"><em class="icon ni ni-plus"></em> <span> {{__('returned_to_greenx')}} </span></a></li>
                            @endif
                            @if(hasPermission('parcel_delivered'))
                                    <li><a href="javascript:void(0);" class="delivery-parcel-partially" id="delivery-parcel-partially" data-toggle="modal" data-target="#parcel-delivered-partially"><em class="icon ni ni-plus"></em> <span> {{__('partially-delivered')}} </span></a></li>
                                    {{-- <li><a href="javascript:void(0);" class="delivery-parcel" id="delivery-parcel" data-toggle="modal" data-target="#parcel-delivered"><em class="icon ni ni-plus"></em> <span> {{__('delivered')}} </span></a></li> --}}
                                    <li><a href="javascript:void(0);" class="delivery-parcel" id="delivery-parcel"><em class="icon ni ni-plus"></em> <span> {{__('delivered')}} </span></a></li>
                            @endif
                            @if(hasPermission('parcel_cancel'))
                                <li><a href="javascript:void(0);" class="cancel-parcel" id="cancel-parcel" data-toggle="modal" data-target="#parcel-cancel"><em class="icon ni ni-cross"></em> <span> {{__('cancel')}} </span></a></li>
                            @endif
                        @elseif($parcel->status == 'partially-delivered')
                            @if(hasPermission('parcel_returned_to_greenx'))
                                <li><a href="javascript:void(0);" class="delivery-return" id="delivery-return" data-toggle="modal" data-target="#return-delivery"><em class="icon ni ni-plus"></em> <span> {{__('returned_to_greenx')}} </span></a></li>
                            @endif
                        @elseif($parcel->status == "returned-to-greenx")
                            @if(hasPermission('parcel_return_assigned_to_merchant'))
                                <li><a href="javascript:void(0);" class="return-assign-to-merchant" id="return-assign-to-merchant" data-toggle="modal" data-target="#return-assign-tomerchant"><em class="icon ni ni-plus"></em> <span> {{__('return_assign_to_merchant')}} </span></a></li>
                            @endif
                        @elseif($parcel->status == "return-assigned-to-merchant")
                            @if(hasPermission('parcel_returned_to_merchant'))
                                {{-- <li><a href="javascript:void(0);" class="parcel-returned-to-merchant" id="parcel-returned-to-merchant" data-toggle="modal" data-target="#returned-to-merchant"><em class="icon ni ni-plus"></em> <span> {{__('returned_to_merchant')}} </span></a></li> --}}
                                <li><a href="javascript:void(0);" class="parcel-returned-to-merchant" id="parcel-returned-to-merchant"><em class="icon ni ni-plus"></em> <span> {{__('returned_to_merchant')}} </span></a></li>
                            @endif

                        @endif
                        <li><a href="{{ route('admin.parcel.detail',$parcel->parcel_no) }}"> <em class="icon ni ni-eye-alt"></em><span> {{__('view')}}</span> </a></li>
                        @if(hasPermission('parcel_create'))
                            <li><a href="{{ route('admin.parcel.duplicate',$parcel->parcel_no) }}"> <em class="icon ni ni-copy"></em><span> {{__('duplicate')}}</span> </a></li>
                        @endif
                        @if($parcel->status == "received" || $parcel->status == 'transferred-received-by-hub')
                            @if($parcel->tracking_number == '' && hasPermission('send_to_paperfly' && $parcel->location!= 'dhaka') && ($parcel->hub_id == 1 || $parcel->hub_id == 7))
                                <li><a href="javascript:void(0);" class="create-paperfly-parcel" data-url="{{ route('admin.get-district') }}" id="create-paperfly-parcel" data-toggle="modal" data-target="#create-paperflyparcel"><em class="icon ni ni-plus"></em> <span> {{__('send_to_paperfly')}} </span></a></li>
                            @endif
                        @endif

                        <li><a href="{{ route('admin.parcel.sticker',$parcel->parcel_no) }}" target="_blank"> <em class="icon ni ni-printer"></em><span> {{__('print_sticker')}}</span> </a></li>
                        <li><a href="{{ route('admin.parcel.print',$parcel->parcel_no) }}" target="_blank"> <em class="icon ni ni-printer"></em><span> {{__('print_sheet')}}</span> </a></li>
                        <li><a href="{{ route('admin.parcel.return-sticker',$parcel->parcel_no) }}" target="_blank"> <em class="icon ni ni-printer"></em><span> {{__('print_return_sticker')}}</span> </a></li>
                        <li><a href="{{ route('admin.parcel.return-print',$parcel->parcel_no) }}" target="_blank"> <em class="icon ni ni-printer"></em><span> {{__('print_return_sheet')}}</span> </a></li>
                        @if($parcel->status == "pickup-assigned" || $parcel->status == "re-schedule-pickup")
                            <li><a href="{{ route('admin.parcel.notify.pickupman',$parcel->parcel_no) }}"> <em class="icon ni ni-notify"></em><span> {{__('notify_pickup_man')}}</span> </a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>
