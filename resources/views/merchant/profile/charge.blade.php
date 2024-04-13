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

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-inner card-inner-lg">
                                            <div class="nk-block-head nk-block-head-lg">
                                                <div class="nk-block-between">
                                                    <div class="nk-block-head-content">
                                                        <h4 class="nk-block-title">{{__('delivery_charge')}}</h4>
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
                                                    <tr class="delivery-charge">
                                                        <th class="tb-col-os"><span class="overline-title">{{__('weight')}}({{__('kg')}})</span></th>
                                                        <th class="tb-col-os"><span class="overline-title">{{__('same_day')}}({{__('tk')}})</span></th>
                                                        <th class="tb-col-os"><span class="overline-title">{{__('next_day')}}({{__('tk')}})</span></th>
                                                        {{-- <th class="tb-col-os"><span class="overline-title">{{__('frozen')}}</span></th> --}}
                                                        <th class="tb-col-os"><span class="overline-title">{{__('sub_city')}}({{__('tk')}})</span></th>
                                                        <th class="tb-col-os"><span class="overline-title">{{__('outside_dhaka')}}({{__('tk')}})</span></th>
                                                        {{-- <th class="tb-col-os"><span class="overline-title">{{__('third_party_booking')}}</span></th> --}}
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $charges = Sentinel::getUser()->user_type == 'merchant' ? Sentinel::getUser()->merchant->charges : Sentinel::getUser()->staffMerchant->charges
                                                    @endphp
                                                    @foreach($charges as $weight => $charge)
                                                        <tr class="delivery-charge">
                                                            <td class="tb-col-os"><span class="text-capitalize">{{$weight}}</span></td>
                                                            <td class="tb-col-os"><span>{{ data_get($charge, 'same_day', 0.00) }} </span></td>
                                                            <td class="tb-col-os"><span>{{ data_get($charge, 'next_day', 0.00) }} </span></td>
                                                            {{-- <td class="tb-col-os"><span>{{ data_get($charge, 'frozen', 0.00) }} </span></td> --}}
                                                            <td class="tb-col-os"><span>{{ data_get($charge, 'sub_city', 0.00) }} </span></td>
                                                            <td class="tb-col-os"><span>{{ data_get($charge, 'outside_dhaka', 0.00) }} </span></td>
                                                            {{-- <td class="tb-col-os"><span>{{ data_get($charge, 'third_party_booking', 0.00) }} </span></td> --}}
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div> <!-- delevery charge -->
                                    <div class="col-12">
                                        <div class="card-inner card-inner-lg" style="padding-top: 0">
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
