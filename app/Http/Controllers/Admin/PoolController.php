<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Pool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $page = $request->input('page', 1);

        $pool = Pool::where('nama_pool', 'LIKE', "%$search%")
        ->paginate(10, ['*'], 'page', $page); // Mengatur jumlah item per halaman menjadi 10

        return view('layouts.admin.pool.index', [
            'pools' => $pool,
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
            'nama_pool' => 'required|unique:rutes',
            'alamat' => 'nullable',
            'phone' => 'nullable',
            'status' => 'nullable',
        ]);

        $pool = Pool::create([
            'nama_pool' => $request->nama_pool,
            'alamat' => $request->alamat,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pools created successfully', 'pools' => $pool], 201);
        }

        return redirect()->route('pool', compact('pool'))->with('success', 'Pool created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pool $pool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pool = Pool::findOrFail($id);
        return view('layouts.admin.pool.edit', compact('pool'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pool' => 'required|unique:pools,nama_pool,' . $id,
            'alamat' => 'nullable',
            'phone' => 'nullable',
            'status' => 'nullable',
        ]);

        // Temukan pool berdasarkan ID
        $pool = Pool::findOrFail($id);

        // Perbarui atribut pool
        $pool->update([
            'nama_pool' => $request->nama_pool,
            'alamat' => $request->alamat,
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pool updated successfully', 'pool' => $pool], 200);
        }

        // Gunakan nama rute yang benar untuk redirect
        return redirect()->route('pool.index')->with('success', 'Pool updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pool $pool)
    {
        //
    }
}
