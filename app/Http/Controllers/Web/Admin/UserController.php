<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Stan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }
        
        $perPage = $request->input('per_page', 5);
        $users = $query->paginate($perPage);
        
        if ($request->ajax()) {
            $html = view('admin.partials.users-table', compact('users'))->render();
            $pagination = view('admin.partials.pagination', compact('users'))->render();
            
            return response()->json([
                'users' => $html,
                'pagination' => $pagination
            ]);
        }
        
        return view('admin.dashboard', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|min:5|max:20|unique:users|alpha_dash',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|in:siswa,admin_stan,admin',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'nama_stan' => 'required_if:role,admin_stan',
                'nama_pemilik' => 'required_if:role,admin_stan',
                'telp' => [
                    'required_if:role,admin_stan,siswa',
                    'regex:/^62[0-9]{9,15}$/',  // Format: 62 followed by 9-15 digits
                ],
                'alamat' => 'required_if:role,siswa',
            ]);

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($request->role === 'siswa') {
                $fotoPath = 'user/picture/default-profile.png'; // Default image path
                
                if ($request->hasFile('foto')) {
                    $file = $request->file('foto');
                    $hashedName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                    $fotoPath = $file->storeAs('user/picture', $hashedName, 'public');
                }

                Siswa::create([
                    'nama_siswa' => $request->name,
                    'alamat' => $request->alamat,
                    'telp' => $request->telp,
                    'id_user' => $user->id,
                    'foto' => $fotoPath,
                ]);
            } elseif ($request->role === 'admin_stan') {
                Stan::create([
                    'nama_stan' => $request->nama_stan,
                    'nama_pemilik' => $request->nama_pemilik,
                    'telp' => $request->telp,
                    'id_user' => $user->id,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());  // Add logging
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|min:5|max:20|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8',
                'role' => 'required|in:siswa,admin_stan,admin',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'nama_stan' => 'required_if:role,admin_stan',
                'nama_pemilik' => 'required_if:role,admin_stan',
                'telp' => [
                    'required_if:role,admin_stan,siswa',
                    'regex:/^62[0-9]{9,15}$/',
                ],
                'alamat' => 'required_if:role,siswa',
            ]);

            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            if ($user->role === 'siswa' && $user->siswa) {
                if ($request->hasFile('foto')) {
                    if ($user->siswa->foto && $user->siswa->foto !== 'user/picture/default-profile.png') {
                        Storage::disk('public')->delete($user->siswa->foto);
                    }
                    $file = $request->file('foto');
                    $hashedName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                    $fotoPath = $file->storeAs('user/picture', $hashedName, 'public');
                    $user->siswa->foto = $fotoPath;
                }
                
                $user->siswa->update([
                    'nama_siswa' => $request->name,
                    'alamat' => $request->alamat,
                    'telp' => $request->telp,
                ]);
            } elseif ($user->role === 'admin_stan' && $user->stan) {
                $user->stan->update([
                    'nama_stan' => $request->nama_stan,
                    'nama_pemilik' => $request->nama_pemilik,
                    'telp' => $request->telp,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('User update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            // Check if trying to delete an admin
            if ($user->role === 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete admin users.'
                ], 400);
            }

            // Check for unfinished transactions for siswa
            if ($user->role === 'siswa' && $user->siswa) {
                $hasUnfinishedTransactions = $user->siswa->transaksi()
                    ->where('status', '!=', 'sampai')
                    ->exists();

                if ($hasUnfinishedTransactions) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete student account with unfinished transactions. Please wait until all transactions are completed.'
                    ], 400);
                }
            }

            // Check for unfinished transactions for admin_stan
            if ($user->role === 'admin_stan' && $user->stan) {
                $hasUnfinishedTransactions = $user->stan->transaksi()
                    ->where('status', '!=', 'sampai')
                    ->exists();

                if ($hasUnfinishedTransactions) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot delete stan admin account with unfinished transactions. Please wait until all transactions are completed.'
                    ], 400);
                }
            }

            // Begin transaction
            DB::beginTransaction();
            
            try {
                // Handle Siswa relation
                if ($user->role === 'siswa') {
                    if ($user->siswa) {
                        // Delete foto if it exists and is not the default
                        if ($user->siswa->foto && $user->siswa->foto !== 'user/picture/default-profile.png') {
                            Storage::disk('public')->delete($user->siswa->foto);
                        }
                        
                        // Delete only completed transactions
                        foreach ($user->siswa->transaksi()->where('status', 'sampai')->get() as $transaksi) {
                            $transaksi->detail_transaksi()->delete();
                            $transaksi->delete();
                        }
                        
                        $user->siswa->delete();
                    }
                }
                // Handle Stan relation
                elseif ($user->role === 'admin_stan') {
                    if ($user->stan) {
                        // Delete related menus and their discounts
                        foreach ($user->stan->menus as $menu) {
                            $menu->menu_diskon()->delete();
                            $menu->delete();
                        }
                        
                        // Delete only completed transactions
                        foreach ($user->stan->transaksi()->where('status', 'sampai')->get() as $transaksi) {
                            $transaksi->detail_transaksi()->delete();
                            $transaksi->delete();
                        }
                        
                        $user->stan->delete();
                    }
                }

                // Finally delete the user
                $user->delete();
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'User and all related data deleted successfully'
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            \Log::error('User deletion error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(User $user)
    {
        $userData = $user->toArray();
        
        if ($user->role === 'siswa') {
            $userData['siswa'] = $user->siswa;
        } elseif ($user->role === 'admin_stan') {
            $userData['stan'] = $user->stan;
        }
        
        return response()->json($userData);
    }
}
