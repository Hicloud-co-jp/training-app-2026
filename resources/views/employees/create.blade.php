<x-app-layout>
    <x-slot name="header">
        <h1 class="h2 mb-0">社員登録</h1>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card page-card">
                <div class="card-body p-4 p-lg-5">
                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        @include('employees.partials._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
