<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in - EduFlow</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 font-sans text-slate-700 antialiased">
    <div class="w-full max-w-sm">
        <h1 class="mb-1 text-center text-2xl font-semibold text-teal-700">EduFlow</h1>
        <p class="mb-6 text-center text-sm text-slate-500">Log in to manage Course and assignments.</p>

        <form method="POST" action="{{ route('login') }}" class="card space-y-4">
            @csrf
            <div>
                <label for="email" class="label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="input">
                @error('email') <p class="err">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password" class="label">Password</label>
                <input id="password" name="password" type="password" required class="input">
                @error('password') <p class="err">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-teal-700 focus:ring-teal-600/30"> Keep me logged in
            </label>
            <button class="btn w-full justify-center">Log in</button>
        </form>
    </div>
</body>

</html>