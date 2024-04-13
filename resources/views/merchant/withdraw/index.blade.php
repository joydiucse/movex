@extends('master')

@section('title')
    {{__('payment').' '.__('lists')}}
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
                                    <p>{{__('Found')}} {{ $withdraws->total() }} {{__('payments')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                @if(Sentinel::getUser()->user_type == 'merchant')
                                    <a href="{{ route('merchant.closing.report')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-download"></em><span>{{__('report') }}</span></a>
                                @endif
                                @if (@settingHelper('preferences')->where('title','create_payment_request')->first()->merchant)
                                    <a href="{{Sentinel::getUser()->user_type == 'merchant' ? route('merchant.withdraw.create') : route('merchant.staff.withdraw.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('request')}}</span></a>
                                @else
                                    <button class="btn btn-danger d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('request').' ('.__('service_unavailable').')'}}</span></button>
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
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{ __('payment_id') }}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('account_details') }}</strong></span></div>
{{--                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('note')}}</strong></span></div>--}}
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('requested_at')}}</strong></span></div>

                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}} ({{ __('tk') }})</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                        </div><!-- .nk-tb-item -->
                                        @foreach($withdraws as $key => $withdraw)
                                            <div class="nk-tb-item" id="row_{{$withdraw->id}}">
                                                <div class="nk-tb-col">
                                                    <span>{{$key + 1}}</span>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <span>{{$withdraw->withdraw_id}}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    @php
                                                        $account_details = json_decode($withdraw->account_details);
                                                    @endphp
                                                    @if($withdraw->withdraw_to == 'bank')
                                                        <span>{{ __('bank_name').': '.@$account_details[0] }} </span> <br>
                                                        <span>{{ __('branch').': '.@$account_details[1] }}</span> <br>
                                                        <span>{{ __('account_holder').': '.@$account_details[2] }}</span> <br>
                                                        <span>{{ __('account_no').': '.@$account_details[3] }}</span>
                                                        @if(@$account_details[4] != '')
                                                            <br>
                                                            <span>{{ __('routing_no').': '.@$account_details[4] }}
                                                                @endif
                                                       </span>
                                                    @else
                                                        <span>{{ __('payment_method').': '.__(@$withdraw->withdraw_to) }} </span>
                                                        @if(@$withdraw->withdraw_to == 'bKash' || @$withdraw->withdraw_to == 'nogod' || @$withdraw->withdraw_to == 'rocket')
                                                            <br><span>{{ __('account_type').': '.__(@$account_details[2]) }} </span> <br>
                                                            <span>{{ __('account_number').': '.@$account_details[1] }} </span>
                                                        @endif
                                                    @endif
                                                </div>
{{--                                                <div class="nk-tb-col  tb-col-lg column-max-width">--}}
{{--                                                    <span>{!! $withdraw->note !!}</span>--}}
{{--                                                </div>--}}

                                                <div class="nk-tb-col tb-col-lg">
                                                    {{$withdraw->created_at != ""? date('M d, Y h:i a', strtotime($withdraw->created_at)):''}}
                                                </div>

                                                <div class="nk-tb-col">

                                                    @if($withdraw->status == 'pending')
                                                        <span  class="badge text-warning">{{ __($withdraw->status) }}</span><br>
                                                    @elseif($withdraw->status == 'approved')
                                                        <span  class="badge text-blue">{{ __($withdraw->status) }}</span><br>
                                                    @elseif($withdraw->status == 'rejected' || $withdraw->status == 'cancelled')
                                                        <span  class="badge text-danger">{{ __($withdraw->status) }}</span><br>
                                                        @if($withdraw->status != 'cancelled')
                                                            @if(@$withdraw->companyAccountReason)
                                                                <span  class="text-warning">{{ __('reject_reason').': ' }}{{ @$withdraw->companyAccountReason->reject_reason!= '' ? __($withdraw->companyAccountReason->reject_reason): '' }}</span><br>
                                                            @endif
                                                        @endif
                                                        <span class="text">{{ __('at').': ' }}{{$withdraw->updated_at != ""? date('M d, Y h:i a', strtotime($withdraw->updated_at)):''}}</span>
                                                    @else
                                                        <span  class="badge text-success">{{__($withdraw->status)}}</span><br>
							
                                                        @if($withdraw->companyAccount->transaction_id != '')
                                                            <span  class="badge text-info">{{__('transaction_id').': '.$withdraw->companyAccount->transaction_id}}</span><br>
                                                        @endif
                                                        <span class="text">{{ __('at').': ' }}{{$withdraw->updated_at != ""? date('M d, Y h:i a', strtotime($withdraw->updated_at)):''}}</span>
                                                    @endif

                                                </div>
                                                <div class="nk-tb-col">
                                                    <span>{{$withdraw->amount}} </span>
                                                </div>
                                                <div class="nk-tb-col">

                                                    @if($withdraw->status == 'pending' || $withdraw->status == 'processed' || $withdraw->status == 'approved')
                                                        <div class="tb-odr-btns d-md-inline">
                                                            <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.invoice', $withdraw->id) : route('merchant.staff.invoice', $withdraw->id) }}" class="btn btn-sm btn-primary btn-tooltip" data-original-title="{{__('view')}}"><em class="icon ni ni-eye-alt"></em></a>
                                                        </div>
                                                        @if($withdraw->status == 'pending' && $withdraw->id > 1939)
                                                            <div class="tb-odr-btns d-md-inline">
                                                                <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.withdraw.edit', $withdraw->id) : route('merchant.staff.withdraw.edit', $withdraw->id)}}" class="btn btn-sm btn-info btn-tooltip" data-original-title="{{__('edit')}}"><em class="icon ni ni-edit"></em></a>
                                                            </div>

                                                            <div class="tb-odr-btns d-md-inline">
                                                                <a href="javascript:void(0);" onclick="changeWithdrawStatus('{{ Sentinel::getUser()->user_type == 'merchant' ? '/merchant/withdraw-status/' : '/staff/withdraw-status/' }}', {{$withdraw->id}}, 'cancelled')" id="delete-btn" class="btn btn-sm btn-danger btn-tooltip" data-original-title="{{__('cancel')}}"><em class="icon ni ni-na"></em></a>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div><!-- .nk-tb-item -->
                                        @endforeach
                                    </div>
                                    <!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-2">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {!! $withdraws->appends(Request::except('page'))->links() !!}
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
    @include('merchant.delete-ajax')
    @include('merchant.withdraw.status-ajax')
@endpush
