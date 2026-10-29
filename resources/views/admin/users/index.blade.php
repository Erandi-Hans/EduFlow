@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 space-y-8">
    <!-- Success Alert Message -->
    @if(session('success'))
    <div class="flex items-center justify-between bg-teal-50 border border-teal-200 text-teal-800 px-6 py-4 rounded-2xl shadow-sm transition-all">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-sm font-bold">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Top Header & Action Buttons -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-teal-600 mb-1">Admin Dashboard / User Management</div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">System Users</h1>
            <p class="text-sm text-slate-500 mt-1">Manage all registered teachers and students with role controls.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.create', ['type' => 'teacher']) }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-extrabold uppercase tracking-wider text-white bg-teal-600 hover:bg-teal-700 shadow-lg shadow-teal-600/30 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Teacher
            </a>
            <a href="{{ route('admin.users.create', ['type' => 'student']) }}"
                class="inline-flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-extrabold uppercase tracking-wider text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200/80 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Student
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center gap-2 bg-slate-200/60 p-1.5 rounded-2xl w-fit border border-slate-300/40">
        <a href="{{ route('admin.users.index', ['type' => 'teachers']) }}"
            class="px-6 py-2 text-xs font-bold rounded-xl transition-all duration-200 {{ $type === 'teachers' ? 'bg-white text-teal-700 shadow-md shadow-slate-200' : 'text-slate-600 hover:text-slate-900' }}">
            Teachers Directory
        </a>
        <a href="{{ route('admin.users.index', ['type' => 'students']) }}"
            class="px-6 py-2 text-xs font-bold rounded-xl transition-all duration-200 {{ $type === 'students' ? 'bg-white text-teal-700 shadow-md shadow-slate-200' : 'text-slate-600 hover:text-slate-900' }}">
            Students Directory
        </a>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-slate-400 uppercase text-[10px] tracking-widest font-black">
                        <th class="py-4 px-6">Full Name</th>
                        <th class="py-4 px-6">Username</th>
                        <th class="py-4 px-6">Email Address</th>
                        <th class="py-4 px-6">Role / Details</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium">
                    @forelse ($members as $u)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-6 text-slate-800 font-bold">
                            {{ $u->displayName() }}
                        </td>
                        <td class="py-4 px-6 text-slate-600 font-mono text-xs">
                            {{ $u->user?->username ?? 'N/A' }}
                        </td>
                        <td class="py-4 px-6 text-slate-600">
                            {{ $u->user?->email ?? 'N/A' }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-200/60">
                                {{ $u->user?->role?->role_name ?? ucfirst(str_replace('s', '', $type)) }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-right space-x-3">
                            <a href="{{ route('admin.users.edit', $u->user?->user_id ?? 1) }}" class="text-xs font-bold text-teal-600 hover:text-teal-800 transition-colors">
                                Edit
                            </a>
                            <form action="{{ route('admin.users.destroy', $u->user?->user_id ?? 1) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 transition-colors">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 font-medium">
                            No records found for {{ $type }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        @if(method_exists($members, 'links'))
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $members->links() }}
        </div>
        @endif
    </div>
</div>
@endsection