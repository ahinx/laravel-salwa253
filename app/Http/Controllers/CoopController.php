<?php

namespace App\Http\Controllers;

use App\Models\Coop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class CoopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all coops from the database
        $coops = Coop::all();

        // Return view with the list of coops
        return view('coops.index', compact('coops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return the form view for creating a new coop
        return view('coops.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'coop_name' => 'required|string|max:255',
        ]);

        // Create a new coop
        Coop::create($request->only('coop_name'));

        // Redirect back with a success message
        return redirect()->route('coops.index')->with('success', 'Coop created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Coop $coop)
    {
        // Show details of a single coop
        return view('coops.show', compact('coop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coop $coop)
    {
        // Return the edit form view
        return view('coops.edit', compact('coop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coop $coop)
    {
        // Validate input
        $request->validate([
            'coop_name' => 'required|string|max:255',
        ]);

        // Update the coop
        $coop->update($request->only('coop_name'));

        // Redirect back with a success message
        return redirect()->route('coops.index')->with('success', 'Coop updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coop $coop)
    {
        // Delete the coop
        $coop->delete();

        // Redirect back with a success message
        return redirect()->route('coops.index')->with('success', 'Coop deleted successfully.');
    }
}
