<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskCommentRequest;
use App\Http\Resources\TaskCommentResource;
use App\Services\DTO\CreateTaskCommentDto;
use App\Services\TaskCommentServiceInterface;
use Illuminate\Http\JsonResponse;

class TaskCommentController extends Controller
{
    public function __construct(private readonly TaskCommentServiceInterface $taskCommentService)
    {
    }

    /**
     * Add a comment to a task
     *
     * @param int $taskId
     * @param CreateTaskCommentRequest $request
     * @return JsonResponse
     */
    public function store(int $taskId, CreateTaskCommentRequest $request): JsonResponse
    {
        $dto = CreateTaskCommentDto::fromRequest($taskId, $request);
        $comment = $this->taskCommentService->addComment($dto);

        return TaskCommentResource::make($comment)
            ->response()
            ->setStatusCode(201);
    }
}