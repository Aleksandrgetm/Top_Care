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
    $categoryMenuOpen = $currentCategory !== null;
@endphp

@section('content')
    <section class="bg-white py-10 sm:py-12 lg:py-14">
        <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
            <div class="grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-10">
                <aside data-reveal class="reveal lg:sticky lg:top-28 lg:self-start">
                    <div class="rounded-[2rem] border border-[#06402B]/8 bg-[#f7faf7] p-6 shadow-[0_18px_50px_rgba(6,64,43,0.05)] sm:p-7">
                        <div
                            class="filter-dropdown"
                            data-filter-dropdown
                            data-open="{{ $categoryMenuOpen ? 'true' : 'false' }}"
                        >
                            <button
                                type="button"
                                class="filter-dropdown__trigger flex w-full items-center justify-between rounded-[1.4rem] border border-[#06402B]/10 bg-white px-4 py-3 text-sm font-semibold text-[#06402B] transition hover:bg-[#eef4ef]"
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
                                <a href="{{ $buildFilterUrl('shop.index') }}" class="flex items-center justify-between rounded-[1.2rem] px-4 py-3 text-sm font-semibold transition {{ $currentCategory ? 'border border-[#06402B]/10 bg-white text-[#06402B] hover:bg-[#eef4ef]' : 'bg-[#06402B] text-white shadow-[0_12px_28px_rgba(6,64,43,0.14)]' }}">
                                    <span>Visas preces</span>
                                    <span class="text-xs {{ $currentCategory ? 'text-[#60716a]' : 'text-white/72' }}">
                                        {{ $categories->sum('active_products_count') }}
                                    </span>
                                </a>

                                @foreach ($categories as $category)
                                    <a href="{{ $buildFilterUrl('shop.category', ['category' => $category]) }}" class="flex items-center justify-between rounded-[1.2rem] border px-4 py-3 text-sm font-semibold transition {{ optional($currentCategory)->is($category) ? 'border-[#06402B] bg-[#06402B] text-white shadow-[0_12px_28px_rgba(6,64,43,0.14)]' : 'border-[#06402B]/10 bg-white text-[#06402B] hover:bg-[#eef4ef]' }}">
                                        <span class="pr-3">{{ $category->name }}</span>
                                        <span class="text-xs {{ optional($currentCategory)->is($category) ? 'text-white/72' : 'text-[#60716a]' }}">
                                            {{ $category->active_products_count }}
                                        </span>
                                    </a>
                                @endforeach
                                </nav>
                            </div>
                        </div>

                        <form method="GET" action="{{ $sidebarAction }}" class="mt-6 space-y-6">
                            <section>
                                <h2 class="text-xs font-semibold uppercase tracking-[0.22em] text-[#60716a]">Pieejamība</h2>
                                <div class="mt-3 space-y-2">
                                    <label class="flex items-center gap-3 rounded-[1.2rem] border border-[#06402B]/10 bg-white px-4 py-3 text-sm font-medium text-[#244338]">
                                        <input type="radio" name="availability" value="in_stock" @checked($filters['availability'] === 'in_stock') class="h-4 w-4 border-[#06402B]/20 text-[#06402B] focus:ring-[#BFD730]">
                                        <span>Ir noliktavā</span>
                                    </label>
                                    <label class="flex items-center gap-3 rounded-[1.2rem] border border-[#06402B]/10 bg-white px-4 py-3 text-sm font-medium text-[#244338]">
                                        <input type="radio" name="availability" value="out_of_stock" @checked($filters['availability'] === 'out_of_stock') class="h-4 w-4 border-[#06402B]/20 text-[#06402B] focus:ring-[#BFD730]">
                                        <span>Nav noliktavā</span>
                                    </label>
                                    <label class="flex items-center gap-3 rounded-[1.2rem] border border-[#06402B]/10 bg-white px-4 py-3 text-sm font-medium text-[#244338]">
                                        <input type="radio" name="availability" value="" @checked($filters['availability'] === null) class="h-4 w-4 border-[#06402B]/20 text-[#06402B] focus:ring-[#BFD730]">
                                        <span>Visi produkti</span>
                                    </label>
                                </div>
                            </section>

                            <section>
                                <h2 class="text-xs font-semibold uppercase tracking-[0.22em] text-[#60716a]">Cena</h2>
                                <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                                    <label class="block">
                                        <span class="mb-2 block text-xs font-medium uppercase tracking-[0.16em] text-[#60716a]">No</span>
                                        <input type="number" min="0" step="0.01" name="price_min" value="{{ $filters['price_min'] }}" class="w-full rounded-[1.2rem] border border-[#06402B]/10 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                                    </label>
                                    <label class="block">
                                        <span class="mb-2 block text-xs font-medium uppercase tracking-[0.16em] text-[#60716a]">Līdz</span>
                                        <input type="number" min="0" step="0.01" name="price_max" value="{{ $filters['price_max'] }}" class="w-full rounded-[1.2rem] border border-[#06402B]/10 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                                    </label>
                                </div>
                            </section>

                            <section>
                                <h2 class="text-xs font-semibold uppercase tracking-[0.22em] text-[#60716a]">Kārtot pēc</h2>
                                <select name="sort" class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/10 bg-white px-4 py-3 text-sm font-medium text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                                    <option value="newest" @selected($filters['sort'] === 'newest')>Jaunākās</option>
                                    <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Cena: zemākā</option>
                                    <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Cena: augstākā</option>
                                    <option value="name_asc" @selected($filters['sort'] === 'name_asc')>Nosaukums A-Z</option>
                                </select>
                            </section>

                            <div class="flex flex-col gap-3 pt-1">
                                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                                    Piemērot filtrus
                                </button>
                                <a href="{{ $clearUrl }}" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-5 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#eef4ef]">
                                    Notīrīt
                                </a>
                            </div>
                        </form>
                    </div>
                </aside>

                <div class="min-w-0">
                    @if ($currentCategory)
                        <div data-reveal class="reveal mb-6 rounded-[1.6rem] border border-[#06402B]/8 bg-[#f7faf7] px-5 py-4 shadow-[0_12px_32px_rgba(6,64,43,0.04)]">
                            <p class="text-sm leading-7 text-[#5c6d66]">
                                Atlasītā kategorija:
                                <span class="font-semibold text-[#12261f]">{{ $currentCategory->name }}</span>
                            </p>
                        </div>
                    @endif

                    @if ($products->isEmpty())
                        <div data-reveal class="reveal rounded-[2rem] border border-dashed border-[#06402B]/16 bg-[#f7faf7] px-8 py-16 text-center shadow-[0_18px_50px_rgba(6,64,43,0.05)]">
                            <h2 class="text-3xl text-[#12261f]">Preces nav atrastas.</h2>
                            <p class="mt-4 text-base leading-8 text-[#5c6d66]">
                                Pamēģiniet mainīt filtrus vai atgriezieties pie visām precēm.
                            </p>
                        </div>
                    @else
                        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                            @foreach ($products as $product)
                                @include('shop.partials.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
