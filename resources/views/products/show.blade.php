<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <!-- Fancybox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">

    <!-- Fancybox JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-md overflow-hidden md:flex md:flex-row">
        <div class="relative md:w-1/2">
            <!-- Main image -->
            <a id="mainImageLink"
                href="{{ asset('storage/' . ($product->photos->count() ? $product->photos->first()->photo_path : 'no-image.png')) }}"
                data-fancybox="gallery" data-caption="{{ $product->product_name }}">
                <img id="mainImage" alt="Product Image" class="w-[550px] h-[550px] object-cover"
                    src="{{ asset('storage/' . ($product->photos->count() ? $product->photos->first()->photo_path : 'no-image.png')) }}"
                    width="550" height="550" />
            </a>

            <!-- Back Button (absolute Position) -->
            <div class="absolute top-6 left-6 z-50">
                <a class="bg-black p-2 w-10 h-10 flex items-center justify-center rounded-full shadow-md"
                    href="{{ route('products.index') }}">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
            </div>

            {{--
            <!-- Back Button (Fixed Position) -->
            <div class="fixed top-6 left-6 z-50 sm:top-8 sm:left-8">
                <a class="bg-black p-2 rounded-full shadow-md" href="{{ route('products.index') }}">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
            </div> --}}


            <!-- Thumbnail images -->
            <div
                class="flex space-x-2 overflow-x-auto ml mb-4 mt-4 md:mt-2 md:mb-0 md:flex-col md:space-x-0 md:space-y-2 md:absolute md:bottom-4 md:left-4">
                @foreach($product->photos as $photo)
                <img alt="Thumbnail" class="w-16 h-16 rounded-md thumbnail" height="60"
                    src="{{ asset('storage/' . $photo->photo_path) }}" width="60" onclick="changeImage(this.src)" />
                @endforeach
            </div>
        </div>
        <div class="p-4 md:w-1/2">
            <div class="mb-4">
                <span class="text-gray-600">{{ $product->coop->coop_name }}</span>
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold">{{ $product->product_name }}</h2>
                </div>
            </div>

            <!-- Size and Color buttons -->
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Size</h3>
                <div class="flex space-x-2">
                    @foreach($product->sizes as $size)
                    <button class="px-4 py-2 border rounded-md">{{ $size->size }}</button>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-semibold">Color</h3>
                <div class="flex space-x-2">
                    @foreach($product->colors as $color)
                    <span class="px-4 py-1 border rounded-md text-white"
                        style="background-color:rgb(50, 196, 176) {{ $color->color_code }}">{{ $color->color }}</span>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between items-center">
                <div class="text-center">
                    <span class="block text-sm text-gray-500">Harga pokok</span>
                    <span class="text-2xl font-bold">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-sm text-gray-500">Harga grosir</span>
                    <span class="text-2xl font-bold">Rp {{ number_format($product->wholesale_price, 0, ',', '.')
                        }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-sm text-gray-500">Harga jual</span>
                    <span class="text-2xl font-bold">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to change the main image when a thumbnail is clicked
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
            document.getElementById('mainImageLink').href = src; // Update the link for Fancybox
        }

        // Function to open the main image in Fancybox when clicked
        $(document).ready(function() {
            $('[data-fancybox="gallery"]').fancybox();
        });
    </script>

</body>

</html>