<x-layout title="Siswa Dashboard">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Siswa Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Logout</button>
        </form>
    </div>
</x-layout>