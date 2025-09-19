<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'comment',
    ];

    /**
     * The task this comment belongs to.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * The user who wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
