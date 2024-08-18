<?php

namespace App\Http\Controllers\Hrd;

use App\Models\User;
use App\Models\Admin\Kota;
use App\Models\Hrd\Biodata;
use App\Models\Hrd\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BiodataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $per_page = $request->input('per_page') ?? 10;

        $users = User::all();
        $kota = Kota::all();

        $query = Biodata::with(['users', 'users.roles']);

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nik', 'like', '%' . $search . '%')
                    ->orWhereHas('users', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('users.roles', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $biodata = $query->paginate($per_page);

        return view('layouts.hrd.biodata.index',
        [
            'biodata' => $biodata,
            'kotas' => $kota,
            'users' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $kotas = Kota::all();

        return view('layouts.hrd.biodata.create',
        [
            'kotas' => $kotas,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nik' => 'required|unique:biodatas,nik',
            'nokk' => 'required|unique:biodatas,nokk',
            'kota_id' => 'required|exists:kotas,id',
            'tgl_lahir' => 'required|date',
            'nikah' => 'required',
            'agama' => 'required',
            'jenis' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Periksa apakah NIK sudah ada
            $existingBiodata = Biodata::where('nik', $request->nik)->first();
            if ($existingBiodata) {
                $existingUser = User::find($existingBiodata->user_id);
                if ($existingUser) {
                    return redirect()->back()->withErrors(['nik' => 'NIK sudah ada: ' . $existingUser->name])->withInput();
                } else {
                    return redirect()->back()->withErrors(['nik' => 'NIK sudah ada tetapi user tidak ditemukan.'])->withInput();
                }
            }

            // Mulai transaksi database
            DB::beginTransaction();

            Log::info('Validated Data: ' . json_encode($validatedData));

            // Simpan biodata dengan mengaitkannya dengan user yang baru dibuat
            $biodata = Biodata::create([
                'nik' => $request->nik,
                'nokk' => $request->nokk,
                'nama' => $request->nama,
                'kota_id' => $request->kota_id,
                'tgl_lahir' => $request->tgl_lahir,
                'nikah' => $request->nikah,
                'agama' => $request->agama,
                'jenis' => $request->jenis,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'user_id' => $request->user_id,
            ]);

            Log::info('Biodata Created: ' . json_encode($biodata->toArray()));

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();

                Log::info('Uploading image: ' . $filename);

                // Simpan gambar ke storage/app/public/images
                $path = $image->storeAs('images', $filename, 'public');

                Log::info('Image stored at: ' . $path);

                // Simpan nama file gambar ke model
                $biodata->image = $filename;
                $biodata->save();

                Log::info('Image filename saved to biodata: ' . $filename);
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('biodata.index')->with('success', 'Biodata and user have been created successfully!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            Log::error('Error creating biodata: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan biodata.'])->withInput();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($nik)
    {
        $biodata = Biodata::where('nik', $nik)->firstOrFail();

        // Define image path based on model's image property
        $imagePath = null;
        if (isset($biodata->image) && !empty($biodata->image)) {
            $imagePath = asset('storage/images/' . $biodata->image);  // Assuming 'public' disk is used
        }

        return view('layouts.hrd.biodata.show', compact('biodata', 'imagePath'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $biodata = Biodata::findOrFail($id);
        $users = User::all();
        $kotas = Kota::all();

        return view('layouts.hrd.biodata.edit', [
            'biodata' => $biodata,
            'kotas' => $kotas,
            'users' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nik' => 'required|unique:biodatas,nik,' . $id,
            'nokk' => 'required|unique:biodatas,nokk,' . $id,
            'kota_id' => 'required|exists:kotas,id',
            'tgl_lahir' => 'required|date',
            'nikah' => 'required',
            'agama' => 'required',
            'jenis' => 'required',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'user_id' => 'required|exists:users,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            // Mulai transaksi database
            DB::beginTransaction();

            // Ambil biodata berdasarkan ID
            $biodata = Biodata::findOrFail($id);

            // Update data biodata
            $biodata->update([
                'nik' => $request->nik,
                'nokk' => $request->nokk,
                'nama' => $request->nama,
                'kota_id' => $request->kota_id,
                'tgl_lahir' => $request->tgl_lahir,
                'nikah' => $request->nikah,
                'agama' => $request->agama,
                'jenis' => $request->jenis,
                'alamat' => $request->alamat,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'user_id' => $request->user_id,
            ]);

            // Proses gambar jika ada
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '.' . $image->getClientOriginalExtension();

                // Simpan gambar ke storage/app/public/images
                $path = $image->storeAs('images', $filename, 'public');

                // Simpan nama file gambar ke model
                $biodata->image = $filename;
                $biodata->save();
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('biodata.index')->with('success', 'Biodata has been updated successfully!');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            Log::error('Error updating biodata: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating biodata.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $biodata = Biodata::findOrFail($id);
        $biodata->delete();

        return redirect()->route('biodata.index')->with('success', 'Biodata has been deleted successfully!');
    }
}
