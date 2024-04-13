@extends('master')

@section('title')
{{__('income')}} {{__('lists')}}
@endsection

@section('mainContent')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('lists')}}</h3>
                            <div class="nk-block-des text-soft">
                                <p>{{__('you_have_total')}} {{ !blank($company_accounts)? $company_accounts->total():'0' }} {{__('incomes')}}.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            @if(hasPermission('income_create'))
{{--                                <a href="{{route('merchant.balance.adjustment')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('merchant_balance_adjustment')}}</span></a>--}}
                                <a href="{{route('incomes.receive.from.merchant')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('credit_receive_from_merchant')}}</span></a>
                                <a href="{{route('incomes.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
                            @endif
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">

                                    </div><!-- .card-tools -->
                                </div><!-- .card-title-group -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist">
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col"><span class="sub-text"><strong>#</strong></span></div>
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('date') .'/' .__('created_at')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('account_details')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('source')}} / {{ __('details') }}</strong></span></div>
                                        <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('parcel')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                        @if(hasPermission('income_update') || hasPermission('income_delete'))
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                        @endif
                                    </div><!-- .nk-tb-item -->
                                    @foreach($company_accounts as $key => $company_account)
                                    <div class="nk-tb-item" id="row_{{$company_account->id}}">
                                        <div class="nk-tb-col">
                                            <span>{{$key + 1}}</span>
                                        </div>
                                        <div class="nk-tb-col tb-col-lg">
                                            {{$company_account->date != "" ? date('M d, Y', strtotime($company_account->date)) : ''}} <br>
                                            {{date('M d, Y h:i a', strtotime($company_account->created_at))}}

                                        </div>
                                        <div class="nk-tb-col">
                                            @if(!blank($company_account->account))
                                            @if($company_account->account->method == 'bank')
                                                <span>{{__('name')}}: {{$company_account->account->account_holder_name}}</span><br>
                                                <span>{{__('account_no')}}: {{$company_account->account->account_no}}</span><br>
                                                <span>{{__('bank')}}: {{__($company_account->account->bank_name)}}</span><br>
                                                <span>{{__('branch')}}:{{$company_account->account->bank_branch}}</span><br>
                                            @elseif($company_account->account->method == 'cash')
                                                <span>{{__('name')}}: {{$company_account->account->user->first_name.' '.$company_account->account->user->last_name}}({{__($company_account->account->method)}})</span><br>
                                                <span>{{__('email')}}: {{$company_account->account->user->email}}</span><br>
                                            @else
                                                <span>{{__('name')}}: {{$company_account->account->account_holder_name}}</span><br>
                                                <span>{{__('number')}}: {{$company_account->account->number}}</span><br>
                                                <span>{{__('account_type')}}: {{__($company_account->account->type)}}</span><br>
                                            @endif
                                            @endif

                                        </div>
                                        <div class="nk-tb-col column-max-width">
                                            @if($company_account->source != "")
                                            {{ __($company_account->source)  }} <br>
                                            @endif

                                            @if($company_account->delivery_man_id != "")
                                            {{@$company_account->deliveryMan->user->first_name.' '.@$company_account->deliveryMan->user->last_name}}
                                            @endif
                                            @isset($company_account->merchant)
                                                {{$company_account->merchant->user->first_name.' '.$company_account->merchant->user->last_name}}
                                                ({{$company_account->merchant->company}})
                                                @endisset

                                            @if($company_account->details)
                                                <br>{{ __('note').': '. $company_account->details }}
                                            @endif

                                        </div>
                                        <div class="nk-tb-col column-max-width tb-col-lg">
                                           @if($company_account->parcel_id != "")
                                                <div class="copy-to-clipboard">
                                                    {{ __('id').'# ' }}<input readonly type="text" class="text-info" data-text="{{ __('copied') }}" value="{{@$company_account->parcel->parcel_no}}"><br>
                                                </div>
                                           @endif
                                        </div>

                                        <div class="nk-tb-col">
                                            {{number_format($company_account->amount,2)}}
                                        </div>

                                        <div class="nk-tb-col nk-tb-col-tools column-max-width">
                                            @if(Sentinel::getUser()->id == 1)
                                                @if((hasPermission('income_update') || hasPermission('income_delete'))
                                                        && $company_account->create_type=='user_defined')

                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        @if(@$company_account->merchantAccount->payment_withdraw_id == null && @$company_account->merchantAccount->is_paid == false)
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    @if(hasPermission('income_update'))
                                                                        <li><a href="{{($company_account->delivery_man_id =='' && $company_account->merchant_id != '') ? route('incomes.receive.from.merchant.edit', $company_account->id): route('incomes.edit', $company_account->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                    @endif
                                                                    @if(hasPermission('income_delete'))
                                                                        <li><a href="javascript:void(0);" onclick="delete_row('income-delete/', {{$company_account->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        @elseif(@$company_account->merchantAccount->payment_withdraw_id != null || @$company_account->merchantAccount->is_paid == true)
                                                            <span  class="badge text-success">{{ __('adjusted_in_payment') }}</span>
                                                        @endif
                                                    </li>
                                                </ul>
                                                @endif
                                            @endif

                                        </div>



                                    </div><!-- .nk-tb-item -->
                                    @endforeach
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! !blank($company_accounts)? $company_accounts->links():'' !!}
                                    </div>
                                </div><!-- .nk-block-between -->
                            </div><!-- .card-inner -->
                        </div><!-- .card-inner-group -->
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
@include('common.delete-ajax')
@include('common.change-status-ajax')
@endpush
