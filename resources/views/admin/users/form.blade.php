{{-- Shared form fields (left card) for create and edit.
     Needs: $type ('teacher'|'student'), $user (null on create), $profile (null on create).
     The parent view provides the <form> tag, @csrf and (for edit) @method('PUT'). --}}
@php
$isEdit = (bool) $user;
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

$sections = [
[1, 'Personal information', $personal],
[2, 'Account credentials', $account],
[3, ucfirst($type) . ' details', $details],
];
@endphp

<div class="rounded-2xl border border-slate-200 bg-white p-6">

    @foreach ($sections as [$num, $title, $fields])

    <div class="{{ $num > 1 ? 'mt-8' : '' }} mb-4 flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-[#00786F]">
        <span class="flex h-6 w-6 items-center justify-center rounded-md bg-[#E6F4F2] text-[11px]">{{ $num }}</span>
        {{ $title }}
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        @foreach ($fields as [$name, $text, $kind, $hint, $value])
        <div>
            <label for="{{ $name }}" class="{{ $label }}">{{ $text }}</label>
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
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" />
                        <circle cx="12" cy="12" r="3" />
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
    // Show / hide the password
    function togglePassword(btn) {
        const input = btn.parentElement.querySelector('input');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>