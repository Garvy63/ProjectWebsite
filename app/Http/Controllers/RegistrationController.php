<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;


class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('login.registration');
    }


    public function register(Request $request)
    {
        // 1. Validasi semua data, termasuk field baru
        $request->validate([
            // Validasi untuk tabel users
            'email' => 'required|email|unique:users',
            'name' => 'required|string|min:3', // Username
            'password' => 'required|string|min:6|confirmed', // Menambahkan 'confirmed' untuk password_confirmation


            // Validasi untuk tabel customers
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
        ]);


        // 2. Membuat record di tabel users
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'role' => 'user', // Mempertahankan field 'role'
        ]);


        // 3. Membuat record di tabel customers (Menggunakan ID dari user yang baru dibuat)
        Customer::create([
            'id' => $user->id, // Kunci asing ke tabel users
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            // Menggunakan operator null coalescing (??) untuk field opsional
            'phone_number' => $request->phone_number ?? null,
            'company_name' => $request->company_name ?? null,
        ]);


        // Opsional: Langsung login user setelah registrasi (jika ini adalah proses registrasi otentikasi)
        // auth()->login($user);


        return redirect()->back()->with('success', 'Registrasi berhasil! Silakan login.');
    }
}


