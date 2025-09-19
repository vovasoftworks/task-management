<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Services\DTO\CreateTaskCommentDto;
use App\Services\DTO\UpdateTaskStatusDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TaskCommentRepositoryInterface
{
    public function addComment(CreateTaskCommentDto $dto): TaskComment;
}

