<?php

namespace App\Repositories;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\TaskComment;
use App\Services\DTO\CreateTaskCommentDto;

/**
 * Repository for task comment data access
 *
 * Handles database operations for task comments,
 * including validation and business rule enforcement.
 */
class TaskCommentRepository implements TaskCommentRepositoryInterface
{
    public function addComment(CreateTaskCommentDto $dto): TaskComment
    {
        $task = Task::findOrFail($dto->taskId);

        if ($task->status === TaskStatusEnum::CANCELLED->value) {
            throw new \Exception('Cannot add comments to cancelled tasks');
        }

        return TaskComment::create($dto->toArray());
    }
}


