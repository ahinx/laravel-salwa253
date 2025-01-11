<x-app-layout>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <div class="max-w-7xl mx-auto bg-white p-6 mt-5 rounded-lg shadow-lg overflow-x-auto">

        <div class="flex justify-between items-center mb-4">
            <form action="{{ route('products.filter') }}" method="GET"
                class="flex flex-wrap items-center space-x-2 w-full">
                {{-- Tanggal Mulai --}}
                <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}"
                    class="border border-gray-300 rounded-lg px-2 py-1 text-xs datepicker text-gray-700"
                    placeholder="DD/MM/YYYY">

                {{-- Tanggal Akhir --}}
                <input type="text" name="end_date" id="end_date" value="{{ request('end_date') }}"
                    class="border border-gray-300 rounded-lg px-2 py-1 text-xs datepicker text-gray-700"
                    placeholder="DD/MM/YYYY">

                {{-- Nama Produk --}}
                <input type="text" name="product_name" id="product_name" value="{{ request('product_name') }}"
                    class="border border-gray-300 rounded-lg px-2 py-1 text-xs w-32" placeholder="Nama Produk">

                {{-- Koperasi --}}
                <select name="coop_id" id="coop_id" class="border border-gray-300 rounded-lg px-2 py-1 text-xs w-32">
                    <option value="">All Coops</option>
                    @foreach($coops as $coop)
                    <option value="{{ $coop->id }}" {{ request('coop_id')==$coop->id ? 'selected' : '' }}>
                        {{ $coop->coop_name }}
                    </option>
                    @endforeach
                </select>

                {{-- Category --}}
                <select name="category_id" id="category_id"
                    class="border border-gray-300 rounded-lg px-2 py-1 text-xs w-32">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id')==$category->id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                    @endforeach
                </select>

                {{-- Brand --}}
                <select name="brand_id" id="brand_id" class="border border-gray-300 rounded-lg px-2 py-1 text-xs w-32">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id')==$brand->id ? 'selected' : '' }}>
                        {{ $brand->brand_name }}
                    </option>
                    @endforeach
                </select>

                {{-- Supplier --}}
                {{-- sm:w-36 md:sw-36 kelas responsive --}}
                <select name="supplier_id" id="supplier_id"
                    class="border border-gray-300 rounded-lg px-2 py-1 text-xs w-36">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id')==$supplier->id ? 'selected' : '' }}>
                        {{ $supplier->supplier_name }}
                    </option>
                    @endforeach
                </select>

                {{-- Tombol Filter --}}
                <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs">Filter</button>

                {{-- Tombol Export Excel --}}
                <a href="{{ route('products.downloadExcel', request()->all()) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs">Export to Excel</a>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white text-xs">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Tanggal Dibuat</th>
                        <th class="py-2 px-4 border-b">Koperasi</th>
                        <th class="py-2 px-4 border-b">Kode Item</th>
                        <th class="py-2 px-4 border-b">Nama Produk</th>
                        <th class="py-2 px-4 border-b">Jenis</th>
                        <th class="py-2 px-4 border-b">Merek</th>
                        <th class="py-2 px-4 border-b">Harga Pokok</th>
                        <th class="py-2 px-4 border-b">Harga Grosir</th>
                        <th class="py-2 px-4 border-b">Harga Jual</th>
                        <th class="py-2 px-4 border-b">Stok</th>
                        <th class="py-2 px-4 border-b">Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="bg-gray-50">
                        <td class="py-2 px-4 border-b">{{ $product->created_at->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->coop->coop_name }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->item_code }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->product_name }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->category->category_name }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->brand->brand_name }}</td>
                        <td class="py-2 px-4 border-b">Rp {{ number_format($product->cost_price) }}</td>
                        <td class="py-2 px-4 border-b">Rp {{ number_format($product->wholesale_price) }}</td>
                        <td class="py-2 px-4 border-b">Rp {{ number_format($product->sale_price) }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->stock }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->supplier->supplier_name }}</td>
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

    {{-- Tambahkan Flatpickr CSS dan JS --}}
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 dengan placeholder
            $('#coop_id').select2({
                placeholder: "Koperasi",
                allowClear: true
            });
            $('#category_id').select2({
                placeholder: "Jenis",
                allowClear: true
            });
            $('#brand_id').select2({
                placeholder: "Merek",
                allowClear: true
            });
            $('#supplier_id').select2({
                placeholder: "Supplier",
                allowClear: true
            });

            // Inisialisasi Flatpickr pada input tanggal dengan format dd/mm/yyyy
            flatpickr(".datepicker", {
                dateFormat: "d/m/Y", // Mengatur format tanggal menjadi dd/mm/yyyy
                allowInput: true, // Memungkinkan input manual
            });
        });
    </script>
</x-app-layout>