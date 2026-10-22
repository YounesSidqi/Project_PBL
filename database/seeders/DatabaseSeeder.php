<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Mahasiswa
        User::updateOrCreate(
            ['email' => '2107411002@stu.pnj.ac.id'],
            [
                'name' => 'Ahmad Fauzi',
                'identifier' => '2107411002',
                'role' => 'mahasiswa',
                'prodi' => 'Teknik Informatika',
                'phone' => '081234567890',
                'password' => bcrypt('password123'),
            ]
        );

        // 2. Dosen Pembimbing
        User::updateOrCreate(
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

        // 3. Mitra Industri / DUDI
        User::updateOrCreate(
            ['email' => 'mentor@mitra-industri.co.id'],
            [
                'name' => 'Mentor Mitra DUDI',
                'identifier' => 'IND-90214',
                'role' => 'dudi',
                'phone' => '082155558888',
                'password' => bcrypt('password123'),
            ]
        );

        // 4. Admin / KPS
        User::updateOrCreate(
            ['email' => 'admin.tik@pnj.ac.id'],
            [
                'name' => 'Koordinator PKL TIK',
                'identifier' => 'ADM-TIK-01',
                'role' => 'admin',
                'phone' => '081290907654',
                'password' => bcrypt('password123'),
            ]
        );
    }
}
