<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(
            User::select('id', 'name', 'email', 'role', 'status')
                ->orderByRaw("FIELD(role, 'super_admin', 'admin', 'admin_pegawai')")
                ->get()
        );
    }

    public function store(Request $request)
    {
        // karena sudah dibatasi middleware super_admin
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:super_admin,admin,admin_pegawai',
            'status'   => 'required|boolean',
        ]);

        return response()->json(
            User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => $data['role'],
                'status'   => $data['status'],
            ]),
            201
        );
    }

    // OPTIONAL (untuk edit user)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $id,
            'role'   => 'required|in:super_admin,admin,admin_pegawai',
            'status' => 'required|boolean',
        ]);

        $user->update($data);

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // optional: cegah hapus super admin sendiri
        if ($user->role === 'super_admin') {
            return response()->json([
                'message' => 'Super Admin tidak bisa dihapus'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }

}
