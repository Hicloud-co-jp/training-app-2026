<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeIndexRequest;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * 社員管理のWebページ用コントローラー
 *
 * 社員情報の一覧表示、登録、編集、削除を処理します。
 */
class EmployeeController extends Controller
{
    /**
     * 社員一覧ページを表示
     *
     * キーワード検索と部署絞り込みを適用し、10件ずつ表示します。
     */
    public function index(EmployeeIndexRequest $request): View
    {
        // 検証済みの検索条件だけをクエリへ渡す
        $validated = $request->validated();
        $employees = Employee::query()
            ->search($validated['search'] ?? null)
            ->department($validated['department'] ?? null)
            ->orderBy('employee_number')
            ->paginate(10);

        // 絞り込み欄に表示する部署一覧を重複なしで取得
        $departments = Employee::query()
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->distinct()
            ->orderBy('department')
            ->pluck('department');

        return view('employees.index', compact('employees', 'departments'));
    }

    /** 社員登録ページを表示 */
    public function create(): View
    {
        return view('employees.create');
    }

    /** 新しい社員情報を登録 */
    public function store(EmployeeRequest $request): RedirectResponse
    {
        // FormRequestで検証済みの値だけを保存
        $employee = Employee::create($request->validated());

        // 個人情報は含めず、操作対象と操作者のIDだけを記録
        Log::info('employee.created', ['employee_id' => $employee->id, 'actor_id' => $request->user()->id]);

        return redirect()->route('employees.index')->with('success', '社員情報を登録しました。');
    }

    /** 社員編集ページを表示 */
    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    /** 既存の社員情報を更新 */
    public function update(EmployeeRequest $request, Employee $employee): RedirectResponse
    {
        // FormRequestで検証済みの値だけを更新
        $employee->update($request->validated());

        // 個人情報は含めず、操作対象と操作者のIDだけを記録
        Log::info('employee.updated', ['employee_id' => $employee->id, 'actor_id' => $request->user()->id]);

        return redirect()->route('employees.index')->with('success', '社員情報を更新しました。');
    }

    /** 社員情報を論理削除 */
    public function destroy(Employee $employee): RedirectResponse
    {
        // SoftDeletesにより、レコードは物理削除せず削除日時を記録
        $employee->delete();

        // 個人情報は含めず、操作対象と操作者のIDだけを記録
        Log::info('employee.deleted', ['employee_id' => $employee->id, 'actor_id' => request()->user()->id]);

        return redirect()->route('employees.index')->with('success', '社員情報を削除しました。');
    }
}
