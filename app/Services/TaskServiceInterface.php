<?php

namespace App\Services;

use App\Services\DTO\CreateTaskDto;
use App\Services\DTO\GetTaskDto;
use App\Services\DTO\UpdateTaskStatusDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Task;
use App\Models\TaskComment;

interface TaskServiceInterface
{
    public function getTasks(GetTaskDto $dto): LengthAwarePaginator;

    public function createTask(CreateTaskDto $dto): Task;

    public function getTaskWithComments(int $taskId): Task;

    public function updateStatus(UpdateTaskStatusDto $dto): Task;

}

