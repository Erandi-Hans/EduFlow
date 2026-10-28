@extends('layouts.app')
@section('title', 'Student')

@section('content')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
    <h1 class="page-title">Student</h1>
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ $search }}" placeholder="Search by name" class="input w-56">
        <button class="btn-light">Search</button>
    </form>
</div>

<div class="card overflow-x-auto p-0">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="th">Name</th>
                <th class="th">Email</th>
                <th class="th">Guardian</th>
                <th class="th">Course</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($Student as $s)
            <tr>
                <td class="td font-medium">{{ $s->full_name }}</td>
                <td class="td">{{ $s->user->email }}</td>
                <td class="td">{{ $s->guardian_name ?: '-' }}</td>
                <td class="td">
                    @forelse ($s->Course as $c)
                    <span class="badge mr-1">{{ $c->course_code }}</span>
                    @empty
                    <span class="text-slate-400">None</span>
                    @endforelse
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="td text-center text-slate-500">No Student found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $Student->links() }}</div>
@endsection