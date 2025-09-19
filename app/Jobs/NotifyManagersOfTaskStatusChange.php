<?php

namespace App\Jobs;

use App\Enums\PositionEnum;
use App\Models\Task;
use App\Models\TaskNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyManagersOfTaskStatusChange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Task $task)
    {
    }

    public function handle(): void
    {
        $managers = User::where('position', PositionEnum::Manager->value)->get();
        foreach ($managers as $manager) {
            TaskNotification::create([
                'user_id' => $manager->id,
                'task_id' => $this->task->id,
                'message' => 'Task #' . $this->task->id . ' status changed to ' . $this->task->status,
            ]);
        }
    }
}


