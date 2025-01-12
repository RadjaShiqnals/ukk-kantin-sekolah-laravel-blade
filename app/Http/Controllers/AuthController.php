<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if user exists first
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors([
                    'email' => 'No account found with this email address.'
                ]);
        }

        // Attempt authentication
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            switch($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'admin_stan':
                    return redirect()->route('stan.dashboard');
                case 'siswa':
                    return redirect()->route('siswa.dashboard');
                default:
                    return redirect()->route('login')
                        ->withErrors([
                            'email' => 'Your account has an invalid role. Please contact administrator.'
                        ]);
            }
        }

        // If we get here, password was wrong
        return back()
            ->withInput($request->except('password'))
            ->withErrors([
                'password' => 'The password you entered is incorrect.'
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role === 'admin_stan') {
            return redirect()->route('stan.dashboard');
        } elseif ($user->role === 'siswa') {
            return redirect()->route('siswa.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
    }
}
