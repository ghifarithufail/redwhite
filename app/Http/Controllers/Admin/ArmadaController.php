<?php

namespace App\Http\Controllers\Admin;

use App\Models\Armada;
use App\Models\Admin\Pool;
use App\Models\Admin\Rute;
use App\Models\TypeArmada;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArmadaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $search = $request->input('search');
    $page = $request->input('page', 1);

    $pools = Pool::get();
    $rutes = Rute::get();
    $typearmadas = TypeArmada::get();

    $armadas = Armada::with('rutes', 'pools')
        ->where('nobody', 'like', '%' . $search . '%')
        ->orWhere('nopolisi', 'like', '%' . $search . '%')
        ->orWhere('nochassis', 'like', '%' . $search . '%')
        ->orWhere('nomesin', 'like', '%' . $search . '%')
        ->orWhereHas('rutes', function ($query) use ($search) {
            $query->where('kode_rute', 'like', '%' . $search . '%');
        })
        ->orWhereHas('rutes.pools', function ($query) use ($search) {
            $query->where('nama_pool', 'like', '%' . $search . '%');
        })
        ->paginate(10, ['*'], 'page', $page);

        return view('layouts.admin.armada.index', [
            'armadas' => $armadas,
            'rutes' => $rutes,
            'pools' => $pools,
            'typearmadas' => $typearmadas
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
            'nobody' => 'required',
            'nochassis' => 'required',
            'nomesin' => 'required',
            'nopolisi' => 'required',
            'rute_id' => 'required|exists:rutes,id',
            'merk' => 'required',
            'tahun' => 'required|integer',
            'jenis' => 'required',
            'seat' => 'required|integer',
            'kondisi' => 'required',
            'type_id' => 'required|exists:type_armadas,id',
            'keterangan' => 'nullable',
        ]);

         // Cek apakah nomor body sudah ada dalam database
        if (Armada::where('nobody', $request->nobody)->exists()) {
            // Jika nobody sudah ada, beri pesan kesalahan
            return redirect()->back()->withInput()->withErrors(['nobody' => 'Nomor Body sudah di ada']);
        }

        $armada = Armada::create([
            'nobody' => $request->nobody,
            'nochassis'=> $request->nochassis,
            'nomesin'=> $request->nomesin,
            'nopolisi'=> $request->nopolisi,
            'rute_id'=> $request->rute_id,
            'merk'=> $request->merk,
            'tahun'=> $request->tahun,
            'jenis'=> $request->jenis,
            'seat'=> $request->seat,
            'kondisi'=> $request->kondisi,
            'type_id'=> $request->type_id,
            'keterangan'=> $request->keterangan,
        ]);

        return redirect()->route('armada.index')->with('success', 'Armada created successfully');
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
        $armada = Armada::findOrFail($id);
        $rutes = Rute::all(); // Mengambil semua data rute
        $typearmadas = TypeArmada::get();
        return view('layouts.admin.armada.edit', compact('armada', 'rutes','typearmadas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nobody' => 'required',
            'nochassis' => 'required',
            'nomesin' => 'required',
            'nopolisi' => 'required',
            'rute_id' => 'required|exists:rutes,id',
            'merk' => 'required',
            'tahun' => 'required|integer',
            'jenis' => 'required',
            'seat' => 'required|integer',
            'kondisi' => 'required',
            'type_id' => 'required|exists:type_armadas,id',
            'keterangan' => 'nullable',
        ]);

        $armada = Armada::findOrFail($id);
        $armada->update($request->all());

        return redirect()->route('armada.index')->with('success', 'Armada updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
