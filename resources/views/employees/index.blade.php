<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="h2 mb-1">社員一覧</h1>
                <p class="text-secondary mb-0">社員情報の検索・登録・編集・削除ができます。</p>
            </div>
            <a class="btn btn-primary" href="{{ route('employees.create') }}">社員を登録</a>
        </div>
    </x-slot>

    {{-- 検索フォームを別ファイルから読み込み --}}
    @include('employees.partials._search_form')

    {{-- 社員一覧テーブルを別ファイルから読み込み --}}
    @include('employees.partials._employee_table')
</x-app-layout>
