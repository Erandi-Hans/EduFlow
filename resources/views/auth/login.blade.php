<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in - EduFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-12px)'
                            }
                        },
                        'fade-up': {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(16px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        'bar-grow': {
                            '0%, 100%': {
                                transform: 'scaleY(1)'
                            },
                            '50%': {
                                transform: 'scaleY(0.5)'
                            }
                        },
                        shimmer: {
                            '0%': {
                                backgroundPosition: '0% 50%'
                            },
                            '100%': {
                                backgroundPosition: '200% 50%'
                            }
                        },
                        glow: {
                            '0%, 100%': {
                                opacity: '0.6',
                                transform: 'scale(1)'
                            },
                            '50%': {
                                opacity: '1',
                                transform: 'scale(1.15)'
                            }
                        },
                        'spin-rev': {
                            to: {
                                transform: 'rotate(-360deg)'
                            }
                        },
                    },
                    animation: {
                        float: 'float 6s ease-in-out infinite',
                        'float-slow': 'float 8s ease-in-out infinite',
                        'fade-up': 'fade-up 0.7s ease-out both',
                        bar: 'bar-grow 2.4s ease-in-out infinite',
                        shimmer: 'shimmer 5s linear infinite',
                        glow: 'glow 8s ease-in-out infinite',
                        'spin-slow': 'spin 60s linear infinite',
                        'spin-rev': 'spin-rev 45s linear infinite',
                    },
                },
            },
        }
    </script>
</head>

<body class="m-0 p-0 font-sans antialiased bg-white overflow-x-hidden">
    <div class="flex min-h-screen w-screen m-0 p-0">

        <!-- Left side -->
        <div class="relative hidden min-h-screen flex-col justify-between overflow-hidden p-10 text-white lg:flex lg:w-1/2 xl:p-14"
            style="background-image:radial-gradient(900px 520px at 90% -5%,rgba(45,212,191,.38),transparent 60%),radial-gradient(700px 500px at -5% 105%,rgba(16,185,129,.28),transparent 60%),linear-gradient(145deg,#075e54 0%,#04403a 45%,#022c22 100%)">

            <!-- Subtle grid, faded at the edges -->
            <div class="pointer-events-none absolute inset-0 opacity-[0.07]"
                style="background-image:linear-gradient(#fff 1px,transparent 1px),linear-gradient(90deg,#fff 1px,transparent 1px);background-size:44px 44px;-webkit-mask-image:radial-gradient(ellipse at 50% 55%,#000 25%,transparent 75%);mask-image:radial-gradient(ellipse at 50% 55%,#000 25%,transparent 75%)"></div>

            <!-- Soft animated glows -->
            <div class="pointer-events-none absolute -right-24 top-10 h-[420px] w-[420px] rounded-full bg-teal-300/20 blur-3xl motion-safe:animate-glow"></div>
            <div class="pointer-events-none absolute -bottom-32 -left-24 h-[360px] w-[360px] rounded-full bg-emerald-400/20 blur-3xl motion-safe:animate-glow" style="animation-delay:-4s"></div>

            <!-- Logo -->
            <div class="relative z-10 flex items-center gap-3 motion-safe:animate-fade-up">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-teal-700 shadow-lg">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5zM4 19.5V21h16" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">EduFlow</span>
            </div>

            <!-- Main -->
            <div class="relative z-10 mx-auto my-auto w-full max-w-2xl py-8">

                <h1 class="max-w-xl text-4xl font-extrabold leading-tight tracking-tight motion-safe:animate-fade-up xl:text-5xl 2xl:text-6xl" style="animation-delay:150ms">
                    Every class, course and assignment in one place.
                </h1>
                <p class="mt-5 max-w-lg text-base leading-relaxed text-teal-50/80 2xl:text-lg motion-safe:animate-fade-up" style="animation-delay:300ms">
                    Admins manage people, teachers run courses, and students submit work without chasing anyone.
                </p>

                <!-- Illustration: everything inside is sized in em, so it scales by changing ONE font-size on the wrapper (no transforms) -->
                <div class="relative mx-auto mt-10 h-[21.25em] w-[26.25em] text-[16px] xl:text-[18.4px] 2xl:text-[21.6px] motion-safe:animate-fade-up" style="animation-delay:450ms">

                    <!-- Backdrop blob + book stack (static) -->
                    <svg class="absolute inset-0 h-full w-full" viewBox="0 0 420 340" fill="none">
                        <path d="M118 150C118 80 210 42 300 70c70 24 82 120 52 180-32 66-160 76-230 36-50-30 0-90-4-136z" fill="rgba(255,255,255,.09)" />
                        <!-- bottom book -->
                        <rect x="80" y="262" width="250" height="48" rx="9" fill="#ffffff" />
                        <rect x="98" y="282" width="92" height="7" rx="3.5" fill="#99f6e4" />
                        <!-- middle book -->
                        <rect x="105" y="222" width="200" height="42" rx="9" fill="#a7e3da" />
                        <!-- top book -->
                        <rect x="125" y="184" width="160" height="40" rx="9" fill="#f5a524" />
                    </svg>

                    <!-- Graduation cap (floats) -->
                    <div class="absolute left-[6.5625em] top-[4.25em] z-[5] motion-safe:animate-float">
                        <svg class="h-[7.5em] w-[12.5em]" viewBox="0 0 200 120" fill="none">
                            <path d="M45 56v28c0 14 25 24 55 24s55-10 55-24V56z" fill="#0f766e" />
                            <polygon points="100,8 192,42 100,78 8,42" fill="#022c22" />
                            <polygon points="100,8 192,42 100,50 8,42" fill="rgba(255,255,255,.08)" />
                            <path d="M100 42 168 52v38" stroke="#f5a524" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="168" cy="93" r="6" fill="#f5a524" />
                        </svg>
                    </div>

                    <!-- Sparkles -->
                    <svg class="absolute right-[1.25em] top-[4.375em] h-[2.25em] w-[2.25em] text-white motion-safe:animate-[spin_10s_linear_infinite]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0c.6 6.6 4.9 11.4 12 12-7.1.6-11.4 5.4-12 12-.6-6.6-4.9-11.4-12-12C7.1 11.4 11.4 6.6 12 0z" />
                    </svg>
                    <svg class="absolute left-[2.125em] top-[12.25em] h-[1.25em] w-[1.25em] text-amber-400 motion-safe:animate-pulse" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0c.6 6.6 4.9 11.4 12 12-7.1.6-11.4 5.4-12 12-.6-6.6-4.9-11.4-12-12C7.1 11.4 11.4 6.6 12 0z" />
                    </svg>
                    <svg class="absolute right-[4em] top-[15.625em] h-[0.875em] w-[0.875em] text-teal-200 motion-safe:animate-pulse" style="animation-delay:-1s" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 0c.6 6.6 4.9 11.4 12 12-7.1.6-11.4 5.4-12 12-.6-6.6-4.9-11.4-12-12C7.1 11.4 11.4 6.6 12 0z" />
                    </svg>

                    <!-- Card: assignment submitted -->
                    <div class="absolute left-0 top-0 z-10 motion-safe:animate-float" style="animation-delay:-1s">
                        <div class="flex items-center gap-[0.75em] rounded-[1em] bg-white p-[0.75em] pr-[1.25em] shadow-xl">
                            <div class="flex h-[2.25em] w-[2.25em] shrink-0 items-center justify-center rounded-[0.75em] bg-amber-400 text-teal-950">
                                <svg class="h-[1em] w-[1em]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[0.75em] font-bold leading-tight text-slate-800">Assignment submitted</p>
                                <p class="text-[0.6875em] font-medium leading-tight text-slate-500">Graph Theory Problem Set</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card: new assignment -->
                    <div class="absolute bottom-0 right-0 z-10 motion-safe:animate-float-slow" style="animation-delay:-3s">
                        <div class="flex items-center gap-[0.75em] rounded-[1em] bg-white p-[0.75em] pr-[1.25em] shadow-xl">
                            <div class="flex h-[2.25em] w-[2.25em] shrink-0 items-center justify-center rounded-[0.75em] bg-teal-100 text-teal-700">
                                <svg class="h-[1em] w-[1em]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2M9 5h6M9 12h6M9 16h4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[0.75em] font-bold leading-tight text-slate-800">New assignment</p>
                                <p class="text-[0.6875em] font-medium leading-tight text-slate-500">Python Basics Quiz</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="relative z-10 text-xs text-teal-200/70">&copy; 2026 EduFlow</div>
        </div>

        <!-- Right Side -->
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
                        <label for="password" class="block text-xs font-medium text-slate-700 uppercase tracking-wider mb-2">Password</label>

                        <div class="relative">
                            <input id="password" name="password" type="password" required
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 pr-12 text-base text-slate-900 placeholder-slate-400 focus:border-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-600/20">

                            <button type="button" onclick="togglePassword(this)" aria-label="Show password"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-teal-700 focus:outline-none">

                                <!-- ON: password hidden now (click to show) -->
                                <svg class="eye-on h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.04 12.32a1 1 0 0 1 0-.64C3.42 7.51 7.36 4.5 12 4.5c4.64 0 8.57 3.01 9.96 7.18.07.21.07.43 0 .64C20.58 16.49 16.64 19.5 12 19.5c-4.64 0-8.57-3.01-9.96-7.18z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                </svg>

                                <!-- OFF: password visible now (click to hide) -->
                                <svg class="eye-off hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.22A10.48 10.48 0 0 0 1.93 12c1.29 3.9 5.1 6.75 9.57 6.75 1.99 0 3.84-.57 5.4-1.55M6.23 6.23A10.45 10.45 0 0 1 12 4.5c4.47 0 8.28 2.85 9.57 6.75a10.5 10.5 0 0 1-4.29 5.41M6.23 6.23 3 3m3.23 3.23 3.65 3.65m7.89 7.89L21 21m-3.23-3.23-3.65-3.65m0 0a3 3 0 1 0-4.24-4.24m4.24 4.24L9.88 9.88" />
                                </svg>
                            </button>
                        </div>

                        @error('password')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between py-1">
                        <label class="flex items-center gap-2 text-sm text-slate-600 rgba(45,212,191,.38) cursor-pointer">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-4 w-4 rounded border-gray-300 text-[#00786F] accent-[#00786F] focus:ring-[#00786F]"> Keep me logged in
                        </label>
                    </div>

                    <button class="w-full rounded-xl bg-teal-700 px-4 py-3 text-center text-base font-semibold text-white shadow-sm hover:bg-teal-800 focus:outline-none focus:ring-2 focus:ring-teal-600 focus:ring-offset-2 transition-colors">Log in</button>
                </form>
            </div>
        </div>
        <script>
            function togglePassword(btn) {
                const input = document.getElementById('password');
                const show = input.type === 'password'; // true = we are about to reveal the password

                input.type = show ? 'text' : 'password';
                btn.querySelector('.eye-on').classList.toggle('hidden', show);
                btn.querySelector('.eye-off').classList.toggle('hidden', !show);
                btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
            }
        </script>
</body>

</html>