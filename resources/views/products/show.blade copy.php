<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Product Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        .thumbnail {
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-md overflow-hidden md:flex md:flex-row">
        <div class="relative md:w-1/2">
            <img id="mainImage" alt="Woman wearing a light brown jacket" class="w-full" height="300"
                src="https://storage.googleapis.com/a1aa/image/ubdFNlcb1gK2EhLJCztVYlYwIRyWhprRzeeUZY29ljW9uEDUA.jpg"
                width="500" />
            <div class="absolute top-4 left-4">
                <button class="bg-white p-2 rounded-full shadow-md">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </button>
            </div>

            {{-- untuk loop beberapa foto dan bisa tampil di main image saat di klik --}}
            <div
                class="flex space-x-2 overflow-x-auto mb-4 mt-4 md:mt-2 md:mb-0 md:flex-col md:space-x-0 md:space-y-2 md:absolute md:bottom-4 md:left-4">
                <img alt="Thumbnail 1" class="w-16 h-16 rounded-md thumbnail" height="60"
                    src="https://storage.googleapis.com/a1aa/image/aWoPfs84YmUnYq02hceezRS5bFkazBfIfPGrmNL2bipK4lYgC.jpg"
                    width="60" onclick="changeImage(this.src)" />
                <img alt="Thumbnail 2" class="w-16 h-16 rounded-md thumbnail" height="60"
                    src="https://storage.googleapis.com/a1aa/image/N01jtQK8Y24uJhegf3keJ8PrHcDOsTgpaCcxZaSJhuVGeSMQB.jpg"
                    width="60" onclick="changeImage(this.src)" />
                <img alt="Thumbnail 3" class="w-16 h-16 rounded-md thumbnail" height="60"
                    src="https://storage.googleapis.com/a1aa/image/ZpT6YxCNarK4E9KHDQYpCeSQPyNzBJqRfkGUjcqlxpAfdJGoA.jpg"
                    width="60" onclick="changeImage(this.src)" />
                <img alt="Thumbnail 4" class="w-16 h-16 rounded-md thumbnail" height="60"
                    src="https://storage.googleapis.com/a1aa/image/lAZwXqYuTvIuCJZOsd10fR0ScnT67mADRMB7Jyvyx4SeuEDUA.jpg"
                    width="60" onclick="changeImage(this.src)" />
                <div class="w-16 h-16 rounded-md bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-600">+10</span>
                </div>
            </div>
        </div>
        <div class="p-4 md:w-1/2">
            {{-- <h1 class="text-xl font-semibold mb-2">Product Details</h1> --}}
            <div class="mb-4">
                <span class="text-gray-500">{{ $product->coop->coop_name }}</span>
                <div class="flex items-center">
                    <h2 class="text-2xl font-bold">{{ $product->product_name }}</h2>
                </div>
            </div>

            {{-- untuk loop beberapa size --}}
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Size</h3>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 border rounded-md">S</button>
                    <button class="px-4 py-2 border rounded-md">M</button>
                    <button class="px-4 py-2 border rounded-md">L</button>
                    <button class="px-4 py-2 border rounded-md">XL</button>
                    <button class="px-4 py-2 border rounded-md">XXL</button>
                    <button class="px-4 py-2 border rounded-md">XXXL</button>
                </div>
            </div>
            {{-- untuk loop beberapa color --}}
            <div class="mb-4">
                <h3 class="text-lg font-semibold">Color:</h3>
                <div class="flex space-x-2">
                    <span class="px-4 py-1 border rounded-md bg-brown-500 text-white">Brown</span>
                    <span class="px-4 py-1 border rounded-md bg-red-500 text-white">Red</span>
                    <span class="px-4 py-1 border rounded-md bg-blue-500 text-white">Blue</span>
                    <span class="px-4 py-1 border rounded-md bg-green-500 text-white">Green</span>
                    <span class="px-4 py-1 border rounded-md bg-yellow-500 text-white">Yellow</span>
                </div>
            </div>
            <div class="flex justify-between items-center">
                <div class="text-center">
                    <span class="block text-sm text-gray-500">Harga pokok</span>
                    <span class="text-2xl font-bold md:text-2xl sm:text-xl">Rp {{ number_format($product->cost_price, 0,
                        ',', '.') }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-sm text-gray-500">Harga grosir</span>
                    <span class="text-2xl font-bold md:text-2xl sm:text-xl">Rp {{
                        number_format($product->wholesale_price, 0, ',', '.') }}</span>
                </div>
                <div class="text-center">
                    <span class="block text-sm text-gray-500">Harga jual</span>
                    <span class="text-2xl font-bold md:text-2xl sm:text-xl">Rp {{ number_format($product->sale_price, 0,
                        ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        function changeImage(src) {
            document.getElementById('mainImage').src = src;
        }
    </script>
</body>

</html>