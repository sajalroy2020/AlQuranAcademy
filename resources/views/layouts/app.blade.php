<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.header')

<body class="g-sidenav-show  bg-gray-100">
    <!-- Main Content -->
    @include('layouts.sidebar')

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Header -->
        @include('layouts.nav')
    </main>

    <!-- footer  -->
    @include('layouts.footer')

    <!-- Content -->
     @yield('content')

    @include('layouts.script')
</body>
</html>
