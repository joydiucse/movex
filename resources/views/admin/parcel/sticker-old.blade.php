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
    <style>
        @page {
            size: 90mm 60mm;
            margin: 0 !important;
        }
        body{
            font-family: F25 Bank Printer Bold !important;
            background-color: #ffffff00 !important;
            color: #000000 !important;
            margin: 0 !important;
        }
    </style>
</head>

<body class="nk-body npc-default has-sidebar">
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main">
        <div class="nk-content">
            <div class="nk-content-inner">
                <div class="nk-block">
                    <div class="row">
                        <div class="sticker-print">
                            <div class="sticker-top  d-flex">
                                <div class="image ml-1 p-1">
                                    <img src="{{ asset('admin/images/logo.svg') }}" alt="{{ __('app_name') }}" class="img-fluid">
                                </div>
                                <div class="merchant-details ml-2 pt-0">
                                    @if($parcel->location == 'outside_dhaka')
                                        <span class="customer ml-2 pt-0">
                                            <span class="merchant font-weight-bold">{{ __('merchant') }}:</span> <span  class="name">{{ __('app_name') }}</span>
                                        </span>
                                        <span class="address ml-2">{{ __('app_address') }}</span>
                                        <span class="phone ml-2 pb-0">+88 01738-494512</span>
                                    @else
                                        <span class="customer ml-2 pt-0">
                                            <span class="merchant font-weight-bold">{{ __('merchant') }}:</span> <span  class="name">{{ $parcel->merchant->company }}</span>
                                        </span>
                                        <span class="address ml-2">{{ $parcel->merchant->address }}</span>
                                        <span class="phone ml-2 pb-0">{{ $parcel->merchant->phone_number }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="customer-details d-flex ml-1">
                                <div class="customer ml-1">
                                    <span class="customer-detail">
                                        <span class="c font-weight-bold">{{ __('customer') }}:</span> <span  class="name">{{ $parcel->customer_name }}</span>
                                    </span>
                                    <span class="phone font-weight-bold">{{ $parcel->customer_phone_number }}</span>
                                    <span class="address">{{ $parcel->customer_address }}</span>
                                </div>
                                <div class="area-details ">
                                    <span class="area ml-1 font-weight-bold">{{ __('area') }}: {{ __($parcel->location) }}</span>
                                    <span class="hub ml-1 font-weight-bold">{{ __('hub') }}: </span>
                                    <span class="zone ml-1 font-weight-bold">{{ __('zone') }}: </span>
                                </div>

                            </div>
                            <div class="invoice-details d-flex justify-content-between ml-1">
                                <span class="invoice ml-1">{{ __('invoice') }}: {{ $parcel->customer_invoice_no }}</span>
                                <span class="price">{{ __('cash') }}: {{ number_format($parcel->price,2) . __('tk') }}</span>
                            </div>
                            <div class="codes d-flex">
                                <div class="qr-code">
                                    <img  class="image m-3" src="data:image/svg;base64,{{ DNS2D::getBarcodePNG($parcel->parcel_no , 'QRCODE') }}" alt="qrcode"   />
                                </div>
                                <div class="bar-code center">
                                    <img  class="image img-fluid ml-0 my-3" src="data:image/svg;base64,{{ DNS1D::getBarcodePNG($parcel->parcel_no , 'C93',1,33) }}" alt="barcode"   />
                                    <span class="text">{{$parcel->parcel_no}}</span>
                                </div>
                            </div>
                        </div>
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
