<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'App' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-50 text-gray-900 flex flex-col min-h-screen">
    <header class="bg-blue-600 text-white p-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="{{ asset('resources/image/icon-rounded.png') }}" alt="Icon" class="h-10 w-10 mr-2">
            <span class="text-xl font-bold">Dashboard</span>
        </div>
        <nav>
            @if (Auth::check())
                <a href="{{ route('dashboard') }}" class="text-white hover:text-gray-200">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="text-white hover:text-gray-200">Logout</button>
            @else
                <a href="{{ route('login') }}" class="text-white hover:text-gray-200">Login</a>
            @endif
        </nav>
    </header>

    <main class="flex-grow p-6">
        {{ $slot }}
    </main>

    <footer class="bg-blue-600 text-white text-center p-4">
        &copy; {{ date('Y') }} RadjaShiqnals Canteen. All rights reserved.
    </footer>

    @stack('scripts')
</body>

</html>