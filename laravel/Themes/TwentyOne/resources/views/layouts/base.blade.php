<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <x-cms::head />
    <body>
        @yield('body')

        <x-cms::foot />
    </body>
</html>
