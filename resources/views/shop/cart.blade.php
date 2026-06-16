@extends('shop.layouts.app')

@php
    $freeShippingThreshold = 150;
@endphp

@section('content')
    <section class="cart-page bg-[#f8fbf8] py-8 sm:py-10 lg:py-14">
        <div class="mx-auto max-w-[1520px] px-5 sm:px-8 lg:px-10">
            <div data-reveal class="reveal cart-page__hero">
                <div>
                    <p class="section-kicker text-[#60716a]">Grozs</p>
                    <h1 class="mt-3 text-[2.8rem] sm:text-[3.4rem] lg:text-[4.25rem]">Jūsu grozs</h1>
                </div>

                <ol class="cart-steps" aria-label="Pasūtījuma soļi">
                    <li class="cart-step cart-step--active">
                        <span class="cart-step__number">1</span>
                        <span class="cart-step__label">Grozs</span>
                    </li>
                    <li class="cart-step cart-step--connector" aria-hidden="true">
                        <span class="cart-step__chevron">›</span>
                    </li>
                    <li class="cart-step">
                        <span class="cart-step__number">2</span>
                        <span class="cart-step__label">Piegāde</span>
                    </li>
                    <li class="cart-step cart-step--connector" aria-hidden="true">
                        <span class="cart-step__chevron">›</span>
                    </li>
                    <li class="cart-step">
                        <span class="cart-step__number">3</span>
                        <span class="cart-step__label">Apmaksa</span>
                    </li>
                </ol>
            </div>

            @if ($items === [])
                <div data-reveal class="reveal cart-empty">
                    <div class="cart-empty__icon">
                        <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <path d="M3 4h2.2c.5 0 .94.34 1.06.82L6.8 7H20a1 1 0 0 1 .97 1.24l-1.4 5.6a1 1 0 0 1-.97.76H9.2a1 1 0 0 1-.97-.76L5.1 3.64A1 1 0 0 0 4.14 3H3" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="10" cy="19" r="1.5"/>
                            <circle cx="18" cy="19" r="1.5"/>
                        </svg>
                    </div>
                    <h2 class="mt-6 text-4xl font-bold tracking-[-0.05em] text-[#12261f] sm:text-5xl">Grozs ir tukšs</h2>
                    <p class="mx-auto mt-4 max-w-[620px] text-base leading-8 text-[#5c6d66]">
                        Jūs vēl neesat pievienojis nevienu preci. Apskatiet katalogu un izvēlieties sev piemērotāko produktu.
                    </p>
                    <a href="{{ route('shop.index') }}" class="shop-button shop-button--primary mt-8">
                        Turpināt iepirkties
                    </a>
                </div>
            @else
                <div class="cart-layout">
                    <section data-reveal class="reveal cart-card">
                        <div class="cart-table cart-table--head">
                            <div>Produkts</div>
                            <div>Cena</div>
                            <div>Daudzums</div>
                            <div>Kopā</div>
                            <div class="text-right"> </div>
                        </div>

                        <div class="cart-items">
                            @foreach ($items as $item)
                                @php
                                    $lineTotal = ((float) $item['price']) * ((int) $item['quantity']);
                                @endphp

                                <article class="cart-item">
                                    <div class="cart-item__product">
                                        <a href="{{ route('shop.show', ['product' => $item['slug']]) }}" class="cart-item__image-wrap">
                                            @if (! empty($item['image']))
                                                <img src="{{ '/storage/' . ltrim($item['image'], '/') }}" alt="{{ $item['name'] }}" class="cart-item__image">
                                            @else
                                                <div class="cart-item__image cart-item__image--placeholder">
                                                    Attēls nav pieejams
                                                </div>
                                            @endif
                                        </a>

                                        <div class="min-w-0">
                                            <a href="{{ route('shop.show', ['product' => $item['slug']]) }}" class="cart-item__title">
                                                {{ $item['name'] }}
                                            </a>

                                            <p class="cart-item__description line-clamp-2">
                                                {{ $item['description'] ?? 'Detalizētāka informācija par produktu ir pieejama produkta lapā.' }}
                                            </p>

                                            <span class="cart-item__stock">
                                                Noliktavā: {{ $item['stock_quantity'] }} gab.
                                            </span>
                                        </div>
                                    </div>

                                    <div class="cart-item__meta" data-label="Cena">
                                        €{{ number_format((float) $item['price'], 2, '.', ' ') }}
                                    </div>

                                    <div class="cart-item__meta" data-label="Daudzums">
                                        <form method="POST" action="{{ route('cart.update', ['product' => $item['product_id']]) }}" class="cart-stepper">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                name="quantity"
                                                value="{{ max(1, ((int) $item['quantity']) - 1) }}"
                                                class="cart-stepper__button"
                                                @disabled((int) $item['quantity'] <= 1)
                                                aria-label="Samazināt daudzumu"
                                            >
                                                −
                                            </button>
                                            <span class="cart-stepper__value">{{ $item['quantity'] }}</span>
                                            <button
                                                type="submit"
                                                name="quantity"
                                                value="{{ min((int) $item['stock_quantity'], ((int) $item['quantity']) + 1) }}"
                                                class="cart-stepper__button"
                                                @disabled((int) $item['quantity'] >= (int) $item['stock_quantity'])
                                                aria-label="Palielināt daudzumu"
                                            >
                                                +
                                            </button>
                                        </form>
                                    </div>

                                    <div class="cart-item__meta cart-item__meta--total" data-label="Kopā">
                                        €{{ number_format($lineTotal, 2, '.', ' ') }}
                                    </div>

                                    <div class="cart-item__actions">
                                        <form method="POST" action="{{ route('cart.destroy', ['product' => $item['product_id']]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cart-delete-button" aria-label="Dzēst produktu no groza">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                    <path d="M4 7h16" stroke-linecap="round"/>
                                                    <path d="M10 11v6M14 11v6" stroke-linecap="round"/>
                                                    <path d="M6 7l1 11a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-11" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <div class="cart-card__footer">
                            <a href="{{ route('shop.index') }}" class="shop-button shop-button--secondary">
                                Turpināt iepirkties
                            </a>

                            <form method="POST" action="{{ route('cart.clear') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="shop-button shop-button--secondary">
                                    Iztīrīt grozu
                                </button>
                            </form>
                        </div>
                    </section>

                    <aside data-reveal class="reveal cart-summary xl:sticky xl:top-28">
                        <div class="cart-summary__card">
                            <p class="section-kicker text-[#60716a]">Kopsavilkums</p>

                            <div class="mt-6 space-y-4">
                                <div class="cart-summary__row">
                                    <span>Preču skaits</span>
                                    <span>{{ $cartCount }}</span>
                                </div>
                                <div class="cart-summary__row">
                                    <span>Starp summa</span>
                                    <span>€{{ number_format($cartTotal, 2, '.', ' ') }}</span>
                                </div>
                                <div class="cart-summary__row">
                                    <span>Piegāde</span>
                                    <span>Tiks aprēķināta</span>
                                </div>
                                <div class="cart-summary__row cart-summary__row--total">
                                    <span>Kopā</span>
                                    <span>€{{ number_format($cartTotal, 2, '.', ' ') }}</span>
                                </div>
                            </div>

                            <div class="cart-shipping-note">
                                <div class="cart-shipping-note__icon">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M3 7h11v8H3z" stroke-linejoin="round"/>
                                        <path d="M14 10h3l4 4v1h-7" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="7.5" cy="18" r="1.5"/>
                                        <circle cx="17.5" cy="18" r="1.5"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-[#12261f]">Bezmaksas piegāde</p>
                                    <p class="mt-1 text-sm text-[#5c6d66]">Pasūtījumiem virs €{{ number_format($freeShippingThreshold, 2, '.', ' ') }}</p>
                                </div>
                            </div>

                            <a href="{{ route('checkout.create') }}" class="shop-button shop-button--primary w-full">
                                Turpināt noformēšanu
                            </a>

                            <p class="mt-4 text-sm leading-7 text-[#60716a]">
                                Piegādes un maksājuma detaļas varēsiet aizpildīt nākamajā solī bez reģistrācijas.
                            </p>
                        </div>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection
