<?php

namespace App\Http\Controllers;

use App\Models\F1Application;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class F1Controller extends Controller
{
    /**
     * Mahasiswa mengajukan Form F1 dan memilih preferensi Dosen Pembimbing.
     * Penetapan resmi dilakukan oleh Koordinator Program Studi (KPS).
     */
    public function selectDosen(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'internship_title' => 'required|string|max:255',
        ]);

        /** @var User $student */
        $student = Auth::user();

        $dosen = User::where('id', $request->dosen_id)->where('role', 'dosen')->firstOrFail();

        // Validasi kuota dosen (maks 7)
        $currentMentoredCount = User::where('role', 'mahasiswa')->where('dosen_id', $dosen->id)->count();
        if ($currentMentoredCount >= 7) {
            return redirect()->back()->with('error', 'Dosen yang Anda pilih baru saja mencapai batas kuota bimbingan penuh. Silakan pilih dosen lain yang tersedia.');
        }

        // Batalkan / hapus pengajuan pending sebelumnya jika ada
        F1Application::where('student_id', $student->id)
            ->where('status', 'pending')
            ->delete();

        $now = now();
        F1Application::create([
            'student_id' => $student->id,
            'dosen_id' => $dosen->id,
            'company_name' => $request->company_name,
            'internship_title' => $request->internship_title,
            'status' => 'pending',
            'notes' => 'Menunggu verifikasi dan penetapan resmi oleh Koordinator Program Studi (KPS).',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Sinkronisasi PengajuanMagang
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $student->id)->latest()->first();
        if ($pengajuan) {
            $pengajuan->update([
                'dosen_id' => $dosen->id,
                'status_dosen' => 'usulan',
                'judul_magang' => $request->internship_title,
                'updated_at' => $now,
            ]);
        } else {
            PengajuanMagang::create([
                'mahasiswa_id' => $student->id,
                'dosen_id' => $dosen->id,
                'status_dosen' => 'usulan',
                'judul_magang' => $request->internship_title,
                'status_surat_pengantar' => 'draft',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return redirect()->back()->with('success', 'Pengajuan preferensi dosen pembimbing ('.$dosen->name.') berhasil dikirimkan pada '.$now->translatedFormat('d M Y, H:i').' WIB! Koordinator Program Studi (KPS) akan memverifikasi dan menetapkan secara resmi.');
    }

    /**
     * Mahasiswa membatalkan pengajuan F1 yang masih pending.
     */
    public function cancel(F1Application $application)
    {
        if ($application->student_id !== Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if ($application->status === 'pending') {
            $studentId = $application->student_id;
            $application->delete();

            // Sinkronisasi PengajuanMagang
            $pengajuan = PengajuanMagang::where('mahasiswa_id', $studentId)->latest()->first();
            if ($pengajuan && $pengajuan->status_dosen === 'usulan') {
                $pengajuan->update([
                    'dosen_id' => null,
                    'status_dosen' => 'pending',
                    'updated_at' => now(),
                ]);
            }

            return redirect()->back()->with('info', 'Pengajuan Form F1 berhasil dibatalkan. Anda dapat memilih dosen pembimbing kembali.');
        }

        return redirect()->back()->with('error', 'Pengajuan tidak dapat dibatalkan.');
    }

    /**
     * Download / Lihat Surat Pernyataan Kesediaan Dosen (Form F1 Resmi).
     */
    public function downloadSurat(F1Application $application)
    {
        if ($application->surat_pernyataan_path && Storage::disk('public')->exists($application->surat_pernyataan_path)) {
            $filePath = Storage::disk('public')->path($application->surat_pernyataan_path);
            $fileName = $application->surat_pernyataan_name ?? 'Surat_Pernyataan_Dospem_F1.pdf';

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$fileName.'"',
            ]);
        }

        return redirect()->route('dokumen.f1', $application->id);
    }
}
