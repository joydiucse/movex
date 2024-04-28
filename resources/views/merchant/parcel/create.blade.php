@extends('master')

@section('title')
    {{ (@$parcel ? __('duplicate'): __('add')).' '.__('parcel')}}
@endsection
@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{@$parcel ? __('duplicate'): __('add')}} {{__('parcel')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                               <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.store') : route('merchant.staff.parcel.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        <input type="hidden" value="{{Sentinel::getUser()->user_type == 'merchant' ? Sentinel::getUser()->merchant->id : Sentinel::getUser()->merchant_id}}" name="merchant" class="merchant">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card-inner">
                                                <div class="row g-gs">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="customer_name">{{__('customer').' '.__('name')}} <span class="text-primary">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="customer_name" value="{{ old('customer_name') != ""? old('customer_name'):@$parcel->customer_name }}" name="customer_name" placeholder="Recipient Name" required>
                                                            </div>
                                                            @if($errors->has('customer_name'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('customer_name') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="customer_invoice_no">{{__('invoice')}}# <span class="text-primary">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="customer_invoice_no" value="{{ old('customer_invoice_no') != ""? old('customer_invoice_no'):@$parcel->customer_invoice_no}}" name="customer_invoice_no" placeholder="{{ __('invoice_or_memo_no') }}" required>
                                                            </div>
                                                            @if($errors->has('customer_invoice_no'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('customer_invoice_no') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="customer_phone_number">{{__('customer').' '.__('phone')}} <span class="text-primary">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="customer_phone_number" value="{{ old('customer_phone_number') != ""? old('customer_phone_number'):@$parcel->customer_phone_number }}" name="customer_phone_number" placeholder="{{__('recipient').' '.__('phone')}}" required>
                                                            </div>
                                                            @if($errors->has('customer_phone_number'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('customer_phone_number') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="fv-full-name">{{__('weight')}} <span class="text-primary">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select form-control form-control-lg weight" name="weight">
                                                                    <option value="">{{ __('select_weight') }}</option>
                                                                    @foreach($charges->slice(0, 10) as $charge)
                                                                        <option value="{{ $charge->weight }}" {{$charge->weight == 1 ? 'selected':''}}>{{ $charge->weight }}{{' '.__('kg')}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            @if($errors->has('weight'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('weight') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="fv-full-name">Cash Collection/COD(Tk) <span class="text-primary">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control cash-collection" id="fv-full-name" value="{{ old('price') != ""? old('price'):@$parcel->price }}" name="price" placeholder="{{__('cash_amount_including_delivery_charge')}}" required>
                                                            </div>
                                                            @if($errors->has('price'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('price') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="area">{{__('delivery_area')}} <span class="text-primary">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select form-control form-control-lg parcel_type" name="parcel_type" required>
                                                                    <option value="">{{ __('select_type') }}</option>
                                                                    @if(settingHelper('preferences')->where('title','same_day')->first()->merchant)
                                                                        <option value="same_day" {{ old('parcel_type') == 'same_day' ? 'selected' : (@$parcel->parcel_type == 'same_day' ? 'selected' : '') }}>{{ __('same_day') }}</option>
                                                                    @endif
                                                                    @if(settingHelper('preferences')->where('title','next_day')->first()->merchant)
                                                                        <option value="next_day" {{ old('parcel_type') == 'next_day' ? 'selected' : (@$parcel->parcel_type == 'next_day' ? 'selected' : '') }}>{{ __('next_day') }}</option>
                                                                    @endif
                                                                    @if(settingHelper('preferences')->where('title','sub_city')->first()->merchant)
                                                                        <option value="sub_city" {{ old('parcel_type') == 'sub_city' ? 'selected' : (@$parcel->parcel_type == 'sub_city' ? 'selected' : '')  }}>{{ __('sub_city') }}</option>
                                                                    @endif
                                                                    @if(settingHelper('preferences')->where('title','outside_dhaka')->first()->merchant)
                                                                        <option value="outside_dhaka" {{ old('parcel_type') == 'outside_dhaka' ? 'selected' : (@$parcel->parcel_type == 'outside_dhaka' ? 'selected' : '') }}>{{ __('outside_dhaka') }}</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            @if($errors->has('parcel_type'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ __('delivery_area_required')  }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="customer_address">{{__('customer').' '.__('address')}} <span class="text-primary">*</span></label>
                                                            <div class="form-control-wrap">
                                                                <textarea class="form-control" id="customer_address" placeholder="{{__('recipient').' '.__('address')}}" required name="customer_address">{{ old('customer_address') != ""? old('customer_address'):@$parcel->customer_address }}</textarea>
                                                            </div>
                                                            @if($errors->has('customer_address'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('customer_address') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="note">{{__('note')}} </label>
                                                            <div class="form-control-wrap">
                                                                <textarea class="form-control" id="note" placeholder="{{__('note').' ('.__('parcel_note_from_merchant').')'}}" name="note">{{ old('note') != ""? old('note'):@$parcel->note }}</textarea>
                                                            </div>
                                                            @if($errors->has('note'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('note') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label" for="">Delivery Area Details <span class="text-primary">*</span></label>
                                                        <div class="border p-3">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="">City <span class="text-primary">*</span></label>
                                                                        <select id="city-live-search" name="city" onchange="selectZone()" class="form-control form-control-md merchant-live-search" required> </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="">Zone <span class="text-primary">*</span></label>
                                                                        <select id="zone-live-search" name="zone" onchange="selectArea()" class="form-control form-control-md merchant-live-search" required> </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="">Area <span class="text-primary">*</span></label>
                                                                        <select id="area-live-search" name="area" class="form-control form-control-md merchant-live-search"> </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="area">{{__('shop')}} </label>
                                                                            <div class="form-control-wrap">
                                                                                <select class="form-select form-control form-control-lg select-shop" data-url="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.shop') : route('merchant.staff.shop') }}" name="shop">
                                                                                    <option value="">{{ __('select_shop') }}</option>
                                                                                    @foreach($shops as $shop)
                                                                                        <option value="{{ $shop->id }}" {{ (@$parcel->shop_id == $shop->id) ? 'selected' :($shop->default ? 'selected':'') }}>{{ __($shop->shop_name) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            @if($errors->has('shop'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('shop') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 mt-4">
                                                                        <div class="">
                                                                            <label class="form-label"
                                                                                   for="fv-full-name">{{__('choose_which_needed_for_parcel')}}</label>
                                                                        </div>
                                                                        <div class="row pt-1">
                                                                            <div class="col-md-6">
                                                                                <div class="form-group">
                                                                                    <div class="preview-block">
                                                                                        <div class="custom-control custom-checkbox">
                                                                                            <input type="checkbox"
                                                                                                   class="custom-control-input"
                                                                                                   id="fragile"
                                                                                                   name="fragile" {{isset($parcel)? ($parcel->fragile == 1? 'checked':''):''}}>
                                                                                            <label class="custom-control-label"
                                                                                                   for="fragile">{{__('liquid')}}
                                                                                                /{{__('fragile')}}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="note">Product Details </label>
                                                                            <div class="form-control-wrap">
                                                                                <textarea class="form-control" id="productDetails" placeholder="Details of product contains" name="product_details">{{ old('note') != ""? old('note'):@$parcel->note }}</textarea>
                                                                            </div>
                                                                            @if($errors->has('note'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('note') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>




                                                     <div class="col-md-6 packaging-area  {{isset($parcel)? ($parcel->fragile == 0? 'd-none':''):'d-none'}}">
                                                         <div class="form-group">
                                                             <label class="form-label" for="fv-full-name">{{__('packaging')}}</label>
                                                             <div class="form-control-wrap">
                                                                 <select class="form-select form-control form-control-lg packaging" name="packaging">
                                                                     <option value="no">{{ __('select_packing') }}</option>
                                                                     @foreach(settingHelper('package_and_charges') as $package_and_charge)
                                                                        <option value="{{ $package_and_charge->id }}" {{isset($parcel)? ($parcel->packaging == $package_and_charge->id? 'selected':''):''}}>{{ __($package_and_charge->package_type) }} ({{ $package_and_charge->charge }} {{ __('tk') }})</option>
                                                                    @endforeach
                                                                 </select>
                                                             </div>
                                                         </div>
                                                     </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-right mt-4">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('submit')}}}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card h-100">
                                    <div class="card-inner">
                                        <div class="card-title-group mb-2">
                                            <div class="card-title">
                                                <h6 class="title">{{ __('charge_details') }}</h6>
                                            </div>
                                        </div>
                                        <ul class="nk-top-products">
                                            <div class="card-inner p-0">
                                                <div class="nk-tb-list nk-tb-ulist">
                                                    <div class="nk-tb-item nk-tb-head">

                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('cash_collection')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="cash-collection-charge">{{isset($parcel)? $parcel->price:'0.00'}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('delivery_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="delivery-charge">{{isset($parcel)? $parcel->charge:'0.00'}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('cod_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="cod-charge">{{isset($parcel)? number_format($parcel->price / 100 * $parcel->cod_charge, 2):'0.00'}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('vat')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="vat-charge">{{isset($parcel)? number_format(($parcel->charge + $parcel->fragile_charge + $parcel->packaging_charge + ($parcel->price / 100 * $parcel->cod_charge)) / 100 * $parcel->vat, 2):'0.00'}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item fragile-charge-area {{isset($parcel)? ($parcel->fragile == 0? 'd-none':''):'d-none'}}">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('liquid')}}/{{__('fragile_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="fragile-charge">{{isset($parcel)? ($parcel->fragile == 0? '0.00':$parcel->fragile_charge):'0.00'}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item packaging-charge-area {{isset($parcel)? ($parcel->packaging == 'no'? 'd-none':''):'d-none'}}">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('packaging_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="packaging-charge">{{isset($parcel)? ($parcel->packaging == 'no'? '0.00':$parcel->packaging_charge):'0.00'}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('total_delivery_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="total-delivery-charge">{{isset($parcel)? number_format(($parcel->total_delivery_charge), 2):'0.00'}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->

                                                    <div class="nk-tb-item nk-tb-head">
                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_payable')}}</strong></span></div>
                                                        <div class="nk-tb-col"><span class="sub-text"><strong id="current-payable-charge">{{isset($parcel)? $parcel->payable:'0.00'}}</strong></span></div>
                                                    </div><!-- .nk-tb-item -->

                                                </div><!-- .nk-tb-list -->
                                            </div><!-- .card-inner -->
                                        </ul>
                                    </div><!-- .card-inner -->
                                </div><!-- .card -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.parcel.charge-script')
@endsection


{{--<div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="fv-full-name">{{__('selling_price')}}</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-full-name" value="{{ old('selling_price') != ""? old('selling_price'):@$parcel->selling_price }}" name="selling_price" placeholder="{{__('selling_price_of_parcel')}}">
                                                            </div>
                                                            @if($errors->has('selling_price'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('selling_price') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>--}}
{{--<div class="col-md-6">
    <div class="form-group">
        <label class="form-label" for="shop_phone_number">{{__('pickup_number')}}</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" id="shop_phone_number" value="{{ old('shop_phone_number') ? old('shop_phone_number'):(@$parcel->pickup_shop_phone_number ? @$parcel->pickup_shop_phone_number : @$default_shop->shop_phone_number) }}" name="shop_phone_number" placeholder="{{__('pickup_number')}}">
        </div>
        @if($errors->has('shop_phone_number'))
            <div class="nk-block-des text-danger">
                <p>{{ $errors->first('shop_phone_number') }}</p>
            </div>
        @endif
    </div>
</div>--}}
{{--<div class="col-md-6">
    <div class="form-group">
        <label class="form-label" for="shop_address">{{__('pickup_address')}}</label>
        <div class="form-control-wrap">
            <input type="text" class="form-control" id="shop_address" value="{{ old('shop_address') ? old('shop_address'):(@$parcel->pickup_address ? @$parcel->pickup_address : @$default_shop->address) }}" name="shop_address" placeholder="{{__('pickup_address')}}">
        </div>
        @if($errors->has('shop_address'))
            <div class="nk-block-des text-danger">
                <p>{{ $errors->first('shop_address') }}</p>
            </div>
        @endif
    </div>
</div>--}}

@push('script')
    <script type="text/javascript">
        $(function (){
            $('#zone-live-search').select2({placeholder: "Select Zone", data:[]});
            $('#area-live-search').select2({placeholder: "Select Zone", data:[]});
            $('#zone-live-search').attr('disabled', true);
            $('#area-live-search').attr('disabled', true)
            $.ajax(`${baseURL}/get-city/`).then(function (res) {
                cities=res;
                $('#city-live-search').select2({
                    placeholder: "Select City",
                    minimumInputLength: 0,
                    data: cities,
                });
                selectZone(false)
            })
        });



    </script>
@endpush
