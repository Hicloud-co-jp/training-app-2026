{{-- 社員番号・氏名・部署による検索・絞り込みフォーム --}}
<div class="card page-card mb-4">
    <div class="card-body">
        <form class="row g-3 align-items-end" method="GET" action="{{ route('employees.index') }}">
            <div class="col-lg-6">
                <label class="form-label" for="search">キーワード</label>
                <input
                    class="form-control"
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="社員番号・氏名・部署"
                >
            </div>

            <div class="col-lg-3">
                <label class="form-label" for="department">部署</label>
                <select class="form-select" id="department" name="department">
                    <option value="">すべて</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department }}" @selected(request('department') === $department)>
                            {{ $department }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-3 d-flex gap-2">
                <button class="btn btn-outline-primary flex-fill">検索</button>
                <a class="btn btn-outline-secondary" href="{{ route('employees.index') }}">解除</a>
            </div>
        </form>
    </div>
</div>
