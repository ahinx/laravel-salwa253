<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    // Menampilkan halaman pengaturan item_code_start
    public function index()
    {
        $setting = DB::table('settings')->where('key', 'item_code_start')->first();
        return view('settings.index', compact('setting'));
    }

    // Menyimpan pengaturan item_code_start
    public function update(Request $request)
    {
        $request->validate([
            'item_code_start' => 'required|numeric|min:0', // Validasi hanya menerima angka >= 0
        ]);

        $key = 'item_code_start';

        // Cek apakah key sudah ada
        $exists = DB::table('settings')->where('key', $key)->exists();

        if ($exists) {
            // Jika key ada, lakukan update
            $updated = DB::table('settings')
                ->where('key', $key)
                ->update(['value' => $request->item_code_start]);

            if ($updated) {
                return redirect()->route('settings.index')->with('success', 'Item code start updated successfully');
            } else {
                return redirect()->route('settings.index')->with('error', 'Failed to update item code start.');
            }
        } else {
            // Jika key tidak ada, lakukan insert
            $inserted = DB::table('settings')->insert([
                'key' => $key,
                'value' => $request->item_code_start,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($inserted) {
                return redirect()->route('settings.index')->with('success', 'Item code start added successfully');
            } else {
                return redirect()->route('settings.index')->with('error', 'Failed to add item code start.');
            }
        }
    }
}
