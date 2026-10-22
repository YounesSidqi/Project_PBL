<?php

namespace Database\Seeders;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Contains only Dosen Dr. Bambang Sutrisno, M.T. and Mahasiswa Ahmad Fauzi.
     */
    public function run(): void
    {
        // 1. Dosen Pembimbing
        $dosen = User::updateOrCreate(
            ['email' => 'bambang.sutrisno@pnj.ac.id'],
            [
                'name' => 'Dr. Bambang Sutrisno, M.T.',
                'identifier' => '198503152010121002',
                'role' => 'dosen',
                'prodi' => 'Teknik Informatika',
                'phone' => '081298765432',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Mahasiswa: Ahmad Fauzi
        $mhs = User::updateOrCreate(
            ['email' => '2107411002@stu.pnj.ac.id'],
            [
                'name' => 'Ahmad Fauzi',
                'identifier' => '2107411002',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika (D4)',
                'phone' => '081234567890',
                'dosen_id' => $dosen->id,
                'password' => bcrypt('password123'),
            ]
        );

        // Clean up any extra dummy users and their logbooks
        $validUserIds = [$dosen->id, $mhs->id];
        Logbook::whereNotIn('user_id', [$mhs->id])->delete();
        User::whereNotIn('id', $validUserIds)->delete();

        // 3. Real Logbooks for Ahmad Fauzi
        Logbook::updateOrCreate(
            [
                'user_id' => $mhs->id,
                'subject' => 'Konfigurasi Multi-Cloud Terraform Manifest & Provisioning EKS',
                'tanggal' => '2026-02-18',
            ],
            [
                'dosen_id' => $dosen->id,
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
                'dosen_id' => $dosen->id,
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
                'dosen_id' => $dosen->id,
                'content' => 'Melakukan pengujian performa high-concurrency pada endpoint autentikasi OAuth2 Telkomsel Cloud Platform. Pengujian dilakukan dengan 5.000 virtual users selama 15 menit menggunakan k6 framework. Hasil: Throughput 3.200 RPS dengan error rate 0.02% dan latency p95 di bawah 85ms.',
                'status' => 'approved',
                'feedback' => 'Logbook terperinci dan progres implementasi sesuai dengan timeline. Lanjutkan ke tahap berikutnya.',
            ]
        );
    }
}
