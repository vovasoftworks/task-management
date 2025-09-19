<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTaskCommentRequest extends FormRequest
{
    public const string COMMENT = 'comment';
    public const string USER_ID = 'user_id';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            self::COMMENT => ['required', 'string', 'min:3'],
            self::USER_ID => ['required', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Get the comment text
     */
    public function getComment(): string
    {
        return $this->input(self::COMMENT);
    }

    /**
     * Get the user ID
     */
    public function getUserId(): int
    {
        return (int) $this->input(self::USER_ID);
    }
}

