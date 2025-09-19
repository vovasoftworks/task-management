<?php

namespace App\Console\Commands;

use App\Enums\TaskStatusEnum;
use App\Jobs\SendTaskStatusNotification;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-overdue {--dry-run : Show what would be done without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue tasks and add comments with notifications';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('Running in DRY-RUN mode');
        }

        $overdueDate = Carbon::now()->subDays(7);

        $overdueTasks = Task::where('status', TaskStatusEnum::IN_PROGRESS->value)
            ->where('created_at', '<', $overdueDate)
            ->with('user')
            ->get();

        if ($overdueTasks->isEmpty()) {
            $this->info('✅ No overdue tasks found!');
            return Command::SUCCESS;
        }

        $this->info("Found {$overdueTasks->count()} overdue task(s):");
        $this->newLine();

        $processedCount = 0;

        foreach ($overdueTasks as $task) {
            $this->displayTaskInfo($task);

            if (!$isDryRun) {
                $this->processOverdueTask($task);
                $processedCount++;
            }

            $this->newLine();
        }

        if ($isDryRun) {
            $this->info("DRY-RUN: Would process {$overdueTasks->count()} overdue task(s)");
        } else {
            $this->info("Successfully processed {$processedCount} overdue task(s)");
        }

        return Command::SUCCESS;
    }

    /**
     * Display task information
     */
    private function displayTaskInfo(Task $task): void
    {
        $assignedUser = $task->user ? $task->user->name : 'Unassigned';
        $daysOverdue = Carbon::parse($task->created_at)->diffInDays(Carbon::now());

        $this->line("Task #{$task->id}: {$task->title}");
        $this->line("Assigned to: {$assignedUser}");
        $this->line("Created: {$task->created_at->format('Y-m-d H:i:s')}");
        $this->line("Days overdue: {$daysOverdue}");
        $this->line(" Priority: {$task->priority->value}");
    }

    /**
     * Process overdue task
     */
    private function processOverdueTask(Task $task): void
    {
        $commentMessage = "Task is overdue! Created {$task->created_at->format('Y-m-d H:i:s')}";

        $manager = User::where('position', 'manager')->first();

        if ($manager) {
            TaskComment::create([
                'task_id' => $task->id,
                'user_id' => $manager->id,
                'comment' => $commentMessage,
            ]);

            $this->line("Added overdue comment");
        }

        SendTaskStatusNotification::dispatch($task->id, 'overdue');
        $this->line("Dispatched overdue notification");
    }
}
