<?php

namespace App\Http\Controllers;

use App\Models\Industri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication login attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['nullable', 'string', 'in:mahasiswa,dosen,dudi,industri,admin,kps'],
        ], [
            'identifier.required' => 'NIM, NIP, atau Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $identifier = trim($request->input('identifier'));
        $password = $request->input('password');
        $role = $request->input('role');
        $remember = $request->boolean('remember');

        // Check whether identifier is an email address or an identifier (NIM, NIP, NRK)
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $field = $isEmail ? 'email' : 'identifier';

        // Prepare credentials
        $credentials = [
            $field => $identifier,
            'password' => $password,
        ];

        // If a specific role is passed, enforce role checking
        if (! empty($role)) {
            if ($role === 'dudi' || $role === 'industri') {
                $userExists = User::where($field, $identifier)->first();
                if (! $userExists && $isEmail) {
                    $pendingInd = Industri::where('email_pj', $identifier)->first();
                    if ($pendingInd && $pendingInd->status_verifikasi === 'pending') {
                        throw ValidationException::withMessages([
                            'identifier' => "Perusahaan \"{$pendingInd->nama_perusahaan}\" masih berstatus Pending. Silakan login sebagai Admin untuk menekan Accept terlebih dahulu agar akun DUDI aktif.",
                        ]);
                    }
                }
                if ($userExists && ! in_array($userExists->role, ['industri', 'dudi'])) {
                    throw ValidationException::withMessages([
                        'identifier' => 'Akun ini bukan merupakan akun Mitra DUDI / Industri.',
                    ]);
                }
            } elseif ($role === 'admin' || $role === 'kps') {
                $userExists = User::where($field, $identifier)->first();
                if ($userExists && ! in_array($userExists->role, ['admin', 'kps'])) {
                    throw ValidationException::withMessages([
                        'identifier' => 'Akun ini bukan merupakan akun KPS / Admin Jurusan.',
                    ]);
                }
            } else {
                $credentials['role'] = $role;
            }
        }

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var User $authUser */
            $authUser = Auth::user();

            $targetRoute = match ($authUser->role) {
                'admin' => route('dashboard.admin'),
                'kps' => route('dashboard.kps'),
                'industri', 'dudi' => route('dashboard.industri'),
                'dosen' => route('dashboard.dosen'),
                default => route('dashboard.mahasiswa'),
            };

            return redirect()->intended($targetRoute)
                ->with('success', 'Selamat datang di SIM-PKL, '.$authUser->name.'!');
        }

        // If role-specific attempt failed, check if account exists under a different role
        if (! empty($role)) {
            $userExists = User::where($field, $identifier)->first();
            if ($userExists && $userExists->role !== $role && ! ($userExists->isKps() && in_array($role, ['admin', 'kps']))) {
                $roleLabels = [
                    'mahasiswa' => 'Mahasiswa',
                    'dosen' => 'Dosen Pembimbing',
                    'dudi' => 'Mitra DUDI',
                    'admin' => 'Admin / KPS',
                    'kps' => 'Admin / KPS',
                ];
                $actualRole = $roleLabels[$userExists->role] ?? $userExists->role;
                throw ValidationException::withMessages([
                    'identifier' => "Akun ini terdaftar sebagai peran \"{$actualRole}\". Silakan pilih tab {$actualRole}.",
                ]);
            }
        }

        throw ValidationException::withMessages([
            'identifier' => 'Kredensial yang dimasukkan tidak sesuai dengan data terdaftar kami.',
        ]);
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('info', 'Anda telah berhasil keluar dari sistem SIM-PKL.');
    }
}
