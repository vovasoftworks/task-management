<?php

namespace App\Services\DTO;

use App\Enums\TaskStatusEnum;
use App\Http\Requests\UpdateTaskStatusRequest;

final class UpdateTaskStatusDto
{
    public function __construct(
        public readonly int            $taskId,
        public readonly TaskStatusEnum $status,
        public readonly int            $userId,
    )
    {
    }

    public static function fromRequest(int $taskId, UpdateTaskStatusRequest $r): self
    {
        return new self(
            taskId: $taskId,
            status: $r->getStatus(),
            userId: $r->getUserId(),
        );
    }
}
