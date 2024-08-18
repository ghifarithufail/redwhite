<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $page = $request->input('page', 1);

        $provinsi = Provinsi::where('nama_provinsi', 'LIKE', "%$search%")
        ->paginate(10, ['*'], 'page', $page); // Mengatur jumlah item per halaman menjadi 10

        return view('layouts.admin.provinsi.index', [
            'provinsis' => $provinsi,
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
            'nama_provinsi' => 'required|string|min:3',
        ]);

        $provinsi = Provinsi::create([
            'nama_provinsi' => $request->nama_provinsi,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Provinsi created successfully', 'provinsi' => $provinsi], 201);
        }

        return redirect()->route('provinsi.index')->with('success', 'Provinsi created successfully');

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
    public function edit($id)
    {
        $provinsi = Provinsi::find($id);
        return view('layouts.admin.provinsi.edit', compact('provinsi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_provinsi' => 'required|string|min:3',
        ]);

        $provinsi = Provinsi::findOrFail($id);

        $provinsi->nama_provinsi = $request->nama_provinsi;
        $provinsi->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Provinsi updated successfully', 'provinsi' => $provinsi], 200);
        }

        return redirect()->route('provinsi.index')->with('success', 'Provinsi updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
