<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** 社員一覧の検索条件を検証するFormRequest */
class EmployeeIndexRequest extends FormRequest
{
    /** 認証済みの利用者だけにリクエストを許可 */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * 検索条件のバリデーションルール
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
