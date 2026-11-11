<?php

namespace App\Http\Controllers;

use App\Models\BimbinganF4;
use App\Models\F1Application;
use App\Models\Logbook;
use App\Models\PengajuanMagang;
use App\Models\User;

class DokumenController extends Controller
{
    /**
     * Cetak / Lihat Surat Pengantar PKL Ber-KOP Resmi JTIK PNJ.
     */
    public function suratPengantar(PengajuanMagang $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'industri', 'dosen']);

        return view('dokumen.surat-pengantar', compact('pengajuan'));
    }

    /**
     * Cetak / Lihat Form F1 (Surat Kesediaan Dosen Pembimbing) dengan Checklist Oval & Stempel Digital.
     */
    public function cetakF1(F1Application $application)
    {
        $application->load(['student', 'dosen']);

        return view('dokumen.f1-pdf', compact('application'));
    }

    /**
     * Cetak / Lihat Form F2 (Spesifikasi Tugas & User Requirement Industri).
     */
    public function cetakF2(PengajuanMagang $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'industri', 'dosen', 'tugasF2']);
        $tugasList = $pengajuan->tugasF2;

        return view('dokumen.f2-pdf', compact('pengajuan', 'tugasList'));
    }

    /**
     * Cetak / Lihat Form F3 (Rekap Logbook Harian Industri dengan Mark Backdate).
     */
    public function cetakF3(PengajuanMagang $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'industri', 'dosen']);
        $logbooks = Logbook::where('user_id', $pengajuan->mahasiswa_id)->orderBy('tanggal', 'asc')->get();

        return view('dokumen.f3-pdf', compact('pengajuan', 'logbooks'));
    }

    /**
     * Cetak / Lihat Form F4 (Buku Catatan Bimbingan Dosen PNJ - Min. 8 Sesi).
     */
    public function cetakF4(PengajuanMagang $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'industri', 'dosen']);
        $bimbingans = BimbinganF4::where('pengajuan_magang_id', $pengajuan->id)->orderBy('sesi_ke', 'asc')->get();

        return view('dokumen.f4-pdf', compact('pengajuan', 'bimbingans'));
    }

    /**
     * Cetak / Lihat Form F5 (Lembar Evaluasi Kinerja Industri - Bobot 60%).
     */
    public function cetakF5(PengajuanMagang $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'industri', 'dosen', 'evaluasiF5']);

        return view('dokumen.f5-pdf', compact('pengajuan'));
    }

    /**
     * Cetak / Lihat Form F6 (Lembar Evaluasi Akademik Dosen - Bobot 40%).
     */
    public function cetakF6(PengajuanMagang $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'industri', 'dosen', 'evaluasiF6', 'bimbingans']);

        return view('dokumen.f6-pdf', compact('pengajuan'));
    }

    /**
     * Cetak / Lihat Form F7 (Surat Pernyataan Selesai PKL).
     */
    public function cetakF7(PengajuanMagang $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'industri', 'dosen', 'evaluasiF5', 'evaluasiF6']);

        return view('dokumen.f7-pdf', compact('pengajuan'));
    }
}
