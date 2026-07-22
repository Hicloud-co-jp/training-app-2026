{{-- 社員一覧テーブル --}}
<div class="card page-card">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>社員番号</th>
                    <th>氏名</th>
                    <th>部署</th>
                    <th>入社日</th>
                    <th>メール</th>
                    <th class="text-end">操作</th>
                </tr>
            </thead>
            <tbody>
                {{-- 社員データがない場合も同じテーブル内にメッセージを表示 --}}
                @forelse ($employees as $employee)
                    <tr>
                        <td class="employee-number">{{ $employee->employee_number }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->department ?: '—' }}</td>
                        <td>{{ $employee->joined_at?->format('Y/m/d') ?? '—' }}</td>
                        <td>{{ $employee->email ?: '—' }}</td>
                        <td class="text-end text-nowrap">
                            <a
                                class="btn btn-sm btn-outline-primary"
                                href="{{ route('employees.edit', $employee) }}"
                            >
                                編集
                            </a>

                            {{-- 削除処理は確認ダイアログを表示してから送信 --}}
                            <form
                                class="d-inline"
                                method="POST"
                                action="{{ route('employees.destroy', $employee) }}"
                                onsubmit="return confirm('この社員情報を削除しますか？')"
                            >
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">削除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-secondary py-5" colspan="6">
                            該当する社員はいません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 複数ページある場合だけページネーションを表示 --}}
    @if ($employees->hasPages())
        <div class="card-footer bg-white py-3">
            {{ $employees->links() }}
        </div>
    @endif
</div>
