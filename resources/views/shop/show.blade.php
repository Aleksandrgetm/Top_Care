@extends('shop.layouts.app')

@section('content')
    @php
        $primaryImage = $product->productImages->first();
        $galleryImages = $product->productImages->slice(1);
    @endphp

    <section class="bg-white py-12 sm:py-16 lg:py-18">
        <div class="mx-auto grid max-w-[1320px] gap-10 px-5 sm:px-8 lg:grid-cols-[minmax(0,0.38fr)_minmax(0,0.62fr)] lg:items-start lg:gap-14 lg:px-10">
            <div class="space-y-4 lg:max-w-[560px]">
                @if ($primaryImage)
                    <div data-reveal class="gallery-reveal overflow-hidden rounded-[2rem] border border-[#06402B]/8 bg-[#edf3ef] shadow-[0_18px_48px_rgba(6,64,43,0.08)]">
                        <img
                            src="{{ route('media.public', ['path' => $primaryImage->image_path]) }}"
                            alt="{{ $product->name }} attēls"
                            class="h-auto max-h-[620px] w-full object-cover lg:max-h-[520px]"
                        >
                    </div>

                    @if ($galleryImages->isNotEmpty())
                        <div data-reveal class="reveal grid grid-cols-3 gap-3 sm:grid-cols-4">
                            @foreach ($galleryImages as $image)
                                <div class="overflow-hidden rounded-[1.25rem] border border-[#06402B]/8 bg-[#edf3ef] shadow-[0_10px_26px_rgba(6,64,43,0.05)]">
                                    <img
                                        src="{{ route('media.public', ['path' => $image->image_path]) }}"
                                        alt="{{ $product->name }} galerija {{ $loop->iteration + 1 }}"
                                        class="aspect-square w-full object-cover"
                                    >
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div data-reveal class="reveal flex min-h-[360px] items-center justify-center rounded-[2rem] border border-dashed border-[#06402B]/16 bg-[radial-gradient(circle_at_top_left,rgba(191,215,48,0.24),transparent_38%),linear-gradient(180deg,#eaf1ec_0%,#dce7df_100%)] px-8 text-center text-base font-medium text-[#60716a] lg:min-h-[420px]">
                        Produkta galerija tiks papildināta drīzumā.
                    </div>
                @endif
            </div>

            <div data-reveal class="reveal lg:self-start">
                <div class="rounded-[2rem] border border-[#06402B]/8 bg-white p-7 shadow-[0_20px_65px_rgba(6,64,43,0.08)] sm:p-8 lg:sticky lg:top-[100px]">
                    <a href="{{ route('shop.category', $product->category) }}" class="section-kicker transition hover:text-[#0b5c3f]">
                        {{ $product->category->name }}
                    </a>
                    <h1 class="mt-4 text-4xl font-bold tracking-[-0.05em] text-[#12261f] sm:text-5xl">
                        {{ $product->name }}
                    </h1>
                    <div class="mt-6 space-y-6">
                        <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f7faf7] px-5 py-5">
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#60716a]">Apraksts</p>
                            <p class="mt-3 whitespace-pre-line text-base leading-8 text-[#5c6d66]">
                                {{ $product->description ?: 'Papildu apraksts šai precei tiks pievienots drīzumā.' }}
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f7faf7] px-5 py-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#60716a]">Cena</p>
                                <p class="mt-2 text-3xl font-bold tracking-[-0.04em] text-[#06402B]">
                                    €{{ number_format((float) $product->price, 2, '.', ' ') }}
                                </p>
                            </div>

                            <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f7faf7] px-5 py-5">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[#60716a]">Pieejamība</p>
                                <p class="mt-2 text-base font-semibold {{ $product->stock_quantity > 0 ? 'text-[#4b6110]' : 'text-[#9a4040]' }}">
                                    {{ $product->stock_quantity > 0 ? "Noliktavā: {$product->stock_quantity} gab." : 'Prece pašlaik nav noliktavā.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col gap-2.5">
                        @if ($product->stock_quantity > 0)
                            <form method="POST" action="{{ route('cart.store', ['product' => $product->id]) }}">
                                @csrf
                                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#06402B] px-5 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                                        <path d="M3 4h2.2a1 1 0 0 1 .98.78L6.7 7H20a1 1 0 0 1 .97 1.24l-1.2 5A2 2 0 0 1 17.83 15H9.1a2 2 0 0 1-1.95-1.58L5.01 3.88A1 1 0 0 0 4.03 3H3" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="10" cy="19" r="1.5" />
                                        <circle cx="17" cy="19" r="1.5" />
                                    </svg>
                                    <span>Pievienot grozam</span>
                                </button>
                            </form>
                        @else
                            <span class="inline-flex w-full items-center justify-center rounded-full border border-[#06402B]/10 bg-[#eef2ef] px-5 py-2.5 text-sm font-semibold text-[#60716a]">
                                Nav pieejams
                            </span>
                        @endif

                        <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-[#06402B]/12 bg-[#f7faf7] px-5 py-2.5 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#eef4ef]">
                            <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M11.5 4.5 6 10l5.5 5.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Atpakaļ uz veikalu</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
