<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        $tasks = [
            [
                'title' => 'Implement user authentication',
                'description' => 'Create login and registration functionality for the application',
                'user_id' => $users->where('position', 'developer')->first()->id,
                'status' => 'in_progress',
                'priority' => 'high',
            ],
            [
                'title' => 'Write unit tests',
                'description' => 'Create comprehensive unit tests for all modules',
                'user_id' => $users->where('position', 'tester')->first()->id,
                'status' => 'new',
                'priority' => 'low',
            ],
            [
                'title' => 'Database optimization',
                'description' => 'Optimize database queries and add proper indexes',
                'user_id' => $users->where('position', 'developer')->first()->id,
                'status' => 'new',
                'priority' => 'low',
            ],
            [
                'title' => 'Code review',
                'description' => 'Review all pull requests and provide feedback',
                'user_id' => $users->where('position', 'manager')->first()->id,
                'status' => 'in_progress',
                'priority' => 'high',
            ],
            [
                'title' => 'API documentation',
                'description' => 'Create comprehensive API documentation',
                'user_id' => $users->where('position', 'developer')->first()->id,
                'status' => 'new',
                'priority' => 'high',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
