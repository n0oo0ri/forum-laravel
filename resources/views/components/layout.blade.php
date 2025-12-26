<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Forum' }}</title>
    @livewireStyles
</head>
<body>
    <header>
        <h2>Forum</h2>
        <hr>
    </header>

    {{ $slot }}

    @livewireScripts
</body>
</html>
