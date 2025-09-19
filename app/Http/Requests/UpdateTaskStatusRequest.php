<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\TaskStatusEnum;

class UpdateTaskStatusRequest extends FormRequest
{
    public const string STATUS = 'status';
    public const string USER_ID = 'user_id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::STATUS => ['required', new Enum(TaskStatusEnum::class)],
            self::USER_ID => ['required', 'integer', 'exists:users,id'],
        ];
    }

    public function getStatus(): TaskStatusEnum
    {
        return TaskStatusEnum::from($this->input(self::STATUS));
    }

    public function getUserId(): int
    {
        return (int)$this->input(self::USER_ID);
    }
}
