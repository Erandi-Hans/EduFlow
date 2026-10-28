<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - EduFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 font-sans text-slate-700 antialiased">
    @php
    $me = auth()->user();
    $role = $me->role->role_name;
    $nav = match ($role) {
    'Admin' => [['Dashboard', 'dashboard', 'dashboard'], ['Users', 'admin.users.index', 'admin.users.*'], ['Course', 'admin.Course.index', 'admin.Course.*']],
    'Teacher' => [['Dashboard', 'dashboard', 'dashboard'], ['My Course', 'teacher.Course.index', 'teacher.Course.*'], ['Student', 'teacher.Student.index', 'teacher.Student.*'], ['Assignments', 'teacher.assignments.index', 'teacher.assignments.*'], ],
    default => [['Dashboard', 'dashboard', 'dashboard'], ['Course', 'student.Course.index', 'student.Course.*'], ['Assignments', 'student.assignments.index', 'student.assignments.*']],
    };
    @endphp

    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-3">
            <div class="flex flex-wrap items-center gap-6">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-teal-700">EduFlow</a>
                <nav class="flex flex-wrap gap-1">
                    @foreach ($nav as [$label, $route, $pattern])
                    <a href="{{ route($route) }}"
                        class="rounded-lg px-3 py-1.5 text-sm font-medium {{ request()->routeIs($pattern) ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-100' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-500">{{ $me->displayName() }} <span class="badge ml-1">{{ $role }}</span></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn-light">Log out</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-4 py-8">
        @if (session('success'))
        <div class="mb-5 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
        <div class="mb-5 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>
</body>

</html>