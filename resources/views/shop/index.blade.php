@extends('shop.layouts.app')

@php
    $sidebarAction = $currentCategory
        ? route('shop.category', $currentCategory)
        : route('shop.index');
    $clearUrl = $currentCategory
        ? route('shop.category', $currentCategory)
        : route('shop.index');
    $buildFilterUrl = static function (string $routeName, array $routeParams = []) use ($filters) {
        return route($routeName, array_filter([
            ...$routeParams,
            'availability' => $filters['availability'],
            'price_min' => $filters['price_min'],
            'price_max' => $filters['price_max'],
            'sort' => $filters['sort'] !== 'newest' ? $filters['sort'] : null,
        ], static fn ($value) => $value !== null && $value !== ''));
    };
    $sortLabels = [
        'newest' => 'Jaunākās',
        'price_asc' => 'Cena: zemākā',
        'price_desc' => 'Cena: augstākā',
        'name_asc' => 'Nosaukums A-Z',
    ];
    $categoryMenuOpen = $currentCategory !== null;
@endphp

@section('content')
    <section class="shop-catalogue bg-[#f8fbf8] py-8 sm:py-10 lg:py-14">
        <div class="mx-auto max-w-[1520px] px-5 sm:px-8 lg:px-10">
            <div class="shop-catalogue__shell">
                <aside data-reveal class="reveal shop-filters lg:sticky lg:top-28 lg:self-start">
                    <div class="shop-filters__card">
                        <div class="filter-dropdown" data-filter-dropdown data-open="{{ $categoryMenuOpen ? 'true' : 'false' }}">
                            <button
                                type="button"
                                class="filter-dropdown__trigger shop-field shop-field--button"
                                data-filter-dropdown-trigger
                                aria-expanded="{{ $categoryMenuOpen ? 'true' : 'false' }}"
                            >
                                <span>Kategorijas</span>
                                <svg class="filter-dropdown__icon h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="m5 7.5 5 5 5-5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>

                            <div class="filter-dropdown__panel mt-3" data-filter-dropdown-panel>
                                <nav class="space-y-2">
                                    <a
                                        href="{{ $buildFilterUrl('shop.index') }}"
                                        class="shop-category-link {{ $currentCategory ? '' : 'shop-category-link--active' }}"
                                    >
                                        <span>Visas preces</span>
                                        <span class="shop-category-link__count">
                                            {{ $categories->sum('active_products_count') }}
                                        </span>
                                    </a>

                                    @foreach ($categories as $category)
                                        <a
                                            href="{{ $buildFilterUrl('shop.category', ['category' => $category]) }}"
                                            class="shop-category-link {{ optional($currentCategory)->is($category) ? 'shop-category-link--active' : '' }}"
                                        >
                                            <span class="pr-3">{{ $category->name }}</span>
                                            <span class="shop-category-link__count">
                                                {{ $category->active_products_count }}
                                            </span>
                                        </a>
                                    @endforeach
                                </nav>
                            </div>
                        </div>

                        <form method="GET" action="{{ $sidebarAction }}" class="mt-5 space-y-5">
                            <section class="shop-filter-section">
                                <h2 class="shop-filter-section__title">Pieejamība</h2>
                                <div class="mt-3 space-y-2.5">
                                    <label class="shop-radio-option">
                                        <input type="radio" name="availability" value="in_stock" @checked($filters['availability'] === 'in_stock') class="h-4 w-4 border-[#06402B]/20 text-[#06402B] focus:ring-[#BFD730]">
                                        <span>Ir noliktavā</span>
                                    </label>
                                    <label class="shop-radio-option">
                                        <input type="radio" name="availability" value="out_of_stock" @checked($filters['availability'] === 'out_of_stock') class="h-4 w-4 border-[#06402B]/20 text-[#06402B] focus:ring-[#BFD730]">
                                        <span>Nav noliktavā</span>
                                    </label>
                                    <label class="shop-radio-option">
                                        <input type="radio" name="availability" value="" @checked($filters['availability'] === null) class="h-4 w-4 border-[#06402B]/20 text-[#06402B] focus:ring-[#BFD730]">
                                        <span>Visi produkti</span>
                                    </label>
                                </div>
                            </section>

                            <section class="shop-filter-section">
                                <h2 class="shop-filter-section__title">Cena</h2>
                                <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                                    <label class="block">
                                        <span class="shop-field-label">No</span>
                                        <div class="shop-field shop-field--input-wrap">
                                            <input type="number" min="0" step="0.01" name="price_min" value="{{ $filters['price_min'] }}" class="shop-field__input" placeholder="0.00">
                                            <span class="shop-field__suffix">€</span>
                                        </div>
                                    </label>
                                    <label class="block">
                                        <span class="shop-field-label">Līdz</span>
                                        <div class="shop-field shop-field--input-wrap">
                                            <input type="number" min="0" step="0.01" name="price_max" value="{{ $filters['price_max'] }}" class="shop-field__input" placeholder="0.00">
                                            <span class="shop-field__suffix">€</span>
                                        </div>
                                    </label>
                                </div>
                            </section>

                            <section class="shop-filter-section">
                                <h2 class="shop-filter-section__title">Kārtot pēc</h2>
                                <select name="sort" class="mt-3 shop-field shop-field--input">
                                    <option value="newest" @selected($filters['sort'] === 'newest')>Jaunākās</option>
                                    <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Cena: zemākā</option>
                                    <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Cena: augstākā</option>
                                    <option value="name_asc" @selected($filters['sort'] === 'name_asc')>Nosaukums A-Z</option>
                                </select>
                            </section>

                            <div class="flex flex-col gap-3 pt-1">
                                <button type="submit" class="shop-button shop-button--primary">
                                    Piemērot filtru
                                </button>
                                <a href="{{ $clearUrl }}" class="shop-button shop-button--secondary">
                                    Notīrīt
                                </a>
                            </div>
                        </form>
                    </div>
                </aside>

                <div class="min-w-0">
                    <div data-reveal class="reveal shop-catalogue__header">
                        <div>
                            <p class="section-kicker text-[#60716a]">Veikals</p>
                            <h1 class="mt-3 text-[2.8rem] sm:text-[3.4rem] lg:text-[4.25rem]">Mūsu produkti</h1>
                        </div>

                        <div class="shop-toolbar">
                            <span class="shop-pill">Atrasti: {{ $products->total() }} produkti</span>

                            <form method="GET" action="{{ $sidebarAction }}" class="shop-toolbar__sort">
                                <input type="hidden" name="availability" value="{{ $filters['availability'] }}">
                                <input type="hidden" name="price_min" value="{{ $filters['price_min'] }}">
                                <input type="hidden" name="price_max" value="{{ $filters['price_max'] }}">
                                <label class="sr-only" for="shop-sort-top">Kārtot pēc</label>
                                <select id="shop-sort-top" name="sort" class="shop-field shop-field--input" onchange="this.form.submit()">
                                    @foreach ($sortLabels as $value => $label)
                                        <option value="{{ $value }}" @selected($filters['sort'] === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <noscript>
                                    <button type="submit" class="shop-button shop-button--secondary">Atjaunot</button>
                                </noscript>
                            </form>
                        </div>
                    </div>

                    @if ($currentCategory)
                        <div data-reveal class="reveal mb-6 rounded-[1.5rem] border border-[#06402B]/8 bg-white px-5 py-4 text-sm text-[#51635b] shadow-[0_16px_40px_rgba(6,64,43,0.06)]">
                            Atlasītā kategorija:
                            <span class="font-semibold text-[#12261f]">{{ $currentCategory->name }}</span>
                        </div>
                    @endif

                    @if ($products->isEmpty())
                        <div data-reveal class="reveal rounded-[2rem] border border-dashed border-[#06402B]/14 bg-white px-8 py-16 text-center shadow-[0_20px_50px_rgba(6,64,43,0.05)]">
                            <h2 class="text-3xl text-[#12261f]">Preces nav atrastas.</h2>
                            <p class="mx-auto mt-4 max-w-[520px] text-base leading-8 text-[#5c6d66]">
                                Pamēģiniet mainīt filtrus vai atgriezieties pie visām precēm.
                            </p>
                        </div>
                    @else
                        <div class="shop-product-grid">
                            @foreach ($products as $product)
                                @include('shop.partials.product-card', ['product' => $product])
                            @endforeach
                        </div>

                        @if ($products->hasPages())
                            <div data-reveal class="reveal mt-10 flex justify-center">
                                {{ $products->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
