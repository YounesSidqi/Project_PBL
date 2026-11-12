<?php

namespace App\Http\Controllers;

use App\Models\Industri;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    /**
     * Redirect Form F2 sesuai role user.
     */
    public function f2Index(Request $request)
    {
        $user = Auth::user();
        $role = $request->query('role', $user?->role ?? 'mahasiswa');

        return ($role === 'dosen') ? redirect()->route('form.f2.dosen') : redirect()->route('form.f2.mahasiswa');
    }

    /**
     * Tampilan Form F2 untuk Mahasiswa.
     */
    public function f2Mahasiswa()
    {
        $user = Auth::user();
        $pengajuan = PengajuanMagang::with(['industri', 'dosen', 'tugasF2'])
            ->where('mahasiswa_id', $user->id)
            ->latest()
            ->first();

        $allTasks = $pengajuan ? $pengajuan->tugasF2 : collect();
        $tasksTodo = $allTasks->whereIn('status', ['todo', 'direncanakan']);
        $tasksProgress = $allTasks->whereIn('status', ['in_progress', 'sedang_berjalan']);
        $tasksDone = $allTasks->whereIn('status', ['acc', 'selesai']);

        return view('form.f2-mahasiswa', compact('pengajuan', 'allTasks', 'tasksTodo', 'tasksProgress', 'tasksDone'));
    }

    /**
     * Tampilan Form F2 untuk Dosen Pembimbing.
     */
    public function f2Dosen(Request $request)
    {
        $user = Auth::user();
        $dosenId = ($user && $user->role === 'dosen') ? $user->id : User::where('role', 'dosen')->value('id');
        $students = PengajuanMagang::with(['mahasiswa', 'industri', 'tugasF2'])
            ->where(function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                    ->orWhereHas('mahasiswa', fn ($m) => $m->where('dosen_id', $dosenId));
            })
            ->get();

        $selectedPengajuan = $request->has('pengajuan_id')
            ? $students->firstWhere('id', (int) $request->query('pengajuan_id'))
            : $students->first();

        $allTasks = $selectedPengajuan ? $selectedPengajuan->tugasF2 : collect();
        $tasksTodo = $allTasks->whereIn('status', ['todo', 'direncanakan']);
        $tasksProgress = $allTasks->whereIn('status', ['in_progress', 'sedang_berjalan']);
        $tasksDone = $allTasks->whereIn('status', ['acc', 'selesai']);

        return view('form.f2-dosen', compact('students', 'selectedPengajuan', 'allTasks', 'tasksTodo', 'tasksProgress', 'tasksDone'));
    }

    /**
     * Redirect Form F4 sesuai role user.
     */
    public function f4Index(Request $request)
    {
        $user = Auth::user();
        $role = $request->query('role', $user?->role ?? 'mahasiswa');

        return ($role === 'dosen') ? redirect()->route('form.f4.dosen') : redirect()->route('form.f4.mahasiswa');
    }

    /**
     * Tampilan Form F4 (Buku Bimbingan) Mahasiswa.
     */
    public function f4Mahasiswa()
    {
        $user = Auth::user();
        $pengajuan = PengajuanMagang::with(['industri', 'dosen', 'bimbingans'])
            ->where('mahasiswa_id', $user->id)
            ->latest()
            ->first();

        $bimbingans = $pengajuan ? $pengajuan->bimbingans()->orderBy('sesi_ke', 'asc')->get() : collect();
        $totalSessions = $bimbingans->count();

        return view('form.f4-mahasiswa', compact('pengajuan', 'bimbingans', 'totalSessions'));
    }

    /**
     * Tampilan Form F4 (Buku Bimbingan) Dosen.
     */
    public function f4Dosen(Request $request)
    {
        $user = Auth::user();
        $dosenId = ($user && $user->role === 'dosen') ? $user->id : User::where('role', 'dosen')->value('id');
        $students = PengajuanMagang::with(['mahasiswa', 'industri', 'bimbingans'])
            ->where(function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                    ->orWhereHas('mahasiswa', fn ($m) => $m->where('dosen_id', $dosenId));
            })
            ->get();

        $selectedPengajuan = $request->has('pengajuan_id')
            ? $students->firstWhere('id', (int) $request->query('pengajuan_id'))
            : $students->first();

        $bimbingans = $selectedPengajuan ? $selectedPengajuan->bimbingans()->orderBy('sesi_ke', 'asc')->get() : collect();
        $totalSessions = $bimbingans->count();

        return view('form.f4-dosen', compact('students', 'selectedPengajuan', 'bimbingans', 'totalSessions'));
    }

    /**
     * Redirect Form F5 sesuai role user.
     */
    public function f5Index(Request $request)
    {
        $user = Auth::user();
        if ($user && ($user->role === 'industri' || $user->role === 'dudi')) {
            return redirect()->route('form.f5.industri');
        }

        return redirect()->route('dashboard.industri');
    }

    /**
     * Tampilan Dedicated Form F5 (Evaluasi Pembimbing Industri - Bobot 60%).
     */
    public function f5Industri(Request $request)
    {
        $user = Auth::user();
        $industri = $user?->industriProfile ?? Industri::where('user_id', $user?->id)->first();

        $pengajuans = PengajuanMagang::with(['mahasiswa', 'evaluasiF5', 'industri'])
            ->when($industri, fn ($q) => $q->where('industri_id', $industri->id))
            ->get();

        $selectedPengajuanId = $request->query('pengajuan_id') ?? $request->query('student_id');
        $selectedPengajuan = $selectedPengajuanId
            ? $pengajuans->firstWhere('id', (int) $selectedPengajuanId) ?? $pengajuans->firstWhere('mahasiswa_id', (int) $selectedPengajuanId)
            : $pengajuans->first();

        $evaluasiF5 = $selectedPengajuan?->evaluasiF5;

        return view('form.f5-industri', compact('user', 'industri', 'pengajuans', 'selectedPengajuan', 'evaluasiF5'));
    }

    /**
     * Redirect Form F6 sesuai role user.
     */
    public function f6Index(Request $request)
    {
        $user = Auth::user();
        if ($user && $user->role === 'dosen') {
            return redirect()->route('form.f6.dosen');
        }

        return redirect()->route('dashboard.dosen');
    }

    /**
     * Tampilan Dedicated Form F6 (Evaluasi Akademik Dosen - Bobot 40% & Fuzzy Engine).
     */
    public function f6Dosen(Request $request)
    {
        $user = Auth::user();
        $dosenId = ($user && $user->role === 'dosen') ? $user->id : User::where('role', 'dosen')->value('id');

        $students = PengajuanMagang::with(['mahasiswa', 'industri', 'bimbingans', 'evaluasiF5', 'evaluasiF6'])
            ->where(function ($q) use ($dosenId) {
                $q->where('dosen_id', $dosenId)
                    ->orWhereHas('mahasiswa', fn ($m) => $m->where('dosen_id', $dosenId));
            })
            ->get();

        $selectedPengajuanId = $request->query('pengajuan_id') ?? $request->query('student_id');
        $selectedPengajuan = $selectedPengajuanId
            ? $students->firstWhere('id', (int) $selectedPengajuanId) ?? $students->firstWhere('mahasiswa_id', (int) $selectedPengajuanId)
            : $students->first();

        $evalF6 = $selectedPengajuan?->evaluasiF6;
        $evalF5 = $selectedPengajuan?->evaluasiF5;
        $bimbingansCount = $selectedPengajuan ? $selectedPengajuan->bimbingans->count() : 0;
        $canTransmitFuzzy = ($bimbingansCount >= 8) && $evalF5 && $evalF6;

        return view('form.f6-dosen', compact('user', 'students', 'selectedPengajuan', 'evalF6', 'evalF5', 'bimbingansCount', 'canTransmitFuzzy'));
    }
}
