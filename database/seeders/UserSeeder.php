<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::firstOrCreate(
            ['email' => 'komguepnelly@gmail.com'],
            [
             'name' => 'Nelly',
             'email' => 'komguepnelly@gmail.com',
             'password' => bcrypt('password123'),
    ]);

            User::firstOrCreate(
            ['email' => 'Joycie@gmail.com'],
            [
             'name' => 'Joycie',
             'email' => 'Joycie@gmail.com',
             'password' => bcrypt('password1234'),
    ]);

            User::firstOrCreate(
            ['email' => 'Abdel@gmail.com'],
            [
             'name' => 'Abdel',
             'email' => 'Abdel@gmail.com',
             'password' => bcrypt('password1235'),
    ]);


    }
}
