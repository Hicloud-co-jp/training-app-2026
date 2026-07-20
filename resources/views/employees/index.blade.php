@extends('layouts.app')
@section('title', '社員一覧')
@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
    <div><h1 class="h2 mb-1">社員一覧</h1><p class="text-secondary mb-0">社員情報の検索・登録・編集・削除ができます。</p></div>
    <a class="btn btn-primary" href="{{ route('employees.create') }}">社員を登録</a>
</div>
<div class="card page-card mb-4"><div class="card-body">
<form class="row g-3 align-items-end" method="GET" action="{{ route('employees.index') }}">
    <div class="col-lg-6"><label class="form-label" for="search">キーワード</label><input class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="社員番号・氏名・部署"></div>
    <div class="col-lg-3"><label class="form-label" for="department">部署</label><select class="form-select" id="department" name="department"><option value="">すべて</option>@foreach ($departments as $department)<option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}</option>@endforeach</select></div>
    <div class="col-lg-3 d-flex gap-2"><button class="btn btn-outline-primary flex-fill">検索</button><a class="btn btn-outline-secondary" href="{{ route('employees.index') }}">解除</a></div>
</form>
</div></div>
<div class="card page-card"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
<thead class="table-light"><tr><th>社員番号</th><th>氏名</th><th>部署</th><th>入社日</th><th>メール</th><th class="text-end">操作</th></tr></thead>
<tbody>@forelse ($employees as $employee)
<tr>
    <td class="employee-number">{{ $employee->employee_number }}</td><td>{{ $employee->name }}</td><td>{{ $employee->department ?: '—' }}</td><td>{{ $employee->joined_at?->format('Y/m/d') ?? '—' }}</td><td>{{ $employee->email ?: '—' }}</td>
    <td class="text-end text-nowrap"><a class="btn btn-sm btn-outline-primary" href="{{ route('employees.edit', $employee) }}">編集</a>
        <form class="d-inline" method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('この社員情報を削除しますか？')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">削除</button></form>
    </td>
</tr>
@empty<tr><td class="text-center text-secondary py-5" colspan="6">該当する社員はいません。</td></tr>@endforelse</tbody>
</table></div>@if ($employees->hasPages())<div class="card-footer bg-white py-3">{{ $employees->links() }}</div>@endif</div>
@endsection
