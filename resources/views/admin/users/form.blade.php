@php
$profile = $user ? ($user->teacher ?? $user->student) : null;
$val = fn ($field, $default = '') => old($field, $profile->$field ?? $default);
@endphp

<form method="POST" action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}" class="card max-w-2xl space-y-4">
    @csrf
    @if ($user) @method('PUT') @else <input type="hidden" name="role" value="{{ $role }}"> @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="label" for="first_name">First name</label>
            <input id="first_name" name="first_name" value="{{ $val('first_name') }}" required class="input">
            @error('first_name') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label" for="last_name">Last name</label>
            <input id="last_name" name="last_name" value="{{ $val('last_name') }}" required class="input">
            @error('last_name') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label" for="username">Username</label>
            <input id="username" name="username" value="{{ old('username', $user->username ?? '') }}" required class="input">
            @error('username') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label" for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required class="input">
            @error('email') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div class="sm:col-span-2">
            <label class="label" for="password">Password @if ($user)<span class="font-normal text-slate-400">(leave empty to keep the current one)</span>@endif</label>
            <input id="password" name="password" type="password" @if (!$user) required @endif class="input">
            @error('password') <p class="err">{{ $message }}</p> @enderror
        </div>

        @if ($role === 'Teacher')
        <div>
            <label class="label" for="phone_number">Phone number</label>
            <input id="phone_number" name="phone_number" value="{{ $val('phone_number') }}" class="input">
            @error('phone_number') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label" for="qualification">Qualification</label>
            <input id="qualification" name="qualification" value="{{ $val('qualification') }}" class="input">
            @error('qualification') <p class="err">{{ $message }}</p> @enderror
        </div>
        @else
        <div>
            <label class="label" for="date_of_birth">Date of birth</label>
            <input id="date_of_birth" name="date_of_birth" type="date"
                value="{{ old('date_of_birth', optional($profile?->date_of_birth)->format('Y-m-d')) }}" class="input">
            @error('date_of_birth') <p class="err">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label" for="guardian_name">Guardian name</label>
            <input id="guardian_name" name="guardian_name" value="{{ $val('guardian_name') }}" class="input">
            @error('guardian_name') <p class="err">{{ $message }}</p> @enderror
        </div>
        @endif
    </div>

    <div class="flex gap-3 pt-2">
        <button class="btn">{{ $user ? 'Save changes' : 'Create ' . strtolower($role) }}</button>
        <a href="{{ route('admin.users.index') }}" class="btn-light">Cancel</a>
    </div>
</form>