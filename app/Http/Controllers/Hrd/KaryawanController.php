<?php

namespace App\Http\Controllers\Hrd;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Admin\Pool;
use App\Models\Hrd\Biodata;
use App\Models\Hrd\Karyawan;
use Illuminate\Http\Request;
use App\Models\Admin\Jabatan;
use App\Http\Controllers\Controller;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perpage', 10);

        $query = Karyawan::with(['users.biodata', 'biodatas', 'pools', 'jabatans'])
        ->leftJoin('biodatas', 'karyawans.user_id', '=', 'biodatas.user_id')
        ->select('karyawans.*')
        ->orderBy('biodatas.nik'); // Sort by NIK

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('noinduk', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('users', function ($q) use ($search) {
                        $q->where('name', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhere('biodatas.nik', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('jabatans', function ($q) use ($search) {
                        $q->where('nama_jabatan', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('pools', function ($q) use ($search) {
                        $q->where('nama_pool', 'LIKE', '%' . $search . '%');
                    });
            });
        }


        $karyawans = $query->paginate($perPage);

        return view('layouts.hrd.karyawan.index', [
            'karyawans' => $karyawans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $biodatas = Biodata::all();
        $users = User::all();
        $jabatans = Jabatan::all();
        $pools = Pool::all();

        return view('layouts.hrd.karyawan.create', [
            'biodatas' => $biodatas,
            'pools' => $pools,
            'jabatans' => $jabatans,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'pool_id' => 'required|exists:pools,id',
            'tanggal_kp' => 'required|date',
            'tgl_masuk' => 'required|date',
            'keterangan' => 'nullable',
        ]);

        // Tentukan apakah nomor induk harus dihasilkan otomatis atau diatur manual
        if ($request->has('noinduk')) {
            // Nomor induk diatur manual
            $noindukBaru = $request->noinduk;
        } else {
            // Dapatkan tahun dan bulan saat ini
            $tahunBulan = Carbon::now()->format('ym');

            // Dapatkan nomor urut terakhir
            $nomorUrutTerakhir = Karyawan::where('noinduk', 'like',  $tahunBulan . '%')->max('noinduk');

            // Jika tidak ada nomor urut sebelumnya, beri nomor urut awal
            if (!$nomorUrutTerakhir) {
                $nomorUrutTerakhir = 0;
            }

            // Tambahkan 1 ke nomor urut terakhir
            $nomorUrutBaru = $nomorUrutTerakhir + 1;

            // Buat noinduk baru dengan format yang diinginkan
            $noindukBaru =  $tahunBulan . str_pad($nomorUrutBaru, 5, '0', STR_PAD_LEFT);
        }

        // Simpan biodata dengan mengaitkannya dengan user yang baru dibuat
        $karyawan = Karyawan::create([
            'user_id' => $request->user_id,
            'noinduk' => $noindukBaru,
            'jabatan_id' => $request->jabatan_id,
            'pool_id' => $request->pool_id,
            'tanggal_kp' => $request->tanggal_kp,
            'tgl_masuk' => $request->tgl_masuk,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan have been created successfully!');
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
        $karyawan = Karyawan::findOrFail($id);
        $biodatas = Biodata::all();
        $users = User::all();
        $jabatans = Jabatan::all();
        $pools = Pool::all();

        return view('layouts.hrd.karyawan.edit', [
            'karyawan' => $karyawan,
            'biodatas' => $biodatas,
            'pools' => $pools,
            'jabatans' => $jabatans,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jabatan_id' => 'required|exists:jabatans,id',
            'pool_id' => 'required|exists:pools,id',
            'tanggal_kp' => 'required|date',
            'tgl_masuk' => 'required|date',
            'keterangan' => 'nullable',
        ]);

        // Dapatkan karyawan yang akan diperbarui
        $karyawan = Karyawan::findOrFail($id);

        // Tentukan apakah nomor induk harus dihasilkan otomatis atau diatur manual
        if ($request->has('noinduk')) {
            // Nomor induk diatur manual
            $noindukBaru = $request->noinduk;
        } else {
            // Buat noinduk baru secara otomatis

            // Dapatkan tahun dan bulan saat ini
            $tahunBulan = Carbon::now()->format('ym');

            // Dapatkan nomor urut terakhir
            $nomorUrutTerakhir = Karyawan::where('noinduk', 'like',  $tahunBulan . '%')->max('noinduk');

            // Jika tidak ada nomor urut sebelumnya, beri nomor urut awal
            if (!$nomorUrutTerakhir) {
                $nomorUrutTerakhir = 0;
            }

            // Tambahkan 1 ke nomor urut terakhir
            $nomorUrutBaru = $nomorUrutTerakhir + 1;

            // Buat noinduk baru dengan format yang diinginkan
            $noindukBaru =  $tahunBulan . str_pad($nomorUrutBaru, 5, '0', STR_PAD_LEFT);
        }

        // Perbarui data karyawan
        $karyawan->update([
            'user_id' => $request->user_id,
            'noinduk' => $noindukBaru,
            'jabatan_id' => $request->jabatan_id,
            'pool_id' => $request->pool_id,
            'tanggal_kp' => $request->tanggal_kp,
            'tgl_masuk' => $request->tgl_masuk,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan has been updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
