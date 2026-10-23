<?php

namespace Database\Seeders;

use App\Models\F1Application;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Daftar Dosen Pembimbing PKL Jurusan TIK
        $dosenList = [
            [
                'name' => 'Dr. Bambang Sutrisno, M.T.',
                'email' => 'bambang.sutrisno@pnj.ac.id',
                'identifier' => '198503152010121002',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765432',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Dr. Eng. Nur Aisyah, S.Kom., M.T.',
                'email' => 'nur.aisyah@pnj.ac.id',
                'identifier' => '198204122008122001',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765433',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Drs. Bambang Wahyudi, M.T.',
                'email' => 'bambang.wahyudi@pnj.ac.id',
                'identifier' => '196803151993031002',
                'role' => 'dosen',
                'prodi' => 'Teknik Komputer & Jaringan',
                'phone' => '081298765434',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Ade Hendrawan, S.Kom., M.Kom.',
                'email' => 'ade.hendrawan@pnj.ac.id',
                'identifier' => '197906202005011003',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765435',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Siti Fatimah, S.T., M.Kom.',
                'email' => 'siti.fatimah@pnj.ac.id',
                'identifier' => '198711042014042001',
                'role' => 'dosen',
                'prodi' => 'Broadband Multimedia',
                'phone' => '081298765436',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Muhammad Rizky, S.T., M.T.',
                'email' => 'muhammad.rizky@pnj.ac.id',
                'identifier' => '199001152019031008',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765437',
                'password' => bcrypt('password123'),
            ],
        ];

        $dosenUsers = [];
        foreach ($dosenList as $d) {
            $dosenUsers[] = User::updateOrCreate(['email' => $d['email']], $d);
        }
        $dosenUtama = $dosenUsers[0]; // Dr. Bambang Sutrisno, M.T.

        // 2. Mahasiswa: Ahmad Fauzi
        $mhs = User::updateOrCreate(
            ['email' => '2107411002@stu.pnj.ac.id'],
            [
                'name' => 'Ahmad Fauzi',
                'identifier' => '2107411002',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081234567890',
                'dosen_id' => $dosenUtama->id,
                'password' => bcrypt('password123'),
            ]
        );

        // 3. Mahasiswa Tambahan untuk demo request F1: Budi Santoso (Pending)
        $mhsPending = User::updateOrCreate(
            ['email' => '2107411005@stu.pnj.ac.id'],
            [
                'name' => 'Budi Santoso',
                'identifier' => '2107411005',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081233445566',
                'dosen_id' => null, // Belum di-ACC
                'password' => bcrypt('password123'),
            ]
        );

        // Mahasiswa Tambahan: Siti Rahmawati (Pending)
        $mhsPending2 = User::updateOrCreate(
            ['email' => '2107411012@stu.pnj.ac.id'],
            [
                'name' => 'Siti Rahmawati',
                'identifier' => '2107411012',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081277889900',
                'dosen_id' => null, // Belum di-ACC
                'password' => bcrypt('password123'),
            ]
        );

        // Create sample PDF for Surat Pernyataan Kesediaan
        Storage::disk('public')->makeDirectory('surat_f1');
        $samplePdfPath = 'surat_f1/surat_pernyataan_bambang_sutrisno.pdf';
        if (!Storage::disk('public')->exists($samplePdfPath)) {
            $pdfContent = "%PDF-1.4\n1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj\n2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj\n3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >> endobj\n4 0 obj << /Length 320 >> stream\nBT\n/F1 16 Tf\n50 720 Td\n(KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI) Tj\n/F1 14 Tf\n0 -25 Td\n(POLITEKNIK NEGERI JAKARTA - JURUSAN TIK) Tj\n/F1 12 Tf\n0 -40 Td\n(SURAT PERNYATAAN KESEDIAAN DOSEN PEMBIMBING PKL (FORM F1)) Tj\n0 -30 Td\n(Menyatakan bersedia membimbing mahasiswa Ahmad Fauzi (NIM: 2107411002).) Tj\n0 -20 Td\n(Instansi PKL: PT Telekomunikasi Seluler (Telkomsel)) Tj\n0 -40 Td\n(Dosen Pembimbing: Dr. Bambang Sutrisno, M.T. | NIP: 198503152010121002) Tj\nET\nendstream\nendobj\n5 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj\nxref\n0 6\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000244 00000 n \n0000000615 00000 n \ntrailer << /Size 6 /Root 1 0 R >>\nstartxref\n690\n%%EOF";
            Storage::disk('public')->put($samplePdfPath, $pdfContent);
        }

        // 4. Form F1 Applications
        // Ahmad Fauzi: Approved
        F1Application::updateOrCreate(
            [
                'student_id' => $mhs->id,
                'dosen_id' => $dosenUtama->id,
            ],
            [
                'company_name' => 'PT Telekomunikasi Seluler (Telkomsel)',
                'internship_title' => 'Cloud Infrastructure & Kubernetes Microservices Platform',
                'status' => 'approved',
                'surat_pernyataan_path' => $samplePdfPath,
                'surat_pernyataan_name' => 'Surat_Pernyataan_Bambang_Sutrisno.pdf',
                'signature_hash' => hash('sha256', '198503152010121002_TELKOMSEL_F1'),
                'approved_at' => now()->subDays(10),
                'notes' => 'Topik PKL dan tempat magang telah disetujui sesuai kurikulum D4 Teknik Informatika.',
            ]
        );

        // Budi Santoso: Pending ke Dr. Bambang Sutrisno
        F1Application::updateOrCreate(
            [
                'student_id' => $mhsPending->id,
                'dosen_id' => $dosenUtama->id,
            ],
            [
                'company_name' => 'PT Tokopedia (GoTo Group)',
                'internship_title' => 'Backend Microservices Engineering & Kafka Event-Driven Architecture',
                'status' => 'pending',
                'surat_pernyataan_path' => null,
                'surat_pernyataan_name' => null,
                'signature_hash' => null,
                'approved_at' => null,
                'notes' => 'Menunggu verifikasi dan berkas surat pernyataan dosen.',
            ]
        );

        // Siti Rahmawati: Pending ke Dr. Bambang Sutrisno
        F1Application::updateOrCreate(
            [
                'student_id' => $mhsPending2->id,
                'dosen_id' => $dosenUtama->id,
            ],
            [
                'company_name' => 'PT Bank Central Asia Tbk (BCA)',
                'internship_title' => 'Quality Assurance & Automated API Security Testing',
                'status' => 'pending',
                'surat_pernyataan_path' => null,
                'surat_pernyataan_name' => null,
                'signature_hash' => null,
                'approved_at' => null,
                'notes' => 'Menunggu verifikasi dan berkas surat pernyataan dosen.',
            ]
        );

        // 5. Real Logbooks for Ahmad Fauzi
        Logbook::updateOrCreate(
            [
                'user_id' => $mhs->id,
                'subject' => 'Konfigurasi Multi-Cloud Terraform Manifest & Provisioning EKS',
                'tanggal' => '2026-02-18',
            ],
            [
                'dosen_id' => $dosenUtama->id,
                'content' => "Hari ini saya bersama tim Cloud Platform Telkomsel melakukan audit manifest Terraform untuk automated provisioning cluster Elastic Kubernetes Service (EKS). Aktivitas yang diselesaikan meliputi:\n1. Menyusun modul VPC, subnet publik-privat, dan Security Group sesuai security baseline Telkomsel.\n2. Melakukan deployment dry-run dengan perintah `terraform plan` dan memastikan resource tag sesuai konvensi unit enterprise.\n3. Mengonfigurasi Ingress NGINX controller dan menguji routing trafik simulasi beban tinggi.\n\nKendala yang dihadapi: Sempat terjadi latency timeout pada NAT Gateway zone B, namun berhasil dimitigasi setelah re-routing VPC route table.",
                'status' => 'pending',
                'feedback' => null,
            ]
        );

        Logbook::updateOrCreate(
            [
                'user_id' => $mhs->id,
                'subject' => 'Implementasi CI/CD Pipeline GitLab Runner & Docker Registry',
                'tanggal' => '2026-02-14',
            ],
            [
                'dosen_id' => $dosenUtama->id,
                'content' => 'Melakukan integrasi GitLab CI/CD runner pada server staging internal Telkomsel Smart Office. Menyiapkan skrip otomatisasi build container Docker, scanning image vulnerability menggunakan Trivy, dan deployment otomatis ke namespace staging EKS cluster.',
                'status' => 'approved',
                'feedback' => 'Implementasi pipeline sangat baik dan terstruktur. Lanjutkan dokumentasi Bab II laporan PKL.',
            ]
        );

        Logbook::updateOrCreate(
            [
                'user_id' => $mhs->id,
                'subject' => 'Pengujian Load Testing API Gateway dengan k6 & Locust',
                'tanggal' => '2026-02-10',
            ],
            [
                'dosen_id' => $dosenUtama->id,
                'content' => 'Melakukan pengujian performa high-concurrency pada endpoint autentikasi OAuth2 Telkomsel Cloud Platform. Pengujian dilakukan dengan 5.000 virtual users selama 15 menit menggunakan k6 framework. Hasil: Throughput 3.200 RPS dengan error rate 0.02% dan latency p95 di bawah 85ms.',
                'status' => 'approved',
                'feedback' => 'Logbook terperinci dan progres implementasi sesuai dengan timeline. Lanjutkan ke tahap berikutnya.',
            ]
        );
    }
}
