<?php

namespace Tests\Feature;

use App\Models\Logbook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogbookTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_create_logbook(): void
    {
        $dosen = User::create([
            'name' => 'Dr. Bambang Sutrisno, M.T.',
            'identifier' => '198503152010121002',
            'email' => 'bambang@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);

        $student = User::create([
            'name' => 'Ahmad Fauzi',
            'identifier' => '2107411002',
            'email' => 'ahmad@stu.pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
            'dosen_id' => $dosen->id,
            'prodi' => 'Teknik Informatika (D4)',
        ]);

        $response = $this->actingAs($student)->post(route('logbooks.store'), [
            'subject' => 'Automated Feature Test Entry',
            'tanggal' => now()->format('Y-m-d'),
            'content' => 'Uraian aktivitas pengujian automated testing logbook mahasiswa.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('logbooks', [
            'user_id' => $student->id,
            'subject' => 'Automated Feature Test Entry',
            'status' => 'pending',
        ]);
    }

    public function test_student_can_update_their_logbook(): void
    {
        $dosen = User::create([
            'name' => 'Dr. Bambang Sutrisno, M.T.',
            'identifier' => '198503152010121002',
            'email' => 'bambang@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);

        $student = User::create([
            'name' => 'Ahmad Fauzi',
            'identifier' => '2107411002',
            'email' => 'ahmad@stu.pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
            'dosen_id' => $dosen->id,
            'prodi' => 'Teknik Informatika (D4)',
        ]);

        $logbook = Logbook::create([
            'user_id' => $student->id,
            'dosen_id' => $student->dosen_id,
            'subject' => 'Original Subject',
            'tanggal' => now(),
            'content' => 'Deskripsi lama yang akan diperbarui dalam test.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($student)->put(route('logbooks.update', $logbook), [
            'subject' => 'Updated Subject Test',
            'tanggal' => now()->format('Y-m-d'),
            'content' => 'Deskripsi baru yang sudah diperbarui dengan baik dan lengkap.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('logbooks', [
            'id' => $logbook->id,
            'subject' => 'Updated Subject Test',
        ]);
    }

    public function test_dosen_can_request_revision_and_approve_logbook(): void
    {
        $dosen = User::create([
            'name' => 'Dr. Bambang Sutrisno, M.T.',
            'identifier' => '198503152010121002',
            'email' => 'bambang@pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);

        $student = User::create([
            'name' => 'Ahmad Fauzi',
            'identifier' => '2107411002',
            'email' => 'ahmad@stu.pnj.ac.id',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
            'dosen_id' => $dosen->id,
            'prodi' => 'Teknik Informatika (D4)',
        ]);

        $logbook = Logbook::create([
            'user_id' => $student->id,
            'dosen_id' => $dosen->id,
            'subject' => 'Test Subject Review',
            'tanggal' => now(),
            'content' => 'Uraian aktivitas magang untuk diverifikasi dosen.',
            'status' => 'pending',
        ]);

        // Dosen requests revision
        $revResponse = $this->actingAs($dosen)->post(route('logbooks.updateStatus', $logbook), [
            'status' => 'revision',
            'feedback' => 'Tolong lengkapi hasil benchmarking.',
        ]);

        $revResponse->assertRedirect();
        $this->assertEquals('revision', $logbook->fresh()->status);
        $this->assertEquals('Tolong lengkapi hasil benchmarking.', $logbook->fresh()->feedback);

        // Student updates and resubmits
        $this->actingAs($student)->put(route('logbooks.update', $logbook), [
            'subject' => 'Test Subject Review (Revisi Selesai)',
            'tanggal' => now()->format('Y-m-d'),
            'content' => 'Uraian aktivitas magang sudah ditambahkan data benchmarking 5000 RPS.',
        ]);

        $this->assertEquals('pending', $logbook->fresh()->status);

        // Dosen approves
        $appResponse = $this->actingAs($dosen)->post(route('logbooks.updateStatus', $logbook), [
            'status' => 'approved',
            'feedback' => 'Sangat bagus, logbook disetujui.',
        ]);

        $appResponse->assertRedirect();
        $this->assertEquals('approved', $logbook->fresh()->status);
    }
}
