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
        $users = [
            [
                'name' => 'John Manager',
                'email' => 'john.manager@example.com',
                'position' => 'manager',
            ],
            [
                'name' => 'Jane Developer',
                'email' => 'jane.developer@example.com',
                'position' => 'developer',
            ],
            [
                'name' => 'Bob Tester',
                'email' => 'bob.tester@example.com',
                'position' => 'tester',
            ],
            [
                'name' => 'Alice Manager',
                'email' => 'alice.manager@example.com',
                'position' => 'manager',
            ],
            [
                'name' => 'Charlie Developer',
                'email' => 'charlie.developer@example.com',
                'position' => 'developer',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
