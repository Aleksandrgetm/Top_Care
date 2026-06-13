<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Top Care Group Admin' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#edf3ef] text-[#12261f]">
    <div class="grid min-h-screen lg:grid-cols-[290px_1fr]">
        <aside class="relative overflow-hidden bg-[#042c1f] px-6 py-8 text-white">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(191,215,48,0.18),transparent_36%)]"></div>
            <div class="relative flex h-full flex-col">
                <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Top Care Group" class="h-12 w-auto rounded-lg bg-white/95 p-1.5">
                    <div>
                        <div class="text-sm font-semibold tracking-[0.24em]">TOP CARE GROUP</div>
                        <div class="text-xs uppercase tracking-[0.2em] text-white/60">Admin</div>
                    </div>
                </a>

                <nav class="mt-12 space-y-2">
                    <a href="{{ route('admin.pages.index') }}" class="{{ ($activeNav ?? '') === 'pages' ? 'bg-white/10 text-white' : 'border border-white/8 text-white/60 hover:bg-white/8 hover:text-white' }} flex items-center rounded-[1.25rem] px-4 py-3 text-sm font-medium transition">
                        Pages
                    </a>
                </nav>

                <div class="mt-auto rounded-[1.5rem] border border-white/10 bg-white/6 p-5 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-[#BFD730]">Authorized user</p>
                    <p class="mt-4 text-lg font-semibold">{{ auth()->user()?->name }}</p>
                    <p class="mt-1 text-sm text-white/64">{{ auth()->user()?->email }}</p>

                    <form method="POST" action="{{ route('admin.logout') }}" class="mt-5">
                        @csrf
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-full border border-white/14 px-4 py-3 text-sm font-semibold transition hover:bg-white/10">
                            Log out
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="px-5 py-6 sm:px-8 lg:px-10 lg:py-8">
            <div class="mx-auto max-w-[1280px]">
                @if (session('status'))
                    <div class="mb-6 rounded-[1.4rem] border border-[#BFD730]/35 bg-[#edf7d3] px-5 py-4 text-sm font-medium text-[#244338]">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
