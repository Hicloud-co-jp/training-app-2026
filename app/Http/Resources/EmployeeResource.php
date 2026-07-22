<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** 社員モデルをAPIレスポンス用の配列へ変換するリソース */
class EmployeeResource extends JsonResource
{
    /**
     * 公開する社員情報を配列形式へ変換
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee_number' => $this->employee_number,
            'name' => $this->name,
            'department' => $this->department,
            'joined_at' => $this->joined_at?->format('Y-m-d'),
            'email' => $this->email,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
