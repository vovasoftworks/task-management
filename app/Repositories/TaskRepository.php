<?php

namespace App\Repositories;

use App\Enums\PositionEnum;
use App\Enums\TaskStatusEnum;
use App\Enums\TaskPriorityEnum;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Services\DTO\UpdateTaskStatusDto;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(array $filters, int $page = 1, int $perPage = 15, array $sort = ['created_at' => 'desc']): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Task::with(['user', 'comments.user']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        foreach ($sort as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function create(array $data): Task
    {
        $data['status'] = TaskStatusEnum::NEW->value;

        if ($data['priority'] === TaskPriorityEnum::HIGH->value) {
            $data['status'] = TaskStatusEnum::IN_PROGRESS->value;
        }

        $userId = $data['user_id'] ?? null;
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $userId = null;
            }
        }

        if (!$userId) {
            $manager = User::where('position', PositionEnum::MANAGER->value)->first();
            if ($manager) {
                $userId = $manager->id;
            }
        }

        $data['user_id'] = $userId;

        return Task::create($data);
    }

    public function updateStatus(UpdateTaskStatusDto $dto): Task
    {
        $task = Task::findOrFail($dto->taskId);

        $newStatus = $dto->status instanceof TaskStatusEnum
            ? $dto->status
            : TaskStatusEnum::from($dto->status['name']);

        $isTransitionToCompleted =
            $task->status !== TaskStatusEnum::COMPLETED &&
            $newStatus === TaskStatusEnum::COMPLETED;

        $task->update([
            'status' => $newStatus,
        ]);

        if ($isTransitionToCompleted) {
            $userName = optional(User::find($dto->userId))->name
                ?? "User #{$dto->userId}";

            $task->comments()->create([
                'user_id' => $dto->userId,
                'comment' => "Task completed by {$userName}",
            ]);
        }

        return $task->fresh(['user', 'comments.user']);
    }

    public function find(int $id): Task
    {
        return Task::with(['user', 'comments.user'])->findOrFail($id);
    }

    public function addComment(int $taskId, array $data): TaskComment
    {
        $task = $this->find($taskId);

        if ($task->status === TaskStatusEnum::CANCELLED->value) {
            throw new \Exception('Cannot add comments to cancelled tasks');
        }

        return TaskComment::create([
            'task_id' => $taskId,
            'user_id' => $data['user_id'],
            'comment' => $data['comment'],
        ]);
    }

    /**
     * Find user by ID
     */
    public function findUser(int $userId): ?User
    {
        return User::find($userId);
    }

    /**
     * Find manager user
     */
    public function findManager(): ?User
    {
        return User::where('position', PositionEnum::MANAGER->value)->first();
    }
}


