@extends('master')

@section('title')
    {{__('edit').' '.__('parcel')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('edit')}} {{__('parcel')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.parcel.update') : route('merchant.staff.parcel.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="text" name="merchant" hidden value="{{ $parcel->merchant_id }}" class="merchant">
                        <input type="hidden" name="id" value="{{$parcel->id}}">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card-inner">
                                                <div class="row g-gs">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="customer_invoice_no">{{__('invoice')}}# *</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="customer_invoice_no" value="{{ old('customer_invoice_no') != ""? old('customer_invoice_no'):$parcel->customer_invoice_no }}" name="customer_invoice_no" placeholder="{{ __('invoice_or_memo_no') }}" name="customer_invoice_no" placeholder="{{ __('invoice_or_memo_no') }}" required>
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
                                                            <label class="form-label" for="fv-full-name">{{__('weight')}}</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select form-control form-control-lg weight" name="weight">
                                                                    <option value="">{{ __('select_weight') }}</option>
                                                                    @foreach($charges->slice(0, 10) as $charge)
                                                                        <option value="{{ $charge->weight }}" {{$charge->weight == $parcel->weight ? 'selected':''}}>{{ $charge->weight }}{{' '.__('kg')}}</option>
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
                                                            <label class="form-label" for="fv-full-name">{{__('cod').' '.__('amount')}}({{__('tk')}})</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control cash-collection" id="fv-full-name" value="{{ old('price') != ""? old('price'):$parcel->price }}" name="price" placeholder="{{__('cash_amount_including_delivery_charge')}}" required>
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
                                                            <label class="form-label" for="fv-full-name">{{__('selling_price')}}</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="fv-full-name" value="{{ old('selling_price') != ""? old('selling_price'):$parcel->selling_price }}" name="selling_price" placeholder="{{__('selling_price_of_parcel')}}">
                                                            </div>
                                                            @if($errors->has('selling_price'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('selling_price') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>


                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="area">{{__('delivery_area')}} *</label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select form-control form-control-lg parcel_type" name="parcel_type">
                                                                    <option value="">{{ __('select_type') }}</option>
                                                                    @if(settingHelper('preferences')->where('title','same_day')->first()->merchant)
                                                                        <option value="same_day" {{ old('parcel_type') == 'same_day' ? 'selected' : ($parcel->parcel_type == 'same_day' ? 'selected' : '') }}>{{ __('same_day') }}</option>
                                                                    @endif
                                                                    @if(settingHelper('preferences')->where('title','next_day')->first()->merchant)
                                                                        <option value="next_day" {{ old('parcel_type') == 'next_day' ? 'selected' : ($parcel->parcel_type == 'next_day' ? 'selected' : '') }}>{{ __('next_day') }}</option>
                                                                    @endif
                                                                    @if(settingHelper('preferences')->where('title','sub_city')->first()->merchant)
                                                                        <option value="sub_city" {{ old('parcel_type') == 'sub_city' ? 'selected' : ($parcel->parcel_type == 'sub_city' ? 'selected' : '') }}>{{ __('sub_city') }}</option>
                                                                    @endif
                                                                    @if(settingHelper('preferences')->where('title','outside_dhaka')->first()->merchant)
                                                                        <option value="outside_dhaka" {{ old('parcel_type') == 'outside_dhaka' ? 'selected' : ($parcel->parcel_type == 'outside_dhaka' ? 'selected' : '') }}>{{ __('outside_dhaka') }}</option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                            @if($errors->has('parcel_type'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ __('delivery_area_required') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="area">{{__('shop')}} </label>
                                                            <div class="form-control-wrap">
                                                                <select class="form-select form-control form-control-lg select-shop" data-url="{{ route('merchant.shop') }}" name="shop">
                                                                    <option value="">{{ __('select_shop') }}</option>
                                                                    @foreach($shops as $shop)
                                                                        <option value="{{ $shop->id }}" {{ $parcel->shop_id == $shop->id ? 'selected' : '' }}>{{ __($shop->shop_name) }}</option>
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
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="shop_phone_number">{{__('pickup_number')}}</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="shop_phone_number" value="{{ old('shop_phone_number') ? old('shop_phone_number'):($parcel->pickup_shop_phone_number ? $parcel->pickup_shop_phone_number : $default_shop->shop_phone_number) }}" name="shop_phone_number" placeholder="{{__('pickup_number')}}">
                                                            </div>
                                                            @if($errors->has('shop_phone_number'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('shop_phone_number') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="shop_address">{{__('pickup_address')}}</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="shop_address" value="{{ old('shop_address') ? old('shop_address'):($parcel->pickup_address ? $parcel->pickup_address : $default_shop->address) }}" name="shop_address" placeholder="{{__('pickup_address')}}">
                                                            </div>
                                                            @if($errors->has('shop_address'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('shop_address') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label" for="customer_name">{{__('customer').' '.__('name')}} *</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="customer_name" value="{{ old('customer_name') != ""? old('customer_name'):$parcel->customer_name }}" name="customer_name" placeholder="{{__('recipient'.' '.__('name'))}}" required>
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
                                                            <label class="form-label" for="customer_phone_number">{{__('customer').' '.__('phone')}} *</label>
                                                            <div class="form-control-wrap">
                                                                <input type="text" class="form-control" id="customer_phone_number" value="{{ old('customer_phone_number') != ""? old('customer_phone_number'):$parcel->customer_phone_number }}" name="customer_phone_number" placeholder="{{__('recipient').' '.__('phone')}}" required>
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
                                                            <label class="form-label" for="customer_address">{{__('customer').' '.__('address')}} *</label>
                                                            <div class="form-control-wrap">
                                                                <textarea class="form-control" id="customer_address" placeholder="{{__('recipient').' '.__('address')}}" required name="customer_address">{{ old('customer_address') != ""? old('customer_address'):$parcel->customer_address }}</textarea>
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
                                                                <textarea class="form-control" id="note" placeholder="{{__('note').' ('.__('optional').')'}}" name="note">{{ old('note') != ""? old('note'):$parcel->note }}</textarea>
                                                            </div>
                                                            @if($errors->has('note'))
                                                                <div class="nk-block-des text-danger">
                                                                    <p>{{ $errors->first('note') }}</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="">
                                                             <label class="form-label" for="fv-full-name">{{__('choose_which_needed_for_parcel')}}</label>
                                                         </div>
                                                         <div class="row pt-1">
                                                             <div class="col-md-6">
                                                                 <div class="form-group">
                                                                     <div class="preview-block">
                                                                         <div class="custom-control custom-checkbox">
                                                                             <input type="checkbox" class="custom-control-input" id="fragile" name="fragile" {{$parcel->fragile == 1? 'checked':''}}>
                                                                             <label class="custom-control-label" for="fragile">{{__('liquid')}}/{{__('fragile')}}</label>
                                                                         </div>
                                                                     </div>
                                                                 </div>
                                                             </div>
                                                         </div>
                                                     </div>
                                                     <div class="col-md-6 packaging-area {{$parcel->fragile == 0? 'd-none':''}}">
                                                         <div class="form-group">
                                                             <label class="form-label" for="fv-full-name">{{__('packaging')}}</label>
                                                             <div class="form-control-wrap">
                                                                 <select class="form-select form-control form-control-lg packaging" name="packaging">
                                                                     <option value="no">{{ __('select_packing') }}</option>
                                                                     @foreach(settingHelper('package_and_charges') as $package_and_charge)
                                                                        <option value="{{ $package_and_charge->id }}" {{$parcel->packaging == $package_and_charge->id? 'selected':''}}>{{ __($package_and_charge->package_type) }} ({{ $package_and_charge->charge }} {{ __('tk') }})</option>
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
                            </div><div class="col-md-4">
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
                                                            <span id="cash-collection-charge">{{number_format($parcel->price, 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('delivery_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="delivery-charge">{{number_format($parcel->charge, 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('cod_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="cod-charge">{{number_format(floor($parcel->price / 100 * $parcel->cod_charge), 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('vat')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="vat-charge">{{number_format((floor($parcel->charge + $parcel->fragile_charge + $parcel->packaging_charge + ($parcel->price / 100 * $parcel->cod_charge)) / 100 * $parcel->vat), 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item fragile-charge-area {{$parcel->fragile == 0? 'd-none':''}}">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('liquid')}}/{{__('fragile_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="fragile-charge">{{number_format($parcel->fragile_charge, 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item packaging-charge-area {{$parcel->packaging == 'no'? 'd-none':''}} ">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('packaging_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="packaging-charge">{{number_format($parcel->packaging_charge, 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->
                                                    <div class="nk-tb-item">

                                                        <div class="nk-tb-col">
                                                            <span>{{__('total_delivery_charge')}}</span>
                                                        </div>
                                                        <div class="nk-tb-col">
                                                            <span id="total-delivery-charge">{{number_format(($parcel->total_delivery_charge), 2)}}</span>
                                                        </div>
                                                    </div><!-- .nk-tb-item -->

                                                    <div class="nk-tb-item nk-tb-head">
                                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_payable')}}</strong></span></div>
                                                        <div class="nk-tb-col"><span class="sub-text"><strong id="current-payable-charge">{{number_format($parcel->payable, 2)}}</strong></span></div>
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

