<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogbookController;
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

        return view('dashboard.dashboard-dosen', compact('students', 'logbooks', 'dosen'));
    }

    $logbooks = $user ? $user->logbooks()->orderBy('tanggal', 'desc')->get() : collect();

    return view('dashboard.dashboard', compact('logbooks'));
})->middleware('auth')->name('dashboard');

Route::get('/dashboard/dosen', function () {
    /** @var User|null $user */
    $user = Auth::user();
    $dosen = ($user && $user->role === 'dosen') ? $user : User::where('role', 'dosen')->first();
    $dosenId = $dosen ? $dosen->id : null;
    $students = User::where('role', 'mahasiswa')->where('dosen_id', $dosenId)->with('logbooks')->get();
    $logbooks = Logbook::where('dosen_id', $dosenId)->with('student')->orderBy('tanggal', 'desc')->get();

    return view('dashboard.dashboard-dosen', compact('students', 'logbooks', 'dosen'));
})->middleware('auth')->name('dashboard.dosen');

Route::get('/dashboard/mahasiswa', function () {
    /** @var User|null $user */
    $user = Auth::user();
    $logbooks = $user ? $user->logbooks()->orderBy('tanggal', 'desc')->get() : collect();

    return view('dashboard.dashboard', compact('logbooks'));
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

        $query = Logbook::where('dosen_id', $dosenId)->with('student')->orderBy('tanggal', 'desc');
        if ($request->filled('student_id')) {
            $query->where('user_id', $request->query('student_id'));
        }
        $logbooks = $query->get();

        return view('logbook.dosen', compact('students', 'logbooks', 'dosen'));
    })->name('logbook.dosen');

    Route::post('/logbooks', [LogbookController::class, 'store'])->name('logbooks.store');
    Route::get('/logbooks/{logbook}', [LogbookController::class, 'show'])->name('logbooks.show');
    Route::put('/logbooks/{logbook}', [LogbookController::class, 'update'])->name('logbooks.update');
    Route::delete('/logbooks/{logbook}', [LogbookController::class, 'destroy'])->name('logbooks.destroy');
    Route::post('/logbooks/{logbook}/status', [LogbookController::class, 'updateStatus'])->name('logbooks.updateStatus');
});
