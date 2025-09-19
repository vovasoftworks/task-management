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
use Illuminate\Support\Facades\Log;

class SendTaskStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $taskId,
        public string $notificationType
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Find the task
        $task = Task::with('user')->find($this->taskId);
        
        if (!$task) {
            Log::warning("Task not found for notification: {$this->taskId}");
            return;
        }

        // Find all managers
        $managers = User::where('position', PositionEnum::MANAGER->value)->get();

        if ($managers->isEmpty()) {
            Log::warning("No managers found for task notification: {$this->taskId}");
            return;
        }

        // Create notification for each manager
        foreach ($managers as $manager) {
            $message = $this->generateMessage($task, $this->notificationType);
            
            TaskNotification::create([
                'user_id' => $manager->id,
                'task_id' => $task->id,
                'message' => $message,
            ]);
        }

        // Log the notification (simulate sending)
        Log::info("Task notification sent", [
            'task_id' => $task->id,
            'type' => $this->notificationType,
            'managers_count' => $managers->count(),
            'message' => $message
        ]);
    }

    /**
     * Generate notification message based on type
     */
    private function generateMessage(Task $task, string $type): string
    {
        $taskTitle = $task->title;
        $assignedUser = $task->user ? $task->user->name : 'Unassigned';

        return match ($type) {
            'task_assigned' => "High priority task '{$taskTitle}' has been assigned to {$assignedUser}",
            'status_changed' => "Task '{$taskTitle}' status changed to {$task->status->value}",
            'overdue' => "Task '{$taskTitle}' is overdue! Created {$task->created_at->format('Y-m-d H:i:s')}",
            default => "Task '{$taskTitle}' notification: {$type}"
        };
    }
}
