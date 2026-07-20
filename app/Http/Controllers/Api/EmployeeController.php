<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeIndexRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmployeeController extends Controller
{
    public function index(EmployeeIndexRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $employees = Employee::query()
            ->search($validated['search'] ?? null)
            ->department($validated['department'] ?? null)
            ->orderBy('employee_number')
            ->paginate($validated['per_page'] ?? 10);

        return EmployeeResource::collection($employees);
    }

    public function show(Employee $employee): EmployeeResource
    {
        return new EmployeeResource($employee);
    }
}
