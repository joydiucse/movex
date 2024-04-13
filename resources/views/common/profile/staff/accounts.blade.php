@extends('master')

@section('title')
    {{__('accounts')}}
@endsection

@section('mainContent')
    <div class="nk-content ">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block">
                        <div class="card">
                            <div class="card-aside-wrap">

                                <div class="card-inner card-inner-lg">
                                    <div class="nk-block-head nk-block-head-lg">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content">
                                                <h4 class="nk-block-title">{{__('accounts')}}</h4>
                                            </div>
                                            <div class="nk-block-head-content align-self-start d-lg-none">
                                                <a href="#" class="toggle btn btn-icon btn-trigger mt-n1" data-target="userAside"><em class="icon ni ni-menu-alt-r"></em></a>
                                            </div>
                                        </div>
                                    </div><!-- .nk-block-head -->
                                    <div class="nk-block card">
                                        <table class="table table-ulogs">
                                            <thead class="thead-light">
                                            <tr>
                                                <th class="tb-col-os"><span class="overline-title">{{__('account_details')}}</th>
                                                <th class="tb-col-ip"><span class="overline-title">{{__('opening_balance')}}</span></th>
                                                <th class="tb-col-ip"><span class="overline-title">{{__('current_balance')}}</span></th>
                                                <th class="tb-col-ip"><span class="overline-title">{{__('options')}}</span></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($accounts as $account)
                                                <tr>
                                                    <td class="tb-col-os">
                                                        @if($account->method == 'bank')
                                                            <span>{{__('name')}}: {{$account->account_holder_name}}</span><br>
                                                            <span>{{__('account_no')}}: {{$account->account_no}}</span><br>
                                                            <span>{{__('account_no')}}: {{$account->account_no}}</span><br>
                                                            <span>{{__('bank')}}: {{__($account->bank_name)}}</span><br>
                                                            <span>{{__('branch')}}: {{$account->bank_branch}}</span><br>
                                                        @elseif($account->method == 'cash')
                                                            <span>{{__($account->method)}}</span><br>
                                                        @else
                                                            <span>{{__('name')}}: {{$account->account_holder_name}}</span><br>
                                                            <span>{{__('number')}}: {{$account->number}}</span><br>
                                                            <span>{{__('account_type')}}: {{__($account->type)}}</span><br>
                                                        @endif
                                                    </td>
                                                    <td class="tb-col-ip"><span class="sub-text">{{number_format($account->balance,2)}}</span></td>
                                                    <td class="tb-col-ip"><span class="sub-text">{{number_format( $account->incomes()->sum('amount') + $account->fundReceives()->sum('amount') - $account->expenses()->sum('amount') - $account->fundTransfers()->sum('amount'),2)}}</span></td>
                                                    <td class="tb-col-ip">
                                                        <ul class="nk-tb-actions gx-1">
                                                            <li>
                                                                <div class="drodown">
                                                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                                    <div class="dropdown-menu dropdown-menu-right">
                                                                        <ul class="link-list-opt no-bdr">
                                                                            <li><a href="{{route('staff.account.statement', $account->id)}}"><em class="icon ni ni-eye"></em> <span> {{__('statement')}}</span></a></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div><!-- .nk-block-head -->
                                </div><!-- .card-inner -->

                                @include('common.profile.staff.profile-sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
