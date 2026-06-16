@extends('shop.layouts.app')

@section('content')
    @php
        $productImages = $product->productImages->values();
        $primaryImage = $productImages->first();
        $galleryPayload = $productImages
            ->map(function ($image) use ($product) {
                return [
                    'src' => $image->imageUrl(),
                    'alt' => $product->name . ' attēls',
                ];
            })
            ->values()
            ->all();
    @endphp

    <section class="bg-white py-10 sm:py-12 lg:py-14">
        <div class="mx-auto max-w-[1280px] px-5 sm:px-8 lg:px-10">
            <div class="overflow-hidden rounded-[2.2rem] border border-[#06402B]/8 bg-[linear-gradient(135deg,#ffffff_0%,#fbfdf9_100%)] p-5 shadow-[0_26px_80px_rgba(6,64,43,0.10)] sm:p-6 xl:p-7">
                <div class="grid gap-7 xl:grid-cols-[minmax(0,0.4fr)_minmax(0,0.6fr)] xl:items-start xl:gap-9">
                    <div
                        class="space-y-4"
                        data-product-gallery
                        @if ($productImages->isNotEmpty())
                            data-gallery-images='@json($galleryPayload)'
                        @endif
                    >
                        @if ($primaryImage)
                            <div class="relative">
                                <button
                                    type="button"
                                    data-reveal
                                    data-gallery-main-trigger
                                    class="gallery-main-trigger gallery-reveal block w-full overflow-hidden rounded-[2rem] border border-[#06402B]/8 bg-[#edf3ef] shadow-[0_18px_48px_rgba(6,64,43,0.08)]"
                                    aria-label="Atvērt attēlu pilnekrāna skatā"
                                >
                                    <img
                                        src="{{ $primaryImage->imageUrl() }}"
                                        alt="{{ $product->name }} attēls"
                                        data-gallery-main-image
                                        data-gallery-index="0"
                                        class="h-auto max-h-[560px] w-full object-cover lg:max-h-[520px] xl:max-h-[500px]"
                                    >
                                </button>

                                <button
                                    type="button"
                                    data-gallery-main-trigger
                                    class="absolute bottom-4 right-4 inline-flex h-13 w-13 items-center justify-center rounded-full border border-white/70 bg-white/92 text-[#123126] shadow-[0_12px_28px_rgba(6,64,43,0.16)] backdrop-blur-md transition hover:bg-white"
                                    aria-label="Pietuvināt attēlu"
                                >
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.85" aria-hidden="true">
                                        <circle cx="11" cy="11" r="5.5" />
                                        <path d="M16 16 21 21" stroke-linecap="round" />
                                    </svg>
                                </button>
                            </div>

                            @if ($productImages->count() > 1)
                                <div data-reveal class="reveal rounded-[1.5rem] border border-[#06402B]/8 bg-[#f7faf7] p-3 shadow-[0_10px_26px_rgba(6,64,43,0.05)]">
                                    <div class="gallery-thumb-strip" aria-label="Produkta attēlu sīktēli">
                                        @foreach ($productImages as $image)
                                            <button
                                                type="button"
                                                class="gallery-thumb group {{ $loop->first ? 'ring-2 ring-[#BFD730] ring-offset-2 ring-offset-[#f7faf7]' : '' }}"
                                                data-gallery-thumb
                                                data-gallery-index="{{ $loop->index }}"
                                                aria-label="Atvērt attēlu {{ $loop->iteration }}"
                                            >
                                                <img
                                                    src="{{ $image->thumbnailUrl() }}"
                                                    alt="{{ $product->name }} galerija {{ $loop->iteration }}"
                                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.03]"
                                                    loading="lazy"
                                                    decoding="async"
                                                >
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div
                                class="product-lightbox"
                                data-gallery-lightbox
                                hidden
                                aria-hidden="true"
                                role="dialog"
                                aria-modal="true"
                                aria-label="Produkta attēlu galerija"
                            >
                                <div class="product-lightbox__backdrop" data-gallery-close></div>
                                <div class="product-lightbox__dialog">
                                    <button type="button" class="product-lightbox__close" data-gallery-close aria-label="Aizvērt galeriju">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M5 5 15 15M15 5 5 15" stroke-linecap="round" />
                                        </svg>
                                    </button>

                                    <div class="product-lightbox__frame">
                                        <img
                                            src="{{ $primaryImage->imageUrl() }}"
                                            alt="{{ $product->name }} attēls"
                                            data-gallery-lightbox-image
                                            class="product-lightbox__image"
                                        >
                                    </div>

                                    @if ($productImages->count() > 1)
                                        <button type="button" class="product-lightbox__nav product-lightbox__nav--prev" data-gallery-prev aria-label="Iepriekšējais attēls">
                                            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path d="M11.5 4.5 6 10l5.5 5.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button type="button" class="product-lightbox__nav product-lightbox__nav--next" data-gallery-next aria-label="Nākamais attēls">
                                            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                <path d="m8.5 4.5 5.5 5.5-5.5 5.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div data-reveal class="reveal flex min-h-[360px] items-center justify-center rounded-[2rem] border border-dashed border-[#06402B]/16 bg-[radial-gradient(circle_at_top_left,rgba(191,215,48,0.24),transparent_38%),linear-gradient(180deg,#eaf1ec_0%,#dce7df_100%)] px-8 text-center text-base font-medium text-[#60716a] lg:min-h-[420px]">
                                Produkta galerija tiks papildināta drīzumā.
                            </div>
                        @endif
                    </div>

                    <div data-reveal class="reveal xl:self-start">
                        <div class="rounded-[2rem] bg-white/78 p-1 xl:sticky xl:top-[100px]">
                            <a href="{{ route('shop.category', $product->category) }}" class="section-kicker transition hover:text-[#0b5c3f]">
                                {{ $product->category->name }}
                            </a>
                            <h1 class="mt-3 text-4xl font-bold tracking-[-0.06em] text-[#12261f] sm:text-5xl xl:text-[3.6rem]">
                                {{ $product->name }}
                            </h1>

                            <div class="mt-6 rounded-[1.8rem] border border-[#06402B]/8 bg-[#F7F8F7] p-5 shadow-[0_16px_40px_rgba(6,64,43,0.04)] sm:p-6">
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#eef4ef] text-[#06402B] shadow-[0_8px_18px_rgba(6,64,43,0.08)]">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                        <path d="M12 20c4.42 0 8-3.58 8-8S16.42 4 12 4 4 7.58 4 12s3.58 8 8 8Z" />
                                        <path d="M12 9.5V12l1.8 1.8" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <p class="mt-4 whitespace-pre-line text-base leading-8 text-[#5c6d66]">
                                    {{ $product->description ?: 'Papildu apraksts šai precei tiks pievienots drīzumā.' }}
                                </p>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#F7F8F7] p-5 shadow-[0_14px_34px_rgba(6,64,43,0.04)]">
                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#60716a]">Cena</p>
                                    <p class="mt-3 text-3xl font-bold tracking-[-0.05em] text-[#06402B]">
                                        €{{ number_format((float) $product->price, 2, '.', ' ') }}
                                    </p>
                                </div>

                                <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#F7F8F7] p-5 shadow-[0_14px_34px_rgba(6,64,43,0.04)]">
                                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#60716a]">Pieejamība</p>
                                    <p class="mt-3 text-lg font-semibold {{ $product->stock_quantity > 0 ? 'text-[#5b6f11]' : 'text-[#9a4040]' }}">
                                        {{ $product->stock_quantity > 0 ? "Noliktavā: {$product->stock_quantity} gab." : 'Prece pašlaik nav noliktavā.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 flex flex-col gap-2.5">
                                @if ($product->stock_quantity > 0)
                                    <form method="POST" action="{{ route('cart.store', ['product' => $product->id]) }}" class="space-y-2.5">
                                        @csrf
                                        <div class="flex flex-col gap-2">
                                            <span class="text-xs font-semibold uppercase tracking-[0.24em] text-[#60716a]">Daudzums</span>
                                            <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                                                <div
                                                    class="flex h-[52px] w-full overflow-hidden rounded-[1.3rem] border border-[#06402B]/10 bg-[#F7F8F7] shadow-[0_12px_30px_rgba(6,64,43,0.04)] lg:w-[190px]"
                                                    data-quantity-stepper
                                                >
                                                    <button
                                                        type="button"
                                                        class="inline-flex w-13 shrink-0 items-center justify-center text-2xl font-semibold text-[#06402B] transition hover:bg-[#eef4ef]"
                                                        data-stepper-decrement
                                                        aria-label="Samazināt daudzumu"
                                                    >
                                                        -
                                                    </button>
                                                    <input
                                                        type="number"
                                                        name="quantity"
                                                        min="1"
                                                        max="{{ $product->stock_quantity }}"
                                                        value="{{ old('quantity', 1) }}"
                                                        class="h-full min-w-0 flex-1 border-x border-[#06402B]/10 bg-transparent px-2 text-center text-lg font-semibold text-[#12261f] outline-none [appearance:textfield]"
                                                        data-stepper-input
                                                    >
                                                    <button
                                                        type="button"
                                                        class="inline-flex w-13 shrink-0 items-center justify-center text-2xl font-semibold text-[#06402B] transition hover:bg-[#eef4ef]"
                                                        data-stepper-increment
                                                        aria-label="Palielināt daudzumu"
                                                    >
                                                        +
                                                    </button>
                                                </div>

                                                <button type="submit" class="inline-flex h-[52px] w-full items-center justify-center gap-2 rounded-full bg-[#06402B] px-6 text-base font-semibold text-white shadow-[0_16px_38px_rgba(6,64,43,0.18)] transition hover:-translate-y-0.5 hover:bg-[#0b5c3f] lg:flex-1">
                                                    <svg class="h-4.5 w-4.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                                        <path d="M3 4h2.2a1 1 0 0 1 .98.78L6.7 7H20a1 1 0 0 1 .97 1.24l-1.2 5A2 2 0 0 1 17.83 15H9.1a2 2 0 0 1-1.95-1.58L5.01 3.88A1 1 0 0 0 4.03 3H3" stroke-linecap="round" stroke-linejoin="round" />
                                                        <circle cx="10" cy="19" r="1.5" />
                                                        <circle cx="17" cy="19" r="1.5" />
                                                    </svg>
                                                    <span>Pievienot grozam</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <div class="space-y-3">
                                        <span class="text-xs font-semibold uppercase tracking-[0.24em] text-[#60716a]">Daudzums</span>
                                        <div class="flex flex-col gap-3 lg:flex-row lg:items-end">
                                            <div class="flex h-[52px] w-full overflow-hidden rounded-[1.3rem] border border-[#06402B]/10 bg-[#eef2ef] lg:w-[190px]">
                                                <button type="button" disabled class="inline-flex w-13 shrink-0 items-center justify-center text-2xl font-semibold text-[#93a09b]">
                                                    -
                                                </button>
                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    min="1"
                                                    value="1"
                                                    disabled
                                                    class="h-full min-w-0 flex-1 border-x border-[#06402B]/10 bg-transparent px-2 text-center text-lg font-semibold text-[#60716a] outline-none"
                                                >
                                                <button type="button" disabled class="inline-flex w-13 shrink-0 items-center justify-center text-2xl font-semibold text-[#93a09b]">
                                                    +
                                                </button>
                                            </div>

                                            <span class="inline-flex h-[52px] w-full items-center justify-center rounded-full border border-[#06402B]/10 bg-[#eef2ef] px-6 text-base font-semibold text-[#60716a] lg:flex-1">
                                                Nav pieejams
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('shop.index') }}" class="inline-flex h-[50px] items-center justify-center gap-2 rounded-full border border-[#06402B]/12 bg-[#F7F8F7] px-6 text-base font-semibold text-[#06402B] shadow-[0_10px_28px_rgba(6,64,43,0.04)] transition hover:-translate-y-0.5 hover:bg-[#F7F8F7]">
                                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M11.5 4.5 6 10l5.5 5.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span>Atpakaļ uz veikalu</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
