<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Address;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('address');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi data user
        $validatedUser = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        // Validasi data alamat - SESUAIKAN DENGAN FORM/VIEW
        $validatedAddress = $request->validate([
            'address_line_01'   => 'required|string|max:255',
            'address_line_02'   => 'nullable|string|max:255',
            'town_city'         => 'nullable|string|max:100',
            'district'          => 'nullable|string|max:100',
            'country'           => 'nullable|string|max:100',
            'postcode_zip'      => 'nullable|string|max:20',
        ]);

        // Update atau buat alamat - SESUAIKAN DENGAN NAMA KOLOM DI DATABASE
        $address = $user->address ?? new Address();
        $address->address_line_01 = $validatedAddress['address_line_01'];
        $address->address_line_02 = $validatedAddress['address_line_02'] ?? null;
        $address->town_city       = $validatedAddress['town_city'] ?? null;
        $address->district        = $validatedAddress['district'] ?? null;
        $address->country         = $validatedAddress['country'] ?? null;
        $address->postcode_zip    = $validatedAddress['postcode_zip'] ?? null;
        $address->save();

        // Update user
        $user->name       = $validatedUser['name'];
        $user->email      = $validatedUser['email'];
        $user->address_id = $address->id;

        if (isset($validatedUser['password']) && !empty(trim($validatedUser['password']))) {
            $user->password = Hash::make($validatedUser['password']);

            $user->save();

            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        }
    }
}
