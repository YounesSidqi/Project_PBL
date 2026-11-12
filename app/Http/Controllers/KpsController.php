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

        // 1. Pengajuan Form F1 yang telah di-plot Admin dan menunggu Approve resmi KPS (Fase 3)
        $pendingApplications = F1Application::where('status', F1Application::STATUS_PENDING_KPS)
            ->with(['student', 'dosen', 'recommendedDosen', 'plotter'])
            ->latest('plotted_at')
            ->latest()
            ->get();

        // 1b. Pengajuan Form F1 yang sedang diminta revisi oleh KPS ke Admin (Fase 4)
        $revisionApplications = F1Application::where('status', F1Application::STATUS_REVISION_REQUESTED)
            ->with(['student', 'dosen', 'recommendedDosen', 'plotter'])
            ->latest('updated_at')
            ->get();

        // 1c. Pengajuan Form F1 yang masih di tahap awal menunggu plotting Admin (Fase 1 -> Fase 2)
        $pendingAdminApplications = F1Application::whereIn('status', [F1Application::STATUS_PENDING_ADMIN, 'pending'])
            ->with(['student', 'dosen', 'recommendedDosen'])
            ->latest()
            ->get();

        // 2. Pengajuan Form F1 yang sudah disahkan & ditetapkan (Approved)
        $approvedApplications = F1Application::where('status', F1Application::STATUS_APPROVED)
            ->with(['student', 'dosen', 'recommendedDosen', 'plotter'])
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
            'revisionApplications',
            'pendingAdminApplications',
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
     * Fase 3 (Approval oleh KPS): KPS melakukan pengecekan dan menyetujui (Approve) hasil plotting dosen pembimbing dari Admin.
     */
    public function approveF1(Request $request, F1Application $application): RedirectResponse
    {
        if (in_array($application->status, [F1Application::STATUS_PENDING_ADMIN, F1Application::STATUS_REVISION_REQUESTED], true)) {
            return redirect()->back()->with('error', 'Pengajuan ini masih menunggu proses plotting atau revisi dari Admin Jurusan dan belum dapat di-approve.');
        }

        $dosen = User::where('id', $application->dosen_id)->where('role', 'dosen')->firstOrFail();

        // Validasi batas kuota dosen pembimbing (maks 7 mahasiswa)
        $currentMentoredCount = User::where('role', 'mahasiswa')
            ->where('dosen_id', $dosen->id)
            ->where('id', '!=', $application->student_id)
            ->count();

        if ($currentMentoredCount >= 7) {
            return redirect()->back()->with('error', "Kapasitas kuota bimbingan {$dosen->name} telah penuh (7/7 mahasiswa). Silakan minta revisi ke Admin untuk memilih dosen pembimbing lain.");
        }

        $now = now();
        $kpsName = Auth::user()->name ?? 'Koordinator Program Studi (KPS)';
        $notes = $request->input('notes') ?? "Disetujui (Approved) dan disahkan resmi oleh KPS ({$kpsName}) pada {$now->translatedFormat('d M Y, H:i')} WIB.";

        // Perbarui data aplikasi Form F1 menjadi approved
        $application->update([
            'dosen_id' => $dosen->id,
            'status' => F1Application::STATUS_APPROVED,
            'approved_at' => $now,
            'revision_notes' => null,
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
            ->with('success', "Approval berhasil! Hasil plotting mahasiswa {$student->name} (NIM: {$student->identifier}) bersama dosen pembimbing {$dosen->name} resmi disetujui.");
    }

    /**
     * Fase 4 (Sistem Revisi): KPS tidak setuju dengan plotting dari Admin dan mengembalikan status pengajuan ke Admin
     * beserta catatan/alasan revisi yang wajib diisi.
     */
    public function revisiF1(Request $request, F1Application $application): RedirectResponse
    {
        if (! $request->filled('revision_notes')) {
            if ($request->filled('catatan_revisi')) {
                $request->merge(['revision_notes' => $request->input('catatan_revisi')]);
            } elseif ($request->filled('notes')) {
                $request->merge(['revision_notes' => $request->input('notes')]);
            }
        }

        $validated = $request->validate([
            'revision_notes' => 'required|string|max:1000',
        ], [
            'revision_notes.required' => 'Wajib memberikan catatan atau alasan revisi saat mengembalikan plotting ke Admin.',
        ]);

        $now = now();
        $kpsName = Auth::user()->name ?? 'KPS Jurusan TIK';
        $revisionNotes = $validated['revision_notes'];

        $application->update([
            'status' => F1Application::STATUS_REVISION_REQUESTED,
            'revision_notes' => $revisionNotes,
            'approved_at' => null,
            'notes' => "Diminta revisi oleh KPS ({$kpsName}): {$revisionNotes}",
            'updated_at' => $now,
        ]);

        if ($application->student && $application->student->dosen_id !== null) {
            $application->student->update([
                'dosen_id' => null,
            ]);
        }

        // Sinkronisasi PengajuanMagang agar tetap berstatus usulan selama proses revisi di Admin
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $application->student_id)->latest()->first();
        if ($pengajuan) {
            $pengajuan->update([
                'status_dosen' => 'usulan',
                'updated_at' => $now,
            ]);
        }

        return redirect()->route('dashboard.kps', ['tab' => 'plotting'])
            ->with('info', "Permintaan revisi plotting untuk mahasiswa {$application->student->name} telah dikirimkan kembali ke Admin Jurusan beserta catatan KPS.");
    }

    /**
     * Alias kompatibilitas untuk revisi Form F1 oleh KPS.
     */
    public function rejectF1(Request $request, F1Application $application): RedirectResponse
    {
        return $this->revisiF1($request, $application);
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
