<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'EduFlow - Dashboard')</title>

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
            background-color: #F3F5FC;
        }
    </style>
    @stack('styles')
</head>

<body class="text-slate-900 antialiased selection:bg-blue-500 selection:text-white min-h-screen">

    <div class="flex min-h-screen p-4 gap-6">

        <!-- ================= SIDEBAR ================= -->
        <aside class="w-64 bg-white rounded-[28px] p-6 flex flex-col justify-between shadow-sm shrink-0">
            <div>
                <!-- Brand Logo -->
                <div class="flex items-center gap-3 mb-10 px-2">
                    <div class="bg-[#164EC7] text-white p-2.5 rounded-2xl flex items-center justify-center shadow-md shadow-blue-500/20">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900">EduFlow</span>
                </div>

                <!-- Navigation Links -->
                <nav class="space-y-2">
                    <a href="#" class="flex items-center gap-3.5 px-4 py-3.5 text-sm font-semibold text-[#164EC7] bg-[#DEE4F4]/60 rounded-2xl transition-all">
                        <i data-lucide="layout-grid" class="w-5 h-5"></i>
                        Dashboard
                    </a>

                    <a href="#" class="flex items-center gap-3.5 px-4 py-3.5 text-sm font-semibold text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-2xl transition-all">
                        <i data-lucide="users" class="w-5 h-5"></i>
                        Members
                    </a>

                    <a href="#" class="flex items-center gap-3.5 px-4 py-3.5 text-sm font-semibold text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-2xl transition-all">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                        Courses
                    </a>

                    <a href="#" class="flex items-center gap-3.5 px-4 py-3.5 text-sm font-semibold text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-2xl transition-all">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        Assignments
                    </a>
                </nav>
            </div>

            <!-- Current Logged User Info Bar -->
            <div class="bg-slate-100 rounded-2xl p-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#164EC7] text-white flex items-center justify-center font-bold text-sm">
                        AD
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-slate-900 leading-tight">System Assistant</h4>
                        <span class="text-[11px] font-medium text-slate-500">Admin</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') ?? '#' }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ================= MAIN CONTENT CONTAINER ================= -->
        <main class="flex-1 flex flex-col space-y-6">

            <!-- Top Header Search Bar & Actions -->
            <header class="flex items-center justify-between gap-4">
                <div class="relative flex-1 max-w-md">
                    <i data-lucide="search" class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        placeholder="Search members, courses, assignments"
                        class="w-full bg-white rounded-full py-3.5 pl-11 pr-5 text-sm font-medium placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#164EC7]/30 shadow-sm border border-transparent transition-all" />
                </div>

                <div class="flex items-center gap-3">
                    <button class="w-11 h-11 bg-white rounded-full flex items-center justify-center text-slate-700 shadow-sm hover:bg-slate-50 transition-all relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span class="absolute top-3 right-3 w-2 h-2 bg-amber-500 rounded-full"></span>
                    </button>
                </div>
            </header>

            <!-- Dynamic Body View Content Injection -->
            <div class="flex-1">
                @yield('content')
            </div>

        </main>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>

</html>