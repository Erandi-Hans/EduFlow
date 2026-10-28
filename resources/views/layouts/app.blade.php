<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - EduFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white font-sans text-slate-700 antialiased">
    @php
    $me = auth()->user();
    $role = $me->role->role_name;

    // [label, route name, active pattern, icon key]
    $nav = match ($role) {
    'Admin' => [
    ['Dashboard', 'dashboard', 'dashboard', 'grid'],
    ['Users', 'admin.users.index', 'admin.users.*', 'users'],
    ['Courses', 'admin.Course.index', 'admin.Course.*', 'book'],
    ['Assignments', 'admin.assignments.index', 'admin.assignments.*', 'file'],
    ],
    'Teacher' => [
    ['Dashboard', 'dashboard', 'dashboard', 'grid'],
    ['My Courses', 'teacher.Course.index', 'teacher.Course.*', 'book'],
    ['Students', 'teacher.Student.index', 'teacher.Student.*', 'users'],
    ['Assignments', 'teacher.assignments.index', 'teacher.assignments.*', 'file'],
    ],
    default => [
    ['Dashboard', 'dashboard', 'dashboard', 'grid'],
    ['Courses', 'student.Course.index', 'student.Course.*', 'book'],
    ['Assignments', 'student.assignments.index', 'student.assignments.*', 'file'],
    ],
    };

    // Hide menu items whose route does not exist yet (avoids RouteNotFound errors)
    $nav = array_values(array_filter($nav, fn ($item) => \Illuminate\Support\Facades\Route::has($item[1])));
    @endphp

    <div class="min-h-screen lg:flex">

        {{-- Sidebar (desktop) --}}
        @include('layouts.partials.sidebar')

        <div class="min-w-0 flex-1">

            {{-- Mobile header: logo + horizontal menu --}}
            <div class="border-b border-slate-200 lg:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <a href="{{ route('dashboard') }}" class="text-lg font-extrabold text-[#00786F]">EduFlow</a>
                    <button type="button" onclick="document.getElementById('logout-dialog').showModal()"
                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-600">Log out</button>
                </div>
                <nav class="flex gap-1 overflow-x-auto px-3 pb-3">
                    @foreach ($nav as [$label, $route, $pattern])
                    <a href="{{ route($route) }}"
                        class="whitespace-nowrap rounded-lg px-3 py-1.5 text-sm font-semibold {{ request()->routeIs($pattern) ? 'bg-[#00786F] text-white' : 'text-slate-600 hover:bg-[#E6F4F2]' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </nav>
            </div>

            {{-- Top bar: search + notifications (search is visual only for now) --}}
            <div class="flex items-center justify-between gap-4 px-6 pt-6 lg:px-8">
                <label class="flex w-full max-w-sm items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-slate-400 focus-within:border-[#00786F] focus-within:ring-2 focus-within:ring-[#00786F]/20">
                    <svg class="h-[18px] w-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8" />
                        <path stroke-linecap="round" d="m21 21-4.3-4.3" />
                    </svg>
                    <input type="search" placeholder="Search..." class="w-full border-0 bg-transparent py-2.5 text-sm text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-0">
                </label>
                <button type="button" aria-label="Notifications"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:bg-[#E6F4F2] hover:text-[#00786F]">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9M13.7 21a2 2 0 0 1-3.4 0" />
                    </svg>
                </button>
            </div>

            <main class="mx-auto max-w-7xl px-6 py-6 lg:px-8">
                @if (session('success'))
                <div class="mb-5 rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Logout confirm window --}}
    <dialog id="logout-dialog" class="m-auto w-full max-w-md rounded-3xl p-0 shadow-2xl backdrop:bg-slate-900/50 backdrop:backdrop-blur-sm">
        <div class="p-7">
            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-slate-900">Log out of EduFlow?</h3>
            <p class="mt-1 text-sm text-slate-500">You will need to sign in again to access your {{ strtolower($role) }} workspace.</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="this.closest('dialog').close()"
                    class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl bg-[#c0392b] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#a93226]">Yes, log out</button>
                </form>
            </div>
        </div>
    </dialog>
    <script>
        // Clicking the dark area outside the window closes it
        const logoutDialog = document.getElementById('logout-dialog');
        logoutDialog.addEventListener('click', (e) => {
            if (e.target === logoutDialog) logoutDialog.close();
        });
    </script>
</body>

</html>