<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label for="email" class="block font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>
            <div class="mb-4">
                <label for="password" class="block font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2"
                    placeholder="Biarkan kosong jika tidak ingin mengubah">
            </div>
            <div class="mb-4">
                <label for="role" class="block font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Simpan</button>
        </form>
    </div>
</x-app-layout>