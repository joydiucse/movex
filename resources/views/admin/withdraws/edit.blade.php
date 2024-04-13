@extends('master')

@section('title')
    {{__('edit').' '.__('payment')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('edit').' '.__('payment')}}</h3>
                            </div><!-- .nk-block-head-content -->
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-menu-alt-r"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <div class="drodown">
                                                    <a href="{{url()->previous()}}" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div><!-- .toggle-wrap -->
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <form action="{{ route('admin.withdraw.update')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" value="{{$withdraw->id}}" name="id">
                        <input type="hidden" value="{{$withdraw->amount}}" id="withdraw_amount" name="withdraw_amount">
                        <input type="hidden" value="{{$withdraw->merchant_id}}" name="merchant">
                        <div class="card">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card-inner">

                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('merchant') }}({{__('we_dont_allow_to_change_merchant')}}) *</label>
                                                    <input type="text" id="current-amount" value="{{ old('amount') != ""? old('amount'):$withdraw->amount }}" name="amount" hidden>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" value="{{ $withdraw->merchant->user->first_name.' '.$withdraw->merchant->user->last_name }} ({{$withdraw->merchant->company}})" required disabled>
                                                    </div>
                                                    @if($errors->has('merchant'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('merchant') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="amount">{{__('amount').' ('.__('tk')}}) *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" class="form-control" id="amount" value="{{ old('amount') != ""? old('amount'):$withdraw->amount }}" placeholder="{{ __('amount') }}" readonly required>
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
                                                        <select class="form-select form-control form-control-lg" id="withdraw_to" name="withdraw_to" required>
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
                                            <div class="col-md-3">
                                                <div class="preview-block">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="status" value="processed" {{$withdraw->status == "processed"? 'checked':''}}>
                                                        <label class="custom-control-label" for="customCheck1">{{__('is_processed')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs d-none" id="transaction-area">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="area">{{ __('transaction_id') }} *</label>
                                                    <input type="text" name="transaction_id" class="form-control" id="transaction_id" value="{{ old('transaction_id') }}" required>
                                                    @if($errors->has('transaction_id'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('transaction_id') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs d-none" id="account-area">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{ __('account') }} *</label>
                                                    <div class="form-control-wrap">
                                                        <select class="form-select form-control form-control-lg account-change" id="account" name="account" required>
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
                                                        <p>{{ __('current_payable_balance') }}: <span id="balance"></span>{{__('tk')}}</p>
                                                        <input type="hidden" name="current_payable_balance" id="current_payable_balance">
                                                    </div>
                                                    @if($errors->has('account'))
                                                        <div class="nk-block-des text-danger">
                                                            <p>{{ $errors->first('account') }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-gs d-none" id="receipt-area">
                                            <div class="col-sm-6">
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
                                            </div>
                                        </div>
                                        <div class="row g-gs">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="details">{{__('details')}} </label>
                                                    <div class="form-control-wrap">
                                                        <textarea class="form-control" id="details" placeholder="{{__('details').' ('.__('optional').')'}}" name="details">{{ old('details') != '' ? old('details') : $withdraw->account->details }}</textarea>
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

@push('script')
<script type="text/javascript">
    $(document).ready(function(){
        $('#change-merchant').on('change', function(){

            var token = "{{ csrf_token() }}";
            var url = "{{url('')}}"+'/admin/get-merchant-info';

            var formData = {
                id: $(this).val(),
                amount: $('#withdraw_amount').val()
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

                $("#amount").val(response['balance']);
                $("#current-amount").val(response['balance']);

                $("#withdraw_to").empty();
                $("#withdraw_to").append(response['options']);

            })
            .fail(function(error) {
                Swal.fire('{{ __('opps') }}...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
            })

        });

        $('#customCheck1').on('click', function() {
            if ($(this).is(':checked')) {
                $("#transaction-area").removeClass("d-none");
                $("#account-area").removeClass("d-none");
                $("#receipt-area").removeClass("d-none");

            }
            else{
                $("#transaction-area").addClass("d-none");
                $("#account-area").addClass("d-none");
                $("#receipt-area").addClass("d-none");
                $("#transaction_id").removeAttribute("required");
                $("#account").removeAttribute("required");
            }
        });
    });

</script>
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
                        $("#balance").parent().removeClass("text-success");
                        $("#balance").parent().addClass("text-danger");
                    }else{
                        $("#balance").parent().removeClass("text-danger");
                        $("#balance").parent().addClass("text-success");
                    }

                    $("#balance").html(response);
                    $("#current_payable_balance").val(response);

                })
                .fail(function(error) {
                    Swal.fire('{{ __('opps') }}...', '{{ __('something_went_wrong_with_ajax') }}', 'error');
                })

        });
    });

</script>
@endpush

@include('live_search.merchants')
