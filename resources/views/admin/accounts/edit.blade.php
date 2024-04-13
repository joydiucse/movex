@extends('master')

@section('title')
    {{__('edit').' '.__('income')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('edit')}} {{__('income')}}</h3>

                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('incomes.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{$company_account->id}}" name="id">
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('delivery_man') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <select id="delivery-man-live-search" name="delivery_man" class="form-control form-control-lg delivery-man-live-search get-delivery-man-balance" data-for="update" data-id="{{ $company_account->id }}" required>
{{--                                                        <select class="form-select form-control form-control-lg" name="delivery_man">--}}
                                                            <option value="{{ $company_account->delivery_man_id }}">{{ $company_account->deliveryMan->user->first_name.' '.$company_account->deliveryMan->user->last_name }}</option>
                                                        </select>
                                                    </div>
                                                    <span class="current-balance">
                                                        {{ $current_amount }}
                                                    </span>
                                                    @if($errors->has('delivery_man'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('delivery_man') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('parcel') }}</label>
                                                    <div class="form-control-wrap">
                                                        <select id="parcel-live-search" name="parcel" class="form-control form-control-lg parcel-live-search">
{{--                                                        <select class="form-select form-control form-control-lg" data-search="on" name="parcel" required>--}}
                                                            <option value="{{ @$company_account->parcel_id }}">{{ @$company_account->parcel->parcel_no.' ('.@$company_account->parcel->merchant->company.')'  }}</option>
                                                        </select>
                                                    </div>
                                                    @if($errors->has('parcel'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('parcel') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('account') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg" name="account" required>
                                                            <option value="">{{ __('select_parcel') }}</option>
                                                            @foreach($accounts as $account)
                                                                <option value="{{ $account->id }}" {{$account->id == $company_account->account_id? 'selected':''}}>
                                                                    ({{__($account->method)}})
                                                                    @if($account->method == 'bank')
                                                                    {{$account->account_holder_name.', '. $account->account_no.', '. __($account->bank_name).','. $account->bank_branch}}.
                                                                    @elseif($account->method == 'cash')
                                                                    {{$account->user->first_name.' '. $account->user->last_name}}
                                                                    @else
                                                                        {{$account->account_holder_name.', '. $account->number.', '. __($account->type)}}
                                                                    @endif
                                                                </option>
                                                            @endforeach

                                                        </select>
                                                    </div>
                                                    @if($errors->has('account'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('account') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">{{__('amount')}} *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="fv-full-name" value="{{ old('amount') != ""? old('amount'):$company_account->amount }}" name="amount" required>
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
                                                    <label class="form-label">{{__('date')}} *</label>
                                                    <div class="form-control-wrap focused">
                                                        <div class="form-icon form-icon-right">
                                                            <em class="icon ni ni-calendar-alt"></em>
                                                        </div>
                                                        <input type="text" class="form-control date-picker" name="date" required autocomplete="off" value="{{old('date') != ""? old('date'):date('m/d/Y', strtotime($company_account->date))}}">
                                                    </div>
                                                    @if($errors->has('date'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('date') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="note">{{__('details')}}</label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="details" placeholder="{{__('details')}}" name="details">{{ old('details') != ""? old('details'):$company_account->details }}</textarea>
                                                    </div>
                                                </div>
                                                @if($errors->has('details'))
                                                    <div class="nk-block-des text-danger">
                                                        <p>{{ $errors->first('details') }}</p>
                                                    </div>
                                                @endif
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

@include('live_search.delivery-man')
@include('live_search.parcel')
