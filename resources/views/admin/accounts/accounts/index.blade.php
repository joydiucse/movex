@extends('master')

@section('title')
{{__('account')}} {{__('lists')}}
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
                                <p>{{__('you_have_total')}} {{ !blank($accounts)? $accounts->total():'0' }} {{__('accounts')}}.</p>
                            </div>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            @if(hasPermission('account_create'))
                                <a href="{{route('admin.account.create')}}" class="btn btn-primary d-md-inline-flex"><em class="icon ni ni-plus"></em><span>{{__('add')}}</span></a>
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
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>#</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('user')}}/{{__('account_details')}}</strong></span></div>
                                        <div class="nk-tb-col tb-col-lg"><span class="sub-text"><strong>{{__('method')}}</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('opening_balance')}}({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('current_balance')}}({{__('tk')}})</strong></span></div>
                                        <div class="nk-tb-col"><span class="sub-text"><strong>{{__('options')}}</strong></span></div>
                                       
                                    </div><!-- .nk-tb-item -->
                                    @foreach($accounts as $key => $account)
                                    <div class="nk-tb-item" id="row_{{$account->id}}">
                                        <div class="nk-tb-col tb-col-lg">
                                            <span>{{$key + 1}}</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            @if(hasPermission('account_statement'))
                                            <a href="{{route('admin.account.statement', $account->id)}}">
                                            @endif

                                                <div class="bank-details">
                                                    @if($account->method == 'bank')
                                                        <span>{{__('name')}}: {{$account->account_holder_name}}</span><br>
                                                        <span>{{__('account_no')}}: {{$account->account_no}}</span><br>
                                                        <span>{{__('bank')}}: {{__($account->bank_name)}}</span><br>
                                                        <span>{{__('branch')}}:{{$account->bank_branch}}</span><br>
                                                    @elseif($account->method == 'cash')
                                                        <span>{{__('name')}}: {{$account->user->first_name.' '.$account->user->last_name}}</span><br>
                                                        <span>{{__('email')}}: {{$account->user->email}}</span><br>
                                                    @else
                                                        <span>{{__('name')}}: {{$account->account_holder_name}}</span><br>
                                                        <span>{{__('number')}}: {{$account->number}}</span><br>
                                                        <span>{{__('account_type')}}: {{__($account->type)}}</span><br>
                                                    @endif
                                                </div>
                                            @if(hasPermission('account_statement'))
                                            </a>
                                            @endif
                                        </div>

                                        <div class="nk-tb-col tb-col-lg">
                                            {{__($account->method)}}
                                        </div>

                                        <div class="nk-tb-col">
                                            {{number_format($account->balance,2)}}
                                        </div>

                                        <div class="nk-tb-col">
                                            {{number_format( $account->incomes()->sum('amount') + $account->fundReceives()->sum('amount') - $account->expenses()->sum('amount') - $account->fundTransfers()->sum('amount'),2)}}
                                        </div>

                                        <div class="nk-tb-col nk-tb-col-tools">
                                            @if(Sentinel::getUser()->id == 1)
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    @if(hasPermission('account_update'))
                                                                    <li><a href="{{route('admin.account.edit', $account->id)}}"><em class="icon ni ni-edit"></em> <span> {{__('edit')}}</span></a></li>
                                                                    @endif
                                                                    {{-- @if(hasPermission('account_delete'))
                                                                    <li><a href="javascript:void(0);" onclick="delete_row('account-delete/', {{$account->id}})" id="delete-btn"><em class="icon ni ni-trash"></em> <span> {{__('delete')}} </span></a></li>
                                                                    @endif --}}
                                                                    {{-- @if(hasPermission('account_view'))
                                                                    <li><a href="{{route('admin.account.view', $account->id)}}"><em class="icon ni ni-eye"></em> <span> {{__('view')}}</span></a></li>
                                                                    @endif --}}
                                                                    @if(hasPermission('account_statement'))
                                                                    <li><a href="{{route('admin.account.statement', $account->id)}}"><em class="icon ni ni-eye"></em> <span> {{__('statement')}}</span></a></li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            @endif

                                        </div>

                                    </div><!-- .nk-tb-item -->
                                    @endforeach
                                </div><!-- .nk-tb-list -->
                            </div><!-- .card-inner -->
                            <div class="card-inner p-2">
                                <div class="nk-block-between-md g-3">
                                    <div class="g">
                                        {!! !blank($accounts)? $accounts->links():'' !!}
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
