{{-- Shared form fields (left card) for create and edit.
     Needs: $type ('teacher'|'student'), $user (null on create), $profile (null on create).
     The parent view provides the <form> tag, @csrf and (for edit) @method('PUT'). --}}


@php
$isEdit = (bool) $user; # if user exist true otherwise false
$input = 'w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-[#00786F] focus:outline-none focus:ring-2 focus:ring-[#00786F]/20';
$label = 'mb-2 block text-xs font-semibold text-slate-700';
$dob = $profile && $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '';

// [name, label, input type, placeholder, current value]
$personal = [
['first_name', 'First name *', 'text', 'e.g. Erandi', $profile->first_name ?? ''],
['last_name', 'Last name *', 'text', 'e.g. Hansika', $profile->last_name ?? ''],
];
$account = [
['email', 'Email address *', 'email', 'user@eduflow.lk', $user->email ?? ''],
['username', 'Username *', 'text', 'e.g. ' . $type . '1', $user->username ?? ''],
];
$details = $type === 'teacher'
? [['phone_number', 'Phone number', 'tel', '+94 7X XXX XXXX', $profile->phone_number ?? ''],
['qualification', 'Qualification', 'text', 'e.g. BSc in ICT', $profile->qualification ?? '']]
: [['date_of_birth', 'Date of birth', 'date', '', $dob],
['guardian_name', 'Guardian name', 'text', 'e.g. Mr. Silva', $profile->guardian_name ?? '']];

$photoInitials = $profile
? \Illuminate\Support\Str::upper(collect(explode(' ', trim($profile->full_name)))->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode(''))
: '+';

$sections = [
[1, 'Personal information', $personal],
[2, 'Account credentials', $account],
[3, ucfirst($type) . ' details', $details],
];
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-6">

    {{-- Profile photo --}}
    <div class="mb-8 flex items-center gap-5">
        <div>
            <!--profile photo sections-->
            <img id="photo-preview" src="{{ $user?->profile_photo }}" alt="" class="h-20 w-20 rounded-full object-cover {{ $user?->profile_photo ? '' : 'hidden' }}">
            <span id="photo-initials" class="flex h-20 w-20 items-center justify-center rounded-full bg-[#E6F4F2] text-xl font-extrabold text-[#00786F] {{ $user?->profile_photo ? 'hidden' : '' }}">{{ $photoInitials ?? 'U' }}</span>
        </div>
        <div>
            <!--if the form is edit mode custom button as 'Change Photo' and if it is Create Mode custom button as 'Upload photo'-->
            <label for="photo" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-[#E6F4F2] hover:text-[#00786F]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                </svg>
                {{ $isEdit ?? false ? 'Change photo' : 'Upload photo' }}
            </label>
            <input id="photo" name="photo" type="file" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewPhoto(this)">
            <p class="mt-2 text-xs text-slate-500">JPG, PNG or WEBP, up to 2 MB.</p>

            <!-- if we want to remove the profile photo in Edit Mode , show the checkbox -->
            @if (($isEdit ?? false) && $user?->profile_photo)
            <label class="mt-2 flex items-center gap-2 text-xs text-slate-600">
                <input type="checkbox" name="remove_photo" value="1" class="h-4 w-4 rounded border-slate-300 accent-[#00786F]"> Remove current photo
            </label>
            @endif
            @error('photo')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
    </div>

    <!--Call above sections array one by one create section numbering and heading  -->
    @foreach ($sections as [$num, $title, $fields])

    <div class="{{ $num > 1 ? 'mt-8' : '' }} mb-4 flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-[#00786F]">
        <span class="flex h-6 w-6 items-center justify-center rounded-md bg-[#E6F4F2] text-[11px]">{{ $num }}</span>
        {{ $title }}
    </div>

    <!-- Loop relavant 2 input field in sectio  -->
    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($fields as [$name, $text, $kind, $hint, $value])
        <div>
            <label for="{{ $name }}" class="{{ $label }}">{{ $text }}</label> <!--  when the reload again because of error not all the details errors  -->
            <input id="{{ $name }}" name="{{ $name }}" type="{{ $kind }}" value="{{ old($name, $value) }}" placeholder="{{ $hint }}" class="{{ $input }}">
            @error($name)<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        @endforeach


        @if ($num === 2)
        <div class="sm:col-span-2">
            <label for="password" class="{{ $label }}">Password {{ $isEdit ? '(leave blank to keep current)' : '*' }}</label>
            <div class="relative">
                <input id="password" name="password" type="password" autocomplete="new-password"
                    placeholder="{{ $isEdit ? 'Leave blank to keep the current password' : 'Minimum 8 characters' }}" class="{{ $input }} pr-12">
                <button type="button" onclick="togglePassword(this)" aria-label="Show or hide password"
                    class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-[#00786F]">
                    {{-- ON: password hidden now (click to show) --}}
                    <svg class="eye-on h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                    {{-- OFF: password visible now (click to hide) --}}
                    <svg class="eye-off hidden h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M3.98 8.22A10.48 10.48 0 0 0 1.93 12c1.29 3.9 5.1 6.75 9.57 6.75 1.99 0 3.84-.57 5.4-1.55M6.23 6.23A10.45 10.45 0 0 1 12 4.5c4.47 0 8.28 2.85 9.57 6.75a10.5 10.5 0 0 1-4.29 5.41M6.23 6.23 3 3m3.23 3.23 3.65 3.65m7.89 7.89L21 21m-3.23-3.23-3.65-3.65m0 0a3 3 0 1 0-4.24-4.24m4.24 4.24L9.88 9.88" />
                    </svg>
                </button>
            </div>
            @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
        </div>
        @endif
    </div>
    @endforeach



    {{-- Buttons --}}
    <div class="mt-8 flex items-center justify-between border-t border-slate-100 pt-5">
        <a href="{{ route('admin.users.index', ['type' => $type . 's']) }}"
            class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Cancel</a>
        <button type="submit" class="rounded-xl bg-[#00786F] px-6 py-2.5 text-sm font-bold text-white hover:bg-[#00948a]">
            {{ $isEdit ? 'Save changes' : 'Create account' }}
        </button>
    </div>
</div>

<script>
    // Password eye: ON (hidden) <-> OFF (visible)
    function togglePassword(btn) {
        const input = btn.parentElement.querySelector('input');
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.querySelector('.eye-on').classList.toggle('hidden', show);
        btn.querySelector('.eye-off').classList.toggle('hidden', !show);
    }

    // Show the chosen photo before saving
    function previewPhoto(input) {
        const file = input.files[0];
        if (!file) return;
        const img = document.getElementById('photo-preview');
        img.src = URL.createObjectURL(file);
        img.classList.remove('hidden');
        document.getElementById('photo-initials').classList.add('hidden');
    }
</script>