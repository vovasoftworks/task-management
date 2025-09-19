<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use App\Services\DTO\UpdateTaskStatusDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function getAll(array $filters, int $page = 1, int $perPage = 15, array $sort = ['created_at' => 'desc']): LengthAwarePaginator;

    public function create(array $data): Task;

    public function find(int $id): Task;

    public function updateStatus(UpdateTaskStatusDto $dto): Task;

    public function addComment(int $taskId, array $data): TaskComment;

    public function findUser(int $userId): ?User;

    public function findManager(): ?User;
}
