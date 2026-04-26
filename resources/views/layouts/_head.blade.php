<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

@hasSection('title')
    <title>@yield('title')</title>
@else
    @hasSection('header')
        <title>@yield('header')@hasSection('subheader'){{ ' | ' }}@yield('subheader')@endif</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif
@endif

<link rel="stylesheet" href="{{ url('dist/bootstrap/css/bootstrap.rtl.min.css') }}">
<link rel="stylesheet" href="{{ url('dist/vazir-font/font-face.css') }}">
<link rel="stylesheet" href="{{ url('dist/bootstrap-icons/bootstrap-icons.min.css') }}">
<link rel="stylesheet" href="{{ url('dist/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ url('style.css') }}?ver=4">

<script defer src="{{ url('dist/alpinejs/cdn.min.js') }}"></script>
<script type="text/javascript" src="{{ url('dist/bootstrap/js/bootstrap.bundle.js') }}"></script>
<script type="text/javascript" src="{{ url('dist/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script type="text/javascript" src="{{ url('dist/canvas-confetti/confetti.browser.js') }}"></script>
<script>
    !function(e,t,n){e.yektanetAnalyticsObject=n,e[n]=e[n]||function(){e[n].q.push(arguments)},e[n].q=e[n].q||[];var a=t.getElementsByTagName("head")[0],r=new Date,c="https://cdn.yektanet.com/superscript/C8RxV4PZ/native-board.akrez.ir-46054/yn_pub.js?v="+r.getFullYear().toString()+"0"+r.getMonth()+"0"+r.getDate()+"0"+r.getHours(),s=t.createElement("link");s.rel="preload",s.as="script",s.href=c,a.appendChild(s);var l=t.createElement("script");l.async=!0,l.src=c,a.appendChild(l)}(window,document,"yektanet");
</script>