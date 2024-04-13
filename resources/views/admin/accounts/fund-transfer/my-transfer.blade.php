@extends('master')

@section('title')
{{__('fund_transfers')}} {{__('lists')}}
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
                                <p>{{__('you_have_total')}} {{ !blank($fund_transfers)? $fund_transfers->total():'0' }} {{__('transfers')}}.</p>
                            </div>
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
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('from')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('to')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date') .'/' .__('created_at')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('details')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                       
                                    </div><!-- .nk-tb-item -->
                                    @foreach($fund_transfers as $key => $fund_transfer)
                                    <div class="nk-tb-item {{ ($fund_transfer->status == 0) ? 'bg-teal-dim text-teal': '' }}" id="row_{{$fund_transfer->id}}">
                                        <div class="nk-tb-col">
                                            <span>{{$key + 1}}</span>
                                        </div>

                                        <div class="nk-tb-col">
                                            @if($fund_transfer->fromAccount->method == 'bank')
                                                <span>{{__('name')}}: {{$fund_transfer->fromAccount->account_holder_name}}</span><br>
                                                <span>{{__('account_no')}}: {{$fund_transfer->fromAccount->account_no}}</span><br>
                                                <span>{{__('bank')}}: {{__($fund_transfer->fromAccount->bank_name)}}</span><br>
                                                <span>{{__('branch')}}: {{$fund_transfer->fromAccount->bank_branch}}</span><br>
                                            @elseif($fund_transfer->fromAccount->method == 'cash')
                                                <span>{{__('name')}}: {{$fund_transfer->fromAccount->user->first_name.' '.$fund_transfer->fromAccount->user->last_name}}({{__($fund_transfer->fromAccount->method)}})</span><br>
                                                <span>{{__('email')}}: {{$fund_transfer->fromAccount->user->email}}</span><br>
                                            @else
                                                <span>{{__('name')}}: {{$fund_transfer->fromAccount->account_holder_name}}</span><br>
                                                <span>{{__('number')}}: {{$fund_transfer->fromAccount->number}}</span><br>
                                                <span>{{__('account_type')}}: {{__($fund_transfer->fromAccount->type)}}</span><br>
                                            @endif
                                        </div>
                                        <div class="nk-tb-col">
                                            @if($fund_transfer->toAccount->method == 'bank')
                                                <span>{{__($fund_transfer->toAccount->method)}}</span><br>
                                                <span>{{__('name')}}: {{$fund_transfer->toAccount->account_holder_name}}</span><br>
                                                <span>{{__('account_no')}}: {{$fund_transfer->toAccount->account_no}}</span><br>
                                                <span>{{__('bank')}}: {{__($fund_transfer->toAccount->bank_name)}}</span><br>
                                                <span>{{__('branch')}}:{{$fund_transfer->toAccount->bank_branch}}</span><br>
                                            @elseif($fund_transfer->toAccount->method == 'cash')
                                                <span>{{__($fund_transfer->toAccount->method)}}</span><br>
                                                <span>{{__('name')}}: {{$fund_transfer->toAccount->user->first_name.' '.$fund_transfer->toAccount->user->last_name}}</span><br>
                                                <span>{{__('email')}}: {{$fund_transfer->toAccount->user->email}}</span><br>
                                            @else
                                                <span>{{__($fund_transfer->toAccount->method)}}</span><br>
                                                <span>{{__('name')}}: {{$fund_transfer->toAccount->account_holder_name}}</span><br>
                                                <span>{{__('number')}}: {{$fund_transfer->toAccount->number}}</span><br>
                                                <span>{{__('account_type')}}: {{__($fund_transfer->toAccount->type)}}</span><br>
                                            @endif
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>
                                                {{$fund_transfer->date != "" ? date('M d, Y', strtotime($fund_transfer->date)) : ''}} <br>
                                                {{date('M d, Y h:i a', strtotime($fund_transfer->created_at))}}
                                            </span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{$fund_transfer->note}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>{{ ($fund_transfer->id == $fund_transfer->StaffTransferAmount->fund_transfer_id) ?  number_format($fund_transfer->StaffTransferAmount->amount,2) : $fund_transfer->amount }} </span>
                                        </div>

                                        @if( $fund_transfer->status== 0 &&  $fund_transfer->toAccount->user_id == Sentinel::getUser()->id )
                                        <div class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="drodown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a href="{{route('admin.fund-transfer.accept', $fund_transfer->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('Accept')}}</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        @endif


                                    </div><!-- .nk-tb-item -->
                                    @endforeach
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! !blank($fund_transfers)? $fund_transfers->links():'' !!}
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
