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

        if (Auth::attempt($cred + ['is_active' => true], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Ujumbe tofauti kwa akaunti iliyozuiwa
        $blocked = User::where('email', $cred['email'])->where('is_active', false)->exists();
        return back()->withErrors(['email' => $blocked ? __('soko.errors.account_disabled') : __('soko.errors.login_failed')])->onlyInput('email');
    }

    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users',
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
