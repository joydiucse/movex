@if($status == 'pickup-assigned')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending" selected>{{__('pending')}}</option>
@elseif($status == 'deleted')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending" selected>{{__('pending')}}</option>
@elseif($status == 're-schedule-pickup')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
@elseif($status == 'received-by-pickup-man')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
@elseif($status == 'received')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
@elseif($status == 'transferred-to-hub')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
@elseif($status == 'transferred-received-by-hub')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
@elseif($status == 'delivery-assigned')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
@elseif($status == 're-schedule-delivery')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
@elseif($status == 'returned-to-greenx' && $is_partially_delivered)
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
    <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
    <option value="partially-delivered">{{__('partially-delivered')}}</option>
@elseif($status == 'returned-to-greenx')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
    <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
@elseif($status == 'return-assigned-to-merchant' && $is_partially_delivered)
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
    <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
    <option value="partially-delivered">{{__('partially-delivered')}}</option>
    <option value="returned-to-greenx">{{__('returned-to-greenx')}}</option>
@elseif($status == 'return-assigned-to-merchant')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
    <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
    <option value="returned-to-greenx">{{__('returned-to-greenx')}}</option>
@elseif($status == 'returned-to-merchant' && $is_partially_delivered)
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
    <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
    <option value="partially-delivered">{{__('partially-delivered')}}</option>
    <option value="returned-to-greenx">{{__('returned-to-greenx')}}</option>
    <option value="return-assigned-to-merchant">{{__('return-assigned-to-merchant')}}</option>
@elseif($status == 'returned-to-merchant')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
    <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
    <option value="returned-to-greenx">{{__('returned-to-greenx')}}</option>
    <option value="return-assigned-to-merchant">{{__('return-assigned-to-merchant')}}</option>
@elseif($status == 'delivered' || $status == 'partially-delivered')
    <option value="">{{ __('select_status') }}</option>
    <option value="pending">{{__('pending')}}</option>
    <option value="pickup-assigned">{{__('pickup-assigned')}}</option>
    <option value="received-by-pickup-man">{{__('received-by-pickup-man')}}</option>
    <option value="received">{{__('received_by_warehouse')}}</option>
    <option value="transferred-received-by-hub">{{__('transferred-received-by-hub')}}</option>
    <option value="delivery-assigned">{{__('delivery-assigned')}}</option>
@endif
