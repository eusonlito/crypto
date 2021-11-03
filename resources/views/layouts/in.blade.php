<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include ('layouts.molecules.head')
    </head>

    <body class="main body-{{ str_replace('.', '-', $ROUTE) }}">
        @include ('layouts.molecules.in-sidebar-mobile')

        <div class="wrapper">
            <div class="wrapper-box">
                @include ('layouts.molecules.in-sidebar')

                <div class="content pt-3 pb-10 sm:px-10">
                    @include ('layouts.molecules.in-top-bar')

                    <x-message type="error" />
                    <x-message type="success" />

                    @yield ('body')
                </div>
            </div>
        </div>

        @include ('layouts.molecules.footer')
    </body>
</html>
