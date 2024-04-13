@extends('master')

@section('title')
    {{__('cash_on_delivery_charge')}}
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
                                                <h4 class="nk-block-title">{{__('cash_on_delivery_charge')}}</h4>
                                                <div class="nk-block-des">
                                                </div>
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
                                                <th class="tb-col-os"><span class="overline-title">{{__('location')}}</th>
                                                <th class="tb-col-os"><span class="overline-title">{{__('charge')}}(%)</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $cod_charges = Sentinel::getUser()->user_type == 'merchant' ? Sentinel::getUser()->merchant->cod_charges : Sentinel::getUser()->staffMerchant->cod_charges
                                            @endphp
                                            @foreach($cod_charges as $key => $cod_charge)
                                                <tr class="charge">
                                                    <td class="tb-col-os">{{__($key)}}</td>
                                                    <td class="tb-col-os">{{ $cod_charge }}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                @include('merchant.profile.profile-sidebar')

                            </div><!-- .card-aside-wrap -->
                        </div><!-- .card -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>
@endsection
