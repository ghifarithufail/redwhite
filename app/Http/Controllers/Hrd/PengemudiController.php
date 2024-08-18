<?php

namespace App\Http\Controllers\Hrd;


use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin\Pool;
use App\Models\Admin\Rute;
use Illuminate\Http\Request;
use App\Models\Hrd\Pengemudi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class PengemudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perpage', 10);

        $query = Pengemudi::with(['users.biodata', 'biodatas', 'pools', 'rutes'])
            ->leftJoin('biodatas', 'pengemudis.user_id', '=', 'biodatas.user_id')
            ->select('pengemudis.*')
            ->orderBy('biodatas.nik'); // Sort by NIK

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nopengemudi', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('users', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%');
                        })
                        ->orWhere('biodatas.nik', 'LIKE', '%' . $search . '%')
                        ->orWhereHas('rutes', function ($q) use ($search) {
                            $q->where('kode_rute', 'LIKE', '%' . $search . '%');
                        });
                });
            }

        $pengemudis = $query->paginate($perPage);

        return view('layouts.hrd.pengemudi.index', [
            'pengemudis' => $pengemudis,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $rutes = Rute::all();
        $pools = Pool::all();

        return view('layouts.hrd.pengemudi.create', compact('users', 'rutes','pools'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'rute_id' => 'required|exists:rutes,id',
                'pool_id' => 'required|exists:pools,id',
                'tanggal_kp' => 'required|date',
                'tgl_masuk' => 'required|date',
                'nosim' => 'required|string',
                'jenis_sim'=> 'required|string',
                'tgl_sim'=> 'required|date',
                'nojamsostek'=> 'nullable',
                'status' => 'required|string',
                'ket_pengemudi' => 'nullable',
            ]);

            if ($request->has('nopengemudi')) {
                $nopengemudiBaru = $request->nopengemudi;
            } else {
                $tahunBulan = Carbon::now()->format('ym');
                $nomorUrutTerakhir = Pengemudi::where('nopengemudi', 'like',  $tahunBulan . '%')->max('nopengemudi');

                if (!$nomorUrutTerakhir) {
                    $nomorUrutTerakhir = 0;
                }

                $nomorUrutBaru = $nomorUrutTerakhir + 1;
                $nopengemudiBaru = "P2". $tahunBulan . str_pad($nomorUrutBaru, 5, '0', STR_PAD_LEFT);
            }

            $pengemudi = Pengemudi::create([
                'user_id' => $request->user_id,
                'nopengemudi' => $nopengemudiBaru,
                'rute_id' => $request->rute_id,
                'pool_id' => $request->pool_id,
                'tanggal_kp' => $request->tanggal_kp,
                'tgl_masuk' => $request->tgl_masuk,
                'nosim' => $request->nosim,
                'jenis_sim'=>  $request->jenis_sim,
                'tgl_sim'=> $request->tgl_sim,
                'nojamsostek'=> $request->nojamsostek,
                'status'=> $request->status,
                'ket_pengemudi' => $request->ket_pengemudi,
            ]);

            DB::commit();
            return redirect()->route('pengemudi.index')->with('success', 'Pengemudi have been created successfully!');
        } catch (\Exception $e) {
            // Log the exception message for debugging
            Log::error('Error creating pengemudi: '.$e->getMessage());

            if ($e->getCode() == 1062) { // Duplicate entry error code
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, 'nopengemudi') !== false) {
                    return redirect()->back()->withErrors(['nopengemudi' => 'Nomor Pengemudi sudah terdaftar.'])->withInput();
                } else if (strpos($errorMessage, 'nosim') !== false) {
                    return redirect()->back()->withErrors(['nosim' => 'Nomor SIM sudah terdaftar.'])->withInput();
                // } else if (strpos($errorMessage, 'nojamsostek') !== false) {
                //     return redirect()->back()->withErrors(['nojamsostek' => 'Nomor Jamsostek sudah terdaftar.'])->withInput();
                }
            }
        }
            // Log the exception for other errors
            Log::error('Error creating pengemudi: '.$e->getMessage().' in file '.$e->getFile().' on line '.$e->getLine());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan ada double data atau tidak di isi colomnya'])->withInput();
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
        $pengemudi = Pengemudi::findOrFail($id);
        $users = User::all();
        $rutes = Rute::all();
        $pools = Pool::all();

        return view('layouts.hrd.pengemudi.edit',[
            'pengemudi' => $pengemudi,
            'users' => $users,
            'rutes' => $rutes,
            'pools' => $pools,
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'rute_id' => 'required|exists:rutes,id',
                'pool_id' => 'required|exists:pools,id',
                'tgl_masuk' => 'required|date',
                'tanggal_kp' => 'required|date',
                'nosim' => 'required',
                'jenis_sim' => 'required',
                'tgl_sim' => 'required|date',
                'nojamsostek' => 'nullable|string',
                'status' => 'required|string',
                'ket_pengemudi' => 'nullable',
            ]);

            // Cari pengemudi berdasarkan ID
            $pengemudi = Pengemudi::findOrFail($id);

            if ($request->has('nopengemudi')) {
                // Nomor induk diatur manual
                $nopengemudiBaru = $request->nopengemudi;
            } else {

                // Dapatkan tahun dan bulan saat ini
                $tahunBulan = Carbon::now()->format('ym');

                // Dapatkan nomor urut terakhir
                $nomorUrutTerakhir = Pengemudi::where('nopengemudi', 'like',  $tahunBulan . '%')->max('nopengemudi');

                // Jika tidak ada nomor urut sebelumnya, beri nomor urut awal
                if (!$nomorUrutTerakhir) {
                    $nomorUrutTerakhir = 0;
                }

                $nomorUrutBaru = $nomorUrutTerakhir + 1;

                // Buat nopengemudi baru dengan format yang diinginkan
                $nopengemudiBaru = "P2". $tahunBulan . str_pad($nomorUrutBaru, 5, '0', STR_PAD_LEFT);
            }

            // Perbarui atribut-atribut pengemudi dengan data baru
            $pengemudi->update([
                'user_id' => $request->user_id,
                'nopengemudi' => $nopengemudiBaru,
                'rute_id' => $request->rute_id,
                'pool_id' => $request->pool_id,
                'tgl_masuk' => $request->tgl_masuk,
                'tanggal_kp' => $request->tanggal_kp,
                'nosim' => $request->nosim,
                'jenis_sim' => $request->jenis_sim,
                'tgl_sim' => $request->tgl_sim,
                'nojamsostek' => $request->nojamsostek,
                'status'=> $request->status,
                'ket_pengemudi' => $request->ket_pengemudi,
            ]);

            return redirect()->route('pengemudi.index')->with('success', 'Pengemudi berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui pengemudi.'])->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
