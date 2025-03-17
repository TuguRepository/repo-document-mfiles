<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Aditya';
        $user->email = 'aditya@tugu.com';
        $user->password = Hash::make('password123');
        $user->save();

        // Cek apakah Spatie Permission digunakan
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('admin');
        }
    }
}
