<?php

namespace App\Http\Controllers\Cso;

use App\Models\Cso\Tujuan;
use App\Models\TypeArmada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class TujuanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $per_page = $request->input('per_page', 20);

        $query = Tujuan::query();
        $typearmadas = TypeArmada::all();
        if (!empty($search)) {
            $query->where('nama_tujuan', 'like', '%' . $search . '%')
                ->orWhere('type_bus', 'like', '%' . $search . '%');
        }

        $tujuans = $query->paginate($per_page);

        return view('layouts.cso.tujuan.index', compact('tujuans','typearmadas', 'search', 'per_page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typearmadas = TypeArmada::all();
        return view('layouts.cso.tujuan.create', compact('typearmadas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255',
            'pemakaian' => 'required|string|max:255',
            'type_bus' => 'required|exists:type_armadas,id',
            'harga_std' => 'required|string|max:255',
        ]);

        try {
            $hargaStd = (float) str_replace('.', '', $request->input('harga_std'));

            Tujuan::create([
                'nama_tujuan' => $request->nama_tujuan,
                'pemakaian' => $request->pemakaian,
                'type_bus' => $request->type_bus,
                'harga_std' => $hargaStd,
            ]);

            return redirect()->route('tujuan.index')->with('success', 'Tujuan created successfully');
        } catch (\Exception $e) {
            Log::error('Error storing Tujuan:', $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create Tujuan'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tujuan = Tujuan::findOrFail($id);
        $typearmadas = TypeArmada::all();

        return view('layouts.cso.tujuan.edit', compact('tujuan','typearmadas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_tujuan' => 'required|string|max:255',
            'pemakaian' => 'required|string|max:255',
            'type_bus' => 'required|exists:type_armadas,id',
            'harga_std' => 'required|string|max:255', // Sesuaikan jenis validasi dengan kebutuhan
        ]);

        $tujuan = Tujuan::findOrFail($id);

        // Menghapus titik dari input harga
        $hargaStd = str_replace('.', '', $request->input('harga_std'));

        // Konversi string ke float untuk penyimpanan dalam database
        $tujuan->harga_std = (float) $hargaStd;

        $tujuan->nama_tujuan = $request->nama_tujuan;
        $tujuan->pemakaian = $request->pemakaian;
        $tujuan->type_bus = $request->type_bus;

        $tujuan->save();

        return redirect()->route('tujuan.index')->with('success', 'Tujuan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
