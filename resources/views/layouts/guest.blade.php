<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ElectroCharge Maroc') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">

        <a href="{{ route('home') }}" class="flex items-center gap-2 mb-8">
            <span class="w-8 h-8 rounded-xl bg-green-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </span>
            <span class="font-semibold text-slate-800 text-base tracking-tight">
                ElectroCharge <span class="text-green-600">Maroc</span>
            </span>
        </a>

        <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-sm px-8 py-8">
            {{ $slot }}
        </div>

    </div>
</body>

</html>