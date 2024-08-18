<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin\Pool;
use App\Models\Admin\Rute;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $page = $request->input('page', 1);

        // Ambil semua data pool
        $pool = Pool::all();

        // Query rute dengan memuat relasi pools dan filter berdasarkan nama_rute atau nama_pool
        $rutes = Rute::with('pools')
                    ->whereHas('pools', function($query) use ($search) {
                        $query->where('nama_pool', 'LIKE', "%$search%");
                    })
                    ->orWhere('nama_rute', 'LIKE', "%$search%")
                    ->paginate(10, ['*'], 'page', $page);

        return view('layouts.admin.rute.index', [
            'rutes' => $rutes,
            'pools' => $pool, // sertakan variabel $pools saat merender view
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
        $pools = Pool::all();

        $request->validate([
            'kode_rute' => 'required|unique:rutes',
            'nama_rute' => 'nullable',
            'jenis' => 'nullable',
            'stdrit' => 'nullable',
            'pool_id' => 'required|exists:pools,id',
            'status' => 'nullable',
        ]);

        $rute = Rute::create([
            'kode_rute' => $request->kode_rute,
            'nama_rute' => $request->nama_rute,
            'jenis' => $request->jenis,
            'stdrit' => $request->stdrit,
            'pool_id' => $request->pool_id,
            'status' => $request->status,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Rute created successfully', 'rute' => $rute], 201);
        }

        return redirect()->route('rute.index', compact('pools'))->with('success', 'Rute created successfully');
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
        $rute = Rute::findOrFail($id);
        $pools = Pool::all();
        return view('layouts.admin.rute.edit', compact('rute', 'pools'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_rute' => 'required|unique:rutes,kode_rute,'.$id,
            'nama_rute' => 'nullable',
            'jenis' => 'nullable',
            'stdrit' => 'nullable',
            'pool_id' => 'required|exists:pools,id',
            'status' => 'nullable',
        ]);

        $rute = Rute::findOrFail($id);
        $rute->update($request->all());

        return redirect()->route('rute.index')->with('success', 'Rute updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
