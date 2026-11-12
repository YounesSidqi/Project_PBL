<?php

namespace Tests\Feature;

use App\Models\BimbinganF4;
use App\Models\EvaluasiF5;
use App\Models\F1Application;
use App\Models\Industri;
use App\Models\Logbook;
use App\Models\PengajuanMagang;
use App\Models\TugasF2;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrdCompleteFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $dosen;

    private User $mahasiswa;

    private User $industriUser;

    private Industri $industri;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Jurusan TIK',
            'identifier' => 'ADM001',
            'email' => 'admin.jurusan@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->dosen = User::create([
            'name' => 'Dr. Bambang Sutrisno, M.T.',
            'identifier' => '198503152010121002',
            'email' => 'bambang.sutrisno@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
            'prodi' => 'Teknik Informatika',
        ]);

        $this->mahasiswa = User::create([
            'name' => 'Ahmad Fauzi',
            'identifier' => '2107411002',
            'email' => '2107411002@stu.pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
            'prodi' => 'Teknik Informatika (D4)',
        ]);

        $this->industriUser = User::create([
            'name' => 'Bagas Wicaksono (PIC Telkomsel)',
            'identifier' => 'IND001',
            'email' => 'pembimbing.telkomsel@mitra.pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'industri',
        ]);

        $this->industri = Industri::create([
            'nama_perusahaan' => 'PT Telekomunikasi Seluler (Telkomsel)',
            'alamat' => 'Telkom Landmark Tower, Jakarta Selatan',
            'bidang_usaha' => 'Telekomunikasi & Cloud',
            'status_verifikasi' => 'acc',
            'user_id' => $this->industriUser->id,
        ]);
    }

    public function test_admin_can_onboard_student_and_reset_password(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.mahasiswa.store'), [
            'name' => 'Budi Santoso',
            'identifier' => '2107411099',
            'email' => 'budi.santoso@stu.pnj.ac.id',
            'prodi' => 'Teknik Informatika',
            'phone' => '08123456789',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'budi.santoso@stu.pnj.ac.id',
            'identifier' => '2107411099',
            'role' => 'mahasiswa',
        ]);

        $newStudent = User::where('email', 'budi.santoso@stu.pnj.ac.id')->first();
        $resetResponse = $this->actingAs($this->admin)->post(route('admin.users.reset-password', $newStudent->id), [
            'new_password' => 'newsecret2026',
        ]);

        $resetResponse->assertRedirect();
        $this->assertTrue(auth()->attempt([
            'email' => 'budi.santoso@stu.pnj.ac.id',
            'password' => 'newsecret2026',
        ]));
    }

    public function test_mahasiswa_can_request_surat_pengantar_and_admin_approves(): void
    {
        $response = $this->actingAs($this->mahasiswa)->post(route('mahasiswa.request-surat'), [
            'nama_perusahaan' => 'PT Bank Mandiri (Persero) Tbk',
            'alamat' => 'Plaza Mandiri, Jl. Jend. Gatot Subroto Kav. 36-38 Jakarta',
            'kota' => 'Jakarta Selatan',
            'tanggal_mulai' => '2026-03-01',
            'tanggal_selesai' => '2026-08-31',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pengajuan_magangs', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'status_surat_pengantar' => 'pending',
        ]);

        $pengajuan = PengajuanMagang::where('mahasiswa_id', $this->mahasiswa->id)->latest()->first();

        // Admin approves and issues Surat Pengantar
        $approveResponse = $this->actingAs($this->admin)->post(route('admin.surat.terbitkan', $pengajuan->id));

        $approveResponse->assertRedirect();
        $this->assertDatabaseHas('pengajuan_magangs', [
            'id' => $pengajuan->id,
            'status_surat_pengantar' => 'diterbitkan',
        ]);
        $this->assertNotNull($pengajuan->fresh()->nomor_surat_pengantar);
    }

    public function test_admin_can_approve_new_industri_and_generate_account(): void
    {
        $pendingIndustri = Industri::create([
            'nama_perusahaan' => 'PT Solusi Teknologi Indonesia',
            'alamat' => 'BSD Green Office Park, Tangerang',
            'bidang_usaha' => 'Software House',
            'email_pj' => 'hr@solusitek.co.id',
            'telepon' => '081234567890',
            'status_verifikasi' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.industri.acc', $pendingIndustri->id));
        $response->assertRedirect();

        $this->assertDatabaseHas('industris', [
            'id' => $pendingIndustri->id,
            'status_verifikasi' => 'acc',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'hr@solusitek.co.id',
            'role' => 'industri',
        ]);
    }

    public function test_mahasiswa_backdated_logbook_calculates_late_days(): void
    {
        $backdate = Carbon::now()->subDays(3)->format('Y-m-d');

        $response = $this->actingAs($this->mahasiswa)->post(route('logbooks.store'), [
            'tanggal' => $backdate,
            'subject' => 'Testing Microservices Architecture',
            'content' => 'Implementasi unit testing microservice authentication dengan container.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('form_f3_logbook', [
            'user_id' => $this->mahasiswa->id,
            'hari_terlambat' => 3,
        ]);
    }

    public function test_industri_can_manage_tugas_f2_and_acc_logbook_and_submit_f5(): void
    {
        $pengajuan = PengajuanMagang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'industri_id' => $this->industri->id,
            'dosen_id' => $this->dosen->id,
            'status' => 'aktif',
        ]);

        // 1. Tambah Tugas F2
        $tugasResponse = $this->actingAs($this->industriUser)->post(route('industri.tugas_f2.store', $pengajuan->id), [
            'modul_pekerjaan' => 'CI/CD Pipeline Automation',
            'spesifikasi_kebutuhan' => 'Automasi testing and linting sebelum build container image.',
            'target_minggu' => 'Minggu 4',
        ]);
        $tugasResponse->assertRedirect();
        $this->assertDatabaseHas('form_f2_tugas', [
            'pengajuan_magang_id' => $pengajuan->id,
            'modul_pekerjaan' => 'CI/CD Pipeline Automation',
        ]);

        // 2. ACC Logbook F3
        $logbook = Logbook::create([
            'user_id' => $this->mahasiswa->id,
            'subject' => 'Monitoring Pipeline',
            'content' => 'Melakukan pemantauan build pipeline pada server staging.',
            'tanggal' => date('Y-m-d'),
            'status' => 'pending',
            'status_industri' => 'pending',
        ]);
        $accLogbookResponse = $this->actingAs($this->industriUser)->post(route('industri.logbooks.acc', $logbook->id), [
            'catatan' => 'Kerja bagus, pipeline berjalan lancar.',
        ]);
        $accLogbookResponse->assertRedirect();
        $this->assertDatabaseHas('form_f3_logbook', [
            'id' => $logbook->id,
            'status_industri' => 'acc',
        ]);

        // 3. Submit Evaluasi F5 (Rahasia Industri bobot 60%)
        $f5Response = $this->actingAs($this->industriUser)->post(route('industri.evaluasi_f5.store', $pengajuan->id), [
            'skor_komunikasi' => 90,
            'skor_berpikir_kritis' => 85,
            'skor_kerjasama' => 88,
            'skor_inisiatif' => 92,
            'skor_literasi' => 86,
            'skor_deskripsi_produk' => 95,
            'skor_spesifikasi' => 89,
            'skor_desain' => 87,
            'skor_implementasi' => 90,
            'skor_pengujian' => 88,
            'saran_industri' => 'Mahasiswa sangat berkompeten dan proaktif.',
        ]);
        $f5Response->assertRedirect();
        $this->assertDatabaseHas('form_f5_evaluasi', [
            'pengajuan_magang_id' => $pengajuan->id,
        ]);
        $this->assertNotNull($pengajuan->fresh()->evaluasiF5);
    }

    public function test_dosen_enforces_8_sessions_before_final_evaluation_and_executes_fuzzy(): void
    {
        $pengajuan = PengajuanMagang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'industri_id' => $this->industri->id,
            'dosen_id' => $this->dosen->id,
            'status' => 'aktif',
        ]);

        // Evaluasi F5 dari industri
        EvaluasiF5::create([
            'pengajuan_magang_id' => $pengajuan->id,
            'skor_komunikasi' => 88,
            'skor_berpikir_kritis' => 85,
            'skor_kerjasama' => 89,
            'skor_inisiatif' => 90,
            'skor_literasi' => 85,
            'skor_deskripsi_produk' => 90,
            'skor_spesifikasi' => 88,
            'skor_desain' => 86,
            'skor_implementasi' => 88,
            'skor_pengujian' => 87,
            'rata_rata_f5' => 87.6,
        ]);

        // Dosen input Evaluasi F6
        $f6Response = $this->actingAs($this->dosen)->post(route('dosen.evaluasi_f6.store', $pengajuan->id), [
            'skor_kesesuaian_judul' => 85,
            'skor_tata_bahasa' => 80,
            'skor_format_laporan' => 82,
            'skor_pengetahuan' => 84,
            'skor_tanya_jawab' => 80,
            'skor_etika' => 85,
            'skor_justifikasi' => 88,
            'skor_rutinitas_bimbingan' => 86,
            'skor_progres' => 90,
        ]);
        $f6Response->assertRedirect();
        $this->assertDatabaseHas('form_f6_evaluasi', [
            'pengajuan_magang_id' => $pengajuan->id,
        ]);

        // 1. Coba kalkulasi Fuzzy saat sesi bimbingan F4 masih 0 (< 8 sesi)
        // Harus ditolak dengan pesan error
        $earlyFuzzy = $this->actingAs($this->dosen)->post(route('dosen.kalkulasi-fuzzy', $pengajuan->id));
        $earlyFuzzy->assertRedirect();
        $earlyFuzzy->assertSessionHas('error');

        // 2. Tambah 8 sesi bimbingan F4
        for ($i = 1; $i <= 8; $i++) {
            BimbinganF4::create([
                'pengajuan_magang_id' => $pengajuan->id,
                'mahasiswa_id' => $this->mahasiswa->id,
                'dosen_id' => $this->dosen->id,
                'sesi_ke' => $i,
                'tanggal_bimbingan' => Carbon::now()->subWeeks(8 - $i)->format('Y-m-d'),
                'materi_bimbingan' => "Asistensi PKL Sesi Ke-{$i}",
                'status' => 'acc',
            ]);
        }

        // 3. Eksekusi Python Fuzzy Calculation sekarang harus berhasil
        $fuzzyResponse = $this->actingAs($this->dosen)->post(route('dosen.kalkulasi-fuzzy', $pengajuan->id));
        $fuzzyResponse->assertRedirect();
        $fuzzyResponse->assertSessionHas('success');

        $freshPengajuan = $pengajuan->fresh();
        $this->assertNotNull($freshPengajuan->nilai_akhir_fuzzy);
        $this->assertStringContainsString('/50', $freshPengajuan->nilai_akhir_fuzzy);
        $this->assertNotNull($freshPengajuan->fuzzy_score_num);
        $this->assertNotNull($freshPengajuan->fuzzy_percentage);
    }

    public function test_all_official_documents_and_form_views_are_renderable(): void
    {
        $pengajuan = PengajuanMagang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'industri_id' => $this->industri->id,
            'dosen_id' => $this->dosen->id,
            'status' => 'aktif',
            'status_surat_pengantar' => 'diterbitkan',
            'nomor_surat_pengantar' => 'B/123/PL3.18/PK.01.06/2026',
            'nilai_akhir_fuzzy' => '43.2/50',
            'fuzzy_score_num' => 43.2,
            'fuzzy_percentage' => 86.4,
        ]);

        TugasF2::create([
            'pengajuan_magang_id' => $pengajuan->id,
            'modul_pekerjaan' => 'Cloud Infrastructure',
            'spesifikasi_kebutuhan' => 'Deploy microservices on Kubernetes.',
            'status' => 'acc',
        ]);

        BimbinganF4::create([
            'pengajuan_magang_id' => $pengajuan->id,
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'sesi_ke' => 1,
            'tanggal_bimbingan' => date('Y-m-d'),
            'materi_bimbingan' => 'Konsultasi Bab I',
            'status' => 'acc',
        ]);

        $f1App = F1Application::create([
            'student_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'status' => 'approved',
        ]);

        // Test Dokumen Surat Pengantar
        $this->actingAs($this->mahasiswa)->get(route('dokumen.surat-pengantar', $pengajuan->id))->assertOk();

        // Test Dokumen F1
        $this->actingAs($this->mahasiswa)->get(route('dokumen.f1', $f1App->id))->assertOk();

        // Test Dokumen F2
        $this->actingAs($this->mahasiswa)->get(route('dokumen.f2', $pengajuan->id))->assertOk();

        // Test Dokumen F3
        $this->actingAs($this->mahasiswa)->get(route('dokumen.f3', $pengajuan->id))->assertOk();

        // Test Dokumen F4
        $this->actingAs($this->mahasiswa)->get(route('dokumen.f4', $pengajuan->id))->assertOk();

        // Test Dokumen F5 (Industri)
        $this->actingAs($this->industriUser)->get(route('dokumen.f5', $pengajuan->id))->assertOk();

        // Test Dokumen F6 (Dosen)
        $this->actingAs($this->dosen)->get(route('dokumen.f6', $pengajuan->id))->assertOk();

        // Test Dokumen F7
        $this->actingAs($this->mahasiswa)->get(route('dokumen.f7', $pengajuan->id))->assertOk();

        // Test Standalone Views F2, F4, F5, and F6
        $this->actingAs($this->mahasiswa)->get(route('form.f2.mahasiswa'))->assertOk();
        $this->actingAs($this->dosen)->get(route('form.f2.dosen'))->assertOk();
        $this->actingAs($this->mahasiswa)->get(route('form.f4.mahasiswa'))->assertOk();
        $this->actingAs($this->dosen)->get(route('form.f4.dosen'))->assertOk();
        $this->actingAs($this->industriUser)->get(route('form.f5.industri'))->assertOk();
        $this->actingAs($this->dosen)->get(route('form.f6.dosen'))->assertOk();
    }

    public function test_admin_can_reject_industri(): void
    {
        $industriPending = Industri::create([
            'nama_perusahaan' => 'CV Fiktif Digital',
            'alamat' => 'Alamat tidak jelas',
            'status_verifikasi' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.industri.reject', $industriPending->id), [
            'catatan_admin' => 'Kontak perusahaan tidak dapat diverifikasi oleh admin.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('industris', [
            'id' => $industriPending->id,
            'status_verifikasi' => 'reject',
            'catatan_admin' => 'Kontak perusahaan tidak dapat diverifikasi oleh admin.',
        ]);
    }

    public function test_admin_can_reset_pengajuan_to_draft_on_company_rejection(): void
    {
        $pengajuan = PengajuanMagang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'industri_id' => $this->industri->id,
            'status_surat_pengantar' => 'diterbitkan',
            'nomor_surat_pengantar' => 'B/123/PL3/2026',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.pengajuan.reset-draft', $pengajuan->id), [
            'catatan_penolakan' => 'Kuota mitra industri telah penuh. Mahasiswa diminta mengajukan industri baru.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pengajuan_magangs', [
            'id' => $pengajuan->id,
            'status_surat_pengantar' => 'draft',
            'nomor_surat_pengantar' => null,
            'catatan_penolakan' => 'Kuota mitra industri telah penuh. Mahasiswa diminta mengajukan industri baru.',
        ]);
    }

    public function test_kps_bidirectional_state_sync_with_pengajuan_magang(): void
    {
        $kpsUser = User::create([
            'name' => 'Koordinator Prodi TIK',
            'identifier' => 'KPS001',
            'email' => 'kps.ti@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'kps',
        ]);

        // 1. Mahasiswa apply Form F1
        $this->actingAs($this->mahasiswa)->post(route('f1.select-dosen'), [
            'dosen_id' => $this->dosen->id,
            'company_name' => 'PT Telkom Indonesia',
            'internship_title' => 'Backend Engineering Intern',
        ]);

        $this->assertDatabaseHas('pengajuan_magangs', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'status_dosen' => 'usulan',
        ]);

        $f1 = F1Application::where('student_id', $this->mahasiswa->id)->first();

        // 2. KPS menyetujui penetapan F1
        $this->actingAs($kpsUser)->post(route('kps.f1.approve', $f1->id));

        $this->assertDatabaseHas('pengajuan_magangs', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => $this->dosen->id,
            'status_dosen' => 'acc_kps',
        ]);
        $this->assertEquals($this->dosen->id, $this->mahasiswa->fresh()->dosen_id);

        // 3. KPS unassign mahasiswa
        $this->actingAs($kpsUser)->delete(route('kps.student.unassign', $this->mahasiswa->id));

        $this->assertDatabaseHas('pengajuan_magangs', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'dosen_id' => null,
            'status_dosen' => 'pending',
        ]);
        $this->assertNull($this->mahasiswa->fresh()->dosen_id);
    }

    public function test_mahasiswa_inputs_company_and_admin_verifies_or_forwards_to_existing_industri(): void
    {
        // 1. Mahasiswa menginput perusahaan baru beserta detail lengkap (PRD 5.2)
        $response = $this->actingAs($this->mahasiswa)->post(route('mahasiswa.request-surat'), [
            'nama_perusahaan' => 'PT Nusantara Cloud Digital',
            'bidang_usaha' => 'Cloud & AI Infrastructure',
            'alamat' => 'Jl. Jend. Sudirman Kav. 21, Jakarta Pusat',
            'kota' => 'Jakarta Pusat',
            'kontak_person' => 'Hendra Wijaya, M.Kom.',
            'email_pj' => 'hendra.wijaya@nusantaracloud.id',
            'telepon' => '081122334455',
            'posisi_magang' => 'DevOps Engineer Intern',
            'tanggal_mulai' => '2026-03-01',
            'tanggal_selesai' => '2026-08-31',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('industris', [
            'nama_perusahaan' => 'PT Nusantara Cloud Digital',
            'bidang_usaha' => 'Cloud & AI Infrastructure',
            'email_pj' => 'hendra.wijaya@nusantaracloud.id',
            'status_verifikasi' => 'pending',
        ]);

        $newIndustri = Industri::where('nama_perusahaan', 'PT Nusantara Cloud Digital')->first();
        $pengajuan = PengajuanMagang::where('mahasiswa_id', $this->mahasiswa->id)->first();
        $this->assertEquals($newIndustri->id, $pengajuan->industri_id);

        // 2. Proteksi Anti-Spam: Mahasiswa tidak bisa spam submit perusahaan baru saat status masih pending
        $spamResponse = $this->actingAs($this->mahasiswa)->post(route('mahasiswa.request-surat'), [
            'nama_perusahaan' => 'PT Perusahaan Spam Fiktif',
        ]);
        $spamResponse->assertRedirect();
        $spamResponse->assertSessionHas('error');
        $this->assertDatabaseMissing('industris', [
            'nama_perusahaan' => 'PT Perusahaan Spam Fiktif',
        ]);

        // 3. Skenario A: Admin mem-forward penugasan mahasiswa ke industri yang sudah terdaftar (PRD 5.2 poin 2)
        $forwardResponse = $this->actingAs($this->admin)->post(route('admin.pengajuan.forward', $pengajuan->id), [
            'industri_id' => $this->industri->id,
        ]);

        $forwardResponse->assertRedirect();
        $this->assertEquals($this->industri->id, $pengajuan->fresh()->industri_id);
    }

    public function test_kps_can_login_using_kps_admin_tab(): void
    {
        User::create([
            'name' => 'Dr. Eng. Nur Aisyah, S.Kom., M.T. (KPS)',
            'identifier' => '197508102000031001',
            'email' => 'kps.ti@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'kps',
        ]);

        $response = $this->post(route('login.post'), [
            'identifier' => 'kps.ti@pnj.ac.id',
            'password' => 'password123',
            'role' => 'admin', // Tab "KPS / Admin" mengirim role=admin
        ]);

        $response->assertRedirect(route('dashboard.kps'));
        $this->assertAuthenticated();
    }

    public function test_admin_full_control_can_edit_mahasiswa_status_and_delete_tempat_pkl(): void
    {
        $pengajuan = PengajuanMagang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'industri_id' => $this->industri->id,
            'posisi_magang' => 'Backend Intern',
            'status_surat_pengantar' => 'pending',
        ]);

        // 1. Admin mengedit data, status surat pengantar, dan dosen pembimbing mahasiswa
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.mahasiswa.update', $this->mahasiswa->id), [
            'name' => 'Ahmad Fauzi Updated',
            'identifier' => '2107411002',
            'email' => '2107411002@stu.pnj.ac.id',
            'prodi' => 'Teknik Informatika (D4)',
            'phone' => '081299998888',
            'dosen_id' => $this->dosen->id,
            'industri_id' => $this->industri->id,
            'posisi_magang' => 'Senior Cloud Intern',
            'status_surat_pengantar' => 'diterbitkan',
            'status_dosen' => 'acc_kps',
        ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $this->mahasiswa->id,
            'name' => 'Ahmad Fauzi Updated',
            'dosen_id' => $this->dosen->id,
        ]);
        $this->assertDatabaseHas('pengajuan_magangs', [
            'id' => $pengajuan->id,
            'posisi_magang' => 'Senior Cloud Intern',
            'status_surat_pengantar' => 'diterbitkan',
        ]);

        // 2. Admin menghapus tempat PKL mahasiswa
        $deletePklResponse = $this->actingAs($this->admin)->delete(route('admin.mahasiswa.destroy-pkl', $this->mahasiswa->id));
        $deletePklResponse->assertRedirect();

        $this->assertDatabaseMissing('pengajuan_magangs', [
            'id' => $pengajuan->id,
        ]);
    }

    public function test_mahasiswa_dashboard_progressive_step_by_step_flow(): void
    {
        // TAHAP 1: Belum mengajukan surat pengantar -> Hanya Card Surat Pengantar yang tampil
        $step1View = $this->actingAs($this->mahasiswa)->get(route('dashboard.mahasiswa'));
        $step1View->assertOk();
        $step1View->assertSee('Pengajuan Surat Pengantar PKL');
        $step1View->assertDontSee('Tempat Pelaksanaan PKL');
        $step1View->assertDontSee('Form F1: Dosen Pembimbing');
        $step1View->assertDontSee('Logbook Kegiatan Harian');
        $step1View->assertDontSee('Riwayat Bimbingan (F4)');

        // Mahasiswa mengisi form pengajuan surat pengantar (masuk proses pending)
        $this->actingAs($this->mahasiswa)->post(route('mahasiswa.request-surat'), [
            'tahap' => 'surat_pengantar',
            'name' => 'Ahmad Fauzi',
            'identifier' => '2107411002',
            'prodi' => 'Teknik Informatika (D4)',
            'phone' => '081234567890',
            'posisi_magang' => 'Cloud Engineer Intern',
            'tanggal_mulai' => '2026-03-01',
            'tanggal_selesai' => '2026-08-31',
        ])->assertRedirect();

        $pengajuan = PengajuanMagang::where('mahasiswa_id', $this->mahasiswa->id)->first();
        $this->assertNotNull($pengajuan);
        $this->assertEquals('pending', $pengajuan->status_surat_pengantar);
        $this->assertNull($pengajuan->industri_id);

        // Saat masih pending, card perusahaan belum muncul
        $pendingSuratView = $this->actingAs($this->mahasiswa)->get(route('dashboard.mahasiswa'));
        $pendingSuratView->assertSee('Permohonan Surat Pengantar Sedang Diproses Admin');
        $pendingSuratView->assertDontSee('Tempat Pelaksanaan PKL');

        // Admin menerbitkan surat pengantar
        $this->actingAs($this->admin)->post(route('admin.surat.terbitkan', $pengajuan->id))->assertRedirect();
        $this->assertEquals('diterbitkan', $pengajuan->fresh()->status_surat_pengantar);

        // TAHAP 2: Setelah mendapat surat pengantar -> Card Pengajuan Perusahaan muncul
        $step2View = $this->actingAs($this->mahasiswa)->get(route('dashboard.mahasiswa'));
        $step2View->assertOk();
        $step2View->assertSee('Tempat Pelaksanaan PKL');
        $step2View->assertDontSee('Form F1: Dosen Pembimbing');
        $step2View->assertDontSee('Logbook Kegiatan Harian');
        $step2View->assertDontSee('Riwayat Bimbingan (F4)');

        // Mahasiswa mengajukan perusahaan tempat PKL
        $this->actingAs($this->mahasiswa)->post(route('mahasiswa.request-surat'), [
            'tahap' => 'perusahaan',
            'nama_perusahaan' => 'PT Siber Kreasi Nusantara',
            'bidang_usaha' => 'Cybersecurity & Cloud',
            'kota' => 'Jakarta Selatan',
            'alamat' => 'Jl. TB Simatupang Kav. 10',
            'kontak_person' => 'Rizki Pratama',
            'email_pj' => 'hrd@siberkreasi.id',
            'telepon' => '021778899',
            'posisi_magang' => 'Cloud Security Intern',
            'tanggal_mulai' => '2026-03-01',
            'tanggal_selesai' => '2026-08-31',
        ])->assertRedirect();

        $industriBaru = Industri::where('nama_perusahaan', 'PT Siber Kreasi Nusantara')->first();
        $this->assertNotNull($industriBaru);
        $this->assertEquals('pending', $industriBaru->status_verifikasi);

        // Saat perusahaan masih pending Admin, Card Pengajuan Dosen belum muncul
        $pendingCompanyView = $this->actingAs($this->mahasiswa)->get(route('dashboard.mahasiswa'));
        $pendingCompanyView->assertSee('Menunggu Verifikasi Perusahaan oleh Admin');
        $pendingCompanyView->assertDontSee('Form F1: Dosen Pembimbing');

        // Admin meng-Accept perusahaan mahasiswa
        $this->actingAs($this->admin)->post(route('admin.industri.acc', $industriBaru->id))->assertRedirect();
        $this->assertEquals('acc', $industriBaru->fresh()->status_verifikasi);

        // TAHAP 3: Setelah terdaftar di perusahaan -> Card Pengajuan Dosen (Form F1) muncul
        $step3View = $this->actingAs($this->mahasiswa)->get(route('dashboard.mahasiswa'));
        $step3View->assertOk();
        $step3View->assertSee('Form F1: Dosen Pembimbing');
        $step3View->assertDontSee('Logbook Kegiatan Harian');
        $step3View->assertDontSee('Riwayat Bimbingan (F4)');

        // Mahasiswa melakukan pengajuan dosen pembimbing (Form F1)
        $this->actingAs($this->mahasiswa)->post(route('f1.select-dosen'), [
            'dosen_id' => $this->dosen->id,
            'company_name' => 'PT Siber Kreasi Nusantara',
            'internship_title' => 'Implementasi Zero Trust Network Architecture',
        ])->assertRedirect();

        // TAHAP 4: Setelah pengajuan dosen -> Card Logbook Kegiatan Harian & Riwayat Bimbingan muncul
        $step4View = $this->actingAs($this->mahasiswa)->get(route('dashboard.mahasiswa'));
        $step4View->assertOk();
        $step4View->assertSee('Logbook Kegiatan Harian');
        $step4View->assertSee('Riwayat Bimbingan (F4)');
    }

    public function test_form_f2_tasks_appear_in_todo_and_transition_through_progress_to_done(): void
    {
        $pengajuan = PengajuanMagang::create([
            'mahasiswa_id' => $this->mahasiswa->id,
            'industri_id' => $this->industri->id,
            'dosen_id' => $this->dosen->id,
            'posisi_magang' => 'Full Stack Developer',
            'status_surat_pengantar' => 'diterbitkan',
            'status_dosen' => 'acc_kps',
        ]);

        // 1. Tambah tugas baru ke Form F2 (status default: todo)
        $this->actingAs($this->industriUser)->post(route('industri.tugas_f2.store', $pengajuan->id), [
            'modul_pekerjaan' => 'Tampilan A',
            'spesifikasi_kebutuhan' => 'Buatkan Tampilan yang menarik',
            'target_minggu' => '2',
        ])->assertRedirect();

        $tugas = TugasF2::where('pengajuan_magang_id', $pengajuan->id)->first();
        $this->assertNotNull($tugas);
        $this->assertEquals('todo', $tugas->status);

        // 2. Pastikan tugas muncul di kolom To-Do halaman Mahasiswa & Dosen (Read-Only untuk Mahasiswa)
        $mhsTodoView = $this->actingAs($this->mahasiswa)->get(route('form.f2.mahasiswa'));
        $mhsTodoView->assertOk();
        $mhsTodoView->assertSee('Tampilan A');
        $mhsTodoView->assertSee('Buatkan Tampilan yang menarik');
        $mhsTodoView->assertViewHas('tasksTodo', fn ($c) => $c->count() === 1);

        $dosenTodoView = $this->actingAs($this->dosen)->get(route('form.f2.dosen'));
        $dosenTodoView->assertOk();
        $dosenTodoView->assertSee('Tampilan A');

        // 3. Mahasiswa tidak boleh mengubah status tugas F2 (hanya pihak Industri/DUDI)
        $mhsForbiddenResponse = $this->actingAs($this->mahasiswa)->put(route('industri.tugas_f2.update', $tugas->id), [
            'status' => 'in_progress',
        ]);
        $mhsForbiddenResponse->assertRedirect();
        $mhsForbiddenResponse->assertSessionHas('error');
        $this->assertEquals('todo', $tugas->fresh()->status);

        // 4. Pihak Industri mengubah status dari To-Do -> Progres (in_progress)
        $this->actingAs($this->industriUser)->put(route('industri.tugas_f2.update', $tugas->id), [
            'status' => 'in_progress',
        ])->assertRedirect();

        $this->assertEquals('in_progress', $tugas->fresh()->status);

        $mhsProgressView = $this->actingAs($this->mahasiswa)->get(route('form.f2.mahasiswa'));
        $mhsProgressView->assertOk();
        $mhsProgressView->assertSee('Tampilan A');
        $mhsProgressView->assertViewHas('tasksTodo', fn ($c) => $c->count() === 0);
        $mhsProgressView->assertViewHas('tasksProgress', fn ($c) => $c->count() === 1);
        $mhsProgressView->assertViewHas('tasksDone', fn ($c) => $c->count() === 0);

        // 5. Pihak Industri mengubah status dari Progres (in_progress) -> Selesai (acc)
        $this->actingAs($this->industriUser)->put(route('industri.tugas_f2.update', $tugas->id), [
            'status' => 'acc',
        ])->assertRedirect();

        $this->assertEquals('acc', $tugas->fresh()->status);

        $mhsDoneView = $this->actingAs($this->mahasiswa)->get(route('form.f2.mahasiswa'));
        $mhsDoneView->assertOk();
        $mhsDoneView->assertSee('Tampilan A');
        $mhsDoneView->assertViewHas('tasksTodo', fn ($c) => $c->count() === 0);
        $mhsDoneView->assertViewHas('tasksProgress', fn ($c) => $c->count() === 0);
        $mhsDoneView->assertViewHas('tasksDone', fn ($c) => $c->count() === 1);
    }
}
