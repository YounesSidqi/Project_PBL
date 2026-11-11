<?php

namespace App\Http\Controllers;

use App\Models\BimbinganF4;
use App\Models\PengajuanMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimbinganF4Controller extends Controller
{
    /**
     * Catat Sesi Bimbingan Akademik Form F4.
     */
    public function store(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate([
            'tanggal_bimbingan' => 'required|date',
            'materi_bimbingan' => 'required|string|max:2000',
            'progres_laporan' => 'nullable|string|max:1000',
            'catatan_dosen' => 'nullable|string|max:1000',
        ]);

        $nextSession = (BimbinganF4::where('pengajuan_magang_id', $pengajuan->id)->max('sesi_ke') ?? 0) + 1;

        BimbinganF4::create([
            'pengajuan_magang_id' => $pengajuan->id,
            'mahasiswa_id' => $pengajuan->mahasiswa_id,
            'dosen_id' => $pengajuan->dosen_id ?? Auth::id(),
            'sesi_ke' => $nextSession,
            'tanggal_bimbingan' => $request->tanggal_bimbingan,
            'materi_bimbingan' => $request->materi_bimbingan,
            'progres_laporan' => $request->progres_laporan,
            'catatan_dosen' => $request->catatan_dosen,
            'status' => 'acc',
        ]);

        $totalSessions = BimbinganF4::where('pengajuan_magang_id', $pengajuan->id)->count();

        $info = $totalSessions >= 8
            ? ' (Status: Telah memenuhi syarat minimum 8 sesi bimbingan)'
            : " (Progres: {$totalSessions}/8 Sesi)";

        return redirect()->back()
            ->with('success', "Sesi Bimbingan Ke-{$nextSession} berhasil dicatat pada Form F4!{$info}");
    }

    /**
     * Hapus Catatan Bimbingan.
     */
    public function destroy(BimbinganF4 $bimbingan)
    {
        $sesi = $bimbingan->sesi_ke;
        $bimbingan->delete();

        return redirect()->back()
            ->with('info', "Catatan bimbingan sesi ke-{$sesi} telah dihapus.");
    }
}
