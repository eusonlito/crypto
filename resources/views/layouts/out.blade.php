<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @include ('layouts.molecules.head')
    </head>

    <body class="login body-{{ str_replace('.', '-', $ROUTE) }}">
        @yield ('body')

        @include ('layouts.molecules.footer')
    </body>
</html>
