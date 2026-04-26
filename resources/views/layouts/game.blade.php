@spaceless
    <!doctype html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('layouts._head')
        @yield('POS_HEAD')
    </head>

    <body class="d-flex min-vh-100" dir="rtl">
        @yield('content')
    </body>

    </html>
@endspaceless
