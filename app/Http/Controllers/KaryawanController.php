<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = User::where('level', 'karyawan')->get();

        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:20', 'unique:users,username'],
            'email'    => ['nullable', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' akan cek field 'password_confirmation'
        ]);
        
        try {
            User::create([
                'nama'     => $data['nama'],
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'level'    => $data['level'] ?? 'karyawan', // Gunakan nilai dari form atau default ke 'karyawan'
            ]);

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $karyawan = User::findOrFail($id);

        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:20', 'unique:users,username'],
            'email'    => ['nullable', 'string', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'level'    => ['nullable', 'string', 'in:karyawan,admin'],
        ]);

        $karyawan = User::findOrFail($id);
        
        try {
            $karyawan->update([
                'nama'     => $data['nama'],
                'username' => $data['username'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'level'    => $data['level'] ?? 'karyawan',
            ]);

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diupdate');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = User::findOrFail($id);

        if (Auth::id() === $karyawan->id) {
            return redirect()
                ->back()
                ->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }

        if ($karyawan->level === 'admin') {
            return redirect()
                ->back()
                ->with('error', 'Tidak bisa menghapus akun admin.');
        }

        $karyawan->delete();

        return redirect()
            ->route('karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}
