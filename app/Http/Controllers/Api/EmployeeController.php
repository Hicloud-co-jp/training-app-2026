<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeIndexRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * 社員情報をJSON形式で提供するAPIコントローラー
 *
 * 読み取り専用の一覧・詳細取得機能を提供します。
 */
class EmployeeController extends Controller
{
    /** 検索・絞り込み・ページネーション付きの社員一覧を取得 */
    public function index(EmployeeIndexRequest $request): AnonymousResourceCollection
    {
        // 検証済みの検索条件だけをクエリへ渡す
        $validated = $request->validated();
        $employees = Employee::query()
            ->search($validated['search'] ?? null)
            ->department($validated['department'] ?? null)
            ->orderBy('employee_number')
            ->paginate($validated['per_page'] ?? 10);

        return EmployeeResource::collection($employees);
    }

    /** 指定された社員の詳細を取得 */
    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }
}
