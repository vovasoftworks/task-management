<?php

namespace App\Services;

use App\Models\TaskComment;
use App\Services\DTO\CreateTaskCommentDto;

interface TaskCommentServiceInterface
{
    /**
     * Add a comment to a task
     *
     * @param CreateTaskCommentDto $dto
     * @return TaskComment
     */
    public function addComment(CreateTaskCommentDto $dto): TaskComment;
}

