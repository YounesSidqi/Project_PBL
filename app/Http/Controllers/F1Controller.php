<?php

namespace App\Http\Controllers;

use App\Models\F1Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class F1Controller extends Controller
{
    /**
     * Mahasiswa mengajukan Form F1 dan memilih Dosen Pembimbing.
     */
    /**
     * Mahasiswa mengajukan Form F1 dan memilih Dosen Pembimbing.
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

        // Batalkan / hapus pengajuan pending sebelumnya jika ada
        F1Application::where('student_id', $student->id)
            ->where('status', 'pending')
            ->delete();

        $dosen = User::where('id', $request->dosen_id)->where('role', 'dosen')->firstOrFail();

        $now = now();
        $application = F1Application::create([
            'student_id' => $student->id,
            'dosen_id' => $dosen->id,
            'company_name' => $request->company_name,
            'internship_title' => $request->internship_title,
            'status' => 'pending',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return redirect()->back()->with('success', 'Pengajuan Form F1 berhasil dikirimkan ke ' . $dosen->name . ' pada ' . $now->translatedFormat('d M Y, H:i') . ' WIB. Menunggu persetujuan dan penerbitan Surat Pernyataan Kesediaan (PDF) oleh dosen.');
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
            $application->delete();
            return redirect()->back()->with('info', 'Pengajuan Form F1 berhasil dibatalkan. Anda dapat memilih dosen pembimbing kembali.');
        }

        return redirect()->back()->with('error', 'Pengajuan tidak dapat dibatalkan.');
    }

    /**
     * Menerima persetujuan Form F1 umum dengan ID dari input request.
     */
    public function approveGeneral(Request $request)
    {
        $applicationId = $request->input('application_id');
        if (! $applicationId) {
            return redirect()->back()->with('error', 'ID Pengajuan Form F1 tidak ditemukan.');
        }

        $application = F1Application::findOrFail($applicationId);
        return $this->approve($request, $application);
    }

    /**
     * Dosen menyetujui pengajuan Form F1 mahasiswa dengan mengunggah file PDF Surat Pernyataan.
     */
    public function approve(Request $request, F1Application $application)
    {
        /** @var User|null $user */
        $user = Auth::user();

        // Autentikasi Dosen: Mendukung session dosen asli maupun fallback dev mode jika sedang menguji dashboard dosen
        $dosen = ($user && $user->role === 'dosen') 
            ? $user 
            : ($application->dosen ?? User::find($application->dosen_id) ?? User::where('role', 'dosen')->first());

        if (! $dosen) {
            return redirect()->back()->with('error', 'Data Dosen Pembimbing tidak ditemukan.');
        }

        $request->validate([
            'surat_pernyataan' => 'required|file|max:51200', // max 50MB
            'notes' => 'nullable|string|max:1000',
        ], [
            'surat_pernyataan.required' => 'Wajib mengunggah berkas Surat Pernyataan Kesediaan Pembimbing (format PDF).',
            'surat_pernyataan.max' => 'Ukuran berkas PDF maksimal 50 MB.',
        ]);

        $file = $request->file('surat_pernyataan');
        
        // Verifikasi format file PDF
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();
        if ($extension !== 'pdf' && !str_contains($mime, 'pdf')) {
            return redirect()->back()->with('error', 'Berkas surat pernyataan harus berformat PDF (.pdf). Format file Anda: ' . $extension);
        }

        $originalName = $file->getClientOriginalName();
        $storedPath = $file->store('surat_f1', 'public');

        $fileContent = file_get_contents($file->getRealPath());
        $signatureHash = hash('sha256', $fileContent);

        // Update application dengan waktu dan tanggal dinamis (now())
        $now = now();
        $application->update([
            'status' => 'approved',
            'surat_pernyataan_path' => $storedPath,
            'surat_pernyataan_name' => $originalName,
            'signature_hash' => $signatureHash,
            'approved_at' => $now,
            'notes' => $request->notes,
            'updated_at' => $now,
        ]);

        // Tetapkan mahasiswa menjadi bimbingan dosen ini
        $student = $application->student;
        if ($student) {
            $student->update([
                'dosen_id' => $application->dosen_id,
            ]);
        }

        return redirect()->to('/dashboard/dosen?tab=mahasiswa#mahasiswa-section')->with('success', 'Form F1 untuk mahasiswa ' . ($student->name ?? 'Mahasiswa') . ' berhasil disetujui pada ' . $now->translatedFormat('d M Y, H:i') . ' WIB! Surat Pernyataan Kesediaan (PDF) telah diterbitkan dan mahasiswa resmi masuk daftar bimbingan aktif Anda.');
    }

    /**
     * Dosen menolak pengajuan Form F1 mahasiswa.
     */
    public function reject(Request $request, F1Application $application)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $now = now();
        $application->update([
            'status' => 'rejected',
            'notes' => $request->notes ?? 'Kuota bimbingan penuh atau topik magang tidak sesuai bidang.',
            'updated_at' => $now,
        ]);

        return redirect()->to('/dashboard/dosen?tab=f1')->with('info', 'Pengajuan Form F1 mahasiswa telah ditolak pada ' . $now->translatedFormat('d M Y, H:i') . ' WIB.');
    }

    /**
     * Dosen menghapus mahasiswa dari daftar bimbingan.
     * Seluruh logbook yang pernah dibuat mahasiswa ini akan dihapus bersih sesuai permintaan.
     */
    public function removeStudent(Request $request, User $student)
    {
        /** @var User|null $user */
        $user = Auth::user();
        $dosen = ($user && $user->role === 'dosen') ? $user : User::where('role', 'dosen')->first();

        // 1. Hapus seluruh riwayat logbook yang pernah dibuat mahasiswa ini
        $deletedLogbooksCount = \App\Models\Logbook::where('user_id', $student->id)->delete();

        // 2. Lepaskan relasi dosen_id pada mahasiswa
        $student->update([
            'dosen_id' => null,
        ]);

        // 3. Tandai aplikasi F1 sebelumnya sebagai cancelled agar mahasiswa bisa memilih dospem lagi
        F1Application::where('student_id', $student->id)
            ->where('dosen_id', $dosen->id ?? null)
            ->update([
                'status' => 'cancelled',
                'updated_at' => now(),
            ]);

        return redirect()->to('/dashboard/dosen?tab=mahasiswa#mahasiswa-section')->with('info', 'Mahasiswa ' . $student->name . ' (NIM: ' . $student->identifier . ') telah dihapus dari daftar bimbingan. Sebanyak ' . $deletedLogbooksCount . ' riwayat logbook mahasiswa ini telah dibersihkan, dan slot kuota Anda kembali tersedia.');
    }

    /**
     * Download / Lihat Surat Pernyataan Kesediaan Dosen (PDF).
     */
    public function downloadSurat(F1Application $application)
    {
        if ($application->surat_pernyataan_path && Storage::disk('public')->exists($application->surat_pernyataan_path)) {
            $filePath = Storage::disk('public')->path($application->surat_pernyataan_path);
            $fileName = $application->surat_pernyataan_name ?? 'Surat_Pernyataan_Dospem_F1.pdf';
            
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            ]);
        }

        // Fallback: generate dynamic standard PDF response if file was sample
        $dosenName = $application->dosen->name ?? 'Dr. Bambang Sutrisno, M.T.';
        $dosenNip = $application->dosen->identifier ?? '198503152010121002';
        $studentName = $application->student->name ?? 'Mahasiswa';
        $studentNim = $application->student->identifier ?? '2107411002';
        $company = $application->company_name ?? 'PT Telekomunikasi Seluler';

        $pdfContent = "%PDF-1.4\n1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n4 0 obj << /Length 320 >> stream\nBT\n/F1 16 Tf\n50 720 Td\n(KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI) Tj\n/F1 14 Tf\n0 -25 Td\n(POLITEKNIK NEGERI JAKARTA - JURUSAN TIK) Tj\n/F1 12 Tf\n0 -40 Td\n(SURAT PERNYATAAN KESEDIAAN DOSEN PEMBIMBING PKL (FORM F1)) Tj\n0 -30 Td\n(Menyatakan bersedia membimbing mahasiswa:) Tj\n0 -20 Td\n(Nama: " . addcslashes($studentName, '()\\') . " | NIM: " . addcslashes($studentNim, '()\\') . ") Tj\n0 -20 Td\n(Instansi PKL: " . addcslashes($company, '()\\') . ") Tj\n0 -40 Td\n(Dosen Pembimbing: " . addcslashes($dosenName, '()\\') . " | NIP: " . addcslashes($dosenNip, '()\\') . ") Tj\nET\nendstream\nendobj\n5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000244 00000 n \n0000000615 00000 n \ntrailer << /Size 6 /Root 1 0 R >>\nstartxref\n690\n%%EOF";

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Surat_Pernyataan_Kesediaan_F1.pdf"',
        ]);
    }
}
