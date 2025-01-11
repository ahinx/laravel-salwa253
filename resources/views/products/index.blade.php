<x-app-layout>
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center">
            <!-- Search Input -->
            <input id="search"
                class="w-full py-2 px-4 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Search products here" type="text" onkeyup="liveSearch()" value="{{ request('search') }}" />

            <a href="{{ route('products.create') }}"
                class="ml-4 bg-blue-500 text-white rounded-full p-2 flex items-center">
                <i class="fas fa-plus"></i>
            </a>
        </div>

        <!-- Display Total Search Results -->
        @if($total !== null)
        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">Showing {{ $total }} results</p>
        </div>
        @endif
    </div>

    <main class="container mx-auto px-4 py-4">
        <!-- Product list container -->
        <div id="product-list" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            @include('products.partials.product_list', ['products' => $products])
        </div>

        <!-- Pagination Links (only show if there is no search) -->
        @if($total === null)
        <div class="mt-6">
            {{ $products->appends(['search' => request('search')])->links() }}
        </div>
        @endif
    </main>

    <script>
        // Debounce function to limit the rate of function calls
        function debounce(func, delay) {
            let timeout;
            return function() {
                const context = this;
                const args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), delay);
            };
        }

        // Live search function with debounce
        const liveSearch = debounce(function() {
            const search = document.getElementById('search').value;

            fetch(`?search=${search}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest' // Penting untuk mengidentifikasi AJAX request
                },
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('product-list').innerHTML = data;
                attachPaginationEvents(); // Re-attach event listeners
            })
            .catch(error => console.error('Error:', error));
        }, 300); // Delay 300ms

        // Attach AJAX event to pagination links
        function attachPaginationEvents() {
            const pagination = document.querySelector('.pagination');

            if (pagination) {
                pagination.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const url = this.getAttribute('href');
                        fetch(url, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                        })
                        .then(response => response.text())
                        .then(data => {
                            document.getElementById('product-list').innerHTML = data;
                            attachPaginationEvents(); // Re-attach event listeners
                        })
                        .catch(error => console.error('Error:', error));
                    });
                });
            }
        }

        // Initial attachment for pagination links on page load
        document.addEventListener('DOMContentLoaded', function() {
            attachPaginationEvents();
        });
    </script>
</x-app-layout>