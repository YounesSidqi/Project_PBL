<?php

namespace Tests\Feature;

use App\Models\F1Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KpsDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $adminTi;

    private User $kpsUser;

    private User $dosen1;

    private User $dosen2;

    private User $student1;

    private User $student2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminTi = User::create([
            'name' => 'Admin Jurusan TI',
            'identifier' => '198001012005011001',
            'email' => 'admin.ti@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'jurusan' => 'TI',
            'prodi' => 'Teknik Informatika (TI)',
        ]);

        $this->kpsUser = User::create([
            'name' => 'Dr. Eng. Nur Aisyah, S.Kom., M.T. (KPS)',
            'identifier' => '197508102000031001',
            'email' => 'kps.ti@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'kps',
            'jurusan' => 'TI',
            'prodi' => 'Teknik Informatika (D4)',
        ]);

        $this->dosen1 = User::create([
            'name' => 'Dr. Bambang Sutrisno, M.T.',
            'identifier' => '198503152010121002',
            'email' => 'bambang@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
            'prodi' => 'Teknik Informatika',
        ]);

        $this->dosen2 = User::create([
            'name' => 'Ade Hendrawan, S.Kom., M.Kom.',
            'identifier' => '197906202005011003',
            'email' => 'ade@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
            'prodi' => 'Teknik Informatika',
        ]);

        $this->student1 = User::create([
            'name' => 'Ahmad Fauzi',
            'identifier' => '2107411002',
            'email' => 'ahmad@stu.pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
            'prodi' => 'Teknik Informatika (D4)',
        ]);

        $this->student2 = User::create([
            'name' => 'Budi Santoso',
            'identifier' => '2107411005',
            'email' => 'budi@stu.pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
            'prodi' => 'Teknik Informatika (D4)',
        ]);
    }

    /**
     * Test Mahasiswa hanya melihat dosen yang tersedia dan kuota bimbingan terisi tidak ditampilkan.
     */
    public function test_mahasiswa_dashboard_only_shows_available_lecturers_and_hides_quota_numbers(): void
    {
        // Penuhkan kuota dosen 2 dengan 7 mahasiswa
        for ($i = 1; $i <= 7; $i++) {
            User::create([
                'name' => "Student Full {$i}",
                'identifier' => "210749900{$i}",
                'email' => "student{$i}@stu.pnj.ac.id",
                'password' => bcrypt('password123'),
                'role' => 'mahasiswa',
                'dosen_id' => $this->dosen2->id,
            ]);
        }

        $response = $this->actingAs($this->student1)->get(route('dashboard.mahasiswa'));

        $response->assertStatus(200);
        // Dosen 1 yang masih tersedia muncul
        $response->assertSee($this->dosen1->name);
        // Dosen 2 yang penuh (7/7) tidak muncul untuk dipilih mahasiswa
        $response->assertDontSee($this->dosen2->name);
        // Mahasiswa tidak melihat teks angka Kuota: X/7
        $response->assertDontSee('Kuota: 7/7');
        $response->assertDontSee('Kuota: 0/7');
        $response->assertSee('Tersedia');
    }

    /**
     * Test Fase 1 & Fase 2: Mahasiswa mengajukan rekomendasi dosen (pending_admin),
     * lalu Admin melakukan plotting dosen dan meneruskannya ke KPS (pending_kps).
     */
    public function test_mahasiswa_can_apply_to_available_dosen_with_pending_kps_status(): void
    {
        $response = $this->actingAs($this->student1)->post(route('f1.select-dosen'), [
            'dosen_id' => $this->dosen1->id,
            'company_name' => 'PT Telekomunikasi Seluler',
            'internship_title' => 'Cloud Platform Engineering',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('form_f1_pengajuan', [
            'student_id' => $this->student1->id,
            'dosen_id' => $this->dosen1->id,
            'recommended_dosen_id' => $this->dosen1->id,
            'status' => F1Application::STATUS_PENDING_ADMIN,
        ]);

        // Dashboard mahasiswa menampilkan status menunggu plotting Admin
        $dashboardResponse = $this->actingAs($this->student1)->get(route('dashboard.mahasiswa'));
        $dashboardResponse->assertSee('Menunggu Plotting Admin');
        $dashboardResponse->assertDontSee('Menunggu Persetujuan Dosen');

        // Fase 2: Admin melakukan plotting dosen pembimbing -> status menjadi pending_kps
        $application = F1Application::where('student_id', $this->student1->id)->firstOrFail();
        $this->actingAs($this->adminTi)->post(route('admin.f1.plot', $application->id), [
            'dosen_id' => $this->dosen1->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('form_f1_pengajuan', [
            'id' => $application->id,
            'dosen_id' => $this->dosen1->id,
            'status' => F1Application::STATUS_PENDING_KPS,
        ]);

        $afterPlotDashboard = $this->actingAs($this->student1)->get(route('dashboard.mahasiswa'));
        $afterPlotDashboard->assertSee('Menunggu Approval KPS');
    }

    /**
     * Test KPS dapat melihat Dashboard KPS dan tab penetapan.
     */
    public function test_kps_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->kpsUser)->get(route('dashboard.kps'));

        $response->assertStatus(200);
        $response->assertSee('Penetapan Pembimbing PKL');
        $response->assertSee('Penetapan &amp; Plotting Form F1', false);
        $response->assertSee('Distribusi Kuota Dosen');
        $response->assertSee('Rekapitulasi Seluruh Mahasiswa');
    }

    /**
     * Test Fase 3: KPS menyetujui (Approve) hasil plotting F1 dari Admin.
     */
    public function test_kps_can_approve_f1_application(): void
    {
        $application = F1Application::create([
            'student_id' => $this->student1->id,
            'dosen_id' => $this->dosen1->id,
            'recommended_dosen_id' => $this->dosen1->id,
            'plotted_by' => $this->adminTi->id,
            'plotted_at' => now(),
            'company_name' => 'PT Telekomunikasi Seluler',
            'internship_title' => 'Cloud Platform Engineering',
            'status' => F1Application::STATUS_PENDING_KPS,
        ]);

        $response = $this->actingAs($this->kpsUser)->post(route('kps.f1.approve', $application->id), [
            'notes' => 'Disetujui dan disahkan oleh KPS.',
        ]);

        $response->assertRedirect(route('dashboard.kps', ['tab' => 'plotting']));

        $this->assertDatabaseHas('form_f1_pengajuan', [
            'id' => $application->id,
            'status' => F1Application::STATUS_APPROVED,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->student1->id,
            'dosen_id' => $this->dosen1->id,
        ]);
    }

    /**
     * Test Fase 4 (Sistem Revisi): KPS meminta revisi plotting ke Admin (wajib mengisi catatan revisi),
     * Admin melakukan plotting ulang ke dosen lain, lalu KPS meng-approve.
     */
    public function test_kps_can_approve_f1_and_redirect_to_different_dosen(): void
    {
        $application = F1Application::create([
            'student_id' => $this->student1->id,
            'dosen_id' => $this->dosen1->id,
            'recommended_dosen_id' => $this->dosen1->id,
            'plotted_by' => $this->adminTi->id,
            'plotted_at' => now(),
            'company_name' => 'PT Tokopedia',
            'internship_title' => 'Backend Engineering',
            'status' => F1Application::STATUS_PENDING_KPS,
        ]);

        // 1. Validasi wajib isi catatan revisi saat KPS menekan Revisi tanpa teks
        $emptyRevisi = $this->actingAs($this->kpsUser)->post(route('kps.f1.revisi', $application->id), [
            'revision_notes' => '',
        ]);
        $emptyRevisi->assertSessionHasErrors('revision_notes');
        $this->assertEquals(F1Application::STATUS_PENDING_KPS, $application->fresh()->status);

        // 2. KPS meminta revisi dengan mengisi catatan/alasan revisi
        $revisiResponse = $this->actingAs($this->kpsUser)->post(route('kps.f1.revisi', $application->id), [
            'revision_notes' => 'Mohon dialihkan ke Ade Hendrawan untuk pemerataan kuota topik Backend.',
        ]);
        $revisiResponse->assertRedirect(route('dashboard.kps', ['tab' => 'plotting']));

        $this->assertDatabaseHas('form_f1_pengajuan', [
            'id' => $application->id,
            'status' => F1Application::STATUS_REVISION_REQUESTED,
            'revision_notes' => 'Mohon dialihkan ke Ade Hendrawan untuk pemerataan kuota topik Backend.',
        ]);

        // 3. Admin melakukan plotting ulang ke dosen 2 dan meneruskan kembali ke KPS
        $this->actingAs($this->adminTi)->post(route('admin.f1.plot', $application->id), [
            'dosen_id' => $this->dosen2->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('form_f1_pengajuan', [
            'id' => $application->id,
            'dosen_id' => $this->dosen2->id,
            'status' => F1Application::STATUS_PENDING_KPS,
        ]);

        // 4. KPS meng-approve hasil plotting ulang Admin
        $response = $this->actingAs($this->kpsUser)->post(route('kps.f1.approve', $application->id));

        $response->assertRedirect(route('dashboard.kps', ['tab' => 'plotting']));

        $this->assertDatabaseHas('form_f1_pengajuan', [
            'id' => $application->id,
            'dosen_id' => $this->dosen2->id,
            'status' => F1Application::STATUS_APPROVED,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->student1->id,
            'dosen_id' => $this->dosen2->id,
        ]);
    }

    /**
     * Test KPS dapat melakukan plotting langsung terhadap mahasiswa yang belum terplot.
     */
    public function test_kps_can_directly_plot_student(): void
    {
        $response = $this->actingAs($this->kpsUser)->post(route('kps.student.plot'), [
            'student_id' => $this->student2->id,
            'dosen_id' => $this->dosen1->id,
            'company_name' => 'PT Bank Mandiri',
            'internship_title' => 'Data Center Ops',
        ]);

        $response->assertRedirect(route('dashboard.kps', ['tab' => 'plotting']));

        $this->assertDatabaseHas('users', [
            'id' => $this->student2->id,
            'dosen_id' => $this->dosen1->id,
        ]);

        $this->assertDatabaseHas('form_f1_pengajuan', [
            'student_id' => $this->student2->id,
            'dosen_id' => $this->dosen1->id,
            'status' => 'approved',
        ]);
    }

    /**
     * Test KPS dapat memindahkan (reassign) mahasiswa antar dosen.
     */
    public function test_kps_can_reassign_student_to_another_dosen(): void
    {
        $this->student1->update(['dosen_id' => $this->dosen1->id]);

        $response = $this->actingAs($this->kpsUser)->post(route('kps.student.reassign', $this->student1->id), [
            'new_dosen_id' => $this->dosen2->id,
        ]);

        $response->assertRedirect(route('dashboard.kps', ['tab' => 'kuota']));

        $this->assertDatabaseHas('users', [
            'id' => $this->student1->id,
            'dosen_id' => $this->dosen2->id,
        ]);
    }

    /**
     * Test Dosen dashboard hanya menampilkan mahasiswa bimbingan yang telah ditetapkan KPS
     * dan tidak lagi menampilkan form persetujuan/penolakan F1 oleh dosen.
     */
    public function test_dosen_dashboard_receives_assigned_students_cleanly(): void
    {
        $this->student1->update(['dosen_id' => $this->dosen1->id]);

        $response = $this->actingAs($this->dosen1)->get(route('dashboard.dosen'));

        $response->assertStatus(200);
        $response->assertSee($this->student1->name);
        $response->assertSee('Penetapan Mahasiswa Bimbingan Dikelola Terpusat oleh KPS');
        // Tidak ada lagi tombol ACC / Tolak oleh dosen
        $response->assertDontSee('ACC &amp; Upload Surat PDF', false);
        $response->assertDontSee('Tolak Pengajuan');
    }
}
