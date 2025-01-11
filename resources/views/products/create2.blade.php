<x-app-layout>
    <div class="container mx-auto px-6 py-6">
        <h2 class="text-2xl font-semibold text-gray-800">Create New Product</h2>

        <form id="product-form" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
            class="mt-6 space-y-6">
            @csrf
            <!-- Product Name -->
            <div class="flex flex-col">
                <label for="product_name" class="font-medium text-gray-700">Product Name</label>
                <input type="text" name="product_name" id="product_name"
                    class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Cost Price -->
            <div class="flex flex-col">
                <label for="cost_price" class="font-medium text-gray-700">Cost Price</label>
                <input type="text" name="cost_price" id="cost_price"
                    class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Wholesale Price -->
            <div class="flex flex-col">
                <label for="wholesale_price" class="font-medium text-gray-700">Wholesale Price</label>
                <input type="text" name="wholesale_price" id="wholesale_price"
                    class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Sale Price -->
            <div class="flex flex-col">
                <label for="sale_price" class="font-medium text-gray-700">Sale Price</label>
                <input type="text" name="sale_price" id="sale_price"
                    class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
            </div>

            <!-- Supplier Dropdown -->
            <div class="flex flex-col">
                <label class="font-medium text-gray-700">Supplier</label>
                <select name="supplier_id" id="supplier_id"
                    class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Supplier</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Color Dropdown (Multiple Select) -->
            <div class="flex flex-col">
                <label class="font-medium text-gray-700">Color</label>
                <select name="colors[]" id="colors"
                    class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    multiple>
                    @foreach($colors as $color)
                    <option value="{{ $color->id }}">{{ $color->color }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Size Dropdown (Multiple Select) -->
            <div class="flex flex-col">
                <label class="font-medium text-gray-700">Size</label>
                <select name="sizes[]" id="sizes"
                    class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    multiple>
                    @foreach($sizes as $size)
                    <option value="{{ $size->id }}">{{ $size->size }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Upload Images (Multiple Files) -->
            <div class="flex flex-col">
                <label for="photos" class="font-medium text-gray-700">Product Images</label>

                <!-- Tombol untuk memilih gambar -->
                <button type="button" id="add-photo"
                    class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Add Image
                </button>

                <!-- Input file yang tersembunyi untuk memilih gambar -->
                <input type="file" name="photos[]" id="photos" class="hidden" accept="image/*" multiple>

                <!-- Tempat untuk menampilkan gambar preview -->
                <div id="image-preview" class="mt-4 flex space-x-4"></div>
            </div>



            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 text-white py-3 px-6 rounded-lg mt-6">Save Product</button>
        </form>
    </div>

    <script>
        // Initialize select2 on supplier, color, and size
$(document).ready(function() {
// Single select for supplier with search
$('#supplier_id').select2({
placeholder: "Select Supplier",
allowClear: true
});

// Multiple select for color with search
$('#colors').select2({
placeholder: "Select Color",
allowClear: true
});

// Multiple select for size with search
$('#sizes').select2({
placeholder: "Select Size",
allowClear: true
});
});

// Function to format numbers as Rupiah
function formatRupiah(value) {
return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); // Format as 1.000.000
}

// Apply Rupiah format on input changes for cost, wholesale, and sale prices
document.getElementById('cost_price').addEventListener('input', function(event) {
let value = event.target.value.replace(/[^\d]/g, ''); // Remove non-numeric characters
event.target.value = 'Rp ' + formatRupiah(value); // Add Rupiah symbol and format
});

document.getElementById('wholesale_price').addEventListener('input', function(event) {
let value = event.target.value.replace(/[^\d]/g, ''); // Remove non-numeric characters
event.target.value = 'Rp ' + formatRupiah(value); // Add Rupiah symbol and format
});

document.getElementById('sale_price').addEventListener('input', function(event) {
    let value = event.target.value.replace(/[^\d]/g, ''); // Remove non-numeric characters
    event.target.value = 'Rp ' + formatRupiah(value); // Add Rupiah symbol and format
});

// Clean input values before submitting form
document.getElementById('product-form').addEventListener('submit', function(event) {
    const costPrice = document.getElementById('cost_price');
    const wholesalePrice = document.getElementById('wholesale_price');
    const salePrice = document.getElementById('sale_price');

    // Remove 'Rp' and thousand separator (dot) before submitting the form
    costPrice.value = costPrice.value.replace(/[^\d]/g, '');
    wholesalePrice.value = wholesalePrice.value.replace(/[^\d]/g, '');
    salePrice.value = salePrice.value.replace(/[^\d]/g, '');
});

let selectedFiles = [];  // Array untuk menyimpan file yang telah dipilih

// Fungsi untuk membuka dialog file saat tombol "Add Image" diklik
document.getElementById('add-photo').addEventListener('click', function() {
    document.getElementById('photos').click();  // Trigger file input click
});

// Fungsi untuk menangani perubahan pada input file
document.getElementById('photos').addEventListener('change', function(event) {
    const previewContainer = document.getElementById('image-preview');
    const files = event.target.files;

    // Log untuk memeriksa file yang dipilih
    console.log('Selected files:', files);

    // Iterate over each file selected
    for (let i = 0; i < files.length; i++) {
        const file = files[i];

        // Menambahkan file yang dipilih ke array selectedFiles
        selectedFiles.push(file);

        const reader = new FileReader();
        reader.onload = function(e) {
            // Membuat elemen gambar untuk setiap file yang dipilih
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('w-20', 'h-20', 'object-cover', 'rounded-md'); // Ukuran gambar dapat diubah di sini

            // Membuat tombol untuk menghapus gambar
            const removeButton = document.createElement('button');
            removeButton.innerHTML = 'X';
            removeButton.classList.add('absolute', 'top-0', 'right-0', 'bg-red-500', 'text-white', 'w-5', 'h-5', 'rounded-full', 'flex', 'items-center', 'justify-center');
            removeButton.onclick = function() {
                img.remove();
                removeButton.remove();
                // Menghapus file yang terkait dari array selectedFiles
                const index = selectedFiles.indexOf(file);
                if (index > -1) {
                    selectedFiles.splice(index, 1);  // Hapus file dari array
                }
            };

            // Membungkus gambar dan tombol hapus bersama-sama
            const imgWrapper = document.createElement('div');
            imgWrapper.classList.add('relative');
            imgWrapper.appendChild(img);
            imgWrapper.appendChild(removeButton);
            previewContainer.appendChild(img);
        };

        reader.readAsDataURL(file);  // Membaca gambar sebagai string base64
    }
});

// Form submit handler
document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();  // Mencegah form submit otomatis

    let formData = new FormData();

    // Log untuk memeriksa file yang akan dikirim
    console.log('Sending files:', selectedFiles);

    // Menambahkan file yang sudah dipilih ke FormData
    selectedFiles.forEach(function(file) {
        formData.append('photos[]', file);
    });

    // Menambahkan data lainnya ke FormData (seperti nama produk, harga, dll.)
    formData.append('product_name', document.getElementById('product_name').value);
    formData.append('cost_price', document.getElementById('cost_price').value);
    formData.append('wholesale_price', document.getElementById('wholesale_price').value);
    formData.append('sale_price', document.getElementById('sale_price').value);
    formData.append('supplier_id', document.getElementById('supplier_id').value);
    formData.append('colors', JSON.stringify([...document.getElementById('colors').selectedOptions].map(option => option.value)));
    formData.append('sizes', JSON.stringify([...document.getElementById('sizes').selectedOptions].map(option => option.value)));

    // Kirim data menggunakan AJAX
    fetch('{{ route("products.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content  // Jika menggunakan CSRF
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
        // Lakukan sesuatu setelah data berhasil dikirim
    })
    .catch(error => {
        console.error('Error:', error);
    });
});



    </script>
</x-app-layout>