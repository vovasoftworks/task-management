<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TaskCommentRepositoryInterface;
use App\Repositories\TaskCommentRepository;
use App\Repositories\TaskRepositoryInterface;
use App\Repositories\TaskRepository;
use App\Services\TaskServiceInterface;
use App\Services\TaskService;
use App\Services\TaskCommentServiceInterface;
use App\Services\TaskCommentService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(TaskCommentRepositoryInterface::class, TaskCommentRepository::class);
        $this->app->bind(TaskServiceInterface::class, TaskService::class);
        $this->app->bind(TaskCommentServiceInterface::class, TaskCommentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
