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
     * Mahasiswa mengajukan Permintaan Surat Pengantar PKL & Data Industri.
     */
    public function requestSuratPengantar(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'kota' => 'nullable|string|max:100',
            'kontak_person' => 'nullable|string|max:150',
            'email_pj' => 'nullable|email|max:150',
            'telepon' => 'nullable|string|max:50',
            'posisi_magang' => 'nullable|string|max:150',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        /** @var User $student */
        $student = Auth::user();

        // Cek atau buat entitas Industri baru jika belum ada
        $industri = Industri::firstOrCreate(
            ['nama_perusahaan' => trim($request->nama_perusahaan)],
            [
                'alamat' => $request->alamat,
                'kota' => $request->kota ?: 'Jakarta',
                'kontak_person' => $request->kontak_person,
                'email_pj' => $request->email_pj,
                'telepon' => $request->telepon,
                'status_verifikasi' => 'pending',
                'catatan_admin' => 'Pendaftaran diajukan oleh mahasiswa melalui permohonan Surat Pengantar.',
            ]
        );

        // Ambil atau buat PengajuanMagang aktif mahasiswa
        $pengajuan = PengajuanMagang::updateOrCreate(
            ['mahasiswa_id' => $student->id],
            [
                'industri_id' => $industri->id,
                'posisi_magang' => $request->posisi_magang ?: 'Software Engineer Intern',
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status_surat_pengantar' => 'pending',
                'dosen_id' => $student->dosen_id,
            ]
        );

        return redirect()->back()
            ->with('success', "Permohonan Surat Pengantar PKL untuk PT/Instansi \"{$industri->nama_perusahaan}\" berhasil dikirimkan ke Admin Jurusan TIK!");
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
