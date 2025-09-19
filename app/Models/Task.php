<?php

namespace App\Models;

use App\Enums\TaskStatusEnum;
use App\Enums\TaskPriorityEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'status',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'status' => TaskStatusEnum::class,
            'priority' => TaskPriorityEnum::class,
        ];
    }

    /**
     * The user who owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The comments for the task.
     */
    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * The notifications for the task.
     */
    public function notifications()
    {
        return $this->hasMany(TaskNotification::class);
    }
}
