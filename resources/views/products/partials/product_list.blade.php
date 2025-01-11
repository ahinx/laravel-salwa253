@foreach($products as $product)
<div class="relative w-full h-0 pb-[100%] product-card">
    @php
    // Cek apakah product punya foto
    $photoPath = $product->photos->count()
    ? 'storage/' . $product->photos->first()->photo_path
    : 'storage/no-image.png';
    // 'no-image.png' adalah contoh placeholder jika tidak ada foto
    @endphp

    <style>
        .text-xxs {
            font-size: 0.7rem;
            /* Ukuran yang lebih kecil dari text-xs */
        }
    </style>

    <img alt="{{ $product->product_name }}" class="absolute inset-0 w-full h-full object-cover rounded-lg"
        src="{{ asset($photoPath) }}" />
    <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black to-transparent rounded-lg p-4">
        <span class="text-white text-xs truncate">{{ $product->coop->coop_name }} </span>
        <span class="text-white text-xxs truncate">Rp {{ number_format($product->cost_price, 0, ',', '.') }} | Rp {{
            number_format($product->wholesale_price, 0, ',', '.') }} | Rp {{ number_format($product->sale_price, 0, ',',
            '.')
            }}</span>

        <a href="{{ route('products.show', $product->id) }}" class="inline-block">
            <div style="text-align: center; width: 100%; overflow: hidden; white-space: nowrap;"
                class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-lg bg-blue-500 text-white mt-2">
                {{ $product->product_name }}
            </div>
        </a>

    </div>

    <!-- Kode Produk di Kiri Atas -->
    <span class="absolute top-2 left-2 text-white bg-black bg-opacity-50 px-2 py-1 text-xxs rounded-lg">
        {{ $product->item_code }}
    </span>

    <a href="{{ route('products.edit', $product->id) }}"
        class="absolute top-2 right-2 text-white rounded-full p-2 flex items-center justify-center mr-2">
        <i class="fas fa-edit"></i>
    </a>

    <form action="{{ route('products.destroy', $product->id) }}" method="POST" id="delete-form-{{ $product->id }}"
        class="inline-block">
        @csrf
        @method('DELETE')
        <button type="button"
            class="absolute top-2 right-10 text-white rounded-full p-2 flex items-center justify-center"
            onclick="confirmDelete({{ $product->id }})">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
@endforeach

<script>
    function confirmDelete(productId) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',       // merah
            cancelButtonColor: '#3085d6',     // biru
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + productId).submit();
            }
        });
    }
</script>