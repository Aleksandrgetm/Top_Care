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
            $cartCount = (int) collect(session('cart', []))->sum('quantity');
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

                    <a href="{{ route('cart.index') }}" class="relative inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#06402B]/12 bg-white text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#eef4ef]">
                        <span class="sr-only">Grozs</span>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M3 4h2.2c.5 0 .94.34 1.06.82L6.8 7H20a1 1 0 0 1 .97 1.24l-1.4 5.6a1 1 0 0 1-.97.76H9.2a1 1 0 0 1-.97-.76L5.1 3.64A1 1 0 0 0 4.14 3H3" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="10" cy="19" r="1.5"/>
                            <circle cx="18" cy="19" r="1.5"/>
                        </svg>
                        @if ($cartCount > 0)
                            <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-[#BFD730] px-1.5 text-[11px] font-bold leading-none text-[#0f241d]">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

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
                            <a href="{{ route('cart.index') }}" class="mt-1 flex items-center justify-between rounded-2xl border border-[#06402B]/10 bg-white px-4 py-3 text-sm font-semibold text-[#06402B] transition hover:bg-[#f3f7f2]">
                                <span>Grozs</span>
                                <span class="inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-[#BFD730] px-1.5 text-[11px] font-bold leading-none text-[#0f241d]">
                                    {{ $cartCount }}
                                </span>
                            </a>
                            <a href="/kontakti" class="mt-2 rounded-full border border-[rgba(6,64,43,0.12)] bg-[#06402B] px-5 py-3 text-center text-sm font-semibold text-white shadow-[0_8px_20px_rgba(6,64,43,0.18)] transition-all duration-[250ms] ease-in-out hover:translate-y-[-2px] hover:bg-[#0b5c3f] hover:shadow-[0_12px_28px_rgba(6,64,43,0.25)]">
                                Saņemt piedāvājumu
                            </a>
                        </div>
                    </div>
                </details>
            </div>
        </header>

        <main>
            @if (session('status') || session('error'))
                <div class="mx-auto max-w-[1320px] px-5 pt-6 sm:px-8 lg:px-10">
                    @if (session('status'))
                        <div class="mb-4 rounded-[1.4rem] border border-[#BFD730]/35 bg-[#edf7d3] px-5 py-4 text-sm font-medium text-[#244338]">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 rounded-[1.4rem] border border-[#a12626]/15 bg-[#fff6f6] px-5 py-4 text-sm font-medium text-[#8f2a2a]">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            @endif
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
                const dropdowns = document.querySelectorAll('[data-filter-dropdown]');
                const galleries = document.querySelectorAll('[data-product-gallery]');
                const steppers = document.querySelectorAll('[data-quantity-stepper]');
                const deliverySelectors = document.querySelectorAll('[data-delivery-selector]');
                const addressAutocompleteRoots = document.querySelectorAll('[data-address-autocomplete]');

                if (revealElements.length) {
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
                }

                dropdowns.forEach((dropdown) => {
                    const trigger = dropdown.querySelector('[data-filter-dropdown-trigger]');
                    const panel = dropdown.querySelector('[data-filter-dropdown-panel]');

                    if (!trigger || !panel) {
                        return;
                    }

                    const setOpen = (open) => {
                        dropdown.dataset.open = open ? 'true' : 'false';
                        trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
                        panel.style.maxHeight = open ? `${panel.scrollHeight}px` : '0px';
                    };

                    setOpen(dropdown.dataset.open === 'true');

                    trigger.addEventListener('click', () => {
                        setOpen(dropdown.dataset.open !== 'true');
                    });

                    window.addEventListener('resize', () => {
                        if (dropdown.dataset.open === 'true') {
                            panel.style.maxHeight = `${panel.scrollHeight}px`;
                        }
                    });
                });

                galleries.forEach((gallery) => {
                    const images = JSON.parse(gallery.dataset.galleryImages ?? '[]');
                    const mainImage = gallery.querySelector('[data-gallery-main-image]');
                    const mainTriggers = gallery.querySelectorAll('[data-gallery-main-trigger]');
                    const thumbnails = gallery.querySelectorAll('[data-gallery-thumb]');
                    const lightbox = gallery.querySelector('[data-gallery-lightbox]');
                    const lightboxImage = gallery.querySelector('[data-gallery-lightbox-image]');
                    const closeButtons = gallery.querySelectorAll('[data-gallery-close]');
                    const prevButton = gallery.querySelector('[data-gallery-prev]');
                    const nextButton = gallery.querySelector('[data-gallery-next]');

                    if (!images.length || !mainImage || !mainTriggers.length || !lightbox || !lightboxImage) {
                        return;
                    }

                    let activeIndex = Number(mainImage.dataset.galleryIndex ?? 0);
                    const primaryIndex = Number(mainImage.dataset.galleryIndex ?? 0);

                    const syncThumbnailState = (index) => {
                        thumbnails.forEach((thumbnail) => {
                            const isActive = Number(thumbnail.dataset.galleryIndex) === index;
                            thumbnail.classList.toggle('ring-2', isActive);
                            thumbnail.classList.toggle('ring-[#BFD730]', isActive);
                            thumbnail.classList.toggle('ring-offset-2', isActive);
                            thumbnail.classList.toggle('ring-offset-white', isActive);
                        });
                    };

                    const syncLightboxImage = (index) => {
                        const image = images[index];

                        if (!image) {
                            return;
                        }

                        activeIndex = index;
                        lightboxImage.classList.add('is-switching');

                        window.setTimeout(() => {
                            lightboxImage.src = image.src;
                            lightboxImage.alt = image.alt;
                            lightboxImage.classList.remove('is-switching');
                        }, 120);

                        syncThumbnailState(index);
                    };

                    const openLightbox = (index) => {
                        syncLightboxImage(index);
                        lightbox.hidden = false;
                        requestAnimationFrame(() => {
                            lightbox.classList.add('is-open');
                            lightbox.setAttribute('aria-hidden', 'false');
                        });
                        document.body.classList.add('overflow-hidden');
                    };

                    const closeLightbox = () => {
                        lightbox.classList.remove('is-open');
                        lightbox.setAttribute('aria-hidden', 'true');
                        document.body.classList.remove('overflow-hidden');
                        activeIndex = primaryIndex;
                        syncThumbnailState(primaryIndex);
                        window.setTimeout(() => {
                            if (!lightbox.classList.contains('is-open')) {
                                lightbox.hidden = true;
                            }
                        }, 280);
                    };

                    const showRelativeImage = (direction) => {
                        if (images.length <= 1) {
                            return;
                        }

                        const nextIndex = (activeIndex + direction + images.length) % images.length;
                        syncLightboxImage(nextIndex);
                    };

                    syncThumbnailState(primaryIndex);

                    mainTriggers.forEach((trigger) => {
                        trigger.addEventListener('click', () => openLightbox(primaryIndex));
                    });

                    thumbnails.forEach((thumbnail) => {
                        thumbnail.addEventListener('click', () => {
                            const index = Number(thumbnail.dataset.galleryIndex ?? 0);
                            openLightbox(index);
                        });
                    });

                    closeButtons.forEach((button) => {
                        button.addEventListener('click', closeLightbox);
                    });

                    prevButton?.addEventListener('click', () => showRelativeImage(-1));
                    nextButton?.addEventListener('click', () => showRelativeImage(1));

                    document.addEventListener('keydown', (event) => {
                        if (lightbox.hidden) {
                            return;
                        }

                        if (event.key === 'Escape') {
                            closeLightbox();
                        }

                        if (event.key === 'ArrowLeft') {
                            showRelativeImage(-1);
                        }

                        if (event.key === 'ArrowRight') {
                            showRelativeImage(1);
                        }
                    });
                });

                steppers.forEach((stepper) => {
                    const input = stepper.querySelector('[data-stepper-input]');
                    const decrement = stepper.querySelector('[data-stepper-decrement]');
                    const increment = stepper.querySelector('[data-stepper-increment]');

                    if (!input || !decrement || !increment) {
                        return;
                    }

                    const min = Number(input.min || 1);
                    const max = Number(input.max || Number.MAX_SAFE_INTEGER);

                    const clampValue = (value) => {
                        if (Number.isNaN(value)) {
                            return min;
                        }

                        return Math.min(Math.max(value, min), max);
                    };

                    const syncValue = (value) => {
                        input.value = String(clampValue(value));
                    };

                    decrement.addEventListener('click', () => {
                        syncValue(Number(input.value || min) - 1);
                    });

                    increment.addEventListener('click', () => {
                        syncValue(Number(input.value || min) + 1);
                    });

                    input.addEventListener('input', () => {
                        if (input.value === '') {
                            return;
                        }

                        syncValue(Number(input.value));
                    });

                    input.addEventListener('blur', () => {
                        syncValue(Number(input.value || min));
                    });
                });

                deliverySelectors.forEach((root) => {
                    const input = root.querySelector('[data-delivery-method-input]');
                    const options = root.querySelectorAll('[data-delivery-option]');
                    const sections = root.querySelectorAll('[data-delivery-section], [data-delivery-section-values]');

                    if (!input || !options.length || !sections.length) {
                        return;
                    }

                    const sectionMatchesValue = (section, value) => {
                        const sectionValues = (section.dataset.deliverySectionValues || section.dataset.deliverySection || '')
                            .split(/\s+/)
                            .filter(Boolean);

                        return sectionValues.includes(value);
                    };

                    const syncSectionFields = (section, active) => {
                        section.querySelectorAll('input, textarea, select').forEach((field) => {
                            field.disabled = !active;

                            if (field.dataset.deliveryRequired) {
                                field.required = active && field.dataset.deliveryRequired === 'address';
                            }
                        });
                    };

                    const setValue = (value) => {
                        const fallbackValue = options[0]?.dataset.deliveryOption ?? '';
                        const hasMatchingOption = Array.from(options).some((option) => option.dataset.deliveryOption === value);
                        const activeValue = hasMatchingOption ? value : fallbackValue;

                        input.value = activeValue;

                        options.forEach((option) => {
                            const selected = option.dataset.deliveryOption === activeValue;
                            option.classList.toggle('is-selected', selected);
                            option.setAttribute('aria-checked', selected ? 'true' : 'false');
                        });

                        sections.forEach((section) => {
                            const active = sectionMatchesValue(section, activeValue);
                            section.classList.toggle('is-active', active);
                            syncSectionFields(section, active);
                        });
                    };

                    const initialValue = input.value || options[0].dataset.deliveryOption;
                    setValue(initialValue);

                    options.forEach((option) => {
                        option.addEventListener('click', () => {
                            setValue(option.dataset.deliveryOption ?? initialValue);
                        });
                    });
                });

                addressAutocompleteRoots.forEach((root) => {
                    const input = root.querySelector('[data-address-input]');
                    const panel = root.querySelector('[data-address-suggestions]');
                    const endpoint = root.dataset.suggestUrl;
                    const streetField = root.dataset.addressFillStreet ? document.querySelector(root.dataset.addressFillStreet) : null;
                    const houseField = root.dataset.addressFillHouse ? document.querySelector(root.dataset.addressFillHouse) : null;
                    const cityField = root.dataset.addressFillCity ? document.querySelector(root.dataset.addressFillCity) : null;
                    const postalField = root.dataset.addressFillPostal ? document.querySelector(root.dataset.addressFillPostal) : null;

                    if (!input || !panel || !endpoint) {
                        return;
                    }

                    let debounceTimer = null;
                    let abortController = null;
                    let activeRequest = 0;

                    const closePanel = () => {
                        panel.hidden = true;
                        panel.innerHTML = '';
                        input.setAttribute('aria-expanded', 'false');
                    };

                    const openPanel = () => {
                        panel.hidden = false;
                        input.setAttribute('aria-expanded', 'true');
                    };

                    const escapeHtml = (value) =>
                        String(value)
                            .replace(/&/g, '&amp;')
                            .replace(/</g, '&lt;')
                            .replace(/>/g, '&gt;')
                            .replace(/"/g, '&quot;')
                            .replace(/'/g, '&#39;');

                    const renderSuggestions = (suggestions) => {
                        if (!suggestions.length) {
                            closePanel();
                            return;
                        }

                        panel.innerHTML = suggestions
                            .map(
                                (suggestion) => `
                                    <button
                                        type="button"
                                        class="checkout-autocomplete__item"
                                        data-address-option
                                        data-address-value="${escapeHtml(suggestion.value ?? '')}"
                                        data-address-street="${escapeHtml(suggestion.street ?? '')}"
                                        data-address-house="${escapeHtml(suggestion.house ?? '')}"
                                        data-address-city="${escapeHtml(suggestion.city ?? '')}"
                                        data-address-postal="${escapeHtml(suggestion.postal_code ?? '')}"
                                    >
                                        ${escapeHtml(suggestion.label ?? '')}
                                    </button>
                                `
                            )
                            .join('');

                        openPanel();

                        panel.querySelectorAll('[data-address-option]').forEach((option) => {
                            option.addEventListener('click', () => {
                                input.value = option.dataset.addressStreet || option.dataset.addressValue || '';

                                if (streetField && option.dataset.addressStreet) {
                                    streetField.value = option.dataset.addressStreet;
                                }

                                if (houseField && option.dataset.addressHouse) {
                                    houseField.value = option.dataset.addressHouse;
                                }

                                if (cityField && option.dataset.addressCity) {
                                    cityField.value = option.dataset.addressCity;
                                }

                                if (postalField && option.dataset.addressPostal) {
                                    postalField.value = option.dataset.addressPostal;
                                }

                                closePanel();
                            });
                        });
                    };

                    const renderMessage = (message) => {
                        panel.innerHTML = `<div class="checkout-autocomplete__status">${escapeHtml(message)}</div>`;
                        openPanel();
                    };

                    const fetchSuggestions = async (query) => {
                        activeRequest += 1;
                        const requestId = activeRequest;

                        if (abortController) {
                            abortController.abort();
                        }

                        abortController = new AbortController();

                        try {
                            const response = await fetch(`${endpoint}?q=${encodeURIComponent(query)}`, {
                                headers: {
                                    Accept: 'application/json',
                                },
                                signal: abortController.signal,
                            });

                            if (!response.ok) {
                                throw new Error('Request failed');
                            }

                            const data = await response.json();

                            if (requestId !== activeRequest) {
                                return;
                            }

                            renderSuggestions(Array.isArray(data.suggestions) ? data.suggestions : []);
                        } catch (error) {
                            if (error.name === 'AbortError') {
                                return;
                            }

                            closePanel();
                        }
                    };

                    input.addEventListener('input', () => {
                        const query = input.value.trim();

                        window.clearTimeout(debounceTimer);

                        if (query.length < 3) {
                            if (abortController) {
                                abortController.abort();
                            }

                            closePanel();
                            return;
                        }

                        debounceTimer = window.setTimeout(() => {
                            renderMessage('Meklējam adreses...');
                            fetchSuggestions(query);
                        }, 400);
                    });

                    input.addEventListener('focus', () => {
                        if (panel.innerHTML.trim() !== '') {
                            openPanel();
                        }
                    });

                    input.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') {
                            closePanel();
                        }
                    });

                    document.addEventListener('click', (event) => {
                        if (!root.contains(event.target)) {
                            closePanel();
                        }
                    });
                });
            });
        </script>
    </body>
</html>
