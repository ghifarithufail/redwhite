<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Jabatan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $page = $request->input('page', 1);

        $jabatan = Jabatan::where('nama_jabatan', 'LIKE', "%$search%")
        ->paginate(10, ['*'], 'page', $page); // Mengatur jumlah item per halaman menjadi 10

        return view('layouts.admin.jabatan.index', [
            'jabatans' => $jabatan,
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
        // Melakukan validasi data
        $validatedData = $request->validate([
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan',
            'kodejab' => 'nullable',
        ]);

        try {

            $jabatan = Jabatan::create([
                'nama_jabatan' => $validatedData['nama_jabatan'],
                'kodejab' => $validatedData['kodejab'],
            ]);

            return redirect()->route('jabatan')->with('success', 'Data jabatan berhasil ditambahkan!');

            } catch (\Exception $e) {
                return redirect()->route('jabatan')->with('error', 'Data jabatan gagal ditambahkan!');
                }
    }


    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $jabatan = Jabatan::findOrFail($id);

        return view('layouts.admin.jabatan.edit', compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
            // Melakukan validasi data
        $validatedData = $request->validate([
            'nama_jabatan' => 'required|unique:jabatans,nama_jabatan,' . $id,
            'kodejab' => 'nullable',
        ]);

        try {
            // Mengambil data jabatan berdasarkan ID
            $jabatan = Jabatan::findOrFail($id);

            // Memperbarui data jabatan dengan data yang baru
            $jabatan->update([
                'nama_jabatan' => $validatedData['nama_jabatan'],
                'kodejab' => $validatedData['kodejab'],
            ]);

            return redirect()->route('jabatan.index')->with('success', 'Data jabatan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('jabatan.index')->with('error', 'Data jabatan gagal diperbarui!');
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jabatan $jabatan)
    {
        //
    }
}
