<?php

namespace App\Services\DTO;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Http\Requests\StoreTaskRequest;

final class CreateTaskDto
{
    public function __construct(
        public readonly string            $title,
        public readonly ?string           $description,
        public readonly ?int              $userId,
        public readonly ?TaskPriorityEnum $priority,
    ) {}

    public static function fromRequest(StoreTaskRequest $r): self
    {
        return new self(
            title:       $r->getTitle(),
            description: $r->getDescription(),
            userId:      $r->getUserId(),
            priority:    $r->getPriority(),
        );
    }

    public function toArray(): array
    {
        return [
            'title'       => $this->title,
            'description' => $this->description,
            'user_id'     => $this->userId,
            'priority'    => $this->priority?->value,
        ];
    }
}
