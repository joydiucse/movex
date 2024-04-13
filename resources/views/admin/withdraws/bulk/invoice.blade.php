@extends('master')

@section('title')
    {{__('batch_payment_invoice')}}
@endsection

@section('mainContent')

    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">{{__('batch_payment_invoice')}}</h3>
                                <div class="nk-block-des text-soft">

                                </div>
                            </div><!-- .nk-block-head-content -->

                            <div class="nk-block-head-content">
                                <a href="{{url()->previous()}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('back')}}</span></a>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->
                    <div class="nk-block ">
                        <div class="card card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card assign-delivery border-bottom">
                                                    <div class="row mb-2">
                                                        <div class="col-6">
                                                            <div class="right-content d-block">
                                                                <img src="{{ asset('admin/images/logo-green.png') }}" alt="logo" class="img-fluid image">
                                                                <div class="greenx-details d-block mt-2">
                                                                    {{ __('address_1st') }} <br>
                                                                    {{ __('address_2nd') }} <br>
                                                                    {{ __('app_phone_number') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">

                                                        </div>
                                                        <div class="col-2">
                                                            <div class="invoice d-block">
                                                                <center>
                                                                    <h4 >{{ __('INVOICE') }}</h4>
                                                                    <p>{{ __('id') }}# {{ $withdraw->batch_no }}</p>
                                                                    {{ __('date').': '.date('d.m.Y') }}
                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

{{--                                        <div class="row mt-4">--}}
{{--                                            <div class="col-6">--}}
{{--                                                <table class="table table-bordered">--}}
{{--                                                    <tbody>--}}
{{--                                                    <tr>--}}
{{--                                                        <th scope="row" colspan="2"><span class="center"> {{ __('withdraw_details') }} </span></th>--}}
{{--                                                    </tr>--}}
{{--                                                    @php--}}
{{--                                                        $account_details = json_decode($withdraw->account_details);--}}
{{--                                                    @endphp--}}
{{--                                                    @if($withdraw->withdraw_to == 'bank')--}}
{{--                                                        <tr>--}}
{{--                                                            <th scope="row">{{ __('payment_method') }}</th>--}}
{{--                                                            <td>{{ __('bank') }}</td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <th scope="row">{{ __('bank_name') }}</th>--}}
{{--                                                            <td>{{ @$account_details[0] }}</td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <th scope="row">{{ __('branch') }}</th>--}}
{{--                                                            <td>{{ @$account_details[1] }}</td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <th scope="row">{{ __('account_holder') }}</th>--}}
{{--                                                            <td>{{ @$account_details[2] }}</td>--}}
{{--                                                        </tr>--}}
{{--                                                        <tr>--}}
{{--                                                            <th scope="row">{{ __('account_no') }}</th>--}}
{{--                                                            <td>{{ @$account_details[3] }}</td>--}}
{{--                                                        </tr>--}}
{{--                                                        @if(@$account_details[4] != '')--}}
{{--                                                        <tr>--}}
{{--                                                            <th scope="row">{{ __('routing_no') }}</th>--}}
{{--                                                            <td>{{ @$account_details[4] }}</td>--}}
{{--                                                        </tr>--}}
{{--                                                        @endif--}}
{{--                                                    @else--}}
{{--                                                        <tr>--}}
{{--                                                            <th scope="row">{{ __('payment_method') }}</th>--}}
{{--                                                            <td>{{ __(@$withdraw->withdraw_to) }}</td>--}}
{{--                                                        </tr>--}}
{{--                                                        @if(@$withdraw->withdraw_to == 'bKash' || @$withdraw->withdraw_to == 'nogod' || @$withdraw->withdraw_to == 'rocket')--}}
{{--                                                            <tr>--}}
{{--                                                                <th scope="row">{{ __('account_type') }}</th>--}}
{{--                                                                <td>{{ __(@$account_details[2]) }}</td>--}}
{{--                                                            </tr>--}}
{{--                                                            <tr>--}}
{{--                                                                <th scope="row">{{ __('account_number') }}</th>--}}
{{--                                                                <td>{{ @$account_details[1] }}</td>--}}
{{--                                                            </tr>--}}

{{--                                                        @endif--}}
{{--                                                    @endif--}}
{{--                                                    </tbody>--}}
{{--                                                </table>--}}
{{--                                                @if($withdraw->note != '' or $withdraw->note != null)--}}
{{--                                                    <div class="note mt-2">--}}
{{--                                                        <textarea class="form-control">{{ __('note') }}: {{ $withdraw->note }}</textarea>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                    @if(!blank($withdraw->withdraws))
                                        <div class="col-12 mt-5">
                                            <center>
                                                <h5 class="card-title mb-3">{{ __('cleared_payment_requests') }}</h5>
                                                <hr width="50%">
                                            </center>

                                            <table class="table table-bordered">
                                                <tbody>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">{{ __('invoice_no') }}</th>
                                                    <th scope="col">{{ __('payment_to') }}</th>
                                                    <th scope="col">{{ __('amount').' ('.__('tk').')' }}</th>
                                                    @if($withdraw->status == 'pending' && hasPermission('add_to_bulk_withdraw'))
                                                        <th scope="col">{{ __('options') }}</th>
                                                    @endif
                                                </tr>
                                                @foreach($withdraw->withdraws as $key=>$request)
                                                    <tr id="row_{{$request->id}}">
                                                        <td>
                                                            {{ $key+1 }}
                                                        </td>
                                                        <td>{{ $request->withdraw_id }}</td>

                                                        <td>
                                                            @php
                                                                $account_details = json_decode($request->account_details);
                                                            @endphp
							    <span>{{ __('merchant').': '.$request->merchant->company }}</span> <br>
                                                            @if($request->withdraw_to == 'bank')
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
                                                                <span>{{ __('payment_method').': '.__(@$request->withdraw_to) }} </span>
                                                                @if(@$request->withdraw_to == 'bKash' || @$request->withdraw_to == 'nogod' || @$request->withdraw_to == 'rocket')
                                                                    <br><span>{{ __('account_type').': '.__(@$account_details[2]) }} </span> <br>
                                                                    <span>{{ __('account_number').': '.@$account_details[1] }} </span>
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{number_format($request->amount,2)}}
                                                        </td>.
                                                        @if($withdraw->status == 'pending' && hasPermission('add_to_bulk_withdraw'))
                                                        <td>
                                                            <button type="button" onclick="delete_row('remove-from-batch/', {{$request->id}})" class="btn btn-danger"><i class="ni ni-trash"></i></button>
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <th scope="row" colspan="3">{{ __('total_amount') }}</th>
                                                    <td>{{ number_format($withdraw->withdraws->sum('amount'), 2) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    <div class="col-12 mt-5">
                                        <center>
                                            <h5 class="card-title mb-3">{{ __('processed_from_account_details') }}</h5>
                                            <p>{{ __('account_info_from_which_this_transaction_processed') }}</p>
                                            <hr width="50%">
                                        </center>

                                        <table class="table table-bordered">
                                            <tbody>
                                            <tr>
                                                <th scope="row">{{ __('request_created_by') }}</th>
                                                <td>{{ __($withdraw->user->first_name.' '.$withdraw->user->last_name.' ') }}({{ __($withdraw->user->user_type) }})</td>
                                            </tr>
                                            @isset($withdraw->account)
                                                <tr>
                                                    <th scope="row">{{ __('payment_method') }}</th>
                                                    <td>{{ __($withdraw->account->method) }}</td>
                                                </tr>
                                                @if($withdraw->account->method != 'cash')
                                                    <tr>
                                                        <th scope="row">{{ __('account_holder_name') }}</th>
                                                        <td>{{ __($withdraw->account->account_holder_name) }}</td>
                                                    </tr>
                                                @endif
                                                @if($withdraw->account->method == 'bank')

                                                    <tr>
                                                        <th scope="row">{{ __('bank_name') }}</th>
                                                        <td>{{ __($withdraw->account->bank_name) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">{{ __('branch') }}</th>
                                                        <td>{{ __($withdraw->account->bank_branch) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">{{ __('account_no') }}</th>
                                                        <td>{{ __($withdraw->account->account_no) }}</td>
                                                    </tr>
                                                @elseif($withdraw->account->method == 'cash')

                                                    <tr>
                                                        <th scope="row">{{ __('account_holder_name') }}</th>
                                                        <td>{{ __($withdraw->account->user->first_name.' '.$withdraw->account->user->last_name) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">{{ __('email') }}</th>
                                                        <td>{{ __($withdraw->account->user->email) }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <th scope="row">{{ __('account_type') }}</th>
                                                        <td>{{ __($withdraw->account->type) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">{{ __('account_number') }}</th>
                                                        <td>{{ $withdraw->account->number }}</td>
                                                    </tr>
                                                @endif
                                            @endisset
                                            </tbody>
                                        </table>
                                    </div>
                                </div><!-- .card-inner -->
                            </div><!-- .card-inner-group -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection

@include('common.delete-ajax')
