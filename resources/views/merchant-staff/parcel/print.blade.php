<!DOCTYPE html>
<html lang="zxx" class="js">
@php $assetVersion=getAssetVersion(); @endphp
<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('admin/')}}/images/favicon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('admin/')}}/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('admin/')}}/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('admin/')}}/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin/')}}/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('admin/')}}/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('admin/')}}/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('admin/')}}/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('admin/')}}/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/')}}/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('admin/')}}/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/')}}/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('admin/')}}/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/')}}/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('admin/')}}/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('admin/')}}/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Page Title  -->
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('admin/')}}/css/dashlite.css?ver=2.3.0">
    <link id="skin-default" rel="stylesheet" href="{{ asset("assets/{$assetVersion}")}}/css/style.css">

    <title>{{__('parcel').' '.__('details').' '.__('print')}} | {{__('app_name')}}</title>
</head>

<body class="nk-body print-bg-lighter npc-default has-sidebar">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main print">
        <div class="nk-content">
                <div class="nk-content-inner">
                        <div class="nk-block">
                            <div class="row g-gs">
                                <div class="col-xxl-12 col-sm-12 col-md-12">
                                    <div class="card">
                                        <div class="justify-content-between d-inline-flex page-header">
                                            <h3 class="nk-block-title page-title">{{__('app_name')}}</h3></br>
                                            <p class="date">{{ __('date').' '.date('d.m.Y') }}</p>
                                        </div>
                                        <table>
                                            <tr><td class="font-weight-bold">{{ __('phone') }}:</td><td>{{ __('app_phone_number') }}</td></tr>
                                            <tr><td class="font-weight-bold">{{ __('address') }}:</td><td>{{ __('address_1st') }}</br>{{ __('address_2nd') }}</td></tr>
                                        </table>


                                        <div class="mt-5">
                                            <div class="cod-invoice d-flex">
                                                <div class="font-weight-bold p-2">{{ __('invno') }}: #{{$parcel->customer_invoice_no}}</div>
                                                <div class="font-weight-bold p-2">{{ __('COD') }}: {{number_format($parcel->price,2) .' '. __('tk')}}</div>
                                            </div>
                                            <div class="border">
                                                <div class="d-inline-flex print-table">
                                                    <table>
                                                        <tr><td class="font-weight-bold">{{ __('merchant') }}</td></tr>
                                                        <tr><td>{{ __('name') }}
                                                            : {{ $parcel->merchant->user->first_name.' '.$parcel->merchant->user->last_name }}</td></tr>
                                                        <tr><td>{{ __('company_name') }}
                                                                : {{ $parcel->merchant->company }}</td></tr>
                                                        <tr><td>{{ __('phone_number') }}
                                                                : {{ $parcel->merchant->phone_number }}</td></tr>
                                                        <tr>
                                                            <td>{{ __('email') }}
                                                                : {{ $parcel->merchant->user->email }}</td>
                                                        </tr>
                                                    </table>
                                                    <span class="separator"></span>
                                                    <table>
                                                        <tr><td class="font-weight-bold">{{ __('customer') }}</td></tr>
                                                        <tr><td>{{ __('name') }}
                                                            : {{ $parcel->customer_name }}</td></tr>
                                                        <tr><td>{{ __('customer_phone') }}
                                                                : {{ $parcel->customer_phone_numberr }}</td></tr>
                                                        <tr>
                                                            <td>{{ __('customer_address') }}
                                                                : {{ $parcel->customer_address }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('id')}}: #{{ $parcel->parcel_no }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{__('weight').': '. $parcel->weight.' '.__('kg')}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>{{ __('details') }}: {{ @$parcel->note }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- .card -->
                                </div><!-- .col -->
                            </div>
                        </div><!-- .nk-block -->
                    </div>
                </div>
        </div>

    </div>
    <!-- main @e -->
</div>
<!-- app-root @e -->
<!-- JavaScript -->
@include('partials.footer-assets')
<script type="text/javascript">
    $(document).ready(function () {
        window.print();
    });
</script>

</body>

</html>
