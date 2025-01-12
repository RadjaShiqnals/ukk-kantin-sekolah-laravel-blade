<x-layout title="Login">
    <div class="flex items-center justify-center h-full" style="height: 70vh;">
        <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold mb-4 text-center">Login</h1>
            
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email:</label>
                    <input type="email" id="email" name="email" 
                        class="w-full px-3 py-2 border rounded @error('email') border-red-500 @enderror" 
                        value="{{ old('email') }}" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password:</label>
                    <input type="password" id="password" name="password" 
                        class="w-full px-3 py-2 border rounded @error('password') border-red-500 @enderror" 
                        required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Login
                </button>
            </form>
        </div>
    </div>
</x-layout>