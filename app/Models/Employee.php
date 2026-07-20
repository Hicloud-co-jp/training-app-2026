<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_number',
        'name',
        'department',
        'joined_at',
        'email',
    ];

    protected function casts(): array
    {
        return ['joined_at' => 'date'];
    }

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

    public function scopeDepartment(Builder $query, ?string $department): Builder
    {
        $department = trim((string) $department);

        return $department === '' ? $query : $query->where('department', $department);
    }
}
