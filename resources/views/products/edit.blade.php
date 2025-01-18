{{-- views/products/edit.blade.php --}}

<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Pastikan Anda juga menaruh CSS Select2 di HEAD layout:
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

        {{-- Buat style agar Select2 bersifat responsif --}}
        <style>
            .select2-container {
                width: 100% !important;
                /* Agar Select2 full width */
            }

            /* Menyamakan tinggi Select2 single dan multiple */
            .select2-container--default .select2-selection--single,
            .select2-container--default .select2-selection--multiple {
                height: 42px;
                /* Sesuaikan dengan tinggi input lainnya */
                border: 1px solid #d1d5db;
                /* Warna border seperti Tailwind */
                border-radius: 0.375rem;
                /* Border radius seperti Tailwind */
                padding: 0.5rem 0.75rem;
                /* Sesuaikan padding */
                display: flex;
                align-items: center;
                /* Centerkan konten secara vertikal */
                box-sizing: border-box;
            }

            /* Khusus untuk Select2 single */
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 1.5;
                /* Tinggi teks di dalam Select2 single */
            }

            /* Khusus untuk Select2 multiple */
            .select2-container--default .select2-selection--multiple .select2-selection__rendered {
                line-height: 1.5;
                /* Tinggi teks di dalam Select2 multiple */
            }
        </style>



        <div class="bg-white rounded-lg shadow p-6">
            {{-- FORM UTAMA --}}
            <form id="update-form" method="POST" action="{{ route('products.update', $product->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- jika submit standar (non-AJAX) --}}

                {{-- Koperasi --}}
                <div class="mb-4">
                    <label for="coop_id" class="block font-medium text-gray-700 mb-1">Koperasi</label>
                    <div class="flex items-center gap-2">
                        <select name="coop_id" id="coop_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">Pilih Koperasi</option>
                            @foreach($coops as $coop)
                            <option value="{{ $coop->id }}" {{ $coop->id == $product->coop_id ? 'selected' : '' }}>
                                {{ $coop->coop_name }}
                            </option>
                            @endforeach
                        </select>
                        <button type="button" class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition"
                            onclick="toggleModal('#modal-add-coop')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4.75v14.5m7.25-7.25H4.75"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Nama Barang --}}
                <div class="mb-4">
                    <label for="product_name" class="block font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input type="text" name="product_name" id="product_name"
                        value="{{ old('product_name', $product->product_name) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Jenis dan Merek --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Jenis --}}
                    <div class="mb-4">
                        <label for="category_id" class="block font-medium text-gray-700 mb-1">Jenis</label>
                        <div class="flex items-center gap-2">
                            <select name="category_id" id="category_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <option value="">Pilih Jenis</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $product->category_id ?
                                    'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                                @endforeach
                            </select>
                            <button type="button"
                                class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition"
                                onclick="toggleModal('#modal-add-category')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 4.75v14.5m7.25-7.25H4.75"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Merek --}}
                    <div class="mb-4">
                        <label for="brand_id" class="block font-medium text-gray-700 mb-1">Merek</label>
                        <div class="flex items-center gap-2">
                            <select name="brand_id" id="brand_id"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                <option value="">Pilih Merek</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : ''
                                    }}>
                                    {{ $brand->brand_name }}
                                </option>
                                @endforeach
                            </select>
                            <button type="button"
                                class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition"
                                onclick="toggleModal('#modal-add-brand')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 4.75v14.5m7.25-7.25H4.75"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Harga --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Cost Price --}}
                    <div>
                        <label for="cost_price" class="block font-medium text-gray-700 mb-1">Cost Price</label>
                        <input type="text" name="cost_price" id="cost_price"
                            value="{{ old('cost_price', $product->cost_price) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>

                    {{-- Wholesale Price --}}
                    <div>
                        <label for="wholesale_price" class="block font-medium text-gray-700 mb-1">Wholesale
                            Price</label>
                        <input type="text" name="wholesale_price" id="wholesale_price"
                            value="{{ old('wholesale_price', $product->wholesale_price) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>

                    {{-- Sale Price --}}
                    <div>
                        <label for="sale_price" class="block font-medium text-gray-700 mb-1">Sale Price</label>
                        <input type="text" name="sale_price" id="sale_price"
                            value="{{ old('sale_price', $product->sale_price) }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                    </div>
                </div>


                {{-- Warna dan Ukuran --}}
                <div class="mb-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Color --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Color</label>
                        <div class="flex items-center gap-2">
                            <select name="colors[]" id="colors" multiple
                                class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                @foreach($colors as $color)
                                <option value="{{ $color->id }}" @if(in_array($color->id,
                                    $product->colors->pluck('id')->toArray())) selected @endif>
                                    {{ $color->color }}
                                </option>
                                @endforeach
                            </select>
                            <button type="button"
                                class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition"
                                onclick="toggleModal('#modal-add-color')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 4.75v14.5m7.25-7.25H4.75"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Size --}}
                    <div>
                        <label class="block font-medium text-gray-700 mb-1">Size</label>
                        <div class="flex items-center gap-2">
                            <select name="sizes[]" id="sizes" multiple
                                class="w-full border border-gray-300 rounded-lg px-4 py-2">
                                @foreach($sizes as $size)
                                <option value="{{ $size->id }}" @if(in_array($size->id,
                                    $product->sizes->pluck('id')->toArray())) selected @endif>
                                    {{ $size->size }}
                                </option>
                                @endforeach
                            </select>
                            <button type="button"
                                class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition"
                                onclick="toggleModal('#modal-add-size')">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 4.75v14.5m7.25-7.25H4.75"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>



                {{-- Supplier (Single Select2) --}}
                <div class="mb-4">
                    <label for="supplier_id" class="block font-medium text-gray-700 mb-1">Supplier</label>
                    <div class="flex items-center">
                        <select name="supplier_id" id="supplier_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">Pilih Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $supplier->id == $product->supplier_id ? 'selected' :
                                ''}}>
                                {{ $supplier->supplier_name }}
                            </option>
                            @endforeach
                        </select>
                        {{-- Tombol plus (open modal) --}}
                        <button type="button" class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition"
                            onclick="toggleModal('#modal-add-supplier')">
                            {{-- Ikon Plus (SVG) --}}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4.75v14.5m7.25-7.25H4.75"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Stock --}}
                <div class="mb-4">
                    <label for="stock" class="block font-medium text-gray-700 mb-1">Stok</label>
                    <input type="text" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                </div>


                <!-- Foto lama -->
                @if($product->photos->count() > 0)
                <div id="old-photos-container" class="flex flex-wrap gap-4 mb-3">
                    @foreach($product->photos as $photo)
                    <div class="relative w-20 h-20" id="old-photo-{{ $photo->id }}">
                        <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="Photo"
                            class="object-cover w-full h-full rounded-md">
                        <!-- Tombol X -->
                        <button type="button" class="absolute top-0 right-0
                       bg-red-500 text-white rounded-full text-xs
                       w-5 h-5 flex items-center justify-center
                       hover:bg-red-600 transition" onclick="removeOldPhoto({{ $photo->id }})">
                            X
                        </button>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 mb-3">No existing photos.</p>
                @endif

                <!-- Hidden container untuk menampung ID foto yang dihapus -->
                <!-- Nanti di-append <input type="hidden" name="removed_photos[]" value="..."> via JS -->
                <div id="removed-photos-inputs"></div>


                <!-- Bagian menambahkan foto baru (tetap sama) -->
                <div>
                    <label class="block font-medium text-gray-700 mb-1">
                        Add New Photos
                    </label>
                    <div class="flex items-center">
                        <button type="button" id="add-photo"
                            class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 4.75v14.5m7.25-7.25H4.75"></path>
                            </svg>
                        </button>

                        <input type="file" id="photos" name="photos[]" class="hidden" accept="image/*" multiple>
                    </div>
                    <div id="image-preview" class="mt-4 flex flex-wrap gap-4"></div>
                </div>


                {{-- Tombol Submit --}}
                <div class="pt-4 flex flex-col md:flex-row gap-4">
                    <button type="submit" style="text-align: center"
                        class="w-full md:w-auto bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition">
                        Simpan Produk
                    </button>
                    <a href="{{ url('/products') }}" style="text-align: center"
                        class="w-full md:w-auto bg-gray-500 text-white rounded-md p-2 hover:bg-gray-600 transition">
                        Cancel
                    </a>
                </div>
        </div>
        </form>
    </div>
    </div>


    {{-- MODAL: ADD COOP (Single) --}}
    <div id="modal-add-coop" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-11/12 sm:w-2/3 md:w-1/3 p-6 rounded-md relative">
            <h3 class="text-xl font-semibold mb-4">Tambah Koperasi</h3>
            <form id="form-add-coop">
                @csrf
                <div class="mb-4">
                    <input type="text" name="coop_name"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Nama Koperasi" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition"
                        onclick="toggleModal('#modal-add-coop')">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                        Save
                    </button>
                </div>
            </form>
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                onclick="toggleModal('#modal-add-coop')">
                X
            </button>
        </div>
    </div>

    {{-- MODAL: ADD CATEGORY (Single) --}}
    <div id="modal-add-category"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-11/12 sm:w-2/3 md:w-1/3 p-6 rounded-md relative">
            <h3 class="text-xl font-semibold mb-4">Tambah Jenis</h3>
            <form id="form-add-category">
                @csrf
                <div class="mb-4">
                    <input type="text" name="category_name"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Nama Jenis" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition"
                        onclick="toggleModal('#modal-add-category')">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                        Save
                    </button>
                </div>
            </form>
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                onclick="toggleModal('#modal-add-category')">
                X
            </button>
        </div>
    </div>

    {{-- MODAL: ADD BRAND (Single) --}}
    <div id="modal-add-brand" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-11/12 sm:w-2/3 md:w-1/3 p-6 rounded-md relative">
            <h3 class="text-xl font-semibold mb-4">Tambah Merek</h3>
            <form id="form-add-brand">
                @csrf
                <div class="mb-4">
                    <input type="text" name="brand_name"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Nama Merek" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition"
                        onclick="toggleModal('#modal-add-brand')">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                        Save
                    </button>
                </div>
            </form>
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                onclick="toggleModal('#modal-add-brand')">
                X
            </button>
        </div>
    </div>

    {{-- MODAL ADD COLOR --}}
    <div id="modal-add-color" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-11/12 sm:w-2/3 md:w-1/3 p-6 rounded-md relative">
            <h3 class="text-xl font-semibold mb-4">Tambah Warna</h3>
            <form id="form-add-color">
                @csrf
                <div class="mb-4">
                    <input type="text" name="color"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Nama Warna" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition"
                        onclick="toggleModal('#modal-add-color')">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition">
                        Save
                    </button>
                </div>
            </form>
            {{-- Tombol close (X) --}}
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                onclick="toggleModal('#modal-add-color')">
                X
            </button>
        </div>
    </div>

    {{-- MODAL ADD SIZE --}}
    <div id="modal-add-size" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-11/12 sm:w-2/3 md:w-1/3 p-6 rounded-md relative">
            <h3 class="text-xl font-semibold mb-4">Tambah Ukuran</h3>
            <form id="form-add-size">
                @csrf
                <div class="mb-4">
                    <input type="text" name="size"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Nama Ukuran" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition"
                        onclick="toggleModal('#modal-add-size')">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 text-white rounded-md p-2 hover:bg-blue-600 transition">
                        Save
                    </button>
                </div>
            </form>
            {{-- Tombol close (X) --}}
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                onclick="toggleModal('#modal-add-size')">
                X
            </button>
        </div>
    </div>

    {{-- MODAL: ADD SUPPLIER (Single) --}}
    <div id="modal-add-supplier"
        class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-11/12 sm:w-2/3 md:w-1/3 p-6 rounded-md relative">
            <h3 class="text-xl font-semibold mb-4">Tambah Supplier</h3>
            <form id="form-add-supplier">
                @csrf
                <div class="mb-4">
                    <input type="text" name="supplier_name"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Nama Supplier" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition"
                        onclick="toggleModal('#modal-add-supplier')">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                        Save
                    </button>
                </div>
            </form>
            <button class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
                onclick="toggleModal('#modal-add-supplier')">
                X
            </button>
        </div>
    </div>

    <script>
        // 1) Init Select2 (dengan placeholder)
     $(document).ready(function() {
            $('#supplier_id').select2({
                placeholder: "Pilih Supplier",
                allowClear: true
            });
            $('#colors').select2({
                placeholder: "Pilih Color",
                allowClear: true
            });
            $('#sizes').select2({
                placeholder: "Pilih Size",
                allowClear: true
            });
            $('#brand_id').select2({
                placeholder: "Pilih Merek",
                allowClear: true
            });
            $('#category_id').select2({
                placeholder: "Pilih Jenis",
                allowClear: true
            });
            $('#coop_id').select2({
                placeholder: "Pilih Koperasi",
                allowClear: true
            });
        });

        // 2) Toggle Modal
        function toggleModal(modalId) {
            const modal = document.querySelector(modalId);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        // 3) Format Rupiah
        function formatRupiah(value) {
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        const costPriceInput      = document.getElementById('cost_price');
        const wholesalePriceInput = document.getElementById('wholesale_price');
        const salePriceInput      = document.getElementById('sale_price');

         // Format nilai yang sudah ada di input pada saat halaman dimuat
        if (costPriceInput.value) handlePriceInput(costPriceInput);
        if (wholesalePriceInput.value) handlePriceInput(wholesalePriceInput);
        if (salePriceInput.value) handlePriceInput(salePriceInput);

        function handlePriceInput(elem) {
            let raw = elem.value.replace(/[^\d]/g, '');
            elem.value = raw ? 'Rp ' + formatRupiah(raw) : '';
        }

        costPriceInput.addEventListener('input',      () => handlePriceInput(costPriceInput));
        wholesalePriceInput.addEventListener('input', () => handlePriceInput(wholesalePriceInput));
        salePriceInput.addEventListener('input',      () => handlePriceInput(salePriceInput));


        // Form Add coops (AJAX)
           const formAddCoop = document.getElementById('form-add-coop');
        formAddCoop.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(formAddCoop);

            // Log data sebelum dikirim untuk debugging
             console.log('Form data being sent:', formData);

            fetch('{{ route("product.storeCoop") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                
                console.log('Success', data);
                if (data.id) {
                    const newOption = new Option(data.coop_name, data.id, false, false);
                    $('#coop_id').append(newOption).trigger('change');

                    toggleModal('#modal-add-coop');
                    formAddCoop.reset();
                }
            })
            .catch(err => console.log(err));
        });

        // Form Add category (AJAX)
        const formAddCategory = document.getElementById('form-add-category');
        formAddCategory.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(formAddCategory);

            // Log data sebelum dikirim untuk debugging
             console.log('Form data being sent:', formData);

            fetch('{{ route("product.storeCategory") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                
                console.log('Success', data);
                if (data.id) {
                    const newOption = new Option(data.category_name, data.id, false, false);
                    $('#category_id').append(newOption).trigger('change');

                    toggleModal('#modal-add-category');
                    formAddCategory.reset();
                }
            })
            .catch(err => console.log(err));
        });

         // Form Add Brand (AJAX)
         const formAddBrand = document.getElementById('form-add-brand');
        formAddBrand.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(formAddBrand);

            // Log data sebelum dikirim untuk debugging
             console.log('Form data being sent:', formData);

            fetch('{{ route("product.storeBrand") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                
                console.log('Success', data);
                if (data.id) {
                    const newOption = new Option(data.brand_name, data.id, false, false);
                    $('#brand_id').append(newOption).trigger('change');

                    toggleModal('#modal-add-brand');
                    formAddBrand.reset();
                }
            })
            .catch(err => console.log(err));
        });

        // Form Add Color (AJAX)
          const formAddColor = document.getElementById('form-add-color');
        formAddColor.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(formAddColor);

            fetch('{{ route("product.storeColor") }}', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.id) {
                    const newOption = new Option(data.color, data.id, false, false);
                    $('#colors').append(newOption).trigger('change');
                    toggleModal('#modal-add-color');
                    formAddColor.reset();
                }
            })
            .catch(err => console.log(err));
        });

        // Form Add Size (AJAX)
        const formAddSize = document.getElementById('form-add-size');
        formAddSize.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(formAddSize);

            fetch('{{ route("product.storeSize") }}', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.id) {
                    const newOption = new Option(data.size, data.id, false, false);
                    $('#sizes').append(newOption).trigger('change');
                    toggleModal('#modal-add-size');
                    formAddSize.reset();
                }
            })
            .catch(err => console.log(err));
        });

        // FORM ADD SUPPLIER (AJAX)
        const formAddSupplier = document.getElementById('form-add-supplier');
        formAddSupplier.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(formAddSupplier);

            fetch('{{ route("product.storeSupplier") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                // data = {id: X, supplier_name: "Baru"}
                console.log('Success', data);
                if (data.id) {
                    // Tambah ke #supplier_id (single select)
                    const newOption = new Option(data.supplier_name, data.id, false, false);
                    $('#supplier_id').append(newOption).trigger('change');

                    // Tutup modal
                    toggleModal('#modal-add-supplier');
                    formAddSupplier.reset();
                }
            })
            .catch(err => console.error(err));

        });

        // Upload Images (Preview & Remove)
        let selectedFiles = [];

        document.getElementById('add-photo').addEventListener('click', function() {
            document.getElementById('photos').click();
        });

        document.getElementById('photos').addEventListener('change', function(e) {
            const previewContainer = document.getElementById('image-preview');
            const files = e.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                selectedFiles.push(file);

                const reader = new FileReader();
                reader.onload = function(ev) {
                    const wrapper = document.createElement('div');
                    wrapper.classList.add('relative', 'w-20', 'h-20');

                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.classList.add('object-cover', 'rounded-md', 'w-full', 'h-full');

                    const removeBtn = document.createElement('button');
                    removeBtn.innerText = 'X';
                    removeBtn.classList.add(
                        'absolute', 'top-0', 'right-0',
                        'bg-red-500', 'text-white', 'rounded-full', 'text-xs',
                        'w-5', 'h-5', 'flex', 'items-center', 'justify-center',
                        'hover:bg-red-600', 'transition'
                    );
                    removeBtn.onclick = function() {
                        wrapper.remove();
                        const idx = selectedFiles.indexOf(file);
                        if (idx > -1) {
                            selectedFiles.splice(idx, 1);
                        }
                    };

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    previewContainer.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            }
        });

      
        // menghapus foto lama
        function removeOldPhoto(photoId) {
                // 1) Sembunyikan / hapus elemen di DOM
                const photoDiv = document.getElementById('old-photo-' + photoId);
                if (photoDiv) {
                    photoDiv.remove();
                }

                // 2) Tambahkan input hidden agar server tahu ID foto mana yang dihapus
                const removedInputsContainer = document.getElementById('removed-photos-inputs');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'removed_photos[]';
                input.value = photoId;
                removedInputsContainer.appendChild(input);
            }

        // 7) Submit Form Product (AJAX) -- (contoh, sesuaikan controller)
        //     const productForm = document.getElementById('update-form');
        //     productForm.addEventListener('submit', function(e) {
        //     e.preventDefault();

        //     let fd = new FormData();

        //    // 1) Tambah data lain
        //     fd.append('_token', document.querySelector('input[name="_token"]').value);
        //     fd.append('_method', 'PUT');

        //     // CSRF
        //     fd.append('_token', document.querySelector('input[name="_token"]').value);

        //     // 2) Ambil semua input[name="removed_photos[]"] di form
        //     const removedPhotoInputs = document.querySelectorAll('input[name="removed_photos[]"]');
        //     removedPhotoInputs.forEach(input => {
        //         fd.append('removed_photos[]', input.value);
        //     });

        //     // Hapus format Rupiah => hanya angka
        //     const costVal      = costPriceInput.value.replace(/[^\d]/g, '');
        //     const wholesaleVal = wholesalePriceInput.value.replace(/[^\d]/g, '');
        //     const saleVal      = salePriceInput.value.replace(/[^\d]/g, '');

        //     fd.append('product_name', document.getElementById('product_name').value);
        //     fd.append('cost_price', costVal);
        //     fd.append('wholesale_price', wholesaleVal);
        //     fd.append('sale_price', saleVal);
        //     fd.append('supplier_id', document.getElementById('supplier_id').value);
        //     fd.append('coop_id', document.getElementById('coop_id').value);
        //     fd.append('category_id', document.getElementById('category_id').value);
        //     fd.append('brand_id', document.getElementById('brand_id').value);
        //     fd.append('stock', document.getElementById('stock').value);


        //     // Colors
        //     const selectedColors = $('#colors').val() || [];
        //     selectedColors.forEach(colorId => {
        //         fd.append('colors[]', colorId);
        //     });
        //     // Sizes
        //     const selectedSizes = $('#sizes').val() || [];
        //     selectedSizes.forEach(sizeId => {
        //         fd.append('sizes[]', sizeId);
        //     });

        //     // Photos
        //     selectedFiles.forEach(file => {
        //         fd.append('photos[]', file);
        //     });

        //     fetch('{{ route("products.update", $product->id) }}', {
        //         method: 'POST', // atau 'PUT' tapi kebanyakan kita pakai POST + _method=PUT
        //         body: fd,
        //         headers: {
        //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        //         }
        //     })
        //     .then(res => {
        //         if (!res.ok) {
        //             return res.json().then(errData => {
        //                 throw new Error(errData.message || 'Something went wrong');
        //             });
        //         }
        //         return res.json();
        //     })
        //     .then(data => {
        //         if (data.status === 'success') {
        //             Swal.fire({
        //                 icon: 'success',
        //                 title: 'Success',
        //                 text: data.message || 'Product updated!',
        //             }).then(() => {
        //                 // redirect ke index (atau tutup modal)
        //                 window.location.href = "{{ route('products.index') }}";
        //             });
        //         } else {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error',
        //                 text: data.message || 'Failed to update product.',
        //             });
        //         }
        //     })
        //     .catch(err => {
        //         console.error('Error:', err);
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Error',
        //             text: err.message || 'Terjadi kesalahan saat mengupdate product.',
        //         });
        //     });
        // });


        // Submit Form Product (AJAX)
       // Submit Form Product (AJAX)
    let isSubmittingUpdate = false;
    const updateForm = document.getElementById('update-form');
    if (updateForm) {
        updateForm.addEventListener('submit', function(e) {
            e.preventDefault();

            console.log("Form submitted for update"); // Logging

            // Jika sudah di-submit, cegah submit ulang
            if (isSubmittingUpdate) {
                console.log("Already submitting, exiting.");
                return;
            }
            isSubmittingUpdate = true;

            // Disable tombol submit
            const submitBtn = updateForm.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerText = 'Sedang mengupdate...';

            let fd = new FormData();

            // CSRF dan Method Spoofing
            fd.append('_token', document.querySelector('input[name="_token"]').value);
            fd.append('_method', 'PUT');

            // Hapus format Rupiah => hanya angka
            const costVal      = costPriceInput.value.replace(/[^\d]/g, '');
            const wholesaleVal = wholesalePriceInput.value.replace(/[^\d]/g, '');
            const saleVal      = salePriceInput.value.replace(/[^\d]/g, '');

            // Tambahkan logging untuk memastikan nilai yang dikirim
            console.log('costVal:', costVal);
            console.log('wholesaleVal:', wholesaleVal);
            console.log('saleVal:', saleVal);

            // Validasi di Frontend: Pastikan nilai tidak kosong
            if (!costVal || !wholesaleVal || !saleVal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Harga tidak boleh kosong atau invalid.',
                });
                isSubmittingUpdate = false;
                submitBtn.disabled = false;
                submitBtn.innerText = 'Simpan Produk';
                return;
            }

            // Tambah field yang diperlukan
            fd.append('product_name', document.getElementById('product_name').value);
            fd.append('cost_price', costVal);
            fd.append('wholesale_price', wholesaleVal);
            fd.append('sale_price', saleVal);
            fd.append('supplier_id', document.getElementById('supplier_id').value);
            fd.append('coop_id', document.getElementById('coop_id').value);
            fd.append('category_id', document.getElementById('category_id').value);
            fd.append('brand_id', document.getElementById('brand_id').value);
            fd.append('stock', document.getElementById('stock').value);

            // Colors
            const selectedColors = $('#colors').val() || [];
            selectedColors.forEach(colorId => {
                fd.append('colors[]', colorId);
            });

            // Sizes
            const selectedSizes = $('#sizes').val() || [];
            selectedSizes.forEach(sizeId => {
                fd.append('sizes[]', sizeId);
            });

            // Photos (foto baru)
            selectedFiles.forEach(file => {
                fd.append('photos[]', file);
            });

            // Removed Photos (foto lama yang dihapus)
            const removedPhotoInputs = document.querySelectorAll('input[name="removed_photos[]"]');
            removedPhotoInputs.forEach(input => {
                fd.append('removed_photos[]', input.value);
            });

            fetch('{{ route("products.update", $product->id) }}', {
                method: 'POST', // Menggunakan POST dengan method spoofing
                body: fd,
                headers: {
                    'Accept': 'application/json', // Penting untuk mendapatkan respon JSON
                    'X-Requested-With': 'XMLHttpRequest', // Menandakan ini adalah request AJAX
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(async (res) => {
                if (!res.ok) {
                    const contentType = res.headers.get("content-type") || "";
                    if (contentType.includes("application/json")) {
                        const errorData = await res.json();
                        throw new Error(errorData.message || 'Terjadi kesalahan (JSON)');
                    } else {
                        const errorText = await res.text();
                        throw new Error('Terjadi kesalahan. Server mengirimkan non-JSON response');
                    }
                }
                return res.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message || 'Product has been updated.',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = "{{ route('products.index') }}";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to update product.',
                    });
                }
            })
            .catch(err => {
                console.error('Error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: err.message || 'Terjadi kesalahan saat mengupdate produk.',
                });
            })
            .finally(() => {
                isSubmittingUpdate = false;
                submitBtn.disabled = false;
                submitBtn.innerText = 'Simpan Produk';
            });
        });
    }
    </script>
</x-app-layout>