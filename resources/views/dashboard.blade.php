@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h1 class="page-title mb-1">Welcome, {{ auth()->user()->displayName() }}</h1>
<p class="mb-6 text-sm text-slate-500">Here is a quick summary of your account.</p>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    @foreach ($stats as $label => $value)
    <div class="card">
        <p class="text-sm text-slate-500">{{ $label }}</p>
        <p class="mt-1 text-3xl font-semibold text-teal-700">{{ $value }}</p>
    </div>
    @endforeach
</div>
@endsection