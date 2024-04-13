<form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.update.shop') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.update.shop') : route('admin.merchant.update.shop'))}}"
      class="form-validate" method="POST" id="edit-shop-form">
    @csrf

    <div class="tab-content">
        <div class="tab-pane active" id="personal">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="shop_name">{{__('shop_name')}}</label>
                        <input type="text" name="shop" value="{{ $shop->id }}" hidden>
                        @if(Sentinel::getUser()->user_type == 'staff' && hasPermission('merchant_shop_name_update'))
                            <input type="text"  name="shop_name" class="form-control form-control-lg" value="{{ old('shop_name') ? old('shop_name') : $shop->shop_name }}" id="shop_name" placeholder="{{__('shop_name')}}" required>
                        @else
                            <input type="text" readonly name="shop_name" class="form-control form-control-lg" value="{{ old('shop_name') ? old('shop_name') : $shop->shop_name }}" id="shop_name" placeholder="{{__('shop_name')}}" required>
                        @endif
                    </div>
                    @if($errors->has('shop_name'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('shop_name') }}</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="contact_number">{{__('contact_number')}}</label>
                        <input type="text" name="contact_number" class="form-control form-control-lg" value="{{ old('contact_number') ? old('contact_number') : $shop->contact_number}}" id="contact_number" placeholder="{{__('contact_number')}}" required>
                    </div>
                    @if($errors->has('contact_number'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('contact_number') }}</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="shop_phone_number">{{__('pickup_number')}}</label>
                        <input type="text" name="shop_phone_number" class="form-control form-control-lg" value="{{ old('shop_phone_number') ? old('shop_phone_number') : $shop->shop_phone_number}}" id="shop_phone_number" placeholder="{{__('pickup_number')}}" required>
                    </div>
                    @if($errors->has('shop_phone_number'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('shop_phone_number') }}</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="address">{{__('pickup_address')}}</label>
                        <textarea name="address" class="form-control form-control-lg">{{ old('address') ? old('address') : $shop->address }}</textarea>
                    </div>
                    @if($errors->has('address'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('address') }}</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('update')}}</button>
                    </div>
                </div>
            </div>
        </div><!-- .tab-pane -->
    </div><!-- .tab-content -->
</form>
