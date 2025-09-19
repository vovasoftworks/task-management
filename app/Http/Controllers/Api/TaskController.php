<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\IndexTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskResource;
use App\Services\DTO\CreateTaskDto;
use App\Services\DTO\GetTaskDto;
use App\Services\DTO\UpdateTaskStatusDto;
use App\Services\TaskServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(private readonly TaskServiceInterface $taskService)
    {
    }

    public function index(IndexTasksRequest $request): AnonymousResourceCollection
    {
        return TaskResource::collection(
            $this->taskService->getTasks(
                new GetTaskDto(
                    $request->getPage(),
                    $request->getPerPage(),
                    [
                        'status'   => $request->getStatus(),
                        'priority' => $request->getPriority(),
                        'user_id'  => $request->getUserId(),
                    ],
                    ['created_at' => 'desc']
                )
            )
        );
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        $task = $this->taskService->createTask(
            CreateTaskDto::fromRequest($request)
        );

        $task->loadMissing('user');

        return TaskResource::make($task);
    }

    public function show(int $id): TaskResource
    {
        $task = $this->taskService->getTaskWithComments($id);

        return TaskResource::make($task);
    }

    public function updateStatus(int $id, UpdateTaskStatusRequest $request): TaskResource
    {
        $dto  = UpdateTaskStatusDto::fromRequest($id, $request);
        $task = $this->taskService->updateStatus($dto);

        return TaskResource::make($task);
    }
}

