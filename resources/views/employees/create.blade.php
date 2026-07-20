@extends('layouts.app')
@section('title', '社員登録')
@section('content')
<div class="row justify-content-center"><div class="col-xl-8"><div class="card page-card"><div class="card-body p-4 p-lg-5"><h1 class="h3 mb-4">社員登録</h1><form method="POST" action="{{ route('employees.store') }}">@csrf @include('employees._form')</form></div></div></div></div>
@endsection
