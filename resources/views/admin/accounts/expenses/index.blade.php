@extends('master')

@section('title')
{{__('expense')}} {{__('lists')}}
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
                                <p>{{__('you_have_total')}} {{ !blank($expenses)? $expenses->total():'0' }} {{__('expenses')}}.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            @if(hasPermission('expense_create'))
                                <a href="{{route('expenses.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
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

                                        <div class="nk-tb-col  tb-col-lg"><span class="sub-text"><strong>{{__('title')}}/{{__('detalis')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('account_details')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('date') .'/' .__('created_at')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('amount')}}({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('receipt')}}</strong></span></div>
                                        @if(hasPermission('expense_update') || hasPermission('expense_delete'))
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                        @endif
                                    </div><!-- .nk-tb-item -->
                                    @foreach($expenses as $key => $expense)
                                    <div class="nk-tb-item" id="row_{{$expense->id}}">
                                        <div class="nk-tb-col">
                                            <span>{{$key + 1}}</span>
                                        </div>
                                        <div class="nk-tb-col column-max-width tb-col-lg">
                                            {{__($expense->details)}}<br>
                                            @if($expense->source == "withdraw")

                                                {{__('company')}} : {{@$expense->withdraw->merchant->company}}

                                            @endif
                                            @if($expense->parcel_id != "")
                                                {{__('parcel_no')}} : {{@$expense->parcel->parcel_no}}<br>
                                                {{__('company')}} : {{@$expense->parcel->merchant->company}}
                                            @endif

                                        </div>
                                        <div class="nk-tb-col">
                                            @if(!blank($expense->account))
                                            @if($expense->account->method == 'bank')
                                                <span>{{__('name')}}: {{$expense->account->account_holder_name}}</span><br>
                                                <span>{{__('account_no')}}: {{$expense->account->account_no}}</span><br>
                                                <span>{{__('bank')}}: {{__($expense->account->bank_name)}}</span><br>
                                                <span>{{__('branch')}}:{{$expense->account->bank_branch}}</span><br>
                                            @elseif($expense->account->method == 'cash')
                                                <span>{{__('name')}}: {{$expense->account->user->first_name.' '.$expense->account->user->last_name}}({{__($expense->account->method)}})</span><br>
                                                <span>{{__('email')}}: {{$expense->account->user->email}}</span><br>
                                            @else
                                                <span>{{__('name')}}: {{$expense->account->account_holder_name}}</span><br>
                                                <span>{{__('number')}}: {{$expense->account->number}}</span><br>
                                                <span>{{__('account_type')}}: {{__($expense->account->type)}}</span><br>
                                            @endif
                                            @endif
                                        </div>
                                        <div class="nk-tb-col">
                                            {{$expense->date != "" ? date('M d, Y', strtotime($expense->date)) : ''}} <br>
                                            {{date('M d, Y h:i a', strtotime($expense->created_at))}}
                                        </div>

                                        <div class="nk-tb-col">
                                            {{number_format($expense->amount,2)}}
                                        </div>

                                        <div class="nk-tb-col">
                                            @if(!blank($expense->receipt) && file_exists($expense->receipt))
                                                <a href="{{asset($expense->receipt)}}" target="_blank"> <em class="icon ni ni-link-alt"></em> {{ __('receipt') }}</a>
                                            @else
                                                {{ __('not_available') }}
                                            @endif
                                        </div>
                                        @if(hasPermission('expense_update') || hasPermission('expense_delete'))
                                        <div class="nk-tb-col nk-tb-col-tools">
                                            @if(Sentinel::getUser()->id == 1)
                                                @if($expense->create_type == "user_defined")
                                                        <ul class="nk-tb-actions gx-1">
                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            @if(hasPermission('expense_update'))
                                                                            <li><a href="{{route('expenses.edit', $expense->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                            @endif
                                                                            @if(hasPermission('expense_delete'))
                                                                            <li><a href="javascript:void(0);" onclick="delete_row('expense-delete/', {{$expense->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    @endif
                                            @endif

                                        </div>
                                        @endif


                                    </div><!-- .nk-tb-item -->
                                    @endforeach
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! !blank($expenses)? $expenses->links():'' !!}
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
