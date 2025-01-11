<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data warna
        $colors = Color::all();

        // Tampilkan view dengan data warna
        return view('colors.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Tampilkan form tambah warna
        return view('colors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'color' => 'required|string|max:255',
        ]);

        // Simpan data warna ke database
        Color::create([
            'color' => $request->color,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('colors.index')->with('success', 'Color created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Color $color)
    {
        // Tampilkan form edit dengan data warna
        return view('colors.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Color $color)
    {
        // Validasi input
        $request->validate([
            'color' => 'required|string|max:255',
        ]);

        // Update data warna
        $color->update([
            'color' => $request->color,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('colors.index')->with('success', 'Color updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Color $color)
    {
        // Hapus data warna
        $color->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('colors.index')->with('success', 'Color deleted successfully!');
    }
}
