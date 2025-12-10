<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showregis()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'address'  => 'nullable',
            'phone'    => 'nullable',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'address'  => $request->address,
            'phone'    => $request->phone,
            'role'     => 'user', // default user
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
    }


    // SHOW LOGIN PAGE
   public function showlog()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect()->route('dashboard')->with('success', 'Welcome Admin!');
        }

        return redirect()->route('dashboard.user')->with('success', 'Welcome!');
    }
        

       return back()->with('error', 'Email atau password salah');

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    public function editAccount()
    {
        $user = Auth::user();
        return view('auth.settings', compact('user'));
    }

   public function updateAccount(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name'     => 'required|string|max:255',
        'address'  => 'required|string|max:255',
        'phone'    => 'required|string|max:20',
        'password' => 'nullable|string|min:8|confirmed', // password optional
    ]);

    // Update data user
    $user->name    = $request->name;
    $user->address = $request->address;
    $user->phone   = $request->phone;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('account.edit')->with('success', 'Account updated successfully!');
}

}