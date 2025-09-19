<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public const string TITLE = 'title';
    public const string DESCRIPTION = 'description';
    public const string USER_ID = 'user_id';
    public const string PRIORITY = 'priority';
    public const string STATUS = 'status';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::TITLE       => ['required', 'string', 'min:5', 'max:100'],
            self::DESCRIPTION => ['nullable', 'string'],
            self::USER_ID     => ['nullable', 'integer'],
            self::PRIORITY    => ['nullable', 'string', Rule::in(['low', 'medium', 'high', 'normal'])],
            self::STATUS      => ['prohibited']
        ];
    }

    public function getTitle(): string
    {
        return $this->get(self::TITLE);
    }

    public function getDescription(): ?string
    {
        return $this->get(self::DESCRIPTION);
    }

    public function getUserId(): ?int
    {
        return $this->get(self::USER_ID);
    }

    public function getPriority(): ?TaskPriorityEnum
    {
        $val = $this->input(self::PRIORITY);
        if (!$val) {
            return null;
        }

        if ($val === 'normal') {
            return TaskPriorityEnum::MEDIUM;
        }

        return TaskPriorityEnum::tryFrom($val);
    }

    public function getStatus(): ?string
    {
        return $this->get(self::STATUS);
    }
}
