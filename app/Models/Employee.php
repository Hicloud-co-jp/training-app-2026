<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 社員情報を管理するEloquentモデル
 *
 * 社員番号、氏名、部署、入社日、メールアドレスを管理します。
 */
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory, SoftDeletes;

    /** 一括代入を許可する属性 */
    protected $fillable = [
        'employee_number',
        'name',
        'department',
        'joined_at',
        'email',
    ];

    /**
     * 属性の型変換設定
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['joined_at' => 'date'];
    }

    /** 社員番号・氏名・部署をキーワードで部分一致検索 */
    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $query) use ($keyword): void {
            $query->where('employee_number', 'like', "%{$keyword}%")
                ->orWhere('name', 'like', "%{$keyword}%")
                ->orWhere('department', 'like', "%{$keyword}%");
        });
    }

    /** 指定された部署で完全一致検索 */
    public function scopeDepartment(Builder $query, ?string $department): Builder
    {
        $department = trim((string) $department);

        return $department === '' ? $query : $query->where('department', $department);
    }
}
