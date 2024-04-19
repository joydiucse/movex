<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.dashboard') : route('dashboard') }}" class="logo-link nk-sidebar-logo">
                <img class="logo-dark  logo-img logo-img-lg" src="{{ asset('admin/')}}/images/logo-green.png" srcset="{{ asset('admin/')}}/images/logo-green.png 2x" alt="logo">
{{--                <img class="logo-dark logo-img logo-img-lg" src="{{ asset('admin/')}}/images/logo-dark.png" srcset="{{ asset('admin/')}}/images/logo-dark2x.png 2x" alt="logo-dark">--}}
{{--                <img class="logo-small logo-img logo-img-small" src="{{ asset('admin/')}}/images/logo-small.png" srcset="{{ asset('admin/')}}/images/logo-small2x.png 2x" alt="logo-small">--}}
            </a>
        </div>
        <div class="nk-menu-trigger mr-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-item">
                        <a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.dashboard') : route('dashboard') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard"></em></span>
                            <span class="nk-menu-text">{{__('dashboard')}}</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    @if(\Sentinel::getUser()->user_type == 'staff')
                        @if(hasPermission('parcel_read'))
                            <li class="nk-menu-item">
                                <a href="{{route('parcel')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-wallet-out"></em></span>
                                    <span class="nk-menu-text">{{__('parcels')}}</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                        @endif

                        @if(hasPermission('parcel_return_assigned_to_merchant'))
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
                                    <span class="nk-menu-text">{{__('return')}}</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{route('parcel.return')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('parcel_return')}}</span></a>
                                    </li>
                                @if(hasPermission('return_read'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('parcel.return.list')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('bulk_returns')}}</span></a>
                                    </li>
                                @endif
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                        @endif

                        @if(hasPermission('parcel_read'))
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
                                <span class="nk-menu-text">{{__('delivery')}}</span>
                            </a>
                            <ul class="nk-menu-sub">
                            @if(hasPermission('parcel_delivery_assigned'))
                                <li class="nk-menu-item">
                                    <a href="{{route('bulk.assigning')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('assign_delivery')}}</span></a>
                                </li>
                            @endif
                            @if(hasPermission('parcel_read'))
                                <li class="nk-menu-item">
                                    <a href="{{route('parcel.delivery-assign.list')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('assign_delivery')}} {{__('list') }}</span></a>
                                </li>
                            @endif
                            </ul><!-- .nk-menu-sub -->
                        </li><!-- .nk-menu-item -->
                    @endif

                        @if(hasPermission('withdraw_read') || hasPermission('bulk_withdraw_read'))
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-account-setting"></em></span>
                                    <span class="nk-menu-text">{{__('payments')}}</span>
                                </a>
                                <ul class="nk-menu-sub">
                                @if(hasPermission('withdraw_read'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('admin.withdraws')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('all_payments')}}</span></a>
                                    </li>
                                @endif
                                @if(hasPermission('bulk_withdraw_read'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('admin.withdraws.bulk')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('bulk_payments')}}</span></a>
                                    </li>
                                @endif
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                        @endif

                        @if(hasPermission('income_read') || hasPermission('expense_read')|| hasPermission('account_read') || hasPermission('fund_transfer_read'))
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-account-setting"></em></span>
                                    <span class="nk-menu-text">{{__('accounts')}}</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if(hasPermission('income_read'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('incomes')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('incomes')}}</span></a>
                                    </li>
                                    @endif
                                    @if(hasPermission('expense_read'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('expenses')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('expenses')}}</span></a>
                                    </li>
                                    @endif
                                    @if(hasPermission('account_read'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('admin.account')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('accounts')}}</span></a>
                                    </li>
                                    @endif
                                    @if(hasPermission('fund_transfer_read'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('admin.fund-transfer')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('fund_transfers')}}</span></a>
                                    </li>
                                    @endif

                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                        @endif
                        @if(hasPermission('merchant_read'))
                        <li class="nk-menu-item">
                            <a href="{{route('merchant')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-cart"></em></span>
                                <span class="nk-menu-text">{{__('merchants')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                        @endif

                        @if(hasPermission('name_change_read'))
                        <li class="nk-menu-item">
                            <a href="{{route('name-change-requests')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-edit"></em></span>
                                <span class="nk-menu-text">{{__('name_changes')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                        @endif

                        @if(hasPermission('deliveryman_read'))
                        <li class="nk-menu-item">
                            <a href="{{route('delivery.man')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-user-list"></em></span>
                                <span class="nk-menu-text">{{__('delivery_man')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                        @endif

                        @if(hasPermission('report_read') && (hasPermission('transaction_history_read') || (hasPermission('parcels_summary_read') || hasPermission('total_summary_read'))
                            || hasPermission('income_report_read') || hasPermission('expense_report_read')))
                        <li class="nk-menu-item has-sub" id="report-main">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-reports"></em></span>
                                <span class="nk-menu-text">{{__('reports')}} </span>
                            </a>
                            <ul class="nk-menu-sub">
                                @if(hasPermission('transaction_history_read'))
                                    <li class="nk-menu-item" id="transaction-history-sub">
                                        <a href="{{route('admin.transaction_history')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('transaction_history')}}</span></a>
                                    </li>
                                @endif
                                @if(hasPermission('parcels_summary_read'))
                                    <li class="nk-menu-item" id="parcel-summery-sub">
                                        <a href="{{route('admin.parcels')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('parcels_summary')}}</span></a>
                                    </li>
                                @endif
                                @if(hasPermission('total_summary_read'))
                                    <li class="nk-menu-item">
                                        <a href="{{route('admin.total_summery')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('total_summery')}}</span></a>
                                    </li>
                                @endif
                                @if(hasPermission('income_expense_report_read'))
                                    <li class="nk-menu-item" id="income-expense-sub">
                                        <a href="{{route('admin.income.expense')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('income').'/'.__('expense')}}</span></a>
                                    </li>
                                @endif
                                @if(hasPermission('merchant_summary_report_read'))
                                    <li class="nk-menu-item" id="income-expense-sub">
                                        <a href="{{route('admin.merchant.summary')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('merchant')}}</span></a>
                                    </li>
                                 @endif
                                 @if(hasPermission('merchant_summary_report_read'))
                                    <li class="nk-menu-item" id="income-expense-sub">
                                        <a href="{{route('admin.agent.sales.summary')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('agent_sales_summary')}}</span></a>
                                    </li>
                                @endif
                                @if(hasPermission('transaction_history_read'))
                                <li class="nk-menu-item" id="transaction-history-sub">
                                    <a href="{{route('admin.account.summary')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('account_summary')}}</span></a>
                                </li>
                            @endif
                            </ul><!-- .nk-menu-sub -->
                        </li><!-- .nk-menu-item -->
                        @endif

                        @if(hasPermission('user_read') || hasPermission('role_read'))
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                <span class="nk-menu-text">{{__('user_manage')}}</span>
                            </a>
                            <ul class="nk-menu-sub">
                                @if(hasPermission('role_read'))
                                <li class="nk-menu-item">
                                    <a href="{{route('roles.index')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('roles')}}</span></a>
                                </li>
                                @endif
                                @if(hasPermission('user_read'))
                                <li class="nk-menu-item">
                                    <a href="{{route('users')}}" class="nk-menu-link"><span class="nk-menu-text">{{__('users')}}</span></a>
                                </li>
                                @endif
                            </ul><!-- .nk-menu-sub -->
                        </li><!-- .nk-menu-item -->
                        @endif
                    @endif

{{--                    merchant routes--}}
                    @if(Sentinel::getUser()->user_type == 'merchant')
                        <li class="nk-menu-item">
                            <a href="{{ route('merchant.parcel') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-package"></em></span>
                                <span class="nk-menu-text">{{__('parcels')}}</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{ route('merchant.parcel.return-list') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
                                <span class="nk-menu-text">{{__('return')}}</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('merchant.withdraw')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-wallet-out"></em></span>
                                <span class="nk-menu-text">{{__('payments')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                        <li class="nk-menu-item">
                            <a href="{{route('merchant.statements')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-wallet-out"></em></span>
                                <span class="nk-menu-text">{{__('payment_logs')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                    @endif

                    @if(Sentinel::getUser()->user_type == 'merchant')
                        <li class="nk-menu-item">
                            <a href="{{ route('merchant.profile')}}"  class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-user-alt"></em></span>
                                <span class="nk-menu-text">{{__('profile')}}</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="{{route('merchant.staffs')}}"  class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                <span class="nk-menu-text">{{__('staffs')}}</span>
                            </a>
                        </li>
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-account-setting"></em></span>
                                <span class="nk-menu-text">{{__('settings')}}</span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item"><a href="{{ route('merchant.profile') }}" class="nk-menu-link">
                                        <span>{{__('personal_information')}}</span></a>
                                </li>

                                <li class="nk-menu-item"><a href="{{ route('merchant.company') }}" class="nk-menu-link">
                                    <span>{{__('company_information')}}</span></a>
                                </li>

                                <li class="nk-menu-item"><a href="{{ route('merchant.security-settings') }}" class="nk-menu-link">
                                    <span>{{__('password_and_security')}}</span></a>
                                </li>

                                <li class="nk-menu-item"><a href="{{ route('merchant.payment.accounts') }}" class="nk-menu-link">
                                    <span>{{__('availabe_payment_accounts')}}</span></a>
                                </li>



                                <li class="nk-menu-item"><a href="{{route('merchant.charge')}}" class="nk-menu-link">
                                    <span>{{__('delivery_charge')}}</span></a>
                                 </li>


                                <li class="nk-menu-item"><a href="{{ route('merchant.shops') }}" class="nk-menu-link">
                                    <span>{{__('my_shops')}}</span></a>
                                </li>


                                <li class="nk-menu-item"><a href="{{ route('merchant.api.credentials') }}" class="nk-menu-link">
                                    <span>{{__('api_credentials')}}</span></a>
                                </li>


                            </ul><!-- .nk-menu-sub -->
                        </li><!-- .nk-menu-item -->

                    @elseif(Sentinel::getUser()->user_type == 'merchant_staff')
                        @if(hasPermission('manage_parcel'))
                            <li class="nk-menu-item">
                                <a href="{{ route('merchant.staff.parcel') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-package"></em></span>
                                    <span class="nk-menu-text">{{__('parcels')}}</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                        @endif
                        @if(hasPermission('manage_payment'))
                            <li class="nk-menu-item">
                                <a href="{{route('merchant.staff.withdraw')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-wallet-out"></em></span>
                                    <span class="nk-menu-text">{{__('payments')}}</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                        @endif
                        @if(hasPermission('read_logs'))
                            <li class="nk-menu-item">
                                <a href="{{route('merchant.staff.statements')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-wallet-out"></em></span>
                                    <span class="nk-menu-text">{{__('payment_logs')}}</span>
                                </a>
                            </li><!-- .nk-menu-item -->
                        @endif
                        <li class="nk-menu-item">
                            <a href="{{ route('merchant.staff.profile')}}"  class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-user-alt"></em></span>
                                <span class="nk-menu-text">{{__('profile')}}</span>
                            </a>
                        </li>
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-account-setting"></em></span>
                                <span class="nk-menu-text">{{__('settings')}}</span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item"><a href="{{ route('merchant.staff.profile') }}" class="nk-menu-link">
                                    <span>{{__('personal_information')}}</span></a>
                                </li>
                                @if(hasPermission('manage_company_information'))
                                <li class="nk-menu-item"><a href="{{ route('merchant.staff.company') }}" class="nk-menu-link">
                                    <span>{{__('company_information')}}</span></a>
                                </li>
                                @endif
                                <li class="nk-menu-item"><a href="{{ route('merchant.staff.security-settings') }}" class="nk-menu-link">
                                    <span>{{__('password_and_security')}}</span></a>
                                </li>
                                @if(hasPermission('manage_payment_accounts'))
                                <li class="nk-menu-item"><a href="{{ route('merchant.staff.payment.accounts') }}" class="nk-menu-link">
                                    <span>{{__('availabe_payment_accounts')}}</span></a>
                                </li>
                                @endif

                                @if(hasPermission('delivery_charge'))
                                <li class="nk-menu-item"><a href="{{ route('merchant.staff.charge') }}" class="nk-menu-link">
                                    <span>{{__('delivery_charge')}}</span></a>
                                </li>
                                @endif

                                @if(hasPermission('manage_shops'))
                                <li class="nk-menu-item"><a href="{{ route('merchant.staff.shops') }}" class="nk-menu-link">
                                    <span>{{__('my_shops')}}</span></a>
                                </li>
                                @endif

                            </ul><!-- .nk-menu-sub -->
                        </li><!-- .nk-menu-item -->
                    @else
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-user-alt"></em></span>
                                <span class="nk-menu-text">{{__('profile')}}</span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item"><a href="{{route('staff.profile')}}" class="nk-menu-link">
                                        <span>{{__('personal_information')}}</span></a>
                                </li>
                                @if(!blank(Sentinel::getUser()->accounts(Sentinel::getUser()->id)))
                                    <li class="nk-menu-item"><a href="{{route('user.accounts')}}" class="nk-menu-link">
                                            <span>{{__('accounts')}}</span></a>
                                    </li>
                                @endif
                                <li class="nk-menu-item"><a href="{{route('staff.account-activity')}}" class="nk-menu-link">
                                        <span>{{__('login_activity')}}</span></a>
                                </li>
                                <li class="nk-menu-item"><a href="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.security-settings') : route('staff.security-settings')}}" class="nk-menu-link">
                                        <span>{{__('security_settings')}}</span></a>
                                </li>
                            </ul><!-- .nk-menu-sub -->
                        </li><!-- .nk-menu-item -->
                    @endif
                    @if(hasPermission('hub_read'))
                        <li class="nk-menu-item">
                            <a href="{{ route('admin.hub') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-network"></em></span>
                                <span class="nk-menu-text">{{__('hubs')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                    @endif
                    @if(hasPermission('third_party_read'))
                        <li class="nk-menu-item">
                            <a href="{{ route('admin.third-parties') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-link-group"></em></span>
                                <span class="nk-menu-text">{{__('third_parties')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                    @endif
                   @if(Sentinel::getUser()->id == 1)
                        @if(hasPermission('sms_setting_read') || hasPermission('sms_campaign_message_send') || hasPermission('custom_sms_send'))
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-msg"></em></span>
                                    <span class="nk-menu-text">{{__('sms')}}</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if(hasPermission('sms_setting_read'))
                                        <li class="nk-menu-item"><a href="{{ route('sms.preference.setting')}}" class="nk-menu-link">
                                                <span>{{__('sms_preference')}}</span></a>
                                        </li>
                                    @endif
                                    @if(hasPermission('sms_campaign_message_send'))
                                        <li class="nk-menu-item"><a href="{{ route('sms.campaign')}}" class="nk-menu-link">
                                                <span>{{__('send_bulk_sms')}}</span></a>
                                        </li>
                                    @endif
                                    @if(hasPermission('custom_sms_send'))
                                        <li class="nk-menu-item"><a href="{{ route('custom.sms.campaign')}}" class="nk-menu-link">
                                                <span>{{__('send_sms')}}</span></a>
                                        </li>
                                    @endif
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                        @endif

                        @if(hasPermission('settings_read'))
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-setting"></em></span>
                                    <span class="nk-menu-text">{{__('settings')}}</span>
                                </a>
                                <ul class="nk-menu-sub">
    {{--                                @if(hasPermission('pagination'))--}}
                                        <li class="nk-menu-item"><a href="{{ route('sms.setting')}}" class="nk-menu-link">
                                                <span>{{__('sms')}}</span></a>
                                        </li>
                                        <li class="nk-menu-item"><a href="{{ route('preference.setting')}}" class="nk-menu-link">
                                                <span>{{__('preference')}}</span></a>
                                        </li>
    {{--                                    <li class="nk-menu-item"><a href="{{ route('pagination.setting')}}" class="nk-menu-link">--}}
    {{--                                            <span>{{__('pagination')}}</span></a>--}}
    {{--                                    </li>--}}
                                        <li class="nk-menu-item"><a href="{{ route('charges.setting')}}" class="nk-menu-link">
                                                <span>{{__('charges')}}</span></a>
                                        </li>
                                        <li class="nk-menu-item"><a href="{{ route('packaging.charge.setting')}}" class="nk-menu-link">
                                                <span>{{__('packaging_type_and_charges')}}</span></a>
                                        </li>
                                        <li class="nk-menu-item"><a href="{{ route('time-and-days.setting')}}" class="nk-menu-link">
                                                <span>{{__('pickup_time_delivery_days')}}</span></a>
                                        </li>
    {{--                                    <li class="nk-menu-item"><a href="{{ route('mobile.app.setting')}}" class="nk-menu-link">--}}
    {{--                                            <span>{{__('mobile_app')}}</span></a>--}}
    {{--                                    </li>--}}
    {{--                                    <li class="nk-menu-item"><a href="{{ route('database.backup.storage.setting')}}" class="nk-menu-link">--}}
    {{--                                            <span>{{__('database_backup_storage')}}</span></a>--}}
    {{--                                    </li>--}}
    {{--                                @endif--}}

                                        <li class="nk-menu-item"><a href="{{ route('sip_domain.setting')}}" class="nk-menu-link">
                                            <span>{{__('sip_domain')}}</span></a>
                                        </li>
                                        <li class="nk-menu-item"><a href="{{ route('app_info.setting')}}" class="nk-menu-link">
                                            <span>{{__('app_info')}}</span></a>
                                        </li>
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                        @endif
                    @endif
                    @if(hasPermission('notice_read'))
                        <li class="nk-menu-item">
                            <a href="{{route('notice')}}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-notice"></em></span>
                                <span class="nk-menu-text">{{__('notice')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                    @endif
                    @if(Sentinel::getUser()->user_type == 'merchant' || Sentinel::getUser()->user_type == 'merchant_staff')
                        <li class="nk-menu-item">
                            <a href="https://www.facebook.com/groups/1713888382436023/" class="nk-menu-link" target="_blank">
                                <span class="nk-menu-icon"><em class="icon ni ni-facebook-f"></em></span>
                                <span class="nk-menu-text">{{__('fb_community')}}</span>
                            </a>
                        </li><!-- .nk-menu-item -->
                    @endif
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>



{{--
images, users, merchant
--}}
