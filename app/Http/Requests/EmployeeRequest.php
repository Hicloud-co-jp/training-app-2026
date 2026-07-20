<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

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
