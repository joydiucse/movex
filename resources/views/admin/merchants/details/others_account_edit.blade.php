@extends('master')

@section('title')
    {{__('update').' '.__('merchant').' '.__('others_account')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-aside-wrap">

                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head nk-block-head-lg">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">{{__('bank_information')}}</h4>
                                                <div class="nk-block-des">
                                                    <p>{{__('bank_info_message')}}</p>
                                                </div>
                                            </div>
                                            @if(hasPermission('merchant_payment_account_read'))
                                            <div class="d-flex">
                                                <a href="{{route('detail.merchant.payment.accounts', $merchant->id)}}"  class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                                                <div class="nk-block-head-content align-self-start d-lg-none">
                                                    <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block">
                                        <div class="nk-data data-list">
                                            <div class="data-head">
                                                <h6 class="overline-title">{{__('basics')}}</h6>
                                            </div>
                                            <form action="{{ route('detail.merchant.payment.others.update')}}" class="form-validate" method="POST">
                                                @csrf
                                                <div class="card">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card-inner">
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <input type="text" name="merchant" hidden value="{{ $merchant->id }}">
                                                                            <label class="form-label" for="bkash_number">{{__('bkash').' '.__('number')}} </label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="bkash_number" value="{{ old('bkash_number') != ""? old('bkash_number'):$payment_account->bkash_number }}" name="bkash_number" placeholder="{{__('enter_bkash_number')}}">
                                                                            </div>
                                                                            @if($errors->has('bkash_number'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('bkash_number') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="bkash_ac_type">{{__('bkash').' '.__('account_type')}} *</label>
                                                                            <div class="form-control-wrap">
                                                                                <select class="form-select form-control form-control-lg" name="bkash_ac_type">
                                                                                    <option value="">{{ __('select_type') }}</option>
                                                                                    @foreach(\Config::get('greenx.account_types') as $type)
                                                                                        <option value="{{ $type }}" {{(old('bkash_ac_type') != '' && $type == old('bkash_ac_type') ) ? 'selected' : ($type == $payment_account->bkash_ac_type ? 'selected':'')}}>{{ __($type) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            @if($errors->has('bkash_ac_type'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('bkash_ac_type') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="rocket_number">{{__('rocket').' '.__('number')}} </label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="rocket_number" value="{{ old('rocket_number') != ""? old('rocket_number'):$payment_account->rocket_number }}" name="rocket_number" placeholder="{{__('enter_rocket_number')}}">
                                                                            </div>
                                                                            @if($errors->has('rocket_number'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('rocket_number') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="rocket_ac_type">{{__('rocket').' '.__('account_type')}} *</label>
                                                                            <div class="form-control-wrap">
                                                                                <select class="form-select form-control form-control-lg" name="rocket_ac_type">
                                                                                    <option value="">{{ __('select_type') }}</option>
                                                                                    @foreach(\Config::get('greenx.account_types') as $type)
                                                                                        <option value="{{ $type }}" {{(old('rocket_ac_type') != '' && $type == old('rocket_ac_type') ) ? 'selected' : ($type == $payment_account->rocket_ac_type ? 'selected':'')}}>{{ __($type) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            @if($errors->has('rocket_ac_type'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('rocket_ac_type') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="nogod_number">{{__('nogod').' '.__('number')}} </label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="nogod_number" value="{{ old('nogod_number') != ""? old('nogod_number'):$payment_account->nogod_number }}" name="nogod_number" placeholder="{{__('enter_nogod_number')}}">
                                                                            </div>
                                                                            @if($errors->has('nogod_number'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('nogod_number') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="nogod_ac_type">{{__('nogod').' '.__('account_type')}} *</label>
                                                                            <div class="form-control-wrap">
                                                                                <select class="form-select form-control form-control-lg" name="nogod_ac_type">
                                                                                    <option value="">{{ __('select_type') }}</option>
                                                                                    @foreach(\Config::get('greenx.account_types') as $type)
                                                                                        <option value="{{ $type }}" {{(old('nogod_ac_type') != '' && $type == old('nogod_ac_type') ) ? 'selected' : ($type == $payment_account->nogod_ac_type ? 'selected':'')}}>{{ __($type) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            @if($errors->has('nogod_ac_type'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('nogod_ac_type') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12 text-right mt-4">
                                                                        <div class="form-group">
                                                                            <button type="submit" class="btn btn-lg btn-primary">{{{__('update')}}}</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div><!-- data-list -->
                                    </div><!-- .nk-block -->
                                </div>

                                @include('admin.merchants.details.sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
