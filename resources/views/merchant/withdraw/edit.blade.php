@extends('master')

@section('title')
    {{__('edit').' '.__('payment_request')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('edit').' '.__('payment_request')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.withdraw.update') : route('merchant.staff.withdraw.update')}}" class="form-validate" method="POST">
                        @csrf
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" name="id" hidden value="{{ $withdraw->id }}">
                                                    <label class="form-label" for="amount">{{__('amount').' ('.__('tk')}})</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="amount" value="{{ old('amount') ?? $withdraw->amount}}" name="amount" placeholder="{{ __('amount') }}" readonly required>
                                                    </div>
                                                    @if($errors->has('amount'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('amount') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="amount">{{__('payment_to')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" name="withdraw_to" required>
                                                            <option value="">{{ __('select_account') }}</option>
                                                            @foreach($payment_account as $account)
                                                                <option value="{{ $account }}" {{ $account ==  $withdraw->withdraw_to ? 'selected' : ''}}>{{ __($account) }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if($errors->has('withdraw_to'))
                                                            <div class="nk-block-des text-danger">
                                                                <p>{{ $errors->first('withdraw_to') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="details">{{__('details')}} </label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="details" placeholder="{{__('details').' ('.__('optional').')'}}" name="details">{{ old('details') ?? $withdraw->account->details}}</textarea>
                                                    </div>
                                                    @if($errors->has('details'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('details') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 text-right mt-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-lg btn-primary resubmit">{{{__('update')}}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

