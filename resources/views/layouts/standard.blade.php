<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $pageTitle or 'Build Accurate Software Estimates - Estimatr' }}</title>
    <meta name="description" content="Estimatr helps you build accurate software estimates, saving you all the headaches, stress and monetary loss that comes with bad estimates.">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-60655221-5', 'auto');
        ga('send', 'pageview');

    </script>

</head>
<body class="{{ $bodyClass or '' }}">

<!--[if lt IE 10]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<header>
    <a href="{{ route('home') }}"><img src="{{ asset('img/logo.svg') }}" /></a>
    <nav>
        @if($user)
            <a class="floater" href="{{ route('estimates') }}">Estimates</a>
            <a class="floater" href="{{ route('logout') }}">Logout</a>
            <a class="settings floater" href="{{ route('account') }}">
                <span>Account</span>
                <img src="{{ $user->avatar }}" />
            </a>
        @else
            <a class="cta" href="{{ route('login') }}"><i class="fa fa-github"></i> Login With Github</a>
        @endif
    </nav>
</header>

@yield('page')

<footer>
    <section class="top">
        <div>
            <div class="logo">
                <img src="{{ asset('img/logo.svg') }}" />
            </div>
            <div class="social">
                <a href="https://twitter.com/estimatrapp">
                    <i class="fa fa-twitter"></i> Twitter
                </a>
                <a href="mailto:info@estimatrapp.com">
                    <i class="fa fa-envelope"></i> Support
                </a>
            </div>
        </div>
    </section>
    <section class="bottom">
        <div>
            <div class="copyright">
                Copyright {{ date('Y') }} Carve Digital LTD
            </div>
            <div class="carve-logo">
                <a href="https://www.carve.io"><img src="{{ asset('img/carve-mark.svg') }}" /></a>
            </div>
        </div>
    </section>
</footer>

@section('javascript')
    <script src="{{ asset('js/vue.development.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
@show
</body>
</html>
