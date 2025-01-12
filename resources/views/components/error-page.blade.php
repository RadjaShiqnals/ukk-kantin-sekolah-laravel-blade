<x-layout title="{{ $title ?? 'Error' }}">
    <div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
            <div class="text-6xl font-bold text-red-500 mb-4">
                {{ $code ?? '403' }}
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                {{ $heading ?? 'Access Denied' }}
            </h1>
            <p class="text-gray-600 mb-6">
                {{ $message ?? 'You do not have permission to access this page.' }}
            </p>
            <a href="{{ route('dashboard') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition-colors">
                Back to Dashboard
            </a>
        </div>
    </div>
</x-layout> 