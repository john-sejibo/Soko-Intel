<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }

    public function login(Request $request)
    {
        $cred = $request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($cred, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        return back()->withErrors(['email' => 'Email au password si sahihi.'])->onlyInput('email');
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:buyer,seller', // admin hawezi kujisajili
        ]);
        $user = User::create($data);
        Auth::login($user);
        return redirect($user->role === 'seller' ? '/seller' : '/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
