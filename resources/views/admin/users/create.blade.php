@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 mb-20">
    <!-- Top Header & Modern Toggle Switch -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-teal-600 mb-1">Admin Dashboard / Users</div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Create New Account</h1>
            <p class="text-sm text-slate-500 mt-1">Add a new {{ $type }} securely to EduFlow system.</p>
        </div>

        <!-- Toggle Button Container -->
        <div class="inline-flex p-1.5 bg-slate-200/80 rounded-2xl border border-slate-300 shadow-inner">
            <a href="{{ route('admin.users.create', ['type' => 'teacher']) }}"
                class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ $type === 'teacher' ? 'bg-teal-600 text-white shadow-md shadow-teal-600/30' : 'text-slate-600 hover:text-slate-900' }}">
                Teacher
            </a>
            <a href="{{ route('admin.users.create', ['type' => 'student']) }}"
                class="px-5 py-2.5 text-xs font-bold rounded-xl transition-all duration-200 {{ $type === 'student' ? 'bg-teal-600 text-white shadow-md shadow-teal-600/30' : 'text-slate-600 hover:text-slate-900' }}">
                Student
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xl shadow-slate-200/50 p-8 sm:p-10">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <input type="hidden" name="type" value="{{ $type }}">

            <!-- Section 1: Personal Info -->
            <div>
                <h3 class="text-xs font-bold uppercase tracking-wider text-teal-600 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">1</span>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="e.g. Erandi"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('first_name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="e.g. Hansika"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('last_name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Account Credentials -->
            <div class="pt-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-teal-600 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">2</span>
                    Account Credentials
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="user@eduflow.lk"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('email') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Username *</label>
                        <input type="text" name="username" value="{{ old('username') }}" required placeholder="username"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('username') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2">Password *</label>
                        <input type="password" name="password" required placeholder="••••••••"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('password') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Dynamic Profile Specific Fields -->
            <div class="pt-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-teal-600 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600">3</span>
                    {{ ucfirst($type) }} Specific Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @if($type === 'teacher')
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="+94 7X XXX XXXX"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('phone_number') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Qualification</label>
                        <input type="text" name="qualification" value="{{ old('qualification') }}" placeholder="e.g. BSc in ICT"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('qualification') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    @else
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('date_of_birth') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Guardian Name</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" placeholder="Guardian's Name"
                            class="w-full rounded-xl border border-slate-300 bg-slate-50/50 px-4 py-3 text-sm text-slate-800 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-600/10 transition-all outline-none">
                        @error('guardian_name') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    @endif
                </div>
            </div>

            <!-- Form Action Footer with SAVE Button -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between gap-4">
                <a href="{{ route('admin.users.index', ['type' => $type === 'student' ? 'students' : 'teachers']) }}"
                    class="px-6 py-3.5 rounded-xl text-xs font-extrabold uppercase tracking-wider text-slate-500 bg-slate-100 hover:bg-slate-200 transition-all">
                    Cancel
                </a>

                <button type="submit"
                    class="px-10 py-3.5 rounded-xl text-xs font-extrabold uppercase tracking-wider text-white bg-teal-600 hover:bg-teal-700 shadow-lg shadow-teal-600/30 transition-all duration-200">
                    SAVE
                </button>
            </div>
        </form>
    </div>
</div>
@endsection