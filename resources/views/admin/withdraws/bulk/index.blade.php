@extends('master')

@section('title')
    {{__('bulk_payments').' '.__('lists')}}
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
                                            @if(hasPermission('bulk_withdraw_create'))
                                                <li class="nk-block-tools-opt">
                                                    <div class="dropdown">
                                                        <a href="{{route('admin.withdraws.bulk.create')}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
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
                                    </div><!-- .card-title-group -->
                                </div><!-- .card-inner -->
                                <div class="card-inner p-0">
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('batch_no')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('title')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('type')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('account')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('total_request')}}</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('total_amount')}} ({{ __('tk') }})</strong></span></div>
                                            <div class="nk-tb-col"><span class="sub-text"><strong>{{__('status')}}</strong></span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('receipt') }}</strong></span></div>
                                            @if(hasPermission('bulk_withdraw_update') || hasPermission('bulk_withdraw_process') || hasPermission('download_payment_sheet'))
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
                                                    <div class="copy-to-clipboard">
                                                        <input readonly type="text" class="text-info" data-text="{{ __('copied') }}" value="{{$withdraw->batch_no}}">
                                                    </div>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    {{ $withdraw->title }}
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    {{ __($withdraw->batch_type) }}
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    @if($withdraw->account)
                                                        @if($withdraw->account->method == 'bank')
                                                            <span>{{ __('bank_name').': '.__($withdraw->account->bank_name) }} </span> <br>
                                                            <span>{{ __('branch').': '.__($withdraw->account->bank_branch) }}</span> <br>
                                                            <span>{{ __('account_holder').': '.$withdraw->account->account_holder_name }}</span> <br>
                                                            <span>{{ __('account_no').': '.$withdraw->account->account_no }}</span>
                                                        @else
                                                            <span>{{ __('payment_method').': '.__($withdraw->account->method) }} </span>
                                                            @if(@$withdraw->withdraw_to == 'bKash' || @$withdraw->withdraw_to == 'nogod' || @$withdraw->withdraw_to == 'rocket')
                                                                <span>{{ __('account_holder').': '.$withdraw->account->account_holder_name }}</span> <br>
                                                                <br><span>{{ __('account_type').': '.__($withdraw->account->type) }} </span> <br>
                                                                <span>{{ __('account_number').': '.$withdraw->account->number }} </span>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="nk-tb-col  tb-col-lg">
                                                    {{ $withdraw->withdraws()->count() }}
                                                </div>
                                                <div class="nk-tb-col">
                                                    <span>{{number_format(($withdraw->withdraws()->sum('amount')),2)}} </span>
                                                </div>
                                                <div class="nk-tb-col">
                                                    <a href="{{route('admin.withdraw.invoice.bulk', $withdraw->id)}}">
                                                        @if($withdraw->status == 'pending')
                                                            <span  class="badge text-warning">{{ __($withdraw->status) }}</span><br>
                                                        @elseif($withdraw->status == 'processed')
                                                            <span  class="badge text-success">{{ __($withdraw->status) }}</span>
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    @if(!blank(@$withdraw->receipt) && file_exists(@$withdraw->receipt))
                                                        <a href="{{asset($withdraw->receipt)}}" target="_blank"> <em class="icon ni ni-link-alt"></em> {{ __('receipt') }}</a>
                                                    @else
                                                        {{ __('not_available') }}
                                                    @endif
                                                </div>
                                                @if(hasPermission('bulk_withdraw_update') || hasPermission('bulk_withdraw_process') || hasPermission('download_payment_sheet') || hasPermission('bulk_withdraw_delete'))
                                                    <div class="nk-tb-col nk-tb-col-tools">
                                                        @if($withdraw->status == 'pending' || $withdraw->status == 'processed')
                                                            <ul class="nk-tb-actions gx-1">
                                                                <li>
                                                                    <div class="drodown">
                                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                            <ul class="link-list-opt no-bdr">
                                                                                @if(hasPermission('bulk_withdraw_update') && Sentinel::getUser()->id == 1)
                                                                                    @if(($withdraw->status == 'pending'))
                                                                                        <li><a href="{{route('admin.withdraws.bulk.edit', $withdraw->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                                    @endif
                                                                                @endif
                                                                                @if(hasPermission('bulk_withdraw_process'))
                                                                                    @if($withdraw->status == 'pending' && !blank($withdraw->withdraws))
                                                                                        <li><a href="javascript:void(0);" class="process-withdraw" id="process-withdraw" data-toggle="modal" data-target="#withdraw-process"><em class="icon ni ni-check-thick"></em> <span> {{__('process')}} </span></a></li>
                                                                                    @endif
                                                                                @endif
                                                                                @if(hasPermission('download_payment_sheet'))
                                                                                    <li><a href="{{route('admin.payment.report', $withdraw->id)}}"><em class="icon ni ni-download"></em> <span> {{__('Payment Sheet')}}</span></a></li>
                                                                                @endif
                                                                                <li><a href="{{route('admin.withdraw.invoice.bulk', $withdraw->id)}}"><em class="icon ni ni-eye"></em> <span> {{__('invoice')}}</span></a></li>
                                                                                @if(hasPermission('bulk_withdraw_delete') && blank($withdraw->withdraws) && Sentinel::getUser()->id == 1)
                                                                                    <li><a href="javascript:void(0);" onclick="delete_row('bulk-withdraw-delete/', {{$withdraw->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
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
                    <form action="{{route('admin.bulk.process-payment')}}" method="POST" class="form-validate is-alter" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="" id="withdraw-process-id">
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
@include('common.delete-ajax')

