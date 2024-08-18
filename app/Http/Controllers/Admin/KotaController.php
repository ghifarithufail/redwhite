<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Kota;
use App\Models\Admin\Provinsi;
use Illuminate\Http\Request;

class KotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $page = $request->input('page', 1);

        $kota = Kota::with('provinsis')
            ->whereHas('provinsis', function ($query) use ($search) {
                $query->where('nama_provinsi', 'LIKE', "%$search%");
            })
            ->orWhere('nama_kota', 'LIKE', "%$search%")
            ->paginate(10, ['*'], 'page', $page);

        // Retrieve all provinces
        $provinsis = Provinsi::all();

        return view('layouts.admin.kota.index', [
            'kotas' => $kota,
            'provinsis' => $provinsis, // Pass the provinsis data to the view
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kota' => 'required|string|min:3',
            'provinsi_id' => 'required',
        ]);

        $kota = Kota::create([
            'nama_kota' => $request->nama_kota,
            'provinsi_id' => $request->provinsi_id,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kota created successfully', 'kotas' => $kota], 201);
        }

        return redirect()->route('kota.index')->with('success', 'Kota created successfully')->with('provinsis', Provinsi::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kota = Kota::findOrFail($id);
        $provinsis = Provinsi::all();
        return view('layouts.admin.kota.edit', compact('kota', 'provinsis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kota' => 'required|string|min:3',
            'provinsi_id' => 'required',
        ]);

        $kota = Kota::findOrFail($id);
        $kota->update($request->all());

        return redirect()->route('kota.index')->with('success', 'Kota updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
