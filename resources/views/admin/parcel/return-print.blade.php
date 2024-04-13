<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <!-- Fav Icon  -->
    <link rel="shortcut icon" href="{{ asset('admin/') }}/images/favicon.png">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('admin/') }}/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('admin/') }}/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('admin/') }}/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('admin/') }}/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('admin/') }}/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('admin/') }}/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('admin/') }}/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('admin/') }}/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/') }}/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ asset('admin/') }}/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/') }}/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('admin/') }}/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/') }}/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('admin/') }}/images/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('admin/') }}/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Page Title  -->
    <!-- StyleSheets  -->
    <link rel="stylesheet" href="{{ asset('admin/') }}/css/dashlite.css?ver=2.3.0">
    <link id="skin-default" rel="stylesheet" href="{{ asset('admin/') }}/css/custom.css">

    <title>{{ __('parcel') . ' ' . __('details') . ' ' . __('print') }} | {{ __('app_name') }}</title>

    <style type="text/css">

        .nk-body{
            background-color: white !important;
        }

        .print .print-table table td {
                font-size: 13px;
        }
        .table th, .table td {
            border-color: black;
        }
        .table thead tr:last-child th{
            border-bottom: 1px solid black;
        }
        .custom-width{
            width: 15%;
        }
        @media print{
            .table th, .table td {
                border-color: black;
            }
            .custom-width{
                width: unset;
            }


            .row{
                display: block;
            }
            .page-break {
                page-break-after: always;
            }
        }

    </style>
</head>

<body class="nk-body  npc-default has-sidebar bg-white">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main print">
            <div class="nk-content">
                <div class="nk-content-inner">
                    <div class="nk-block">
                        <div class="row g-gs">


                                    <div class="col-xxl-12 col-sm-12 col-md-12">
                                        <div class="card m-2">
                                            <div class="">
                                                <div class="border">
                                                    <table class="table">
                                                        <tr>
                                                            <td><img class="img-responsive mt-3"
                                                                    src="{{ asset('admin/images/logo-green.png') }}"
                                                                    alt="" width="50%"></td>
                                                            <td>

                                                                <div class="text-left">
                                                                    <span
                                                                        class="text-dark font-weight-bold d-inline-flex w-15">{{ __('shipped_from') }}
                                                                        </span> <span class="text-dark font-weight-bold">: {{ __('app_name') }}</span> </br>
                                                                    <span
                                                                        class="text-dark font-weight-bold d-inline-flex w-15">{{ __('address') }}
                                                                        </span>  <span class="text-dark font-weight-bold">: {{ __('address_1st') }},
                                                                            {{ __('address_2nd') }}</span> </br>
                                                                    <span
                                                                        class="text-dark font-weight-bold d-inline-flex w-15">{{ __('phone') }}
                                                                        </span> <span class="text-dark font-weight-bold">: {{ __('app_phone_number') }}</span>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="text-right">
                                                                    @if(isset($parcel->merchant->user->image->image_small_two) && $parcel->merchant->user->image->image_small_two !=null)
                                                                    <img class="img-responsive mt-3"
                                                                        src="{{ asset($parcel->merchant->user->image->image_small_two) }}"
                                                                        alt="{{ $parcel->merchant->user->first_name }}" width="50%">
                                                                    @else
                                                                    <img class="img-responsive mt-3"
                                                                    src=""
                                                                    alt="{{ $parcel->merchant->user->first_name }}" width="50%">
                                                                   @endif

                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="border">
                                                    <div class="d-inline-flex print-table">
                                                        <table class="merchant-info">
                                                            <tr>
                                                                <td class="font-weight-bold">{{ __('merchant') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>{{ __('name') }}
                                                                    :
                                                                   {{$parcel->merchant->company}}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>{{ __('phone_number') }}
                                                                    :
                                                                    {{ $parcel->merchant->phone_number  }}
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td>{{ __('merchant_address') }}
                                                                    :
                                                                    {{ $parcel->merchant->address}}
                                                                </td>
                                                            </tr>


                                                        </table>
                                                        <span class="separator"></span>


                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <td class="custom-width"><span class="font-weight-bold">{{__('total')}} {{__('parcel') }}</span> </td>
                                                                    <td>: <span class="font-weight-bold pd-3">1</span></td>
                                                                    <td> </td>


                                                                </tr>
                                                                <tr>
                                                                    <td class="custom-width"><span class="font-weight-bold">{{__('total_cod')}}</span> </td>
                                                                    <td>: <span class="font-weight-bold pd-3">{{($parcel->cod_charge) ? $parcel->cod_charge : 0}} /=</span></td>
                                                                    <td> </td>


                                                                </tr>

                                                            </thead>
                                                                <thead class="table-dark">
                                                                    <tr style="border-top: 1px solid black;">
                                                                                <th scope="col">#</th>
                                                                                <th scope="col">{{__('parcel_no')}} ( {{ __('app_name') }} ) </th>
                                                                                <th scope="col">{{__('invoice_no')}} ({{__('merchant')}} )</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>


                                                                    <tr>
                                                                    <td>1</td>
                                                                    <td> {{$parcel->parcel_no}} </td>
                                                                    <td> {{$parcel->customer_invoice_no}} </td>
                                                                    </tr>


                                                                </tbody>

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
