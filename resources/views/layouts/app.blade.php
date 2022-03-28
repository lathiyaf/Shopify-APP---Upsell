<html>
<head class="royalcart-app">
    <title>Activate Cart Recovery</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- preload -->
    <link rel="preload" href="{{ asset('css/app.css') }}" as="style">
    <link rel="preload" href="{{ asset('js/jquery-3.3.1.slim.min.js') }}" as="script">
    <link rel="preload" href="{{ asset('js/popper.min.js') }}" as="script">
    <link rel="preload" href="{{ asset('js/bootstrap.js') }}" as="script">
    <link rel="preload" href="{{ asset('js/jquery.flagstrap.js') }}" as="script">

    <!-- css -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- js -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery-3.3.1.slim.min.js') }}" defer="defer"></script>
    <script src="{{ asset('js/popper.min.js') }}" defer="defer"></script>
    <script src="{{ asset('js/bootstrap.js') }}" defer="defer"></script>
    <script src="{{ asset('js/jquery.flagstrap.js') }}" defer="defer"></script>

    @if(config('shopify-app.appbridge_enabled'))
        <script src="https://unpkg.com/@shopify/app-bridge@1.28.0"></script>
        <script>
            var AppBridge = window['app-bridge'];

            var createApp = AppBridge.default;
            window.shopify_app_bridge = createApp({
                apiKey: '{{ config('shopify-app.api_key') }}',
                shopOrigin: '{{ Auth::user()->name }}',
                forceRedirect: true,
            });
        </script>
    @endif
    <script>
        window.apiHost = '{{ env('APP_URL') }}'
    </script>
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
    <script>
        window.OneSignal = window.OneSignal || [];
        OneSignal.push(function() {
            OneSignal.init({
                appId: "938de65d-d4d6-4757-83ff-ccddb0061b99",
            });
        });
    </script>
</head>
<body class="">
<div id="app">
    <router-view></router-view>
</div>
</body>
</html>

