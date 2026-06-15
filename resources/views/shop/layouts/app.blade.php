<!DOCTYPE html>
<html lang="lv">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Top Care Group' }}</title>
        <meta name="description" content="{{ $description ?? 'Top Care Group veikals.' }}">
        <meta name="theme-color" content="#06402B">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $title ?? 'Top Care Group' }}">
        <meta property="og:description" content="{{ $description ?? 'Top Care Group veikals.' }}">
        <meta property="og:image" content="{{ asset('images/logo.png') }}">
        <meta property="og:locale" content="lv_LV">
        <meta name="twitter:card" content="summary_large_image">
        <link rel="canonical" href="{{ url($canonical ?? request()->getPathInfo()) }}">
        <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-white text-[#1f2933]">
        @php
            $currentPath = request()->path();
            $navigation = [
                ['label' => 'Sākums', 'path' => '/'],
                ['label' => 'Pakalpojumi', 'path' => '/pakalpojumi'],
                ['label' => 'Galerija', 'path' => '/galerija'],
                ['label' => 'Par mums', 'path' => '/par-mums'],
                ['label' => 'Pirms / Pēc', 'path' => '/pirms-pec'],
                ['label' => 'Veikals', 'path' => '/veikals'],
                ['label' => 'Kontakti', 'path' => '/kontakti'],
            ];
            $isNavItemActive = static function (string $path) use ($currentPath): bool {
                $trimmed = ltrim($path, '/');

                if ($trimmed === '') {
                    return $currentPath === '/';
                }

                return $currentPath === $trimmed || str_starts_with($currentPath, $trimmed . '/');
            };
        @endphp

        <header class="sticky inset-x-0 top-0 z-50 border-b border-[#06402B]/8 bg-white/95 shadow-[0_12px_36px_rgba(6,64,43,0.10)] backdrop-blur-xl">
            <div class="mx-auto flex max-w-[1320px] items-center justify-between px-5 py-4 sm:px-8 lg:px-10">
                <a class="flex items-center gap-3" href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Top Care Group logotips" class="h-10 w-auto sm:h-11" height="283" width="360">
                    <div class="text-left">
                        <p class="font-display text-sm tracking-[0.28em] text-[#06402B]">TOP CARE GROUP</p>
                        <p class="text-xs uppercase tracking-[0.22em] text-[#60716a]">Latvia</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-7 lg:flex">
                    @foreach ($navigation as $item)
                        <a href="{{ $item['path'] }}" class="{{ $isNavItemActive($item['path']) ? 'nav-link nav-link--active text-[#06402B]' : 'nav-link text-[#244338] hover:text-[#06402B]' }} text-sm font-medium transition-all duration-300 ease-in-out">
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <a class="rounded-full border border-[rgba(6,64,43,0.12)] bg-[#06402B] px-5 py-3 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(6,64,43,0.18)] transition-all duration-[250ms] ease-in-out hover:translate-y-[-2px] hover:bg-[#0b5c3f] hover:shadow-[0_12px_28px_rgba(6,64,43,0.25)]" href="/kontakti">
                        Saņemt piedāvājumu
                    </a>
                </nav>

                <details class="relative lg:hidden">
                    <summary class="inline-flex h-11 w-11 cursor-pointer list-none items-center justify-center rounded-full border border-[#06402B]/12 bg-white/80 text-[#06402B]">
                        <span class="sr-only">Atvērt izvēlni</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round" />
                        </svg>
                    </summary>
                    <div class="absolute right-0 mt-3 min-w-[240px] rounded-[1.6rem] border border-[#06402B]/8 bg-white p-3 shadow-[0_18px_60px_rgba(6,64,43,0.12)]">
                        <div class="flex flex-col gap-2">
                            @foreach ($navigation as $item)
                                <a href="{{ $item['path'] }}" class="{{ $isNavItemActive($item['path']) ? 'bg-[#f3f7f2] text-[#06402B]' : 'text-[#244338] hover:bg-[#f3f7f2] hover:text-[#06402B]' }} rounded-2xl px-4 py-3 text-sm font-medium transition-all duration-300 ease-in-out">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                            <a href="/kontakti" class="mt-2 rounded-full border border-[rgba(6,64,43,0.12)] bg-[#06402B] px-5 py-3 text-center text-sm font-semibold text-white shadow-[0_8px_20px_rgba(6,64,43,0.18)] transition-all duration-[250ms] ease-in-out hover:translate-y-[-2px] hover:bg-[#0b5c3f] hover:shadow-[0_12px_28px_rgba(6,64,43,0.25)]">
                                Saņemt piedāvājumu
                            </a>
                        </div>
                    </div>
                </details>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="bg-[#042c1f] py-10 text-white">
            <div class="mx-auto grid max-w-[1320px] gap-10 px-5 sm:px-8 lg:grid-cols-[1fr_0.8fr_0.8fr] lg:px-10">
                <div>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Top Care Group logotips" class="h-10 w-auto" height="283" width="360" loading="lazy" decoding="async">
                        <div>
                            <p class="font-display text-sm tracking-[0.28em]">TOP CARE GROUP</p>
                            <p class="text-xs uppercase tracking-[0.22em] text-white/62">Latvia</p>
                        </div>
                    </div>
                    <p class="mt-5 max-w-[420px] text-sm leading-7 text-white/64">
                        Top Care Group – no nelieliem remontdarbiem līdz pilna cikla būvniecības un renovācijas projektiem.
                    </p>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">Navigācija</p>
                    <div class="mt-5 flex flex-col gap-3 text-sm text-white/72">
                        @foreach ($navigation as $item)
                            <a href="{{ $item['path'] }}" class="transition hover:text-white">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#BFD730]">Kontakti</p>
                    <div class="mt-5 space-y-3 text-sm leading-7 text-white/72">
                        <p>Telefons: +371 28 842 265</p>
                        <p>E-pasts: topcare.lv@gmail.com</p>
                        <p>Darbi tiek veikti visā Latvijā</p>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-10 flex max-w-[1320px] flex-wrap items-center gap-x-2 gap-y-3 border-t border-white/10 px-5 pt-6 text-sm text-white/44 sm:px-8 lg:px-10">
                <span>© 2026 Top Care Group. Visas tiesības aizsargātas</span>
                <span aria-hidden="true">·</span>
                <a class="text-white/64 transition hover:text-white" href="/privatuma-politika">
                    Privātuma politika
                </a>
                <span aria-hidden="true">·</span>
                <a class="text-white/36 transition hover:text-white/52" href="https://getmanenko.lv" target="_blank" rel="noopener noreferrer">
                    Izstrādāja Getmanenko
                </a>
            </div>
        </footer>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const revealElements = document.querySelectorAll('[data-reveal]');

                if (!revealElements.length) {
                    return;
                }

                const observer = new IntersectionObserver(
                    (entries) => {
                        entries.forEach((entry) => {
                            if (!entry.isIntersecting) {
                                return;
                            }

                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        });
                    },
                    {
                        threshold: 0.16,
                        rootMargin: '0px 0px -8% 0px',
                    }
                );

                revealElements.forEach((element) => observer.observe(element));
            });
        </script>
    </body>
</html>
