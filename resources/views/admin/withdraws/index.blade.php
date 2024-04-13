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
                                    <p>{{__('you_have_total')}} {{ $withdraws->total() }} {{__('payments')}}.</p>
                                </div>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            @if(hasPermission('withdraw_create'))
                                            <li class="nk-block-tools-opt">
                                                <div class="dropdown">
                                                    @if (@settingHelper('preferences')->where('title','create_payment_request')->first()->staff)
                                                        <a href="{{route('admin.withdraw.create')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
                                                    @else
                                                        <button class="btn btn-danger d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add').' ('.__('service_unavailable').')'}}</span></button>
                                                    @endif
                                                </div>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner position-relative card-tools-toggle">
                                    <div class="card-title-group">
                                        <div class="card-tools">
                                            <div class="d-block">
                                                <form action="{{route('admin.payment.filter')}}" method="GET">
{{--                                                    @csrf--}}
                                                    <div class="d-inline-flex">
                                                        <input type="text" name="withdraw_id" class="form-control input-parcel" autofocus placeholder="AGEW12WF12" id="withdraw_id">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary ml-2">{{{__('search')}}}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><!-- .card-tools -->
                                        <div class="card-tools mr-n1">
                                            <ul class="btn-toolbar gx-1">
                                                <li>
                                                    <form action="{{route('admin.invoice.filter')}}" method="GET">
{{--                                                        @csrf--}}
                                                        <ul class="btn-toolbar gx-1">
                                                            <li>
                                                                <div class="dropdown">
                                                                    <a href="#" class="btn btn-trigger btn-icon dropdown-toggle" data-toggle="dropdown">
                                                                        <div class="dot dot-primary"></div>
                                                                        <em class="icon ni ni-filter-alt"></em>
                                                                    </a>
                                                                    <div class="filter-wg dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                                                        <div class="dropdown-head">
                                                                            <span class="sub-title dropdown-title">{{__('filter')}}</span>

                                                                        </div>
                                                                        <div class="dropdown-body dropdown-body-rg">
                                                                            <div class="row gx-6 gy-3">
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <div class="form-control-wrap">
                                                                                            <select id="merchant-live-search" name="merchant" class="form-control form-control-lg select-merchant merchant merchant-live-search" data-url="{{ route('merchant.change') }}">
                                                                                                <option value="">{{ __('select_merchant') }}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <div class="form-group">
                                                                                        <div class="form-control-wrap">
                                                                                            <select name="status" class="form-control">
                                                                                                <option value="">{{ __('any_status') }}</option>
                                                                                                <option value="pending">{{ __('pending') }}</option>
                                                                                                <option value="approved">{{ __('approved') }}</option>
                                                                                                <option value="processed">{{ __('processed') }}</option>
                                                                                                <option value="rejected">{{ __('rejected') }}</option>
                                                                                                <option value="cancelled">{{ __('cancelled') }}</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 text-right">
                                                                                    <div class="form-group">
                                                                                        <button type="submit" class="btn btn-primary">{{__('filter')}}</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div><!-- .filter-wg -->
                                                                </div><!-- .dropdown -->
                                                            </li><!-- li -->
                                                        </ul><!-- .btn-toolbar -->
                                                    </form>
                                                </li><!-- li -->
                                            </ul><!-- .btn-toolbar -->
                                        </div>
                                    </div><!-- .card-title-group -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-0">
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('merchant')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('payment_id')}}</strong></span></div>
                                            <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('account_details')}}</strong></span></div>
                                            <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('requested_at')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}} ({{ __('tk') }})</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('receipt') }}</strong></span></div>
                                            @if(hasPermission('withdraw_update') || hasPermission('withdraw_process') || hasPermission('withdraw_reject'))
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                            @endif
                                        </div><!-- .nk-tb-item -->
                                        @foreach($withdraws as $key => $withdraw)
                                            <div class="nk-tb-item" id="row_{{$withdraw->id}}">
                                                <div class="nk-tb-col tb-col-lg">
                                                    <span>{{$key + 1}}</span>
                                                </div>
                                                <input type="hidden" value="{{$withdraw->id}}" id="id">
                                                <div class="nk-tb-col">
                                                    <a href="{{route('detail.merchant.personal.info', $withdraw->merchant->id)}}">
                                                        <div class="user-card">
                                                            <div class="user-avatar bg-primary">
                                                                @if(!blank($withdraw->merchant->user->image) && file_exists($withdraw->merchant->user->image->image_small_two))
                                                                    <img src="{{asset($withdraw->merchant->user->image->image_small_two)}}" alt="{{$withdraw->merchant->user->first_name}}">
                                                                @else
                                                                    <img src="{{asset('admin/images/default/user40x40.jpg')}}" alt="{{$withdraw->merchant->user->first_name}}">
                                                                @endif
                                                            </div>
                                                            <div class="user-info">
                                                                <span class="tb-lead">{{$withdraw->merchant->user->first_name.' '.$withdraw->merchant->user->last_name .'('.$withdraw->merchant->company.')'}} <span class="dot dot-success d-md-none ml-1"></span></span>
                                                                <span>{{$withdraw->merchant->user->email}}</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <div class="copy-to-clipboard">
                                                        <input readonly type="text" class="text-info" data-text="{{ __('copied') }}" value="{{$withdraw->withdraw_id}}">
                                                    </div>
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
                                                <div class="nk-tb-col  tb-col-lg">
                                                    {{$withdraw->created_at != ""? date('M d, Y h:i a', strtotime($withdraw->created_at)):''}}
                                                </div>
                                                <div class="nk-tb-col">

                                                    @if($withdraw->status == 'pending')
                                                        <a href="{{route('admin.withdraw.invoice', $withdraw->id)}}">
                                                            <span  class="badge text-warning">{{ __($withdraw->status) }}</span><br>
                                                        </a>
                                                    @elseif($withdraw->status == 'approved')
                                                        <span  class="badge text-blue">{{ __($withdraw->status) }}</span><br>
                                                        @if($withdraw->withdrawBatch && hasPermission('bulk_withdraw_read'))
                                                            <a href="{{route('admin.withdraw.invoice.bulk', $withdraw->withdrawBatch->id)}}">
                                                                <span  class="badge text-info">{{ __('batch_no').': '. $withdraw->withdrawBatch->batch_no }}</span><br>
                                                            </a>
                                                        @endif
                                                    @elseif($withdraw->status == 'rejected' || $withdraw->status == 'cancelled')

                                                            <span  class="badge text-danger">{{ __($withdraw->status) }}</span><br>
                                                        @if($withdraw->status != 'cancelled')
                                                            @if(@$withdraw->companyAccountReason)
                                                            <span  class="text-warning">{{ __('reject_reason').': ' }}{{ @$withdraw->companyAccountReason->reject_reason!= '' ? __($withdraw->companyAccountReason->reject_reason): '' }}</span><br>
                                                            @endif
                                                        @endif
                                                        <span class="text">{{ __('at').': ' }}{{$withdraw->updated_at != ""? date('M d, Y h:i a', strtotime($withdraw->updated_at)):''}}</span>
                                                    @else
                                                        <a href="{{route('admin.withdraw.invoice', $withdraw->id)}}">
                                                            <span  class="badge text-success">{{__($withdraw->status)}}</span><br>
                                                        </a>
                                                        @if($withdraw->companyAccount->transaction_id != '' && !$withdraw->withdrawBatch)
                                                            <span  class="badge text-info">{{__('transaction_id').': '.$withdraw->companyAccount->transaction_id}}</span><br>
                                                        @endif
                                                    @if($withdraw->withdrawBatch && hasPermission('bulk_withdraw_read'))
                                                            <a href="{{route('admin.withdraw.invoice.bulk', $withdraw->withdrawBatch->id)}}">
                                                                <span  class="badge text-info">{{ __('batch_no').': '. $withdraw->withdrawBatch->batch_no }}</span><br>
                                                            </a>
                                                        @endif
                                                        <span class="text">{{ __('at').': ' }}{{$withdraw->updated_at != ""? date('M d, Y h:i a', strtotime($withdraw->updated_at)):''}}</span>
                                                    @endif

                                                </div>
                                                <div class="nk-tb-col">
                                                    <span>{{number_format($withdraw->amount,2)}} </span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    @if(!blank(@$withdraw->companyAccount->receipt) && file_exists(@$withdraw->companyAccount->receipt))
                                                        <a href="{{asset($withdraw->companyAccount->receipt)}}" target="_blank"> <em class="icon ni ni-link-alt"></em> {{ __('receipt') }}</a>
                                                    @else
                                                        {{ __('not_available') }}
                                                    @endif
                                                </div>
                                                @if(hasPermission('withdraw_update') || hasPermission('withdraw_process') || hasPermission('withdraw_reject'))
                                                <div class="nk-tb-col nk-tb-col-tools">
                                                    @if($withdraw->status == 'pending' || $withdraw->status == 'processed' || $withdraw->status == 'approved')
                                                        <ul class="nk-tb-actions gx-1">
                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            @if(hasPermission('withdraw_update') && Sentinel::getUser()->id == 1)
                                                                                @if(($withdraw->status == 'pending' && $withdraw->id> 1939) || ($withdraw->status == 'approved' && $withdraw->id> 1939))
                                                                                    <li><a href="{{route('admin.withdraw.edit', $withdraw->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                                @endif
                                                                            @endif
                                                                            <li><a href="{{route('admin.withdraw.invoice', $withdraw->id)}}"><em class="icon ni ni-eye"></em> <span> {{__('invoice')}}</span></a></li>
                                                                            @if(hasPermission('withdraw_process'))
                                                                                @if($withdraw->status == 'pending' || $withdraw->status == 'approved')
                                                                                    @if($withdraw->status == 'pending')
                                                                                        <li>
                                                                                            @if(hasPermission('add_to_bulk_withdraw'))
                                                                                                <a href="javascript:void(0);" class="approve-withdraw" id="approve-withdraw" data-url="{{ route('get-batches') }}" data-toggle="modal" data-target="#withdraw-approve">
                                                                                                    <em class="icon ni ni-check"></em> <span>{{ __('approve') }}</span>
                                                                                                </a>
                                                                                            @else
                                                                                                <a href="javascript:void(0);" onclick="changeWithdrawStatus('{{ '/admin/withdraw-status/' }}', {{$withdraw->id}}, 'approved')">
                                                                                                    <em class="icon ni ni-check"></em> <span>{{ __('approve') }}</span>
                                                                                                </a>
                                                                                            @endif
                                                                                        </li>
                                                                                    @endif
                                                                                    @if($withdraw->status == 'approved')
                                                                                        <li>
                                                                                            <a href="javascript:void(0);" class="change-batch" id="change-batch"
                                                                                               data-toggle="modal" data-target="#batch-change" data-url="{{ route('get-batches') }}">
                                                                                                <em class="icon ni ni-shuffle"></em>
                                                                                                <span> {{__('add_change_batch')}} </span>
                                                                                            </a>
                                                                                        </li>
                                                                                    @endif

                                                                                    <li><a href="javascript:void(0);" class="process-withdraw" id="process-withdraw" data-toggle="modal" data-target="#withdraw-process"><em class="icon ni ni-check-thick"></em> <span> {{__('process')}} </span></a></li>

                                                                                @endif
                                                                            @endif
                                                                            @if(hasPermission('withdraw_reject'))
                                                                                @if(($withdraw->status == 'pending' && $withdraw->id> 1939 ) || ($withdraw->status == 'processed' && $withdraw->id> 1939) || ($withdraw->status == 'approved' && $withdraw->id> 1939))
                                                                                    <li><a href="javascript:void(0);" class="reject-payment" id="reject-payment" data-toggle="modal" data-target="#payment-reject"><em class="icon ni ni-na"></em> <span> {{__('reject')}} </span></a></li>
                                                                                @endif
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    @endif
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


{{--    modal--}}
{{--    process payment modal--}}
    <div class="modal fade" tabindex="-1" id="withdraw-process">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('process_payment')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('process-payment')}}" method="POST" class="form-validate is-alter" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="" id="withdraw-process-id">
                        <div class="form-group">
                            <label class="form-label" for="area">{{ __('transaction_id') }} *</label>
                            <input type="text" name="transaction_id" class="form-control" id="transaction_id" value="{{ old('transaction_id') }}" required>
                            @if($errors->has('transaction_id'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('transaction_id') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ __('account') }} *</label>
                            <div class="form-control-wrap">
                                <select class="form-select form-control form-control-lg account-change" name="account" required>
                                    <option value="">{{ __('select_account') }}</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account') == $account->id ? 'selected': '' }}>
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
                            <div class="nk-block-des text-success">
                                <p>{{ __('current_payable_balance') }}: <span id="current-balance"></span>{{__('tk')}}</p>
                                <input type="hidden" name="payable_balance" id="payable_balance">
                            </div>
                            @if($errors->has('account'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('account') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="receipt">{{__('receipt')}}</label>
                            <div class="form-control-wrap">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="receipt" name="receipt">
                                    <label class="custom-file-label" for="receipt">{{__('choose_file')}}</label>
                                </div>
                            </div>
                            @if($errors->has('receipt'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('receipt') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('process')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{{--    approve payment modal--}}
    <div class="modal fade" tabindex="-1" id="withdraw-approve">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('approve_payment')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('approve-payment')}}" method="POST" class="form-validate is-alter" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="" id="withdraw-approve-id">
                        <div class="form-group">
                            <label class="form-label">{{ __('add_to_batch') }}</label>
                            <div class="form-control-wrap">
                                <select class="form-select form-control form-control-lg withdraw_batches" name="withdraw_batch">
                                    <option value="">{{ __('select_batch') }}</option>
                                </select>
                            </div>
                            @if($errors->has('withdraw_batch'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('withdraw_batch') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('approve')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" tabindex="-1" id="batch-change">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('add_change_batch')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('update-payment-batch')}}" method="POST" class="form-validate is-alter" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="" id="change-batch-id">
                        <div class="form-group">
                            <label class="form-label">{{ __('batch') }}</label>
                            <div class="form-control-wrap">
                                <select class="form-select form-control form-control-lg withdraw_batches" name="withdraw_batch">
                                    <option value="">{{ __('select_batch') }}</option>
                                </select>
                            </div>
                            @if($errors->has('withdraw_batch'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('withdraw_batch') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{{--    reject payment modal--}}
    <div class="modal fade" tabindex="-1" id="payment-reject">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Reject Payment')}}</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="{{route('reject-payment')}}" method="POST" class="form-validate is-alter">
                        @csrf
                        <input type="hidden" name="id" value="" id="reject-payment-id">
                        <div class="form-group">
                            <label class="form-label" for="reject_reason">{{ __('reject_reason') }} *</label>
                            <textarea name="reject_reason" class="form-control" required>{{ old('reject_reason') }}</textarea>

                            @if($errors->has('reject_reason'))
                                <div class="nk-block-des text-danger">
                                    <p>{{ $errors->first('reject_reason') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-lg btn-primary resubmit">{{__('reject')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @include('admin.withdraws.status-ajax')
    <script type="text/javascript">
        $(document).ready(function(){
            $('.account-change').on('change', function(){

                var token = "{{ csrf_token() }}";
                var url = "{{url('')}}"+'/admin/get-balance-info';

                var formData = {
                    id: $(this).val()
                }

                $.ajax({
                    type: 'GET',
                    dataType: 'json',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                })
                    .done(function(response) {
                        console.log(response);

                        if(response < 0){
                            $("#current-balance").parent().removeClass("text-success");
                            $("#current-balance").parent().addClass("text-danger");
                        }else{
                            $("#current-balance").parent().removeClass("text-danger");
                            $("#current-balance").parent().addClass("text-success");
                        }

                        $("#current-balance").html(response);
                        $("#payable_balance").val(response);

                    })
                    .fail(function(error) {
                        Swal.fire('{{ __('opps') }}...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                    })

            });
        });
    </script>

@endpush
@include('live_search.merchants')
@include('admin.withdraws.status-ajax')
