<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-4">
                <label for="category_name" class="block font-medium text-gray-700 mb-1">Nama Jenis</label>
                <input type="text" name="category_name" id="category_name"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Simpan</button>
        </form>
    </div>
</x-app-layout>