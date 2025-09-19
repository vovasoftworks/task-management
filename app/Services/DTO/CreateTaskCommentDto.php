<?php

namespace App\Services\DTO;

use App\Http\Requests\CreateTaskCommentRequest;

/**
 * Data Transfer Object for creating task comments
 * 
 * Encapsulates the data required to create a new task comment,
 * providing type safety and validation through the request object.
 */
final class CreateTaskCommentDto
{
    public function __construct(
        public readonly int    $taskId,
        public readonly int    $userId,
        public readonly string $comment,
    ) {}

    /**
     * Create DTO from validated request data
     * 
     * @param int $taskId The ID of the task to comment on
     * @param CreateTaskCommentRequest $request The validated request
     * @return self
     */
    public static function fromRequest(int $taskId, CreateTaskCommentRequest $request): self
    {
        return new self(
            taskId:  $taskId,
            userId:  $request->getUserId(),
            comment: $request->getComment(),
        );
    }

    /**
     * Convert DTO to array for database operations
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'task_id' => $this->taskId,
            'user_id' => $this->userId,
            'comment' => $this->comment,
        ];
    }
}
