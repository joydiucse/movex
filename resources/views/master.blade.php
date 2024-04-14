<!DOCTYPE html>
<html lang="zxx" class="js {{defaultModeCheck()=='dark-mode' ? 'dark' :'' }}">

<head>
    @include('partials.header-assets')
</head>

<body class="nk-body bg-lighter npc-default has-sidebar {{defaultModeCheck()}}  ">
    <div class="nk-app-root">
        <!-- main @s -->
        <div class="nk-main ">
            <!-- sidebar @s -->
            @include('partials.sidebar')
            <!-- sidebar @e -->
            <!-- wrap @s -->
            <div class="nk-wrap ">
                <!-- main header @s -->
                @include('partials.header')
                <!-- main header @e -->
                <!-- content @s -->
                @yield('mainContent')

                <!-- content @e -->
                <!-- footer @s -->
                @include('partials.footer')
                <!-- footer @e -->
            </div>
            <!-- wrap @e -->
        </div>
        <!-- main @e -->
    </div>
    <!-- app-root @e -->
    <!-- JavaScript -->
    @include('partials.message')
    @include('partials.footer-assets')
    @include('partials.modals')

    <input type="hidden" value="{{url('/')}}" id="url">



</body>

</html>
