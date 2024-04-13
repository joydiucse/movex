<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    @include('partials.header-assets')
</head>

<body class="nk-body bg-white npc-default pg-auth {{defaultModeCheck()}}">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- wrap @s -->
            <div class="nk-wrap nk-wrap-nosidebar">
                <!-- content @s -->
                <div class="nk-content ">
                    <div class="nk-block nk-block-middle nk-auth-body wide-xs">
                        <div class="">
                            <div class="absolute-top-right d-lg-none p-3 p-sm-5">
                                <a href="#" class="toggle btn-white btn btn-icon btn-light" data-target="athPromo"><em class="icon ni ni-info"></em></a>
                            </div>
                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="brand-logo pb-5 text-center">
                                    <a href="{{ url('/') }}" class="logo-link">
{{--                                        <img class="logo-light logo-img logo-img-lg" src="{{ asset('admin/')}}/images/logo.png" srcset="{{ asset('admin/')}}/images/logo2x.png 2x" alt="logo">--}}
                                        <img class="logo-dark logo-img logo-img-lg" src="{{ asset('admin/')}}/images/logo-green.png" srcset="{{ asset('admin/')}}/images/logo-green.png 2x" alt="logo-dark">
                                    </a>
                                </div>

                                @yield('mainContent')

                            </div><!-- .nk-block -->
                            <div class="nk-block nk-auth-footer">
                                <div class="nk-block-between">
                                    <ul class="nav nav-sm">
                                        <li class="nav-item">
                                            <a class="nav-link" href="https://movexcourier.com/terms-condition">Terms & Condition</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="https://movexcourier.com/privacy-policy">Privacy Policy</a>
                                        </li>
                                    </ul><!-- .nav -->
                                </div>
                                <div class="mt-3">
                                    <p>{{__('copy_right_text')}}</p>
                                </div>
                            </div><!-- .nk-block -->
                        </div><!-- .nk-split-content -->
                    </div><!-- .nk-split -->
                </div>
                <!-- content @e -->
            </div>
            <!-- main @e -->
        </div>
        <!-- app-root @e -->
        <!-- JavaScript -->
        @include('partials.footer-assets')

    </html>
