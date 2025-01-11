<x-app-layout>
    <div class="container mx-auto px-6 py-6">
        <h2 class="text-2xl font-semibold text-gray-800">Kode Item</h2>

        <form method="POST" action="{{ route('settings.update') }}" class="mt-6 space-y-6">
            @csrf
            <div class="flex flex-col">
                <p>Awalan kode item ini hanya di setting satu kali ketika data masih kosong, barkui urut.</p>
                <input type="number" name="item_code_start" id="item_code_start"
                    value="{{ isset($setting->value) ? $setting->value : '' }}"
                    class="mt-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-3 px-6 rounded-lg mt-6">Save Settings</button>
        </form>
    </div>
</x-app-layout>