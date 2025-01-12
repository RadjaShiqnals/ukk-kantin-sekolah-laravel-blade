<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Siswa',
            'username' => 'siswa',
            'email' => 'siswa@gmail.com',
            'password' => Hash::make('siswa123'),
            'role' => 'siswa'
        ]);
        User::create([
            'name' => 'Admin Stan',
            'username' => 'adminstan',
            'email' => 'adminstan@gmail.com',
            'password' => Hash::make('adminstan123'),
            'role' => 'admin_stan'
        ]);
    }
}
