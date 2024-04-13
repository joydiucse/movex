@extends('master')

@section('title')
    {{__('payment_log').' '.__('lists')}}
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
                                    <p>{{__('you_have_total')}} {{ $statements->count() }} {{__('payment_logs')}}</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            @if(Sentinel::getUser()->user_type == 'merchant')
                                <div class="nk-block-head-content">
                                    <button type="button" class="btn btn-white">
                                        <span class="text-dark">{{ __('current_balance') }} : </span>
                                        @if(Sentinel::getUser()->user_type == 'merchant')
                                            <span class="user-balance {{ Sentinel::getUser()->merchant->balance(\Sentinel::getUser()->merchant->id) < 0  ? 'text-danger': '' }}">{{ number_format(Sentinel::getUser()->merchant->balance(\Sentinel::getUser()->merchant->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small></span>
                                        @else
                                            <span class="user-balance {{ Sentinel::getUser()->balance(\Sentinel::getUser()->staffMerchant->id) < 0  ? 'text-danger': '' }}">{{ number_format(Sentinel::getUser()->balance(\Sentinel::getUser()->staffMerchant->id),2) }} <small class="currency currency-btc">{{ __('tk') }}</small></span>
                                        @endif
                                    </button>
                                </div><!-- .nk-block-head-content -->
                            @endif
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
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('details')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('completed_at')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}} ({{ __('tk') }})</strong></span></div>
                                        </div><!-- .nk-tb-item -->
                                        @php $balance = 0; @endphp
                                        @foreach($statements as $key => $statement)
                                            <div class="nk-tb-item" id="row_{{$statement['id']}}">
                                                <div class="nk-tb-col">
                                                    <span>{{$key + 1}}</span>
                                                </div>
                                                <div class="nk-tb-col column-max-width">
                                                    <span>@if($statement->source =="payment_withdraw_by_merchant") Payment Request Created @else{{ __($statement->details) }} @endif</span><br>
                                                    <span>
                                                    @if(@$statement->parcel != "")
                                                        {{__('id')}}:#{{ __(@$statement->parcel->parcel_no) }}
                                                    @endif
                                                    @if(@$statement->parcel->customer_invoice_no != "")
                                                        {{__('invno')}}:#{{ __($statement->parcel->customer_invoice_no) }}
                                                    @endif
                                                    </span>
                                                </div>

                                                <div class="nk-tb-col">
                                                    {{$statement['created_at'] != ""? date('M d, Y h:i a', strtotime($statement['created_at'])):''}}
                                                </div>
                                                @if($statement['type'] == 'income')
                                                    <div class="nk-tb-col">
                                                        {{$statement['amount']}}
                                                    </div>
                                                @elseif($statement['type'] == 'expense')
                                                    <div class="nk-tb-col text-danger">
                                                        {{$statement['amount']}}
                                                    </div>
                                                @endif
                                            </div><!-- .nk-tb-item -->
                                        @endforeach
                                    </div>
                                    <!-- .nk-tb-list -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-2">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            {!! $statements->links() !!}
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
@endpush
