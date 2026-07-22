<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** 社員情報の登録・更新内容を検証するFormRequest */
class EmployeeRequest extends FormRequest
{
    /** 認証済みの利用者だけにリクエストを許可 */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * 社員情報のバリデーションルール
     *
     * 更新時は、現在編集中の社員番号を重複チェックから除外します。
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'employee_number' => [
                'required',
                'string',
                Rule::unique('employees')->ignore($employee?->id),
            ],
            'name' => ['required', 'string'],
            'department' => ['nullable'],
            'joined_at' => ['nullable'],
            'email' => ['nullable'],
        ];
    }

    /**
     * バリデーションメッセージで使用する日本語項目名
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'employee_number' => '社員番号',
            'name' => '氏名',
            'department' => '部署',
            'joined_at' => '入社日',
            'email' => 'メールアドレス',
        ];
    }
}
