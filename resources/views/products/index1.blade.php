<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Shopping App
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-md mx-auto bg-white md:max-w-full md:px-8">
        <!-- Search Bar -->
        <div class="p-4 flex items-center md:justify-between">
            <input class="flex-grow p-2 border rounded-full text-sm md:w-1/2" placeholder="What are you looking for?"
                type="text" />
            <div class="relative ml-4">
                <img alt="User avatar" class="w-10 h-10 rounded-full cursor-pointer" height="40"
                    onclick="toggleDropdown()"
                    src="https://storage.googleapis.com/a1aa/image/XPD2Zfrfj4vSMU7Y13Ff8HZHRwQYe15ywulsfkP4DyIOPLUgC.jpg"
                    width="40" />
                <div class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg hidden" id="dropdown">
                    <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100" href="#">
                        Profile
                    </a>
                    <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100" href="#">
                        Settings
                    </a>
                    <a class="block px-4 py-2 text-gray-700 hover:bg-gray-100" href="#">
                        Logout
                    </a>
                </div>
            </div>
        </div>
        <div class="overflow-y-auto">
            <!-- Banner -->
            <div class="p-4">
                <div class="relative bg-red-500 rounded-lg p-4 text-white">
                    <span class="absolute top-2 left-2 bg-white text-red-500 text-xs px-2 py-1 rounded-full">
                        Best Seller!
                    </span>
                    <h2 class="text-lg font-semibold mt-4">
                        Discover the perfect shopping journey!
                    </h2>
                    <button class="mt-2 bg-white text-red-500 px-4 py-2 rounded-full text-sm">
                        Shop Now!
                    </button>
                    <img alt="Woman holding shopping bags"
                        class="absolute bottom-0 right-0 h-24 w-24 object-cover rounded-full" height="100"
                        src="https://storage.googleapis.com/a1aa/image/OQ6v4qt2hLa3FpCn1vNxIDfsBRkQlSttGg6Rvw2KzEfkRhCUA.jpg"
                        width="100" />
                </div>
            </div>
            <!-- Categories -->
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4">
                    Categories
                </h3>
                <div class="grid grid-cols-4 gap-4 text-center text-sm text-gray-700 md:grid-cols-8">
                    <div>
                        <i class="fas fa-clock text-2xl mb-2">
                        </i>
                        <p>
                            Watches
                        </p>
                    </div>
                    <div>
                        <i class="fas fa-shopping-bag text-2xl mb-2">
                        </i>
                        <p>
                            Bags
                        </p>
                    </div>
                    <div>
                        <i class="fas fa-magic text-2xl mb-2">
                        </i>
                        <p>
                            Beauty
                        </p>
                    </div>
                    <div>
                        <i class="fas fa-tshirt text-2xl mb-2">
                        </i>
                        <p>
                            Clothing
                        </p>
                    </div>
                    <div>
                        <i class="fas fa-glasses text-2xl mb-2">
                        </i>
                        <p>
                            Accessories
                        </p>
                    </div>
                    <div>
                        <i class="fas fa-shoe-prints text-2xl mb-2">
                        </i>
                        <p>
                            Shoes
                        </p>
                    </div>
                    <div>
                        <i class="fas fa-heart text-2xl mb-2">
                        </i>
                        <p>
                            Lifestyle
                        </p>
                    </div>
                    <div>
                        <i class="fas fa-ellipsis-h text-2xl mb-2">
                        </i>
                        <p>
                            More
                        </p>
                    </div>
                </div>
            </div>
            <!-- Recommended -->
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">
                        Recommended
                    </h3>
                    <a class="text-red-500 text-sm" href="#">
                        See more
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div class="relative bg-white p-4 rounded-lg shadow">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            New!
                        </span>
                        <img alt="Beige handbag" class="w-full h-32 object-cover rounded-lg mb-2" height="100"
                            src="https://storage.googleapis.com/a1aa/image/IQTH7MR5PDKfDS0r5PDS1yINIloJENZXNIM7JjTaDYGxoQBKA.jpg"
                            width="100" />
                        <i class="fas fa-heart text-red-500 absolute bottom-2 right-2">
                        </i>
                    </div>
                    <div class="relative bg-white p-4 rounded-lg shadow">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            New!
                        </span>
                        <img alt="White sneakers" class="w-full h-32 object-cover rounded-lg mb-2" height="100"
                            src="https://storage.googleapis.com/a1aa/image/dE8F6wRhHIIWMt07QzD5fuZw5leRfRR9tKOBrCwqbsxMjCFoA.jpg"
                            width="100" />
                        <i class="fas fa-heart text-gray-500 absolute bottom-2 right-2">
                        </i>
                    </div>
                    <div class="relative bg-white p-4 rounded-lg shadow">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            New!
                        </span>
                        <img alt="Product image" class="w-full h-32 object-cover rounded-lg mb-2" height="100"
                            src="https://storage.googleapis.com/a1aa/image/o9oHkVSXyIq3G1MpjJI5XfSh7ib9qoSmDuXYpPADleGzThCUA.jpg"
                            width="100" />
                        <i class="fas fa-heart text-gray-500 absolute bottom-2 right-2">
                        </i>
                    </div>
                    <div class="relative bg-white p-4 rounded-lg shadow">
                        <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                            New!
                        </span>
                        <img alt="Product image" class="w-full h-32 object-cover rounded-lg mb-2" height="100"
                            src="https://storage.googleapis.com/a1aa/image/o9oHkVSXyIq3G1MpjJI5XfSh7ib9qoSmDuXYpPADleGzThCUA.jpg"
                            width="100" />
                        <i class="fas fa-heart text-gray-500 absolute bottom-2 right-2">
                        </i>
                    </div>
                </div>
            </div>
            <!-- Additional Content -->
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-4">
                    Latest Products
                </h3>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    <div class="relative bg-white p-4 rounded-lg shadow">
                        <img alt="Fashion product 1" class="w-full h-32 object-cover rounded-lg mb-2" height="100"
                            src="https://storage.googleapis.com/a1aa/image/1.jpg" width="100" />
                        <p class="text-sm text-gray-700">
                            Fashion Product 1
                        </p>
                        <p class="text-sm text-red-500">
                            $20.00
                        </p>
                    </div>
                    <div class="relative bg-white p-4 rounded-lg shadow">
                        <img alt="Fashion product 2" class="w-full h-32 object-cover rounded-lg mb-2" height="100"
                            src="https://storage.googleapis.com/a1aa/image/2.jpg" width="100" />
                        <p class="text-sm text-gray-700">
                            Fashion Product 2
                        </p>
                        <p class="text-sm text-red-500">
                            $30.00
                        </p>
                    </div>
                    <div class="relative bg-white p-4 rounded-lg shadow">
                        <img alt="Fashion product 3" class="w-full h-32 object-cover rounded-lg mb-2" height="100"
                            src="https://storage.googleapis.com/a1aa/image/3.jpg" width="100" />
                        <p class="text-sm text-gray-700">
                            Fashion Product 3
                        </p>
                        <p class="text-sm text-red-500">
                            $25.00
                        </p>
                    </div>
                    <div class="relative bg-white p-4 rounded-lg shadow">
                        <img alt="Fashion product 4" class="w-full h-32 object-cover rounded-lg mb-2" height="100"
                            src="https://storage.googleapis.com/a1aa/image/4.jpg" width="100" />
                        <p class="text-sm text-gray-700">
                            Fashion Product 4
                        </p>
                        <p class="text-sm text-red-500">
                            $15.00
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navigation -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t">
            <div class="flex justify-around text-center text-sm text-gray-700 py-2">
                <div>
                    <i class="fas fa-home text-red-500 text-2xl">
                    </i>
                    <p class="text-red-500">
                        Home
                    </p>
                </div>
                <div>
                    <i class="fas fa-heart text-2xl">
                    </i>
                    <p>
                        Wishlist
                    </p>
                </div>
                <div>
                    <i class="fas fa-exchange-alt text-2xl">
                    </i>
                    <p>
                        Transaction
                    </p>
                </div>
                <div>
                    <i class="fas fa-bell text-2xl">
                    </i>
                    <p>
                        Notification
                    </p>
                </div>
                <div>
                    <i class="fas fa-user text-2xl">
                    </i>
                    <p>
                        Profile
                    </p>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        }
    </script>
</body>

</html>