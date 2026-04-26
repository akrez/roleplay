@spaceless
    <!doctype html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('layouts._head')
    </head>

    <body dir="rtl">
        @include('layouts._navigation')
        <div class="container my-3">
            @hasSection('header')
                <h1 class="fs-2 my-4">
                    @yield('header')
                    @hasSection('subheader')
                        <small class="text-muted">@yield('subheader')</small>
                    @endif
                </h1>
            @endif
            @yield('content')
        </div>
    </body>
    </html>
@endspaceless
