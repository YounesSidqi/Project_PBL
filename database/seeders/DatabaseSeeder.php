<?php

namespace Database\Seeders;

use App\Models\BimbinganF4;
use App\Models\EvaluasiF5;
use App\Models\EvaluasiF6;
use App\Models\F1Application;
use App\Models\Industri;
use App\Models\Logbook;
use App\Models\PengajuanMagang;
use App\Models\TugasF2;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database with essential accounts only (no dummy transactions).
     */
    public function run(): void
    {
        // 1. Bersihkan seluruh data transaksi dummy
        EvaluasiF6::query()->delete();
        EvaluasiF5::query()->delete();
        BimbinganF4::query()->delete();
        Logbook::query()->delete();
        TugasF2::query()->delete();
        F1Application::query()->delete();
        PengajuanMagang::query()->delete();

        // 2. Akun Admin Jurusan TIK PNJ (PRD 3 & 5.1)
        User::updateOrCreate(
            ['email' => 'admin.jurusan@pnj.ac.id'],
            [
                'name' => 'Biro Administrasi Akademik Jurusan TIK',
                'identifier' => '198001012005011001',
                'role' => 'admin',
                'prodi' => 'Jurusan Teknik Informatika & Komputer',
                'phone' => '021-7270036',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin.tik@pnj.ac.id'],
            [
                'name' => 'Admin Jurusan TIK',
                'identifier' => '198001012005011002',
                'role' => 'admin',
                'prodi' => 'Jurusan TIK PNJ',
                'phone' => '021-7270037',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Akun Koordinator Program Studi (KPS) Jurusan TIK PNJ (PRD 3 & 5.2)
        User::updateOrCreate(
            ['email' => 'kps.ti@pnj.ac.id'],
            [
                'name' => 'Dr. Eng. Nur Aisyah, S.Kom., M.T. (KPS)',
                'identifier' => '197508102000031001',
                'role' => 'kps',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081298765433',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ]
        );

        // 4. Daftar Akun Dosen Pembimbing PKL Jurusan TIK PNJ (PRD 3 & 5.4)
        $dosenList = [
            [
                'name' => 'Dr. Bambang Sutrisno, M.T.',
                'email' => 'bambang.sutrisno@pnj.ac.id',
                'identifier' => '198503152010121002',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765432',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dr. Eng. Nur Aisyah, S.Kom., M.T.',
                'email' => 'nur.aisyah@pnj.ac.id',
                'identifier' => '198204122008122001',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765433',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Drs. Bambang Wahyudi, M.T.',
                'email' => 'bambang.wahyudi@pnj.ac.id',
                'identifier' => '196803151993031002',
                'role' => 'dosen',
                'prodi' => 'Teknik Komputer & Jaringan',
                'phone' => '081298765434',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Ade Hendrawan, S.Kom., M.Kom.',
                'email' => 'ade.hendrawan@pnj.ac.id',
                'identifier' => '197906202005011003',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765435',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Siti Fatimah, S.T., M.Kom.',
                'email' => 'siti.fatimah@pnj.ac.id',
                'identifier' => '198711042014042001',
                'role' => 'dosen',
                'prodi' => 'Broadband Multimedia',
                'phone' => '081298765436',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Muhammad Rizky, S.T., M.T.',
                'email' => 'muhammad.rizky@pnj.ac.id',
                'identifier' => '199001152019031008',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765437',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($dosenList as $d) {
            User::updateOrCreate(['email' => $d['email']], $d);
        }

        // 5. Akun Pembimbing Industri / DUDI (PRD 3 & 5.3)
        $userTelkomsel = User::updateOrCreate(
            ['email' => 'pembimbing.telkomsel@mitra.pnj.ac.id'],
            [
                'name' => 'Bagas Wicaksono, S.T.',
                'identifier' => 'DUDI-0001',
                'role' => 'industri',
                'prodi' => 'Cloud Platform & DevOps',
                'phone' => '081198765432',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ]
        );

        $userTokopedia = User::updateOrCreate(
            ['email' => 'pembimbing.tokopedia@mitra.pnj.ac.id'],
            [
                'name' => 'Rian Pratama, S.Kom.',
                'identifier' => 'DUDI-0002',
                'role' => 'industri',
                'prodi' => 'Backend Core Services',
                'phone' => '081287654321',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ]
        );

        // Profil Perusahaan Mitra DUDI Resmi (agar akun industri langsung terkait dengan instansinya)
        Industri::updateOrCreate(
            ['nama_perusahaan' => 'PT Telekomunikasi Seluler (Telkomsel)'],
            [
                'user_id' => $userTelkomsel->id,
                'bidang_usaha' => 'Telekomunikasi Digital & Cloud Infrastructure',
                'alamat' => 'Telkom Landmark Tower Lt. 18, Jl. Jend. Gatot Subroto Kav. 52, Jakarta Selatan',
                'kota' => 'Jakarta Selatan',
                'kontak_person' => 'Bagas Wicaksono, S.T.',
                'email_pj' => 'pembimbing.telkomsel@mitra.pnj.ac.id',
                'telepon' => '021-5240123',
                'status_verifikasi' => 'acc',
                'catatan_admin' => 'Mitra industri terverifikasi resmi Jurusan TIK PNJ.',
            ]
        );

        Industri::updateOrCreate(
            ['nama_perusahaan' => 'PT Tokopedia (GoTo Group)'],
            [
                'user_id' => $userTokopedia->id,
                'bidang_usaha' => 'E-Commerce & Technology Platform',
                'alamat' => 'Tokopedia Tower Ciputra World 2, Jl. Prof. DR. Satrio No.Kav. 11, Jakarta Selatan',
                'kota' => 'Jakarta Selatan',
                'kontak_person' => 'Rian Pratama, S.Kom.',
                'email_pj' => 'pembimbing.tokopedia@mitra.pnj.ac.id',
                'telepon' => '021-80647300',
                'status_verifikasi' => 'acc',
                'catatan_admin' => 'Mitra industri terverifikasi resmi Jurusan TIK PNJ.',
            ]
        );

        // 6. Akun Mahasiswa PKL (Fresh / Bersih dari data penempatan awal)
        $mahasiswaList = [
            [
                'name' => 'Ahmad Fauzi',
                'email' => '2107411002@stu.pnj.ac.id',
                'identifier' => '2107411002',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081234567890',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => '2107411005@stu.pnj.ac.id',
                'identifier' => '2107411005',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081233445566',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Siti Rahmawati',
                'email' => '2107411012@stu.pnj.ac.id',
                'identifier' => '2107411012',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081277889900',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dimas Prasetyo',
                'email' => '2107411020@stu.pnj.ac.id',
                'identifier' => '2107411020',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081244556677',
                'dosen_id' => null,
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($mahasiswaList as $m) {
            User::updateOrCreate(['email' => $m['email']], $m);
        }

        // Pastikan seluruh mahasiswa memiliki dosen_id null
        User::where('role', 'mahasiswa')->update(['dosen_id' => null]);
    }
}
