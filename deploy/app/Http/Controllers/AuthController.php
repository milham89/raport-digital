<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Student;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route('dashboard');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = $request->input('login');
        $password   = $request->input('password');

        // Cek login via email atau via NIS (jika siswa)
        $user = null;
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } else {
            // Coba cari siswa berdasarkan NIS, lalu cari user account-nya
            $student = Student::where('nis', $loginInput)->first();
            if ($student) {
                $user = User::where('student_id', $student->id)->first();
            }
            if (!$user) {
                // Bisa jadi NIP guru / admin
                $user = User::where('nip', $loginInput)->first();
            }
        }

        if (!$user || !\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            return back()->withErrors(['login' => 'Email/NIS/NIP atau password tidak cocok.'])->withInput();
        }

        if (!$user->is_active) {
            return back()->withErrors(['login' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function dashboard()
    {
        $user = Auth::user();
        return match($user->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'teacher'   => redirect()->route('teacher.dashboard'),
            'homeroom'  => redirect()->route('homeroom.dashboard'),
            'principal' => redirect()->route('principal.dashboard'),
            'student'   => redirect()->route('student.dashboard'),
            default     => redirect()->route('auth.login'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.login');
    }
}

