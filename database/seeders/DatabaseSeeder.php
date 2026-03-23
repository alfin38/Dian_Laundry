<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Membuat akun admin otomatis saat di-deploy
        User::create([
            'nama'     => 'Admin Dian Laundry', 
            'email'    => 'admin@dianlaundry.com', 
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'level'    => 'admin',
        ]);
    }
}