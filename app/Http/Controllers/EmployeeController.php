<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeIndexRequest;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(EmployeeIndexRequest $request): View
    {
        $validated = $request->validated();
        $employees = Employee::query()
            ->search($validated['search'] ?? null)
            ->department($validated['department'] ?? null)
            ->orderBy('employee_number')
            ->paginate(10);

        $departments = Employee::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create(): View
    {
        return view('employees.create');
    }

    public function store(EmployeeRequest $request): RedirectResponse
    {
        $employee = Employee::create($request->validated());
        Log::info('employee.created', ['employee_id' => $employee->id, 'actor_id' => $request->user()->id]);

        return redirect()->route('employees.index')->with('success', '社員情報を登録しました。');
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());
        Log::info('employee.updated', ['employee_id' => $employee->id, 'actor_id' => $request->user()->id]);

        return redirect()->route('employees.index')->with('success', '社員情報を更新しました。');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();
        Log::info('employee.deleted', ['employee_id' => $employee->id, 'actor_id' => request()->user()->id]);

        return redirect()->route('employees.index')->with('success', '社員情報を削除しました。');
    }
}
