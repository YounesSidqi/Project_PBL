<?php

namespace App\Http\Controllers;

use App\Models\F1Application;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KpsController extends Controller
{
    /**
     * Tampilkan Dashboard Koordinator Program Studi (KPS).
     */
    public function index(Request $request): View
    {
        /** @var User|null $kpsUser */
        $kpsUser = Auth::user();

        // 1. Pengajuan Form F1 yang menunggu penetapan resmi KPS
        $pendingApplications = F1Application::where('status', 'pending')
            ->with(['student', 'dosen'])
            ->latest()
            ->get();

        // 2. Pengajuan Form F1 yang sudah disahkan & ditetapkan
        $approvedApplications = F1Application::where('status', 'approved')
            ->with(['student', 'dosen'])
            ->latest('approved_at')
            ->get();

        // 3. Seluruh dosen beserta mahasiswa bimbingan yang telah terplot
        $allDosen = User::where('role', 'dosen')
            ->withCount('students')
            ->with(['students' => function ($query) {
                $query->with('latestF1Application');
            }])
            ->orderBy('name')
            ->get();

        // 4. Dosen yang masih tersedia (kuota bimbingan < 7)
        $availableDosen = $allDosen->filter(fn (User $d): bool => ($d->students_count ?? 0) < 7)->values();

        // 5. Mahasiswa yang belum terplot pembimbing (dosen_id is null)
        $unassignedStudents = User::where('role', 'mahasiswa')
            ->whereNull('dosen_id')
            ->with('latestF1Application')
            ->orderBy('name')
            ->get();

        // 6. Seluruh data master mahasiswa PKL
        $allStudents = User::where('role', 'mahasiswa')
            ->with(['dosen', 'latestF1Application'])
            ->orderBy('name')
            ->get();

        // Metrik Ringkasan & Kuota
        $totalStudents = $allStudents->count();
        $assignedStudentsCount = $allStudents->whereNotNull('dosen_id')->count();
        $pendingCount = $pendingApplications->count();
        $unassignedCount = $unassignedStudents->count();
        $totalDosen = $allDosen->count();
        $totalCapacity = $totalDosen * 7;
        $usedCapacity = $assignedStudentsCount;
        $remainingCapacity = max(0, $totalCapacity - $usedCapacity);

        return view('dashboard.dashboard-kps', compact(
            'kpsUser',
            'pendingApplications',
            'approvedApplications',
            'allDosen',
            'availableDosen',
            'unassignedStudents',
            'allStudents',
            'totalStudents',
            'assignedStudentsCount',
            'pendingCount',
            'unassignedCount',
            'totalDosen',
            'totalCapacity',
            'usedCapacity',
            'remainingCapacity'
        ));
    }

    /**
     * KPS menyetujui dan menetapkan mahasiswa bersama dosen pembimbing (Pengajuan Form F1).
     * KPS dapat mengesahkan pilihan dosen mahasiswa atau mengalihkan ke dosen lain jika kuota penuh.
     */
    public function approveF1(Request $request, F1Application $application): RedirectResponse
    {
        // KPS dapat menyetujui dosen yang diajukan atau mengalihkan ke dosen lain pilihan KPS
        $targetDosenId = $request->input('dosen_id', $application->dosen_id);

        $dosen = User::where('id', $targetDosenId)->where('role', 'dosen')->firstOrFail();

        // Validasi batas kuota dosen pembimbing (maks 7 mahasiswa)
        $currentMentoredCount = User::where('role', 'mahasiswa')->where('dosen_id', $dosen->id)->count();
        if ($currentMentoredCount >= 7) {
            return redirect()->back()->with('error', "Kapasitas kuota bimbingan {$dosen->name} telah penuh (7/7 mahasiswa). Silakan pilih dosen pembimbing lain yang masih tersedia.");
        }

        $now = now();
        $kpsName = Auth::user()->name ?? 'Koordinator Program Studi (KPS)';
        $notes = $request->input('notes') ?? "Disahkan dan ditetapkan resmi oleh KPS ({$kpsName}) pada {$now->translatedFormat('d M Y, H:i')} WIB.";

        // Perbarui data aplikasi Form F1
        $application->update([
            'dosen_id' => $dosen->id,
            'status' => 'approved',
            'approved_at' => $now,
            'notes' => $notes,
            'signature_hash' => hash('sha256', "KPS_PENETAPAN_{$application->id}_{$dosen->id}_{$now->timestamp}"),
            'updated_at' => $now,
        ]);

        // Tetapkan relasi dosen_id pada data mahasiswa
        $student = $application->student;
        if ($student) {
            $student->update([
                'dosen_id' => $dosen->id,
            ]);

            // Sinkronisasi status_dosen dan dosen_id pada PengajuanMagang
            $pengajuan = PengajuanMagang::where('mahasiswa_id', $student->id)->latest()->first();
            if ($pengajuan) {
                $pengajuan->update([
                    'dosen_id' => $dosen->id,
                    'status_dosen' => 'acc_kps',
                    'judul_magang' => $pengajuan->judul_magang ?: $application->internship_title,
                    'updated_at' => $now,
                ]);
            } else {
                PengajuanMagang::create([
                    'mahasiswa_id' => $student->id,
                    'dosen_id' => $dosen->id,
                    'status_dosen' => 'acc_kps',
                    'judul_magang' => $application->internship_title,
                    'status_surat_pengantar' => 'draft',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        return redirect()->route('dashboard.kps', ['tab' => 'plotting'])
            ->with('success', "Penetapan berhasil! Mahasiswa {$student->name} (NIM: {$student->identifier}) resmi ditetapkan bersama dosen pembimbing {$dosen->name}.");
    }

    /**
     * KPS menolak pengajuan Form F1 mahasiswa.
     */
    public function rejectF1(Request $request, F1Application $application): RedirectResponse
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ], [
            'notes.required' => 'Wajib memberikan catatan / alasan penolakan bagi mahasiswa.',
        ]);

        $now = now();
        $kpsName = Auth::user()->name ?? 'KPS Jurusan TIK';

        $application->update([
            'status' => 'rejected',
            'notes' => "Ditolak oleh KPS ({$kpsName}): ".$request->input('notes'),
            'updated_at' => $now,
        ]);

        // Sinkronisasi PengajuanMagang jika sebelumnya berstatus usulan
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $application->student_id)->latest()->first();
        if ($pengajuan && $pengajuan->status_dosen === 'usulan') {
            $pengajuan->update([
                'dosen_id' => null,
                'status_dosen' => 'pending',
                'updated_at' => $now,
            ]);
        }

        return redirect()->route('dashboard.kps', ['tab' => 'plotting'])
            ->with('info', "Pengajuan Form F1 mahasiswa {$application->student->name} telah ditolak. Mahasiswa dapat mengajukan preferensi dosen baru.");
    }

    /**
     * Plotting langsung mahasiswa oleh KPS (Penetapan tanpa menunggu pengajuan).
     */
    public function plotStudent(Request $request): RedirectResponse
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'dosen_id' => 'required|exists:users,id',
            'company_name' => 'nullable|string|max:255',
            'internship_title' => 'nullable|string|max:255',
        ], [
            'student_id.required' => 'Pilih mahasiswa yang ingin di-plot.',
            'dosen_id.required' => 'Pilih dosen pembimbing.',
        ]);

        $student = User::where('id', $request->input('student_id'))->where('role', 'mahasiswa')->firstOrFail();
        $dosen = User::where('id', $request->input('dosen_id'))->where('role', 'dosen')->firstOrFail();

        // Validasi kuota dosen
        $currentCount = User::where('role', 'mahasiswa')->where('dosen_id', $dosen->id)->count();
        if ($currentCount >= 7) {
            return redirect()->back()->with('error', "Kuota bimbingan {$dosen->name} telah penuh (7/7 mahasiswa). Silakan pilih dosen lain.");
        }

        $now = now();
        $kpsName = Auth::user()->name ?? 'KPS TIK';

        // Tetapkan dosen pembimbing
        $student->update([
            'dosen_id' => $dosen->id,
        ]);

        // Buat atau perbarui record Form F1
        F1Application::updateOrCreate(
            ['student_id' => $student->id],
            [
                'dosen_id' => $dosen->id,
                'company_name' => $request->input('company_name') ?: ($student->latestF1Application->company_name ?? 'Instansi Mitra PKL Jurusan TIK'),
                'internship_title' => $request->input('internship_title') ?: ($student->latestF1Application->internship_title ?? 'Praktik Kerja Lapangan Vokasi'),
                'status' => 'approved',
                'approved_at' => $now,
                'notes' => "Penempatan langsung ditetapkan oleh KPS ({$kpsName}) pada {$now->translatedFormat('d M Y, H:i')} WIB.",
                'signature_hash' => hash('sha256', "KPS_DIRECT_PLOT_{$student->id}_{$dosen->id}_{$now->timestamp}"),
                'updated_at' => $now,
            ]
        );

        // Sinkronisasi status_dosen dan dosen_id pada PengajuanMagang
        $internshipTitle = $request->input('internship_title') ?: 'Praktik Kerja Lapangan Vokasi';
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $student->id)->latest()->first();
        if ($pengajuan) {
            $pengajuan->update([
                'dosen_id' => $dosen->id,
                'status_dosen' => 'acc_kps',
                'judul_magang' => $pengajuan->judul_magang ?: $internshipTitle,
                'updated_at' => $now,
            ]);
        } else {
            PengajuanMagang::create([
                'mahasiswa_id' => $student->id,
                'dosen_id' => $dosen->id,
                'status_dosen' => 'acc_kps',
                'judul_magang' => $internshipTitle,
                'status_surat_pengantar' => 'draft',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return redirect()->route('dashboard.kps', ['tab' => 'plotting'])
            ->with('success', "Mahasiswa {$student->name} (NIM: {$student->identifier}) berhasil langsung di-plot ke dosen pembimbing {$dosen->name}.");
    }

    /**
     * KPS mengalihkan / memindahkan mahasiswa ke dosen pembimbing lain (Reassign).
     */
    public function reassignStudent(Request $request, User $student): RedirectResponse
    {
        $request->validate([
            'new_dosen_id' => 'required|exists:users,id',
        ], [
            'new_dosen_id.required' => 'Pilih dosen pembimbing baru.',
        ]);

        $newDosen = User::where('id', $request->input('new_dosen_id'))->where('role', 'dosen')->firstOrFail();

        if ($student->dosen_id === $newDosen->id) {
            return redirect()->back()->with('info', "Mahasiswa {$student->name} sudah berada di bawah bimbingan {$newDosen->name}.");
        }

        // Cek kuota dosen baru
        $currentCount = User::where('role', 'mahasiswa')->where('dosen_id', $newDosen->id)->count();
        if ($currentCount >= 7) {
            return redirect()->back()->with('error', "Kuota dosen {$newDosen->name} telah penuh (7/7 mahasiswa). Silakan pilih dosen lain.");
        }

        $oldDosenName = $student->dosen->name ?? 'Dosen Sebelumnya';
        $now = now();
        $kpsName = Auth::user()->name ?? 'KPS TIK';

        $student->update([
            'dosen_id' => $newDosen->id,
        ]);

        // Perbarui riwayat F1
        $f1 = $student->latestF1Application;
        if ($f1) {
            $f1->update([
                'dosen_id' => $newDosen->id,
                'notes' => "Dialihkan dari {$oldDosenName} ke {$newDosen->name} oleh KPS ({$kpsName}) pada {$now->translatedFormat('d M Y, H:i')} WIB.",
                'updated_at' => $now,
            ]);
        }

        // Sinkronisasi PengajuanMagang
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $student->id)->latest()->first();
        if ($pengajuan) {
            $pengajuan->update([
                'dosen_id' => $newDosen->id,
                'status_dosen' => 'acc_kps',
                'updated_at' => $now,
            ]);
        }

        return redirect()->route('dashboard.kps', ['tab' => 'kuota'])
            ->with('success', "Mahasiswa {$student->name} berhasil dialihkan dari {$oldDosenName} ke {$newDosen->name}.");
    }

    /**
     * KPS membatalkan penempatan mahasiswa dari dosen pembimbing (Unassign).
     */
    public function unassignStudent(User $student): RedirectResponse
    {
        $dosenName = $student->dosen->name ?? 'Dosen';

        $student->update([
            'dosen_id' => null,
        ]);

        $f1 = $student->latestF1Application;
        if ($f1 && $f1->status === 'approved') {
            $f1->update([
                'status' => 'cancelled',
                'notes' => 'Penempatan dibatalkan oleh KPS. Mahasiswa kembali ke status belum terplot.',
                'updated_at' => now(),
            ]);
        }

        // Sinkronisasi PengajuanMagang
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $student->id)->latest()->first();
        if ($pengajuan) {
            $pengajuan->update([
                'dosen_id' => null,
                'status_dosen' => 'pending',
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('dashboard.kps', ['tab' => 'kuota'])
            ->with('info', "Penempatan mahasiswa {$student->name} dari {$dosenName} telah dibatalkan. Mahasiswa kembali berstatus belum terplot dan slot kuota dosen terbuka.");
    }
}
