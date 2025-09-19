<?php

namespace App\Services;

use App\Enums\TaskPriorityEnum;
use App\Jobs\SendTaskStatusNotification;
use App\Models\Task;
use App\Repositories\TaskRepositoryInterface;
use App\Services\DTO\CreateTaskDto;
use App\Services\DTO\GetTaskDto;
use App\Services\DTO\UpdateTaskStatusDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService implements TaskServiceInterface
{
    public function __construct(private TaskRepositoryInterface $tasks)
    {
    }

    public function getTasks(GetTaskDto $dto): LengthAwarePaginator
    {
        $filters = $dto->filters ?? [];
        if (!array_key_exists('status', $filters) && $status = request()->query('status')) {
            $filters['status'] = $status;
        }
        if (!array_key_exists('priority', $filters) && $priority = request()->query('priority')) {
            $filters['priority'] = $priority;
        }
        if (!array_key_exists('user_id', $filters) && ($userId = request()->query('user_id'))) {
            $filters['user_id'] = (int) $userId;
        }

        $page = $dto->page ?? 1;
        $perPage = $dto->perPage ?? 15;
        $sort = $dto->sort ?? ['created_at' => 'desc'];

        return $this->tasks->getAll($filters, $page, $perPage, $sort);
    }

    /**
     * Create a new task with business logic
     */
    public function createTask(CreateTaskDto $dto): Task
    {
        $data = $dto->toArray();

        $task = $this->tasks->create($data);

        if ($data['priority'] === TaskPriorityEnum::HIGH->value) {
            SendTaskStatusNotification::dispatch($task->id, 'task_assigned');
        }

        return $task;
    }

    /**
     * Get task with comments and user information
     */
    public function getTaskWithComments(int $taskId): Task
    {
        return $this->tasks->find($taskId);
    }

    /**
     * Update task status with business logic
     */
    public function updateStatus(UpdateTaskStatusDto $dto): Task
    {
        $task = $this->tasks->updateStatus($dto);

        SendTaskStatusNotification::dispatch($task->id, 'status_changed');

        return $task;
    }


}


