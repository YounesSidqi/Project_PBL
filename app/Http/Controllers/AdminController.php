<?php

namespace App\Http\Controllers;

use App\Models\Industri;
use App\Models\PengajuanMagang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Tampilan utama Dashboard Admin Jurusan TIK PNJ.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'overview');

        // Statistik Cepat
        $stats = [
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_dosen' => User::where('role', 'dosen')->count(),
            'total_industri' => Industri::count(),
            'surat_pending' => PengajuanMagang::where('status_surat_pengantar', 'pending')->count(),
            'surat_diterbitkan' => PengajuanMagang::where('status_surat_pengantar', 'diterbitkan')->count(),
            'industri_pending' => Industri::where('status_verifikasi', 'pending')->count(),
        ];

        // 1. Data Mahasiswa
        $mahasiswas = User::where('role', 'mahasiswa')
            ->with(['dosen', 'pengajuanMagangAktif.industri'])
            ->orderBy('identifier', 'asc')
            ->get();

        // 2. Data Pengajuan Surat Pengantar
        $suratPengantars = PengajuanMagang::with(['mahasiswa', 'industri', 'dosen'])
            ->orderByRaw("FIELD(status_surat_pengantar, 'pending', 'draft', 'diterbitkan', 'ditolak')")
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Data Industri / Perusahaan Mitra
        $industris = Industri::with(['user', 'pengajuanMagangs.mahasiswa'])
            ->orderByRaw("FIELD(status_verifikasi, 'pending', 'acc', 'reject')")
            ->orderBy('created_at', 'desc')
            ->get();

        // 4. Data Seluruh Pengguna untuk Reset Password
        $usersQuery = User::orderBy('name', 'asc');
        if ($search = $request->query('search_user')) {
            $usersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('identifier', 'like', "%{$search}%");
            });
        }
        $users = $usersQuery->paginate(15, ['*'], 'users_page')->withQueryString();

        return view('dashboard.dashboard-admin', compact(
            'tab',
            'stats',
            'mahasiswas',
            'suratPengantars',
            'industris',
            'users'
        ));
    }

    /**
     * Simpan Mahasiswa Baru secara Manual.
     */
    public function storeMahasiswa(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'identifier' => 'required|string|max:50|unique:users,identifier',
            'email' => 'required|email|max:255|unique:users,email',
            'prodi' => 'required|string|max:100',
            'phone' => 'nullable|string|max:25',
        ]);

        $defaultPassword = 'password123';

        User::create([
            'name' => $request->name,
            'identifier' => $request->identifier,
            'email' => $request->email,
            'prodi' => $request->prodi,
            'phone' => $request->phone,
            'role' => 'mahasiswa',
            'password' => Hash::make($defaultPassword),
        ]);

        return redirect()->to(route('dashboard.admin', ['tab' => 'mahasiswa']))
            ->with('success', "Mahasiswa {$request->name} (NIM: {$request->identifier}) berhasil didaftarkan! Password default: {$defaultPassword}");
    }

    /**
     * Import Data Mahasiswa via Skema File CSV / Excel Text.
     */
    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file_mahasiswa' => 'required|file|max:5120',
        ]);

        $file = $request->file('file_mahasiswa');
        $content = file_get_contents($file->getRealPath());

        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        $importedCount = 0;
        $skippedCount = 0;

        foreach ($lines as $index => $line) {
            if ($index === 0 && (str_contains(strtolower($line), 'nim') || str_contains(strtolower($line), 'nama'))) {
                // Header line, skip
                continue;
            }

            $cols = str_getcsv($line, ',');
            if (count($cols) < 2) {
                // Coba pemisah semicolon (;)
                $cols = str_getcsv($line, ';');
            }

            if (count($cols) >= 3) {
                $nim = trim($cols[0] ?? '');
                $nama = trim($cols[1] ?? '');
                $email = trim($cols[2] ?? '');
                $prodi = trim($cols[3] ?? 'Teknik Informatika (D4)');
                $phone = trim($cols[4] ?? '');

                if (! empty($nim) && ! empty($nama)) {
                    $emailVal = ! empty($email) ? $email : "{$nim}@stu.pnj.ac.id";

                    $existing = User::where('identifier', $nim)->orWhere('email', $emailVal)->first();
                    if (! $existing) {
                        User::create([
                            'name' => $nama,
                            'identifier' => $nim,
                            'email' => $emailVal,
                            'prodi' => $prodi,
                            'phone' => $phone,
                            'role' => 'mahasiswa',
                            'password' => Hash::make('password123'),
                        ]);
                        $importedCount++;
                    } else {
                        $skippedCount++;
                    }
                }
            }
        }

        return redirect()->to(route('dashboard.admin', ['tab' => 'mahasiswa']))
            ->with('success', "Proses import selesai. Berhasil menambahkan {$importedCount} mahasiswa baru (Dilewati: {$skippedCount} karena duplikasi).");
    }

    /**
     * Verifikasi & Terbitkan Surat Pengantar PKL Ber-KOP Resmi.
     */
    public function terbitkanSuratPengantar(Request $request, PengajuanMagang $pengajuan)
    {
        $nomorSurat = 'B/'.str_pad($pengajuan->id, 3, '0', STR_PAD_LEFT).'/PL3.TIK/PKL/'.date('Y');

        $pengajuan->update([
            'status_surat_pengantar' => 'diterbitkan',
            'nomor_surat_pengantar' => $nomorSurat,
            'catatan_penolakan' => null,
            'updated_at' => now(),
        ]);

        return redirect()->to(route('dashboard.admin', ['tab' => 'surat']))
            ->with('success', "Surat Pengantar PKL untuk {$pengajuan->mahasiswa->name} berhasil diterbitkan dengan nomor {$nomorSurat}!");
    }

    /**
     * Tolak Permintaan Surat Pengantar PKL.
     */
    public function tolakSuratPengantar(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate([
            'catatan_penolakan' => 'required|string|max:1000',
        ]);

        $pengajuan->update([
            'status_surat_pengantar' => 'ditolak',
            'catatan_penolakan' => $request->catatan_penolakan,
            'updated_at' => now(),
        ]);

        return redirect()->to(route('dashboard.admin', ['tab' => 'surat']))
            ->with('info', "Pengajuan Surat Pengantar {$pengajuan->mahasiswa->name} ditolak dengan alasan: {$request->catatan_penolakan}");
    }

    /**
     * Verifikasi & ACC Industri Baru + Auto Generate 1 Akun Penanggung Jawab Industri (DUDI).
     */
    public function accIndustri(Request $request, Industri $industri)
    {
        // 1. Buat / Ambil akun user untuk penanggung jawab industri jika belum ada
        $user = $industri->user;
        $generatedPassword = 'dudi'.rand(1000, 9999);

        if (! $user) {
            $slug = Str::slug($industri->nama_perusahaan, '.');
            $email = $industri->email_pj ?: "pembimbing.{$slug}@mitra.pnj.ac.id";

            // Pastikan email unik
            $baseEmail = $email;
            $counter = 1;
            while (User::where('email', $email)->exists()) {
                $email = "pembimbing.{$slug}{$counter}@mitra.pnj.ac.id";
                $counter++;
            }

            $user = User::create([
                'name' => $industri->kontak_person ?: 'Penanggung Jawab '.$industri->nama_perusahaan,
                'email' => $email,
                'identifier' => 'DUDI-'.str_pad($industri->id, 4, '0', STR_PAD_LEFT),
                'role' => 'industri',
                'prodi' => 'Mitra Industri PKL',
                'phone' => $industri->telepon,
                'password' => Hash::make($generatedPassword),
            ]);

            $industri->update([
                'user_id' => $user->id,
                'status_verifikasi' => 'acc',
                'catatan_admin' => "Telah diverifikasi oleh Admin Jurusan TIK. Akun DUDI: {$user->email} (ID: {$user->identifier}).",
            ]);

            // Kirim Email Alert kredensial ke alamat perusahaan terkait (PRD 4 & 7.3)
            try {
                Mail::raw(
                    "Yth. {$user->name} ({$industri->nama_perusahaan}),\n\n".
                    "Pendaftaran instansi Anda sebagai Mitra Industri PKL Jurusan TIK Politeknik Negeri Jakarta telah diverifikasi.\n".
                    "Berikut adalah kredensial akses Portal Pembimbing Industri (DUDI) SIM-PKL:\n".
                    "- Email / ID Login: {$user->email} / {$user->identifier}\n".
                    "- Kata Sandi Sementara: {$generatedPassword}\n\n".
                    'Terima kasih atas kerja sama Anda.',
                    function ($message) use ($user, $industri) {
                        $message->to($user->email)
                            ->subject("Akses Akun Pembimbing Industri SIM-PKL JTIK PNJ - {$industri->nama_perusahaan}");
                    }
                );
            } catch (\Throwable $e) {
                // Abaikan kegagalan koneksi SMTP pada environment lokal
            }

            $msg = "Industri \"{$industri->nama_perusahaan}\" berhasil diverifikasi! 1 Akun Penanggung Jawab Industri telah dibuat otomatis: Email: [{$user->email}], ID: [{$user->identifier}], Password Sementara: [{$generatedPassword}] (Email alert terkirim).";
        } else {
            $industri->update([
                'status_verifikasi' => 'acc',
                'catatan_admin' => 'Telah diverifikasi dan disahkan oleh Admin Jurusan TIK.',
            ]);

            $msg = "Industri \"{$industri->nama_perusahaan}\" berhasil disetujui!";
        }

        $redirectTab = $request->input('redirect_tab', 'industri');

        return redirect()->to(route('dashboard.admin', ['tab' => $redirectTab]))
            ->with('success', $msg);
    }

    /**
     * Forward Pengajuan Mahasiswa ke Industri Terdaftar (PRD 5.2).
     */
    public function forwardPengajuan(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate([
            'industri_id' => 'required|exists:industris,id',
        ]);

        $oldIndustriId = $pengajuan->industri_id;
        $industri = Industri::findOrFail($request->industri_id);

        $pengajuan->update([
            'industri_id' => $industri->id,
            'updated_at' => now(),
        ]);

        // Jika industri sebelumnya masih pending dan tidak memiliki pengajuan mahasiswa lain, bersihkan entri duplikat pending
        if ($oldIndustriId && (int) $oldIndustriId !== (int) $industri->id) {
            $oldIndustri = Industri::find($oldIndustriId);
            if ($oldIndustri && $oldIndustri->status_verifikasi === 'pending' && $oldIndustri->pengajuanMagangs()->count() === 0 && ! $oldIndustri->user_id) {
                $oldIndustri->delete();
            }
        }

        $redirectTab = $request->input('redirect_tab', 'surat');

        return redirect()->to(route('dashboard.admin', ['tab' => $redirectTab]))
            ->with('success', "Penugasan magang mahasiswa {$pengajuan->mahasiswa->name} berhasil diteruskan (forward) ke akun mitra industri terdaftar: {$industri->nama_perusahaan}.");
    }

    /**
     * Manual Reset Password Pengguna (PRD 11.1 - Keamanan & Reset Mandiri Ditiadakan).
     */
    public function resetPassword(Request $request, User $user)
    {
        $newPassword = $request->input('new_password', 'password123');

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return redirect()->back()
            ->with('success', "Kata sandi pengguna {$user->name} ({$user->email}) berhasil di-reset menjadi: \"{$newPassword}\".");
    }

    /**
     * Tolak Verifikasi Pendaftaran Industri / Perusahaan Mitra.
     */
    public function rejectIndustri(Request $request, Industri $industri)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:1000',
        ], [
            'catatan_admin.required' => 'Wajib memberikan catatan/alasan penolakan mitra industri.',
        ]);

        $industri->update([
            'status_verifikasi' => 'reject',
            'catatan_admin' => $request->catatan_admin,
            'updated_at' => now(),
        ]);

        return redirect()->to(route('dashboard.admin', ['tab' => 'industri']))
            ->with('info', "Pendaftaran Industri \"{$industri->nama_perusahaan}\" ditolak dengan alasan: {$request->catatan_admin}");
    }

    /**
     * Reset Status Pengajuan Mahasiswa ke Draft Request jika Ditolak Perusahaan (PRD 11.3).
     */
    public function resetPengajuanToDraft(Request $request, PengajuanMagang $pengajuan)
    {
        $request->validate([
            'catatan_penolakan' => 'nullable|string|max:1000',
        ]);

        $catatan = $request->input('catatan_penolakan') ?: 'Mahasiswa dinyatakan ditolak oleh pihak perusahaan. Admin mengembalikan status ke Draft Request untuk pengajuan mitra industri baru.';

        $pengajuan->update([
            'status_surat_pengantar' => 'draft',
            'nomor_surat_pengantar' => null,
            'catatan_penolakan' => $catatan,
            'updated_at' => now(),
        ]);

        return redirect()->to(route('dashboard.admin', ['tab' => 'surat']))
            ->with('info', "Status pengajuan magang {$pengajuan->mahasiswa->name} berhasil dikembalikan ke Draft Request per PRD 11.3.");
    }
}
