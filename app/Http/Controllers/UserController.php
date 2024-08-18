<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $users = User::with(['roles' => function ($query) {
            $query->select('name');
        }])
            ->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('username', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%');
            })
            ->paginate(10);

        $roles = Role::all();

        return view('layouts.admin.user.index', ['users' => $users, 'roles' => $roles]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('layouts.admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $validatedData = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|min:6',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = new User();
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // Ambil role pertama dari pilihan peran
        $selectedRole = reset($validatedData['roles']);
        $user->role_id = $selectedRole;

        // Set status dan role_id secara manual
        $user->status = 'Active'; // Atau nilai yang sesuai
        $roles = Role::whereIn('id', $validatedData['roles'])->get();
        $user->roles()->sync($roles);
        $user->save();

        return redirect()->route('users')->with('success', 'User created successfully.');
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
    public function edit($identifier)
    {
        $user = User::where('id', $identifier)
                ->orWhere('name', $identifier)
                ->firstOrFail();
        $roles = Role::all();

        return view('layouts.admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required',
            'status' => 'required|in:Active,Inactive,Disable',
            'password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|min:6|same:password',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Update data user
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->status = $validatedData['status'];

        // Periksa apakah password ada, jika ya, hash password baru
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->roles()->sync($validatedData['roles']);
        $user->save();

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
