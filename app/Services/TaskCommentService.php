<?php

namespace App\Services;

use App\Models\TaskComment;
use App\Repositories\TaskCommentRepositoryInterface;
use App\Services\DTO\CreateTaskCommentDto;

/**
 * Service for managing task comments
 * 
 * Handles business logic for task comment operations,
 * including validation and data transformation.
 */
class TaskCommentService implements TaskCommentServiceInterface
{
    public function __construct(private TaskCommentRepositoryInterface $taskCommentRepository)
    {
    }

    /**
     * Add a comment to a task
     * 
     * @param CreateTaskCommentDto $dto The comment data
     * @return TaskComment
     */
    public function addComment(CreateTaskCommentDto $dto): TaskComment
    {
        return $this->taskCommentRepository->addComment($dto);
    }
}

