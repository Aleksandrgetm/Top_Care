@extends('shop.layouts.app')

@section('content')
    <section class="bg-white py-10 sm:py-12 lg:py-14">
        <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
            @if ($items === [])
                <div data-reveal class="reveal mx-auto max-w-[760px] rounded-[2rem] border border-dashed border-[#06402B]/14 bg-[#f7faf7] px-8 py-14 text-center shadow-[0_18px_50px_rgba(6,64,43,0.06)] sm:px-12">
                    <h1 class="text-4xl font-bold tracking-[-0.05em] text-[#12261f] sm:text-5xl">Grozs ir tukšs</h1>
                    <p class="mt-4 text-base leading-8 text-[#5c6d66]">
                        Jūs vēl neesat pievienojis nevienu preci. Apskatiet katalogu un izvēlieties sev piemērotāko.
                    </p>
                    <a href="{{ route('shop.index') }}" class="mt-8 inline-flex items-center justify-center rounded-full bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                        Atgriezties veikalā
                    </a>
                </div>
            @else
                <div class="mb-8 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                    <div data-reveal class="reveal">
                        <p class="section-kicker">Grozs</p>
                        <h1 class="mt-3 text-3xl font-bold tracking-[-0.04em] text-[#12261f] sm:text-4xl">
                            Jūsu izvēlētās preces
                        </h1>
                    </div>
                    <p data-reveal class="reveal text-sm text-[#5c6d66]">
                        {{ $cartCount }} {{ \Illuminate\Support\Str::plural('prece', $cartCount) }} grozā
                    </p>
                </div>

                <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px] xl:items-start xl:gap-8">
                    <div class="space-y-4">
                        @foreach ($items as $item)
                            <article data-reveal class="reveal overflow-hidden rounded-[2rem] border border-[#06402B]/8 bg-white shadow-[0_18px_48px_rgba(6,64,43,0.08)]">
                                <div class="flex flex-col gap-5 p-5 sm:p-6 lg:flex-row lg:items-center">
                                    <a href="{{ route('shop.show', ['product' => $item['slug']]) }}" class="w-full shrink-0 overflow-hidden rounded-[1.5rem] border border-[#06402B]/8 bg-[#edf3ef] lg:w-[180px]">
                                        @if (! empty($item['image']))
                                            <img src="{{ route('media.public', ['path' => $item['image']]) }}" alt="{{ $item['name'] }}" class="h-[180px] w-full object-cover">
                                        @else
                                            <div class="flex h-[180px] items-center justify-center px-4 text-center text-sm font-medium text-[#60716a]">
                                                Attēls nav pieejams
                                            </div>
                                        @endif
                                    </a>

                                    <div class="min-w-0 flex-1">
                                        <div class="min-w-0">
                                            <a href="{{ route('shop.show', ['product' => $item['slug']]) }}" class="text-2xl font-semibold leading-tight text-[#12261f] transition hover:text-[#06402B]">
                                                {{ $item['name'] }}
                                            </a>
                                            <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-[#5c6d66]">
                                                <span class="rounded-full bg-[#f1f5ef] px-3 py-1 font-medium text-[#355348]">
                                                    Cena: €{{ number_format((float) $item['price'], 2, '.', ' ') }}
                                                </span>
                                                <span class="rounded-full bg-[#edf7d3] px-3 py-1 font-medium text-[#4b6110]">
                                                    Noliktavā: {{ $item['stock_quantity'] }} gab.
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mt-6 flex flex-col gap-4 border-t border-[#06402B]/8 pt-5 xl:flex-row xl:items-end xl:justify-between">
                                            <form method="POST" action="{{ route('cart.update', ['product' => $item['product_id']]) }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                                                @csrf
                                                @method('PATCH')
                                                <label class="block">
                                                    <span class="mb-2 block text-xs font-semibold uppercase tracking-[0.18em] text-[#60716a]">Daudzums</span>
                                                    <input type="number" name="quantity" min="1" max="{{ $item['stock_quantity'] }}" value="{{ $item['quantity'] }}" class="w-full rounded-[1.1rem] border border-[#06402B]/10 bg-[#f7faf7] px-4 py-3 text-sm font-medium text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20 sm:w-28">
                                                </label>
                                                <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-5 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#eef4ef]">
                                                    Atjaunināt
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('cart.destroy', ['product' => $item['product_id']]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-full border border-[#a12626]/12 bg-white px-5 py-3 text-sm font-semibold text-[#a12626] transition hover:-translate-y-0.5 hover:bg-[#fff6f6] sm:w-auto">
                                                    Dzēst
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside data-reveal class="reveal xl:sticky xl:top-28">
                        <div class="rounded-[2rem] border border-[#06402B]/8 bg-[#f7faf7] p-6 shadow-[0_18px_48px_rgba(6,64,43,0.08)] sm:p-7">
                            <p class="section-kicker">Kopsavilkums</p>
                            <div class="mt-6 space-y-4">
                                <div class="flex items-center justify-between text-sm text-[#5c6d66]">
                                    <span>Preču skaits</span>
                                    <span class="font-semibold text-[#12261f]">{{ $cartCount }}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm text-[#5c6d66]">
                                    <span>Pozīciju skaits</span>
                                    <span class="font-semibold text-[#12261f]">{{ count($items) }}</span>
                                </div>
                                <div class="flex items-center justify-between border-t border-[#06402B]/8 pt-4">
                                    <span class="text-base font-semibold text-[#12261f]">Kopā</span>
                                    <span class="text-3xl font-bold tracking-[-0.04em] text-[#06402B]">
                                        €{{ number_format($cartTotal, 2, '.', ' ') }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-8 rounded-[1.5rem] bg-white p-4 text-sm leading-7 text-[#5c6d66]">
                                Piegādes un noformēšana tiks pievienota nākamajā etapā. Šobrīd varat pārskatīt preces un turpināt iepirkšanos.
                            </div>

                            <div class="mt-6 flex flex-col gap-3">
                                <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                                    Turpināt iepirkties
                                </a>
                            </div>
                        </div>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection
