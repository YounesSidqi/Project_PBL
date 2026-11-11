<?php

namespace App\Http\Controllers;

use App\Models\BimbinganF4;
use App\Models\EvaluasiF6;
use App\Models\Industri;
use App\Models\PengajuanMagang;
use App\Models\User;
use App\Services\FuzzyScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Mahasiswa mengajukan Permintaan Surat Pengantar PKL & Data Industri (PRD 5.1 & 5.2).
     */
    public function requestSuratPengantar(Request $request)
    {
        $request->validate([
            'industri_id' => 'nullable|exists:industris,id',
            'nama_perusahaan' => 'required_without:industri_id|nullable|string|max:255',
            'bidang_usaha' => 'nullable|string|max:150',
            'alamat' => 'nullable|string|max:500',
            'kota' => 'nullable|string|max:100',
            'kontak_person' => 'nullable|string|max:150',
            'email_pj' => 'nullable|email|max:150',
            'telepon' => 'nullable|string|max:50',
            'posisi_magang' => 'nullable|string|max:150',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ], [
            'nama_perusahaan.required_without' => 'Nama perusahaan wajib diisi jika tidak memilih mitra industri terdaftar.',
        ]);

        /** @var User $student */
        $student = Auth::user();

        $existingPengajuan = PengajuanMagang::where('mahasiswa_id', $student->id)
            ->with('industri')
            ->latest()
            ->first();

        // Proteksi Anti-Spam: Mahasiswa tidak dapat mengirim ulang form jika sedang pending verifikasi Admin atau sudah disahkan
        if ($existingPengajuan) {
            $industriStatus = $existingPengajuan->industri?->status_verifikasi;
            $suratStatus = $existingPengajuan->status_surat_pengantar;

            $canResubmit = in_array($suratStatus, ['draft', 'ditolak'], true)
                || $industriStatus === 'reject';

            if (! $canResubmit) {
                return redirect()->back()
                    ->with('error', 'Proteksi Anti-Spam: Usulan perusahaan Anda sedang dalam antrean verifikasi Admin Jurusan (atau telah disahkan). Anda tidak dapat mengirim pengajuan baru kecuali ditolak atau di-reset ke Draft oleh Admin.');
            }
        }

        $oldPendingIndustri = ($existingPengajuan && $existingPengajuan->industri && $existingPengajuan->industri->status_verifikasi !== 'acc' && ! $existingPengajuan->industri->user_id)
            ? $existingPengajuan->industri
            : null;

        if ($request->filled('industri_id')) {
            $industri = Industri::findOrFail($request->industri_id);
        } else {
            $namaPerusahaan = trim((string) $request->nama_perusahaan);
            $industri = Industri::where('nama_perusahaan', $namaPerusahaan)->first();

            if (! $industri && $oldPendingIndustri && $oldPendingIndustri->pengajuanMagangs()->where('mahasiswa_id', '!=', $student->id)->count() === 0) {
                // Gunakan kembali record usulan lama milik mahasiswa ini agar tidak menimbulkan spam data di tabel industris
                $oldPendingIndustri->update([
                    'nama_perusahaan' => $namaPerusahaan,
                    'bidang_usaha' => $request->bidang_usaha,
                    'alamat' => $request->alamat,
                    'kota' => $request->kota ?: 'Jakarta',
                    'kontak_person' => $request->kontak_person,
                    'email_pj' => $request->email_pj,
                    'telepon' => $request->telepon,
                    'status_verifikasi' => 'pending',
                    'catatan_admin' => 'Usulan perusahaan diperbarui oleh mahasiswa dan menunggu verifikasi Admin Jurusan.',
                ]);
                $industri = $oldPendingIndustri;
            } elseif (! $industri) {
                $industri = Industri::create([
                    'nama_perusahaan' => $namaPerusahaan,
                    'bidang_usaha' => $request->bidang_usaha,
                    'alamat' => $request->alamat,
                    'kota' => $request->kota ?: 'Jakarta',
                    'kontak_person' => $request->kontak_person,
                    'email_pj' => $request->email_pj,
                    'telepon' => $request->telepon,
                    'status_verifikasi' => 'pending',
                    'catatan_admin' => 'Usulan perusahaan diajukan oleh mahasiswa. Menunggu pemeriksaan & pengesahan Admin Jurusan.',
                ]);
            } elseif ($industri->status_verifikasi !== 'acc') {
                $industri->update([
                    'bidang_usaha' => $request->bidang_usaha ?: $industri->bidang_usaha,
                    'alamat' => $request->alamat ?: $industri->alamat,
                    'kota' => $request->kota ?: $industri->kota,
                    'kontak_person' => $request->kontak_person ?: $industri->kontak_person,
                    'email_pj' => $request->email_pj ?: $industri->email_pj,
                    'telepon' => $request->telepon ?: $industri->telepon,
                    'status_verifikasi' => 'pending',
                    'catatan_admin' => 'Usulan perusahaan diperbarui oleh mahasiswa dan menunggu verifikasi Admin Jurusan.',
                ]);
            }
        }

        // Ambil atau buat PengajuanMagang aktif mahasiswa (status pending menunggu verifikasi Admin)
        PengajuanMagang::updateOrCreate(
            ['mahasiswa_id' => $student->id],
            [
                'industri_id' => $industri->id,
                'posisi_magang' => $request->posisi_magang ?: ($existingPengajuan?->posisi_magang ?: 'Software Engineer Intern'),
                'tanggal_mulai' => $request->tanggal_mulai ?: $existingPengajuan?->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai ?: $existingPengajuan?->tanggal_selesai,
                'status_surat_pengantar' => 'pending',
                'catatan_penolakan' => null,
                'dosen_id' => $student->dosen_id ?: $existingPengajuan?->dosen_id,
            ]
        );

        // Bersihkan record industri lama jika mahasiswa berpindah ke industri lain dan record lama tidak dipakai mahasiswa lain
        if ($oldPendingIndustri && $oldPendingIndustri->id !== $industri->id && $oldPendingIndustri->pengajuanMagangs()->count() === 0) {
            $oldPendingIndustri->delete();
        }

        $statusMsg = $industri->status_verifikasi === 'acc'
            ? "Formulir pengajuan penempatan pada mitra terdaftar \"{$industri->nama_perusahaan}\" berhasil dikirim dan menunggu persetujuan Admin Jurusan!"
            : "Formulir usulan perusahaan \"{$industri->nama_perusahaan}\" berhasil dikirim (Status: Pending). Admin Jurusan akan memeriksa validitas perusahaan dan memasukkannya ke daftar mitra resmi.";

        return redirect()->back()->with('success', $statusMsg);
    }

    /**
     * Dosen Pembimbing PNJ menginput Form Evaluasi Akademik (Form F6 - Bobot 40%).
     */
    public function storeEvaluasiF6(Request $request, PengajuanMagang $pengajuan)
    {
        $rules = [
            'skor_kesesuaian_judul' => 'required|numeric|min:0|max:100',
            'skor_tata_bahasa' => 'required|numeric|min:0|max:100',
            'skor_format_laporan' => 'required|numeric|min:0|max:100',
            'skor_pengetahuan' => 'required|numeric|min:0|max:100',
            'skor_tanya_jawab' => 'required|numeric|min:0|max:100',
            'skor_etika' => 'required|numeric|min:0|max:100',
            'skor_justifikasi' => 'required|numeric|min:0|max:100',
            'skor_rutinitas_bimbingan' => 'required|numeric|min:0|max:100',
            'skor_progres' => 'required|numeric|min:0|max:100',
            'catatan_dosen' => 'nullable|string|max:2000',
        ];

        $validated = $request->validate($rules);

        // Hitung rata-rata skor F6 (9 parameter)
        $sum = $validated['skor_kesesuaian_judul'] +
            $validated['skor_tata_bahasa'] +
            $validated['skor_format_laporan'] +
            $validated['skor_pengetahuan'] +
            $validated['skor_tanya_jawab'] +
            $validated['skor_etika'] +
            $validated['skor_justifikasi'] +
            $validated['skor_rutinitas_bimbingan'] +
            $validated['skor_progres'];

        $rataRata = round($sum / 9.0, 1);

        EvaluasiF6::updateOrCreate(
            ['pengajuan_magang_id' => $pengajuan->id],
            array_merge($validated, [
                'rata_rata_f6' => $rataRata,
                'submitted_at' => now(),
            ])
        );

        return redirect()->back()
            ->with('success', "Form Evaluasi Akademik Dosen (F6) untuk mahasiswa {$pengajuan->mahasiswa->name} berhasil disimpan! Rata-rata Skor: {$rataRata}/100 (Bobot 40%).");
    }

    /**
     * Dosen Pembimbing mengeksekusi Kalkulasi & Transmisi Nilai Akhir Fuzzy Logic via Python Engine (PRD 7.5).
     */
    public function kalkulasiFuzzy(Request $request, PengajuanMagang $pengajuan, FuzzyScoringService $fuzzyService)
    {
        $pengajuan->load(['evaluasiF5', 'evaluasiF6', 'mahasiswa']);

        // 1. Validasi syarat minimal 8 sesi bimbingan F4
        $totalBimbingan = BimbinganF4::where('pengajuan_magang_id', $pengajuan->id)->count();
        if ($totalBimbingan < 8) {
            return redirect()->back()
                ->with('error', "Nilai akhir Fuzzy Logic belum dapat ditransmisikan karena jumlah sesi bimbingan F4 baru {$totalBimbingan} kali. Sesuai Panduan PKL 2026, wajib menyelesaikan minimal 8 kali sesi bimbingan sebelum evaluasi akhir.");
        }

        // 2. Validasi kelengkapan Form F5 (Industri)
        if (! $pengajuan->evaluasiF5) {
            return redirect()->back()
                ->with('error', 'Pembimbing Industri belum melengkapi Form Evaluasi Nilai F5 (Bobot 60%). Kalkulasi tidak dapat dijalankan.');
        }

        // 3. Validasi kelengkapan Form F6 (Dosen)
        if (! $pengajuan->evaluasiF6) {
            return redirect()->back()
                ->with('error', 'Form Evaluasi Akademik Dosen F6 (Bobot 40%) belum dilengkapi. Silakan isi form evaluasi F6 terlebih dahulu.');
        }

        $f5Score = $pengajuan->evaluasiF5->rata_rata_f5;
        $f6Score = $pengajuan->evaluasiF6->rata_rata_f6;

        // Eksekusi engine Python Fuzzy Logic (Mamdani/Tsukamoto)
        $result = $fuzzyService->calculate($f5Score, $f6Score);

        $pengajuan->update([
            'nilai_akhir_fuzzy' => $result['display'], // e.g. "41.9/50"
            'fuzzy_score_num' => $result['score_num'],
            'fuzzy_percentage' => $result['percentage'],
            'updated_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "Kalkulasi Nilai Berbasis Python Fuzzy Logic berhasil dieksekusi! Output Skor Akhir: {$result['display']} (Persentase: {$result['percentage']}%). Nilai telah ditransmisikan ke portal mahasiswa.");
    }
}
