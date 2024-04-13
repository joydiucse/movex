<form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.update.shop_name') : (Sentinel::getUser()->user_type == 'merchant_staff' ? route('merchant.staff.update.shop_name') : '')}}"
      class="form-validate" method="POST" id="edit-shop-form">
    @csrf

    <div class="tab-content">
        <div class="tab-pane active" id="personal">
            <div class="row gy-4">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="shop_name">{{__('shop_name')}}</label>
                        <input type="text" name="shop" value="{{ $shop->id }}" hidden>
                        <input type="text" name="request_name" class="form-control form-control-lg" value="{{ old('shop_name') ? old('shop_name') : $shop->shop_name }}" id="shop_name" placeholder="{{__('shop_name')}}" required>
                        
                    </div>
                    @if($errors->has('shop_name'))
                        <div class="nk-block-des text-danger">
                            <p>{{ $errors->first('shop_name') }}</p>
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
