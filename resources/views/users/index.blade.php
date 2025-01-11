<x-app-layout>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    {{-- <div class="max-w-7xl mx-auto bg-white p-6 mt-5 rounded-lg shadow-lg overflow-x-auto"> --}}
        <div class="max-w-7xl mx-auto bg-white p-6 mt-5 rounded-lg shadow-lg">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">Daftar Pengguna</h3>
                <a href="{{ route('users.create') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs">Tambah
                    Pengguna</a>
            </div>


            <div class="overflow-x-auto">
                <table class="min-w-full bg-white text-xs">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">No</th>
                            <th class="py-2 px-4 border-b">Nama</th>
                            <th class="py-2 px-4 border-b">Email</th>
                            <th class="py-2 px-4 border-b">Role</th>
                            <th class="py-2 px-4 border-b">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="bg-gray-50">
                            <td style="text-align: center" class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                            <td class="py-2 px-4 border-b">{{ $user->name }}</td>
                            <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                            <td style="text-align: center" class="py-2 px-4 border-b">{{ ucfirst($user->role) }}</td>
                            <td style="text-align: center" class="py-2 px-4 border-b">
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="bg-yellow-500 text-white px-2 py-1 rounded-lg mr-2">Edit</a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin ingin menghapus user ini?')"
                                        class="bg-red-500 text-white px-2 py-1 rounded-lg">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="py-2 px-4 text-center text-xs"> Tidak ada produk ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>


</x-app-layout>