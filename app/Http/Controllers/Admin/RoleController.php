<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $page = $request->input('page', 1);

        // Mengambil data role dengan memperhitungkan pencarian dan menggunakan paginate
        $roles = Role::where('name', 'LIKE', "%$search%")
                    ->paginate(10, ['*'], 'page', $page); // Mengatur jumlah item per halaman menjadi 10

        // Mendapatkan semua permissions
        $permissions = Permission::all();

        return view('layouts.admin.role.index', compact('roles', 'permissions'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        return view('layouts.admin.role.create', compact('permissions'));
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
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array', // Ubah validasi untuk permissions menjadi array
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        // Jika permissions dipilih dalam bentuk array, Anda dapat langsung menyimpannya
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
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
    public function edit(string $id)
    {
        $role = Role::findById($id);
        $permissions = Permission::all();

        if (!$role) {
            abort(404); // Jika role tidak ditemukan, tampilkan halaman 404
        }

        return view('layouts.admin.role.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $role = Role::findById($id);

        $request->validate([
            'name' => 'required|unique:roles,name,'.$role->id,
            'permissions' => 'required|array',
        ]);

        $role->name = $request->name;
        $role->save();

        // Hapus izin yang sebelumnya terkait dengan peran
        $role->permissions()->detach();

        // Tambahkan izin yang baru dipilih
        foreach ($request->permissions as $permissionId) {
            $permission = Permission::findById($permissionId);
            $role->permissions()->attach($permission);
        }

        // set flash message
        session()->flash('success', 'Role updated successfully');

        return redirect()->route('roles.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        User::where('role_id', $role->id)->update(['role_id' => null]);

        // Hapus role
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus.');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
}
