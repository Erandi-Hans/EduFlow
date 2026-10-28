<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in - EduFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="m-0 p-0 font-sans antialiased bg-white overflow-x-hidden">
    <div class="flex min-h-screen w-screen m-0 p-0">


        <!-- resources/views/auth/partials/brand-panel.blade.php -->
        <!-- Left side: branding, floating cards and animated graphics -->
        <div class="relative hidden min-h-screen flex-col justify-between overflow-hidden bg-gradient-to-br from-teal-800 via-teal-900 to-emerald-950 p-12 text-white lg:flex lg:w-1/2">

            <!-- Pulsing glow (top right) -->
            <div class="pointer-events-none absolute -right-20 -top-20 h-[500px] w-[500px] rounded-full bg-emerald-400/20 blur-3xl motion-safe:animate-glow"></div>

            <!-- Glow (bottom left) -->
            <div class="pointer-events-none absolute -bottom-32 -left-24 h-[380px] w-[380px] rounded-full bg-teal-500/20 blur-3xl motion-safe:animate-glow" style="animation-delay:-4s"></div>

            <!-- Rotating dashed rings (bottom right), each ring spins in its own direction -->
            <div class="pointer-events-none absolute -bottom-20 -right-28 h-[600px] w-[600px] opacity-40">
                <div class="absolute inset-0 rounded-full border border-dashed border-teal-200/50 motion-safe:animate-spin-slow"></div>
                <div class="absolute inset-[80px] rounded-full border border-dashed border-teal-200/40 motion-safe:animate-spin-rev"></div>
                <div class="absolute inset-[160px] rounded-full border border-dashed border-teal-200/30 motion-safe:animate-spin-slow"></div>
            </div>

            <!-- Header logo -->
            <div class="relative z-10 flex items-center gap-3 motion-safe:animate-fade-up">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-teal-400/30 bg-teal-600/60 shadow-inner backdrop-blur-md">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">EduFlow</span>
            </div>

            <!-- Main content -->
            <div class="relative z-10 my-auto max-w-lg space-y-6">

                <!-- Badge -->
                <div class="motion-safe:animate-fade-up" style="animation-delay:150ms">
                    <span class="inline-block rounded-full border border-teal-500/30 bg-teal-900/80 px-3.5 py-1 text-[11px] font-bold uppercase tracking-wider text-teal-200 shadow-sm">
                        School Management System
                    </span>
                </div>

                <!-- Title and subtitle -->
                <div class="space-y-3">
                    <h1 class="text-5xl font-extrabold tracking-tight text-white motion-safe:animate-fade-up sm:text-6xl" style="animation-delay:300ms">
                        Edu<span class="bg-gradient-to-r from-white via-teal-200 to-white bg-[length:200%_auto] bg-clip-text text-transparent motion-safe:animate-shimmer">Flow</span>
                    </h1>
                    <p class="max-w-md text-sm leading-relaxed text-teal-100/90 motion-safe:animate-fade-up sm:text-base" style="animation-delay:450ms">
                        Learn. Teach. Grow. Everything your school needs, flowing in one place.
                    </p>
                </div>

                <!-- Floating cards: the outer div floats, the inner div handles hover (so the two transforms don't clash) -->
                <div class="space-y-3 pt-4">

                    <!-- Card 1 -->
                    <div class="motion-safe:animate-float" style="animation-delay:0s">
                        <div class="flex max-w-xs items-center gap-3.5 rounded-2xl bg-white/95 p-3.5 shadow-xl backdrop-blur-md transition-transform duration-300 hover:translate-x-1">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">Assignment graded</p>
                                <p class="text-[11px] font-medium text-slate-500">Python Basics · 92/100</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="ml-12 motion-safe:animate-float-slow" style="animation-delay:-2s">
                        <div class="flex max-w-xs items-center gap-3.5 rounded-2xl bg-white/95 p-3.5 shadow-xl backdrop-blur-md transition-transform duration-300 hover:translate-x-1">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-teal-100 text-teal-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-800">New course added</p>
                                <p class="text-[11px] font-medium text-slate-500">Database Systems</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3: animated bar chart -->
                    <div class="ml-4 motion-safe:animate-float" style="animation-delay:-4s">
                        <div class="max-w-[210px] rounded-2xl bg-white/95 p-3.5 shadow-xl backdrop-blur-md transition-transform duration-300 hover:translate-x-1">
                            <p class="mb-2 text-xs font-bold text-slate-800">On-time submissions</p>
                            <div class="flex h-8 items-end gap-1.5 pt-1">
                                <div class="h-3 w-2.5 origin-bottom rounded-t bg-teal-600 motion-safe:animate-bar" style="animation-delay:0s"></div>
                                <div class="h-4 w-2.5 origin-bottom rounded-t bg-teal-600 motion-safe:animate-bar" style="animation-delay:.2s"></div>
                                <div class="h-3 w-2.5 origin-bottom rounded-t bg-teal-600 motion-safe:animate-bar" style="animation-delay:.4s"></div>
                                <div class="h-6 w-2.5 origin-bottom rounded-t bg-teal-600 motion-safe:animate-bar" style="animation-delay:.6s"></div>
                                <div class="h-5 w-2.5 origin-bottom rounded-t bg-teal-600 motion-safe:animate-bar" style="animation-delay:.8s"></div>
                                <div class="h-8 w-2.5 origin-bottom rounded-t bg-teal-600 motion-safe:animate-bar" style="animation-delay:1s"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-xs text-teal-300/80">&copy; 2026 EduFlow</div>
        </div>

        <!-- (Right Side - Login Form) -->

        <div class="flex w-full lg:w-1/2 flex-col justify-center px-6 py-12 lg:px-20 xl:px-24 bg-white min-h-screen">
            <div class="mx-auto w-full max-w-md">
                <div class="mb-8">
                    <h2 class="text-4xl font-bold tracking-tight text-slate-900">Welcome Back</h2>
                    <p class="mt-2 text-sm text-slate-500">Sign in to continue to your workspace.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6 rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-900/5">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-2">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-600/20">
                        @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for=" password" class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-2">Password</label>
                        <input id="password" name="password" type="password" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-base text-slate-900 placeholder-slate-400 focus:border-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-600/20">
                        @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-600 focus:ring-teal-600 h-4 w-4"> Keep me logged in
                        </label>
                    </div>

                    <button class="w-full rounded-xl bg-teal-700 px-4 py-3 text-center text-base font-semibold text-white shadow-sm hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 transition-colors">Log in</button>
                </form>
            </div>
        </div>
</body>

</html>