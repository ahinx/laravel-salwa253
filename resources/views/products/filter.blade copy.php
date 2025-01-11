<!-- resources/views/products/filter.blade.php -->

<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4">Filter & Download Products</h2>

            {{-- Form Filter --}}
            <form id="filter-form" method="GET" action="{{ route('products.filter') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Tanggal Mulai --}}
                    <div>
                        <label for="start_date" class="block font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 datepicker"
                            placeholder="DD/MM/YYYY">
                    </div>

                    {{-- Tanggal Akhir --}}
                    <div>
                        <label for="end_date" class="block font-medium text-gray-700 mb-1">End Date</label>
                        <input type="text" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 datepicker"
                            placeholder="DD/MM/YYYY">
                    </div>

                    {{-- Koperasi --}}
                    <div>
                        <label for="coop_id" class="block font-medium text-gray-700 mb-1">Coop</label>
                        <select name="coop_id" id="coop_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">All Coops</option>
                            @foreach($coops as $coop)
                            <option value="{{ $coop->id }}" {{ request('coop_id')==$coop->id ? 'selected' : '' }}>
                                {{ $coop->coop_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nama Produk --}}
                    <div>
                        <label for="product_name" class="block font-medium text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="product_name" id="product_name" value="{{ request('product_name') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category_id" class="block font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="category_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id')==$category->id ? 'selected' :
                                '' }}>
                                {{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Brand --}}
                    <div>
                        <label for="brand_id" class="block font-medium text-gray-700 mb-1">Brand</label>
                        <select name="brand_id" id="brand_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ request('brand_id')==$brand->id ? 'selected' : '' }}>
                                {{ $brand->brand_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Supplier --}}
                    <div>
                        <label for="supplier_id" class="block font-medium text-gray-700 mb-1">Supplier</label>
                        <select name="supplier_id" id="supplier_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">All Suppliers</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id')==$supplier->id ? 'selected' :
                                '' }}>
                                {{ $supplier->supplier_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tombol Filter dan Download --}}
                <div class="mt-4 flex items-center space-x-4">
                    <button type="submit"
                        class="bg-blue-500 text-white rounded-md px-4 py-2 hover:bg-blue-600 transition">
                        Filter
                    </button>

                    {{-- Tombol Download Excel --}}
                    <a href="{{ route('products.downloadExcel', request()->all()) }}"
                        class="bg-green-500 text-white rounded-md px-4 py-2 hover:bg-green-600 transition">
                        Download Excel
                    </a>
                </div>
            </form>

            {{-- List Produk --}}
            <div class="mt-6">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Nama Produk</th>
                            <th class="py-2 px-4 border-b">Kode Item</th>
                            <th class="py-2 px-4 border-b">Stok</th>
                            <th class="py-2 px-4 border-b">Harga Pokok</th>
                            <th class="py-2 px-4 border-b">Harga Grosir</th>
                            <th class="py-2 px-4 border-b">Harga Jual</th>
                            <th class="py-2 px-4 border-b">Supplier</th>
                            <th class="py-2 px-4 border-b">Brand</th>
                            <th class="py-2 px-4 border-b">Kategori</th>
                            <th class="py-2 px-4 border-b">Koperasi</th>
                            <th class="py-2 px-4 border-b">Tanggal Dibuat</th>
                            <th class="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $product->id }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->product_name }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->item_code }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->stock }}</td>
                            <td class="py-2 px-4 border-b">{{ number_format($product->cost_price, 2) }}</td>
                            <td class="py-2 px-4 border-b">{{ number_format($product->wholesale_price, 2) }}</td>
                            <td class="py-2 px-4 border-b">{{ number_format($product->sale_price, 2) }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->supplier->supplier_name }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->brand->brand_name }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->category->category_name }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->coop->coop_name }}</td>
                            <td class="py-2 px-4 border-b">{{ $product->created_at->format('Y-m-d') }}</td>
                            <td class="py-2 px-4 border-b">
                                <!-- Tombol Edit dan Delete -->
                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="text-blue-500 hover:underline">Edit</a>
                                |
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                        class="text-red-500 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="13" class="py-2 px-4 text-center">Tidak ada produk ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
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
                placeholder: "Select Coop",
                allowClear: true
            });
            $('#category_id').select2({
                placeholder: "Select Category",
                allowClear: true
            });
            $('#brand_id').select2({
                placeholder: "Select Brand",
                allowClear: true
            });
            $('#supplier_id').select2({
                placeholder: "Select Supplier",
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