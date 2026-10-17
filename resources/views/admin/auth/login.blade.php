<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin sign in — Negah</title>
    <meta name="robots" content="noindex, nofollow">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="flex min-h-full items-center justify-center bg-ink-950 px-4">

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-brand-600 to-brand-800 text-2xl font-bold text-white shadow-lg shadow-brand-900/40">
                N
            </div>
            <h1 class="mt-5 text-2xl font-bold text-white">Negah Admin Panel</h1>
            <p class="mt-2 text-sm text-ink-400">Concierge review &amp; activation dashboard</p>
        </div>

        {{-- Card --}}
        <div class="mt-8 rounded-3xl bg-white p-8 shadow-2xl shadow-black/30">
            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="username" class="block text-sm font-semibold text-ink-700">Username</label>
                    <input id="username" name="username" type="text" required autofocus
                           value="{{ old('username') }}"
                           class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-ink-700">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-2 w-full rounded-xl border border-ink-200 px-4 py-3 text-[15px] outline-none transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10">
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-brand-600 px-6 py-3.5 text-base font-semibold text-white shadow-lg shadow-brand-600/25 transition hover:bg-brand-700">
                    Sign in
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-ink-500">
            Protected area — authorised Negah staff only.
        </p>
    </div>

</body>
</html>
