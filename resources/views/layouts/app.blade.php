<html>
@include('feature-toggle::partials.head')

<body class="bg-gray-100">
@include('feature-toggle::partials.navbar')

<div class="h-screen flex flex-row flex-wrap">
    <div class="bg-gray-100 flex-1 p-6 md:mt-16">
    @yield('content')
    </div>
</div>

@include('feature-toggle::partials.scripts')
</body>
</html>
