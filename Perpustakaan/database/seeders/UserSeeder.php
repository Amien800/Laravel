<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesAdmin = Roles::where('name', 'admin')->first();

        User::create([
            'name' => 'admin',
            'email' => 'admin@mail.com',
            'role_id' => $rolesAdmin->id,
            'password' => Hash::make('password123'),
        ]);
    }
}
