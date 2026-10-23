<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\F1Controller;
use App\Http\Controllers\LogbookController;
use App\Models\F1Application;
use App\Models\Logbook;
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

    if ($role === 'dosen') {
        $dosen = ($user && $user->role === 'dosen') ? $user : User::where('role', 'dosen')->first();
        $dosenId = $dosen ? $dosen->id : null;
        $students = User::where('role', 'mahasiswa')->where('dosen_id', $dosenId)->with('logbooks')->get();
        $logbooks = Logbook::where('dosen_id', $dosenId)->with('student')->orderBy('tanggal', 'desc')->get();
        $incomingF1 = F1Application::where('dosen_id', $dosenId)->where('status', 'pending')->with('student')->latest()->get();

        return view('dashboard.dashboard-dosen', compact('students', 'logbooks', 'dosen', 'incomingF1'));
    }

    $logbooks = $user ? $user->logbooks()->orderBy('tanggal', 'desc')->get() : collect();
    $dosen = $user?->dosen ?? ($user?->dosen_id ? User::find($user->dosen_id) : null);
    $f1Application = $user ? F1Application::where('student_id', $user->id)->whereIn('status', ['pending', 'approved'])->with('dosen')->latest()->first() : null;
    $allDosen = User::where('role', 'dosen')->withCount('students')->get();

    return view('dashboard.dashboard', compact('logbooks', 'dosen', 'f1Application', 'allDosen'));
})->middleware('auth')->name('dashboard');

Route::get('/dashboard/dosen', function () {
    /** @var User|null $user */
    $user = Auth::user();
    $dosen = ($user && $user->role === 'dosen') ? $user : User::where('role', 'dosen')->first();
    $dosenId = $dosen ? $dosen->id : null;
    $students = User::where('role', 'mahasiswa')->where('dosen_id', $dosenId)->with('logbooks')->get();
    $logbooks = Logbook::where('dosen_id', $dosenId)->with('student')->orderBy('tanggal', 'desc')->get();
    $incomingF1 = F1Application::where('dosen_id', $dosenId)->where('status', 'pending')->with('student')->latest()->get();

    return view('dashboard.dashboard-dosen', compact('students', 'logbooks', 'dosen', 'incomingF1'));
})->middleware('auth')->name('dashboard.dosen');

Route::get('/dashboard/mahasiswa', function () {
    /** @var User|null $user */
    $user = Auth::user();
    $logbooks = $user ? $user->logbooks()->orderBy('tanggal', 'desc')->get() : collect();
    $dosen = $user?->dosen ?? ($user?->dosen_id ? User::find($user->dosen_id) : null);
    $f1Application = $user ? F1Application::where('student_id', $user->id)->whereIn('status', ['pending', 'approved'])->with('dosen')->latest()->first() : null;
    $allDosen = User::where('role', 'dosen')->withCount('students')->get();

    return view('dashboard.dashboard', compact('logbooks', 'dosen', 'f1Application', 'allDosen'));
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
    Route::get('/form/f2', function (Request $request) {
        $user = Auth::user();
        $role = $request->query('role', $user?->role ?? 'mahasiswa');

        return ($role === 'dosen') ? redirect()->route('form.f2.dosen') : redirect()->route('form.f2.mahasiswa');
    })->name('form.f2');
    Route::get('/form/f2/mahasiswa', function () {
        return view('form.f2-mahasiswa');
    })->name('form.f2.mahasiswa');
    Route::get('/form/f2/dosen', function () {
        return view('form.f2-dosen');
    })->name('form.f2.dosen');

    // Form F4 (Buku Penghubung Dosen Pembimbing)
    Route::get('/form/f4', function (Request $request) {
        $user = Auth::user();
        $role = $request->query('role', $user?->role ?? 'mahasiswa');

        return ($role === 'dosen') ? redirect()->route('form.f4.dosen') : redirect()->route('form.f4.mahasiswa');
    })->name('form.f4');
    Route::get('/form/f4/mahasiswa', function () {
        return view('form.f4-mahasiswa');
    })->name('form.f4.mahasiswa');
    Route::get('/form/f4/dosen', function () {
        return view('form.f4-dosen');
    })->name('form.f4.dosen');

    Route::post('/logbooks', [LogbookController::class, 'store'])->name('logbooks.store');
    Route::get('/logbooks/{logbook}', [LogbookController::class, 'show'])->name('logbooks.show');
    Route::put('/logbooks/{logbook}', [LogbookController::class, 'update'])->name('logbooks.update');
    Route::delete('/logbooks/{logbook}', [LogbookController::class, 'destroy'])->name('logbooks.destroy');
    Route::post('/logbooks/{logbook}/status', [LogbookController::class, 'updateStatus'])->name('logbooks.updateStatus');

    // Form F1 Routes (Pemilihan Dosen, Pengesahan Berkas PDF, & Manajemen Mahasiswa Bimbingan)
    Route::post('/f1/select-dosen', [F1Controller::class, 'selectDosen'])->name('f1.select-dosen');
    Route::delete('/f1/{application}/cancel', [F1Controller::class, 'cancel'])->name('f1.cancel');
    Route::post('/f1/approve', [F1Controller::class, 'approveGeneral'])->name('f1.approve.general');
    Route::post('/f1/{application}/approve', [F1Controller::class, 'approve'])->name('f1.approve');
    Route::post('/f1/{application}/reject', [F1Controller::class, 'reject'])->name('f1.reject');
    Route::delete('/dosen/students/{student}/remove', [F1Controller::class, 'removeStudent'])->name('dosen.students.remove');
    Route::get('/f1/{application}/surat', [F1Controller::class, 'downloadSurat'])->name('f1.surat.download');
});
