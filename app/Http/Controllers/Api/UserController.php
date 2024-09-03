<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::select('id', 'name', 'email', 'level')->get();
            return response()->json($users);
        } catch (\Exception $e) {
            // Return error response
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'level'    => 'required|string|in:admin,user',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Mendapatkan timestamp saat ini
            $now = Carbon::now();

            // Simpan data ke tabel users dengan created_at dan updated_at
            $user = User::create([
                'name'       => $request->name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'level'      => $request->level,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            // Mengembalikan response sukses
            return response()->json([
                'success' => true,
                'message' => 'User created successfully!',
                'data'    => $user
            ], 201);

        } catch (\Exception $e) {
            // Mengembalikan response error
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

     // Fungsi show untuk menampilkan user berdasarkan id
     public function show($id)
     {
         try {
             // Cari user berdasarkan id dan ambil semua kolom
             $user = User::findOrFail($id);
             // Kembalikan response sukses dengan data user
             return response()->json([
                 'success' => true,
                 'message' => 'User found',
                 'data'    => $user
             ], 200);
 
         } catch (\Exception $e) {
             // Kembalikan response error jika user tidak ditemukan
             return response()->json([
                 'success' => false,
                 'message' => 'User not found!',
                 'error'   => $e->getMessage()
             ], 404);
         }
     }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'level'    => 'required|string|in:admin,user',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            // Temukan user berdasarkan ID
            $user = User::findOrFail($id);

            // Update data user
            $user->name = $request->name;
            $user->email = $request->email;
            // Hanya update password jika ada input baru
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->level = $request->level;
            $user->updated_at = Carbon::now();
            $user->save();

            // Mengembalikan response sukses
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'data'    => $user
            ], 200);

        } catch (\Exception $e) {
            // Mengembalikan response error
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user!',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Cari user berdasarkan ID
            $user = User::findOrFail($id);

            // Hapus user
            $user->delete();

            // Mengembalikan response sukses
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully!'
            ], 200);

        } catch (\Exception $e) {
            // Mengembalikan response error jika terjadi masalah
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
