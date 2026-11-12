<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BimbinganF4Controller;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\F1Controller;
use App\Http\Controllers\FormController;
use App\Http\Controllers\IndustriController;
use App\Http\Controllers\KpsController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\PenilaianController;
use App\Models\F1Application;
use App\Models\Industri;
use App\Models\Logbook;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing page redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::get('/dashboard', function (Request $request) {
    /** @var User|null $user */
    $user = Auth::user();
    $role = $request->query('role', $user?->role ?? 'mahasiswa');

    if ($role === 'admin') {
        return redirect()->route('dashboard.admin');
    }

    if ($role === 'kps') {
        return redirect()->route('dashboard.kps');
    }

    if ($role === 'industri' || $role === 'dudi') {
        return redirect()->route('dashboard.industri');
    }

    if ($role === 'dosen') {
        return redirect()->route('dashboard.dosen');
    }

    return redirect()->route('dashboard.mahasiswa');
})->middleware('auth')->name('dashboard');

// 1. Dashboard Admin Jurusan TIK PNJ (PRD 5.1 & 7.1, 7.3, 10.1)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');
    Route::post('/admin/mahasiswa', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
    Route::post('/admin/mahasiswa/import', [AdminController::class, 'importMahasiswa'])->name('admin.mahasiswa.import');
    Route::put('/admin/mahasiswa/{mahasiswa}', [AdminController::class, 'updateMahasiswa'])->name('admin.mahasiswa.update');
    Route::delete('/admin/mahasiswa/{mahasiswa}/tempat-pkl', [AdminController::class, 'destroyTempatPkl'])->name('admin.mahasiswa.destroy-pkl');
    Route::delete('/admin/mahasiswa/{mahasiswa}', [AdminController::class, 'destroyMahasiswa'])->name('admin.mahasiswa.destroy');
    Route::post('/admin/surat-pengantar/{pengajuan}/terbitkan', [AdminController::class, 'terbitkanSuratPengantar'])->name('admin.surat.terbitkan');
    Route::post('/admin/surat-pengantar/{pengajuan}/tolak', [AdminController::class, 'tolakSuratPengantar'])->name('admin.surat.tolak');
    Route::post('/admin/industri', [AdminController::class, 'storeIndustri'])->name('admin.industri.store');
    Route::put('/admin/industri/{industri}', [AdminController::class, 'updateIndustri'])->name('admin.industri.update');
    Route::delete('/admin/industri/{industri}', [AdminController::class, 'destroyIndustri'])->name('admin.industri.destroy');
    Route::post('/admin/industri/{industri}/acc', [AdminController::class, 'accIndustri'])->name('admin.industri.acc');
    Route::post('/admin/industri/{industri}/reject', [AdminController::class, 'rejectIndustri'])->name('admin.industri.reject');
    Route::post('/admin/pengajuan/{pengajuan}/forward', [AdminController::class, 'forwardPengajuan'])->name('admin.pengajuan.forward');
    Route::post('/admin/pengajuan/{pengajuan}/reset-to-draft', [AdminController::class, 'resetPengajuanToDraft'])->name('admin.pengajuan.reset-draft');
    Route::post('/admin/users/{user}/reset-password', [AdminController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::post('/admin/f1/{application}/plot', [AdminController::class, 'plotDosenF1'])->name('admin.f1.plot');
    Route::post('/admin/students/plot', [AdminController::class, 'plotStudentDosen'])->name('admin.student.plot');
});

// 2. Dashboard Koordinator Program Studi (KPS)
Route::get('/dashboard/kps', [KpsController::class, 'index'])->middleware('auth')->name('dashboard.kps');

// 3. Dashboard Pembimbing Industri / DUDI (PRD 5.3 & 7.2)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/industri', [IndustriController::class, 'index'])->name('dashboard.industri');
    Route::post('/industri/pengajuan/{pengajuan}/tugas-f2', [IndustriController::class, 'storeTugasF2'])->name('industri.tugas_f2.store');
    Route::put('/industri/tugas-f2/{tugas}', [IndustriController::class, 'updateTugasF2'])->name('industri.tugas_f2.update');
    Route::delete('/industri/tugas-f2/{tugas}', [IndustriController::class, 'destroyTugasF2'])->name('industri.tugas_f2.destroy');
    Route::post('/industri/logbooks/{logbook}/acc', [IndustriController::class, 'accLogbookF3'])->name('industri.logbooks.acc');
    Route::post('/industri/logbooks/{logbook}/revisi', [IndustriController::class, 'revisiLogbookF3'])->name('industri.logbooks.revisi');
    Route::post('/industri/pengajuan/{pengajuan}/evaluasi-f5', [IndustriController::class, 'storeEvaluasiF5'])->name('industri.evaluasi_f5.store');
});

// 4. Dashboard Dosen Pembimbing PNJ
Route::get('/dashboard/dosen', function (Request $request) {
    /** @var User|null $user */
    $user = Auth::user();
    $dosen = ($user && $user->role === 'dosen') ? $user : User::where('role', 'dosen')->first();
    $dosenId = $dosen ? $dosen->id : null;

    $students = User::where('role', 'mahasiswa')
        ->where('dosen_id', $dosenId)
        ->with([
            'logbooks',
            'latestF1Application',
            'pengajuanMagangAktif.industri',
            'pengajuanMagangAktif.bimbingans',
            'pengajuanMagangAktif.tugasF2',
            'pengajuanMagangAktif.evaluasiF5',
            'pengajuanMagangAktif.evaluasiF6',
        ])
        ->get();

    $logbooks = Logbook::where('dosen_id', $dosenId)->with('student')->orderBy('tanggal', 'desc')->get();

    return view('dashboard.dashboard-dosen', compact('students', 'logbooks', 'dosen'));
})->middleware('auth')->name('dashboard.dosen');

// 5. Dashboard Mahasiswa
Route::get('/dashboard/mahasiswa', function () {
    /** @var User|null $user */
    $user = Auth::user();
    $logbooks = $user ? $user->logbooks()->orderBy('tanggal', 'desc')->get() : collect();
    $dosen = $user?->dosen ?? ($user?->dosen_id ? User::find($user->dosen_id) : null);
    $f1Application = $user ? F1Application::where('student_id', $user->id)
        ->whereIn('status', [
            F1Application::STATUS_PENDING_ADMIN,
            F1Application::STATUS_PENDING_KPS,
            F1Application::STATUS_REVISION_REQUESTED,
            F1Application::STATUS_APPROVED,
            'pending',
        ])
        ->with(['dosen', 'recommendedDosen'])
        ->latest()
        ->first() : null;

    $pengajuanAktif = $user ? PengajuanMagang::where('mahasiswa_id', $user->id)
        ->with(['industri', 'dosen', 'tugasF2', 'bimbingans', 'evaluasiF5', 'evaluasiF6'])
        ->latest()
        ->first() : null;

    // Hanya dosen pada jurusan yang sama yang kuotanya masih tersedia (< 7) yang muncul untuk dipilih mahasiswa
    $allDosen = User::where('role', 'dosen')
        ->sameJurusan($user)
        ->withCount('students')
        ->has('students', '<', 7)
        ->orderBy('name')
        ->get();

    $industris = Industri::where('status_verifikasi', 'acc')->orderBy('nama_perusahaan')->get();

    return view('dashboard.dashboard', compact('logbooks', 'dosen', 'f1Application', 'allDosen', 'pengajuanAktif', 'industris'));
})->middleware('auth')->name('dashboard.mahasiswa');

// Logbook Workspace Routes (Mahasiswa & Dosen)
Route::middleware('auth')->group(function () {
    Route::get('/logbook', function (Request $request) {
        /** @var User|null $user */
        $user = Auth::user();
        $role = $request->query('role', $user?->role ?? 'mahasiswa');
        if ($role === 'dosen') {
            return redirect()->route('logbook.dosen');
        }

        return redirect()->route('logbook.mahasiswa');
    })->name('logbook');

    Route::get('/logbook/mahasiswa', function () {
        /** @var User|null $user */
        $user = Auth::user();
        $logbooks = $user ? $user->logbooks()->orderBy('tanggal', 'desc')->get() : collect();

        return view('logbook.mahasiswa', compact('logbooks'));
    })->name('logbook.mahasiswa');

    Route::get('/logbook/dosen', function (Request $request) {
        /** @var User|null $user */
        $user = Auth::user();
        $dosen = ($user && $user->role === 'dosen') ? $user : User::where('role', 'dosen')->first();
        $dosenId = $dosen ? $dosen->id : null;
        $students = User::where('role', 'mahasiswa')->where('dosen_id', $dosenId)->with('logbooks')->get();

        $selectedStudentId = $request->query('student_id');
        $selectedStudent = $selectedStudentId ? $students->firstWhere('id', $selectedStudentId) : null;

        $logbooksQuery = Logbook::where('dosen_id', $dosenId)->with('student')->orderBy('tanggal', 'desc');
        if ($selectedStudentId) {
            $logbooksQuery->where('user_id', $selectedStudentId);
        }
        $logbooks = $logbooksQuery->get();

        return view('logbook.dosen', compact('students', 'logbooks', 'dosen', 'selectedStudent'));
    })->name('logbook.dosen');

    // Form F2 (User Requirement & Capaian Industri)
    Route::get('/form/f2', [FormController::class, 'f2Index'])->name('form.f2');
    Route::get('/form/f2/mahasiswa', [FormController::class, 'f2Mahasiswa'])->name('form.f2.mahasiswa');
    Route::get('/form/f2/dosen', [FormController::class, 'f2Dosen'])->name('form.f2.dosen');

    // Form F4 (Buku Penghubung Dosen Pembimbing)
    Route::get('/form/f4', [FormController::class, 'f4Index'])->name('form.f4');
    Route::get('/form/f4/mahasiswa', [FormController::class, 'f4Mahasiswa'])->name('form.f4.mahasiswa');
    Route::get('/form/f4/dosen', [FormController::class, 'f4Dosen'])->name('form.f4.dosen');

    // Form F5 (Evaluasi Pembimbing Industri - Bobot 60%)
    Route::get('/form/f5', [FormController::class, 'f5Index'])->name('form.f5');
    Route::get('/form/f5/industri', [FormController::class, 'f5Industri'])->name('form.f5.industri');

    // Form F6 (Evaluasi Akademik Dosen - Bobot 40%)
    Route::get('/form/f6', [FormController::class, 'f6Index'])->name('form.f6');
    Route::get('/form/f6/dosen', [FormController::class, 'f6Dosen'])->name('form.f6.dosen');

    Route::post('/logbooks', [LogbookController::class, 'store'])->name('logbooks.store');
    Route::put('/logbooks/{logbook}', [LogbookController::class, 'update'])->name('logbooks.update');
    Route::delete('/logbooks/{logbook}', [LogbookController::class, 'destroy'])->name('logbooks.destroy');
    Route::post('/logbooks/{logbook}/status', [LogbookController::class, 'updateStatus'])->name('logbooks.updateStatus');

    // Form F1 Routes (Pemilihan Dosen & Pengesahan Berkas PDF)
    Route::post('/f1/select-dosen', [F1Controller::class, 'selectDosen'])->name('f1.select-dosen');
    Route::delete('/f1/{application}/cancel', [F1Controller::class, 'cancel'])->name('f1.cancel');
    Route::get('/f1/{application}/surat', [F1Controller::class, 'downloadSurat'])->name('f1.surat.download');

    // KPS Specific Routes (Approval & Revisi Hasil Plotting Admin)
    Route::post('/kps/f1/{application}/approve', [KpsController::class, 'approveF1'])->name('kps.f1.approve');
    Route::post('/kps/f1/{application}/revisi', [KpsController::class, 'revisiF1'])->name('kps.f1.revisi');
    Route::post('/kps/f1/{application}/revision', [KpsController::class, 'revisiF1'])->name('kps.f1.revision');
    Route::post('/kps/f1/{application}/reject', [KpsController::class, 'rejectF1'])->name('kps.f1.reject');
    Route::post('/kps/students/plot', [KpsController::class, 'plotStudent'])->name('kps.student.plot');
    Route::post('/kps/students/{student}/reassign', [KpsController::class, 'reassignStudent'])->name('kps.student.reassign');
    Route::delete('/kps/students/{student}/unassign', [KpsController::class, 'unassignStudent'])->name('kps.student.unassign');

    // Form F4 Sesi Bimbingan
    Route::post('/pengajuan/{pengajuan}/bimbingan-f4', [BimbinganF4Controller::class, 'store'])->name('bimbingan_f4.store');
    Route::delete('/bimbingan-f4/{bimbingan}', [BimbinganF4Controller::class, 'destroy'])->name('bimbingan_f4.destroy');

    // Permintaan Surat Pengantar (Mahasiswa)
    Route::post('/mahasiswa/request-surat-pengantar', [PenilaianController::class, 'requestSuratPengantar'])->name('mahasiswa.request-surat');

    // Form F6 & Kalkulasi Nilai Fuzzy Logic (Dosen)
    Route::post('/dosen/pengajuan/{pengajuan}/evaluasi-f6', [PenilaianController::class, 'storeEvaluasiF6'])->name('dosen.evaluasi_f6.store');
    Route::post('/dosen/pengajuan/{pengajuan}/kalkulasi-fuzzy', [PenilaianController::class, 'kalkulasiFuzzy'])->name('dosen.kalkulasi-fuzzy');

    // Legalisasi Berkas Digital & Cetak PDF F1-F7 (PRD 8)
    Route::get('/dokumen/surat-pengantar/{pengajuan}', [DokumenController::class, 'suratPengantar'])->name('dokumen.surat-pengantar');
    Route::get('/dokumen/surat-keterangan/{pengajuan}', [DokumenController::class, 'suratKeteranganIndustri'])->name('dokumen.surat-keterangan');
    Route::get('/dokumen/f1/{application}', [DokumenController::class, 'cetakF1'])->name('dokumen.f1');
    Route::get('/dokumen/f2/{pengajuan}', [DokumenController::class, 'cetakF2'])->name('dokumen.f2');
    Route::get('/dokumen/f3/{pengajuan}', [DokumenController::class, 'cetakF3'])->name('dokumen.f3');
    Route::get('/dokumen/f4/{pengajuan}', [DokumenController::class, 'cetakF4'])->name('dokumen.f4');
    Route::get('/dokumen/f5/{pengajuan}', [DokumenController::class, 'cetakF5'])->name('dokumen.f5');
    Route::get('/dokumen/f6/{pengajuan}', [DokumenController::class, 'cetakF6'])->name('dokumen.f6');
    Route::get('/dokumen/f7/{pengajuan}', [DokumenController::class, 'cetakF7'])->name('dokumen.f7');
});
