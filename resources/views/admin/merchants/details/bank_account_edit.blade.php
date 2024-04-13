@extends('master')

@section('title')
    {{__('update').' '.__('merchant').' '.__('bank_account')}}
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
                                            <form action="{{ route('detail.merchant.payment.bank.update')}}" class="form-validate" method="POST">
                                                @csrf
                                                <div class="card">

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card-inner">
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="selected_bank">{{__('selected_bank')}} *</label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" name="merchant" hidden value="{{ $merchant->id }}">
                                                                                <select class="form-select form-control form-control-lg" name="selected_bank">
                                                                                    <option value="">{{ __('select_type') }}</option>
                                                                                    @foreach(\Config::get('greenx.banks') as $bank)
                                                                                        <option value="{{ $bank }}" {{$bank == $payment_account->selected_bank ? 'selected':''}}>{{ __($bank) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            @if($errors->has('selected_bank'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('selected_bank') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="bank_branch">{{__('bank_branch')}} </label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="bank_branch" value="{{ old('bank_branch') != ""? old('bank_branch'):$payment_account->bank_branch }}" name="bank_branch" placeholder="{{__('enter_bank_branch')}}" required>
                                                                            </div>
                                                                            @if($errors->has('bank_branch'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('bank_branch') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="bank_ac_name">{{__('bank_ac_name')}} </label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="bank_ac_name" value="{{ old('bank_ac_name') != ""? old('bank_ac_name'):$payment_account->bank_ac_name }}" name="bank_ac_name" placeholder="{{__('enter_bank_ac_name')}}" required>
                                                                            </div>
                                                                            @if($errors->has('bank_ac_name'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('bank_ac_name') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="bank_ac_number">{{__('bank_ac_number')}} </label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="bank_ac_number" value="{{ old('bank_ac_number') != ""? old('bank_ac_number'):$payment_account->bank_ac_number }}" name="bank_ac_number" placeholder="{{__('enter_bank_ac_number')}}" required>
                                                                            </div>
                                                                            @if($errors->has('bank_ac_number'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('bank_ac_number') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row g-gs">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-label" for="routing_no">{{__('routing_no')}} </label>
                                                                            <div class="form-control-wrap">
                                                                                <input type="text" class="form-control" id="routing_no" value="{{ old('routing_no') != ""? old('routing_no'):$payment_account->routing_no }}" name="routing_no" placeholder="{{__('enter_routing_no')}}" required>
                                                                            </div>
                                                                            @if($errors->has('routing_no'))
                                                                                <div class="nk-block-des text-danger">
                                                                                    <p>{{ $errors->first('routing_no') }}</p>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6 text-right mt-4">
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
