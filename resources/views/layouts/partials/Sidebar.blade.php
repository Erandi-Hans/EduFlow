{{-- Sidebar. Uses $nav, $me and $role from layouts/app.blade.php --}}
@php
$icons = [
'grid' => '
<rect x="3" y="3" width="7" height="7" rx="1" />
<rect x="14" y="3" width="7" height="7" rx="1" />
<rect x="3" y="14" width="7" height="7" rx="1" />
<rect x="14" y="14" width="7" height="7" rx="1" />',
'users' => '
<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
<circle cx="9" cy="7" r="4" />
<path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8" />',
'book' => '
<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5zM4 19.5V21h16" />',
'file' => '
<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
<path d="M14 2v6h6M9 13h6M9 17h6" />',
'out' => '
<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />',
];
$name = $me->displayName();
$initials = \Illuminate\Support\Str::upper(collect(explode(' ', trim($name)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''));
@endphp

<aside class="hidden w-64 shrink-0 flex-col gap-1 border-r border-slate-200 bg-white p-4 lg:sticky lg:top-0 lg:flex lg:h-screen">

    {{-- Logo --}}
    <a href="{{ route('dashboard') }}" class="mb-5 flex items-center gap-3 px-2 pt-1">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl text-white" style="background-image:linear-gradient(135deg,#00786F,#2fb5a9)">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $icons['book'] !!}</svg>
        </span>
        <span class="text-xl font-extrabold tracking-tight text-[#00786F]">EduFlow</span>
    </a>

    <p class="px-3 pb-1 pt-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">{{ $role }} menu</p>

    {{-- Menu (changes with the role) --}}
    @foreach ($nav as [$label, $route, $pattern, $icon])
    @php $active = request()->routeIs($pattern); @endphp
    <a href="{{ route($route) }}"
        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ $active ? 'bg-[#00786F] text-white shadow-sm' : 'text-slate-500 hover:bg-[#E6F4F2] hover:text-[#00786F]' }}">
        <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $icons[$icon] !!}</svg>
        {{ $label }}
    </a>
    @endforeach

    {{-- Log out (opens the confirm window) --}}
    <button type="button" onclick="document.getElementById('logout-dialog').showModal()"
        class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-left text-sm font-semibold text-slate-500 transition hover:bg-red-50 hover:text-red-600">
        <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">{!! $icons['out'] !!}</svg>
        Log out
    </button>

    {{-- Current user --}}
    <div class="mt-auto flex items-center gap-3 rounded-2xl border border-slate-200 p-3">
        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#E6F4F2] text-xs font-extrabold text-[#00786F]">{{ $initials }}</span>
        <div class="min-w-0">
            <p class="truncate text-sm font-bold text-slate-900">{{ $name }}</p>
            <p class="text-xs text-slate-500">{{ $role }}</p>
        </div>
    </div>
</aside>