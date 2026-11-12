<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiF5;
use App\Models\Industri;
use App\Models\Logbook;
use App\Models\PengajuanMagang;
use App\Models\TugasF2;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndustriController extends Controller
{
    /**
     * Tampilan utama Dashboard Pembimbing Industri (DUDI).
     */
    public function index(Request $request)
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();

        // Cari profil industri terkait akun ini
        $industri = null;
        if ($authUser && $authUser->isIndustri()) {
            $industri = Industri::where('user_id', $authUser->id)->first();
        }

        // Fallback untuk demo jika login sebagai admin/dosen yang mengunjungi dashboard industri
        if (! $industri) {
            $industri = Industri::where('status_verifikasi', 'acc')->first();
        }

        $tab = $request->query('tab', 'mahasiswa');
        $selectedStudentId = $request->query('student_id');

        if ($tab === 'f5') {
            return redirect()->route('form.f5.industri', array_filter(['student_id' => $selectedStudentId]));
        }

        // Daftar mahasiswa yang magang di industri ini
        $pengajuans = PengajuanMagang::where('industri_id', $industri?->id)
            ->with(['mahasiswa', 'dosen', 'tugasF2', 'evaluasiF5'])
            ->get();

        // Pilih mahasiswa aktif untuk interaksi detail (F2, F3, F5)
        $activePengajuan = null;
        if ($selectedStudentId) {
            $activePengajuan = $pengajuans->firstWhere('mahasiswa_id', $selectedStudentId);
        }
        if (! $activePengajuan) {
            $activePengajuan = $pengajuans->first();
        }

        // Ambil data Tugas F2 untuk mahasiswa aktif
        $tugasList = $activePengajuan
            ? TugasF2::where('pengajuan_magang_id', $activePengajuan->id)->orderBy('created_at', 'asc')->get()
            : collect();

        // Ambil logbook F3 untuk mahasiswa aktif
        $logbooks = $activePengajuan
            ? Logbook::where('user_id', $activePengajuan->mahasiswa_id)->orderBy('tanggal', 'desc')->get()
            : collect();

        // Ambil data evaluasi F5 jika sudah pernah diisi
        $evaluasiF5 = $activePengajuan?->evaluasiF5;

        return view('dashboard.dashboard-industri', compact(
            'industri',
            'tab',
            'pengajuans',
            'activePengajuan',
            'tugasList',
            'logbooks',
            'evaluasiF5'
        ));
    }

    /**
     * Tambah To-Do List Tugas F2 Mahasiswa.
     */
    public function storeTugasF2(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate([
            'modul_pekerjaan' => 'required|string|max:255',
            'spesifikasi_kebutuhan' => 'required|string|max:2000',
            'target_minggu' => 'nullable|string|max:50',
        ]);

        TugasF2::create([
            'pengajuan_magang_id' => $pengajuan->id,
            'modul_pekerjaan' => $request->modul_pekerjaan,
            'spesifikasi_kebutuhan' => $request->spesifikasi_kebutuhan,
            'target_minggu' => $request->target_minggu ?: 'Minggu 1-4',
            'status' => 'todo',
        ]);

        return redirect()->to(route('dashboard.industri', [
            'tab' => 'f2',
            'student_id' => $pengajuan->mahasiswa_id,
        ]))->with('success', "Spesifikasi tugas \"{$request->modul_pekerjaan}\" berhasil ditambahkan ke matriks Form F2!");
    }

    /**
     * Update Status Tugas F2 (todo / in_progress / acc) oleh Pembimbing Industri (DUDI).
     */
    public function updateTugasF2(Request $request, TugasF2 $tugas)
    {
        /** @var User|null $authUser */
        $authUser = Auth::user();
        if ($authUser && $authUser->role === 'mahasiswa') {
            return redirect()->back()
                ->with('error', 'Hanya Pembimbing Industri (DUDI) yang dapat mengubah status tugas Form F2.');
        }

        $request->validate([
            'status' => 'required|in:todo,in_progress,acc',
            'catatan_industri' => 'nullable|string|max:1000',
        ]);

        $tugas->update([
            'status' => $request->status,
            'catatan_industri' => $request->has('catatan_industri') ? $request->catatan_industri : $tugas->catatan_industri,
        ]);

        $statusLabels = [
            'todo' => 'To Do (Rencana)',
            'in_progress' => 'Dalam Pengerjaan',
            'acc' => 'Disetujui / Selesai (ACC)',
        ];

        return redirect()->back()
            ->with('success', "Status tugas \"{$tugas->modul_pekerjaan}\" berhasil diperbarui menjadi {$statusLabels[$request->status]}!");
    }

    /**
     * Hapus Tugas F2.
     */
    public function destroyTugasF2(TugasF2 $tugas)
    {
        $nama = $tugas->modul_pekerjaan;
        $tugas->delete();

        return redirect()->back()
            ->with('info', "Tugas \"{$nama}\" telah dihapus dari backlog.");
    }

    /**
     * Menyetujui (ACC) Logbook Harian F3 Mahasiswa.
     */
    public function accLogbookF3(Request $request, Logbook $logbook)
    {
        $logbook->update([
            'status_industri' => 'acc',
            'catatan_industri' => $request->catatan_industri ?: 'Aktivitas harian disetujui sesuai target kerja industri.',
            'status' => 'approved',
        ]);

        return redirect()->back()
            ->with('success', "Logbook tanggal {$logbook->tanggal->translatedFormat('d M Y')} berhasil di-ACC!");
    }

    /**
     * Memberikan Catatan Revisi pada Logbook F3.
     */
    public function revisiLogbookF3(Request $request, Logbook $logbook)
    {
        $request->validate([
            'catatan_industri' => 'required|string|max:1000',
        ]);

        $logbook->update([
            'status_industri' => 'revisi',
            'catatan_industri' => $request->catatan_industri,
            'status' => 'revision',
        ]);

        return redirect()->back()
            ->with('info', "Catatan revisi logbook tanggal {$logbook->tanggal->translatedFormat('d M Y')} telah dikirimkan ke mahasiswa.");
    }

    /**
     * Simpan Evaluasi Nilai Form F5 (Bobot 60% - Rahasia Industri).
     */
    public function storeEvaluasiF5(Request $request, PengajuanMagang $pengajuan)
    {
        $rules = [
            'skor_komunikasi' => 'required|numeric|min:0|max:100',
            'skor_berpikir_kritis' => 'required|numeric|min:0|max:100',
            'skor_kerjasama' => 'required|numeric|min:0|max:100',
            'skor_inisiatif' => 'required|numeric|min:0|max:100',
            'skor_literasi' => 'required|numeric|min:0|max:100',
            'skor_deskripsi_produk' => 'required|numeric|min:0|max:100',
            'skor_spesifikasi' => 'required|numeric|min:0|max:100',
            'skor_desain' => 'required|numeric|min:0|max:100',
            'skor_implementasi' => 'required|numeric|min:0|max:100',
            'skor_pengujian' => 'required|numeric|min:0|max:100',
            'saran_industri' => 'nullable|string|max:2000',
        ];

        $validated = $request->validate($rules);

        // Hitung rata-rata F5
        $sum = $validated['skor_komunikasi'] +
            $validated['skor_berpikir_kritis'] +
            $validated['skor_kerjasama'] +
            $validated['skor_inisiatif'] +
            $validated['skor_literasi'] +
            $validated['skor_deskripsi_produk'] +
            $validated['skor_spesifikasi'] +
            $validated['skor_desain'] +
            $validated['skor_implementasi'] +
            $validated['skor_pengujian'];

        $rataRata = round($sum / 10.0, 1);

        EvaluasiF5::updateOrCreate(
            ['pengajuan_magang_id' => $pengajuan->id],
            array_merge($validated, [
                'rata_rata_f5' => $rataRata,
                'submitted_at' => now(),
            ])
        );

        return redirect()->route('form.f5.industri', [
            'pengajuan_id' => $pengajuan->id,
            'student_id' => $pengajuan->mahasiswa_id,
        ])->with('success', "Form Evaluasi Industri (F5) untuk mahasiswa {$pengajuan->mahasiswa->name} berhasil disimpan secara rahasia! Rata-rata Skor: {$rataRata}/100 (Bobot 60%).");
    }
}
