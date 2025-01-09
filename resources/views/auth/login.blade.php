<x-layout title="Login">
    <div class="flex items-center justify-center h-full" style="height: 70vh;">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold mb-4 text-center">Login</h1>
            @if ($errors->has('loginError'))
                <div class="text-red-500 mb-4">
                    {{ $errors->first('loginError') }}
                </div>
            @endif
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="mb-4">
                    <label for="username" class="block text-gray-700">Username:</label>
                    <input type="text" id="username" name="username" required class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Login</button>
            </form>
        </div>
    </div>
</x-layout>