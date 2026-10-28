<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduFlow - Sign In</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#164EC7',
                            lightBg: '#F3F5FC',
                            cardBg: '#DEE4F4',
                            accent: '#B0C2F2',
                            bgGray: '#E7DDDE',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E7DDDE;
        }
    </style>
</head>

<body class="antialiased min-h-screen flex items-center justify-center p-4 md:p-6">

    <div class="w-full max-w-7xl bg-white rounded-[40px] shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[780px] p-4 gap-6">

        <!-- ================= LEFT HERO PANEL ================= -->
        <div class="lg:col-span-6 bg-[#164EC7] rounded-[32px] p-8 md:p-12 flex flex-col justify-between relative overflow-hidden text-white">

            <!-- Top Branding -->
            <div class="flex items-center gap-3 z-10">
                <div class="bg-white text-[#164EC7] p-2.5 rounded-2xl flex items-center justify-center shadow-lg">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">EduFlow</span>
            </div>

            <!-- Middle Text & Illustrations -->
            <div class="my-auto py-8 z-10 space-y-6">
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight leading-[1.15]">
                    Every class , course and assignment in one place .
                </h1>
                <p class="text-white/80 text-sm md:text-base font-medium max-w-md leading-relaxed">
                    Admins manage people, teachers run courses, and students submit work without chasing anyone.
                </p>

                <!-- Floating Badges and Art Container -->
                <div class="relative pt-10 pb-6 flex items-center justify-center">

                    <!-- Background Glow -->
                    <div class="absolute w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>

                    <!-- Graduation Cap & Stacked Books Illustration -->
                    <div class="relative z-10 flex flex-col items-center scale-95 md:scale-105">
                        <div class="text-amber-400 mb-2">
                            <i data-lucide="sparkles" class="w-6 h-6 absolute -left-12 top-4 animate-pulse"></i>
                        </div>
                        <div class="text-white mb-2">
                            <i data-lucide="sparkles" class="w-8 h-8 absolute -right-16 top-12 animate-pulse"></i>
                        </div>

                        <!-- Cap -->
                        <div class="relative z-20 -mb-2">
                            <div class="w-28 h-10 bg-slate-900 rounded-t-full flex items-center justify-center shadow-lg">
                                <div class="w-4 h-4 bg-amber-400 rounded-full absolute right-6 top-4"></div>
                            </div>
                        </div>
                        <!-- Book Stack -->
                        <div class="w-44 h-10 bg-amber-400 rounded-xl mb-1.5 shadow-md flex items-center justify-center font-bold text-slate-900 text-xs"></div>
                        <div class="w-52 h-10 bg-[#B0C2F2] rounded-xl mb-1.5 shadow-md"></div>
                        <div class="w-60 h-11 bg-white rounded-xl shadow-lg flex items-center px-4">
                            <div class="w-10 h-3 bg-slate-200 rounded-full"></div>
                        </div>
                    </div>

                    <!-- Floating Badge: Assignment Submitted -->
                    <div class="absolute -left-2 top-2 bg-white text-slate-900 px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 z-30 animate-bounce duration-1000">
                        <div class="w-8 h-8 rounded-xl bg-amber-400 text-slate-900 flex items-center justify-center">
                            <i data-lucide="check" class="w-4 h-4 stroke-[3]"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 leading-tight">Assignment submitted</h4>
                            <span class="text-[10px] font-medium text-slate-500">Graph Theory Problem Set</span>
                        </div>
                    </div>

                    <!-- Floating Badge: New Assignment -->
                    <div class="absolute -right-2 bottom-0 bg-white text-slate-900 px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 z-30">
                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#164EC7] flex items-center justify-center">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 leading-tight">New assignment</h4>
                            <span class="text-[10px] font-medium text-slate-500">Python Basics Quiz</span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Footer Note -->
            <div class="z-10 text-xs text-white/60 font-medium">
                EduFlow School System &copy; 2026
            </div>
        </div>

        <!-- ================= RIGHT LOGIN FORM PANEL ================= -->
        <div class="lg:col-span-6 bg-white rounded-[32px] p-8 md:p-16 flex flex-col justify-center">

            <div class="max-w-md w-full mx-auto space-y-8">

                <!-- Header Title -->
                <div class="space-y-2">
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Welcome back</h2>
                    <p class="text-sm font-medium text-slate-500">
                        Sign in with the account your admin created for you.
                    </p>
                </div>

                <!-- Error Alert if any -->
                @if($errors->any())
                <div class="bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold p-4 rounded-2xl">
                    {{ $errors->first() }}
                </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Username Field -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 tracking-wide">Username</label>
                        <input
                            type="text"
                            name="username"
                            placeholder="e.g. nimali.f"
                            value="{{ old('username') }}"
                            required
                            class="w-full bg-[#F3F5FC] border border-transparent focus:border-[#164EC7] rounded-2xl px-4 py-3.5 text-sm font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm" />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700 tracking-wide">Password</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                            class="w-full bg-[#F3F5FC] border border-transparent focus:border-[#164EC7] rounded-2xl px-4 py-3.5 text-sm font-medium text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white transition-all shadow-sm" />
                    </div>

                    <!-- Sign In Button -->
                    <button
                        type="submit"
                        class="w-full bg-[#164EC7] hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-500/25 transition-all text-sm tracking-wide">
                        Sign in
                    </button>
                </form>

                <!-- Role Quick Preview Box -->
                <div class="bg-[#F3F5FC] p-5 rounded-2xl space-y-3">
                    <span class="text-xs font-bold text-slate-500 block">Just exploring? Preview a role</span>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="fillCredentials('admin', 'password123')" class="bg-white hover:bg-[#DEE4F4] text-slate-800 font-bold text-xs py-2.5 rounded-xl shadow-sm transition-all border border-slate-200/60">
                            Admin
                        </button>
                        <button type="button" onclick="fillCredentials('john_teacher', 'password123')" class="bg-white hover:bg-[#DEE4F4] text-slate-800 font-bold text-xs py-2.5 rounded-xl shadow-sm transition-all border border-slate-200/60">
                            Teacher
                        </button>
                        <button type="button" onclick="fillCredentials('kamal_student', 'password123')" class="bg-white hover:bg-[#DEE4F4] text-slate-800 font-bold text-xs py-2.5 rounded-xl shadow-sm transition-all border border-slate-200/60">
                            Student
                        </button>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Initialize Lucide Icons & Quick Fill Script -->
    <script>
        lucide.createIcons();

        function fillCredentials(username, password) {
            document.querySelector('input[name="username"]').value = username;
            document.querySelector('input[name="password"]').value = password;
        }
    </script>
</body>

</html>