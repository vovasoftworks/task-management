<?php

namespace App\Http\Requests;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rule;

class IndexTasksRequest extends FormRequest
{
    public const int PER_PAGE_DEFAULT = 25;
    public const int PAGE_DEFAULT = 1;

    public const string PAGE = 'page';
    public const string PER_PAGE = 'per_page';
    public const string STATUS = 'status';
    public const string PRIORITY = 'priority';
    public const string USER_ID = 'user_id';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            self::STATUS   => ['sometimes', new Enum(TaskStatusEnum::class)],
            self::PRIORITY => ['sometimes', new Enum(TaskPriorityEnum::class)],
            self::USER_ID  => ['sometimes', 'integer', 'exists:users,id'],
            self::PAGE     => ['sometimes', 'integer', 'min:1'],
            self::PER_PAGE => $this->getPerPageRule(),
        ];
    }

    public function getPage(): int
    {
        return $this->get(self::PAGE) ?? self::PAGE_DEFAULT;
    }

    public function getPerPage(): int
    {
        return $this->get(self::PER_PAGE) ?? self::PER_PAGE_DEFAULT;
    }

    private function getPerPageRule(): string
    {
        return 'integer|min:1|max:100';
    }

    public function getStatus(): ?string
    {
        return $this->get(self::STATUS);
    }

    public function getPriority(): ?string
    {
        return $this->get(self::PRIORITY);
    }

    public function getUserId(): ?int
    {
        return $this->get(self::USER_ID);
    }
}
