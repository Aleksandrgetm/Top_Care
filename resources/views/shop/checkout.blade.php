@extends('shop.layouts.app')

@php
    $selectedDeliveryPointPayload = $selectedDeliveryPoint
        ? [
            'id' => $selectedDeliveryPoint->id,
            'provider' => $selectedDeliveryPoint->provider,
            'name' => $selectedDeliveryPoint->name,
            'city' => $selectedDeliveryPoint->city,
            'address' => $selectedDeliveryPoint->address,
        ]
        : null;

    $inputErrorClasses = 'border-[#b45454] bg-[#fff7f7] focus:border-[#9f3636] focus:ring-[#9f3636]/20';
@endphp

@section('content')
    <section class="checkout-page bg-[#f8fbf8] py-8 sm:py-10 lg:py-14">
        <div class="mx-auto max-w-[1520px] px-5 sm:px-8 lg:px-10">
            <div data-reveal class="reveal cart-page__hero">
                <div>
                    <p class="section-kicker text-[#60716a]">Checkout</p>
                    <h1 class="mt-3 text-[2.8rem] sm:text-[3.4rem] lg:text-[4.25rem]">Piegādes informācija</h1>
                </div>

                <ol class="cart-steps" aria-label="Pasūtījuma soļi">
                    <li class="cart-step cart-step--completed">
                        <span class="cart-step__number">1</span>
                        <span class="cart-step__label">Grozs</span>
                    </li>
                    <li class="cart-step cart-step--connector" aria-hidden="true">
                        <span class="cart-step__chevron">›</span>
                    </li>
                    <li class="cart-step cart-step--active">
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

            <div class="checkout-layout">
                <section data-reveal class="reveal checkout-card">
                    <div class="checkout-card__inner">
                        <div>
                            <p class="section-kicker text-[#60716a]">Pasūtītājs</p>
                            <h2 class="mt-3 text-3xl font-semibold text-[#12261f]">Noformējiet pasūtījumu bez reģistrācijas</h2>
                            <p class="mt-3 max-w-[720px] text-base leading-8 text-[#5c6d66]">
                                Aizpildiet kontaktinformāciju, izvēlieties piegādes veidu un mēs sazināsimies ar jums par pasūtījuma apstiprināšanu.
                            </p>
                        </div>

                        <form method="POST" action="{{ route('checkout.store') }}" class="mt-8 grid gap-5" data-checkout-form novalidate>
                            @csrf

                            @if ($errors->any())
                                <div
                                    class="rounded-[1.75rem] border border-[#b45454]/30 bg-[#fff7f7] px-5 py-5 text-[#7b2f2f] shadow-[0_18px_45px_rgba(123,47,47,0.08)] sm:px-6"
                                    data-checkout-error-summary
                                    tabindex="-1"
                                >
                                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#9f3636]">Lūdzu pārbaudiet ievadīto informāciju.</p>
                                    <ul class="mt-3 space-y-2 text-sm leading-7 sm:text-[0.98rem]">
                                        @foreach ($errors->all() as $error)
                                            <li class="flex gap-3">
                                                <span class="mt-[0.45rem] h-2 w-2 shrink-0 rounded-full bg-[#b45454]"></span>
                                                <span>{{ $error }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="checkout-field">
                                <label for="customer_name" class="checkout-field__label">Vārds un uzvārds</label>
                                <input
                                    id="customer_name"
                                    name="customer_name"
                                    type="text"
                                    value="{{ old('customer_name') }}"
                                    @class(['checkout-field__input', $inputErrorClasses => $errors->has('customer_name')])
                                    aria-invalid="{{ $errors->has('customer_name') ? 'true' : 'false' }}"
                                >
                                @error('customer_name')
                                    <p class="checkout-field__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="checkout-grid checkout-grid--double">
                                <div class="checkout-field">
                                    <label for="customer_phone" class="checkout-field__label">Tālrunis</label>
                                    <input
                                        id="customer_phone"
                                        name="customer_phone"
                                        type="text"
                                        value="{{ old('customer_phone') }}"
                                        @class(['checkout-field__input', $inputErrorClasses => $errors->has('customer_phone')])
                                        aria-invalid="{{ $errors->has('customer_phone') ? 'true' : 'false' }}"
                                    >
                                    @error('customer_phone')
                                        <p class="checkout-field__error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="customer_email" class="checkout-field__label">E-pasts</label>
                                    <input
                                        id="customer_email"
                                        name="customer_email"
                                        type="email"
                                        value="{{ old('customer_email') }}"
                                        @class(['checkout-field__input', $inputErrorClasses => $errors->has('customer_email')])
                                        aria-invalid="{{ $errors->has('customer_email') ? 'true' : 'false' }}"
                                    >
                                    @error('customer_email')
                                        <p class="checkout-field__error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="checkout-delivery-selector"
                                data-delivery-selector
                                data-delivery-prices='@json($deliveryPrices)'
                            >
                                <div class="checkout-field">
                                    <label class="checkout-field__label">Piegādes veids</label>
                                    <input
                                        type="hidden"
                                        name="delivery_method"
                                        value="{{ old('delivery_method', $defaultDeliveryMethod) }}"
                                        data-delivery-method-input
                                    >
                                    <input
                                        type="hidden"
                                        name="selected_delivery_point_id"
                                        value="{{ old('selected_delivery_point_id') }}"
                                        data-delivery-point-id-input
                                    >

                                    <div
                                        @class([
                                            'checkout-delivery-options',
                                            'rounded-[1.75rem] bg-[#fff7f7]/70 p-2 ring-1 ring-[#b45454]/30' => $errors->has('delivery_method'),
                                        ])
                                        role="radiogroup"
                                        aria-label="Piegādes veids"
                                    >
                                        @foreach ($deliveryMethods as $methodKey => $method)
                                            <button
                                                type="button"
                                                class="checkout-delivery-option"
                                                data-delivery-option="{{ $methodKey }}"
                                                role="radio"
                                                aria-checked="false"
                                            >
                                                <span class="checkout-delivery-option__icon" aria-hidden="true">
                                                    @if ($method['icon'] === 'courier')
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M3 7.5h10.5v7.5H3z" />
                                                            <path d="M13.5 10.5H17l3 3v1.5h-6.5z" />
                                                            <circle cx="7" cy="17.5" r="1.5" />
                                                            <circle cx="18" cy="17.5" r="1.5" />
                                                        </svg>
                                                    @elseif ($method['icon'] === 'dpd')
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M4 7.5h9v9H4z" />
                                                            <path d="M13 10h3.5l2 2.5V16H13z" />
                                                            <path d="M7 10.5h3" />
                                                            <path d="M7 13h2" />
                                                            <circle cx="8" cy="18" r="1.5" />
                                                            <circle cx="17" cy="18" r="1.5" />
                                                        </svg>
                                                    @elseif ($method['icon'] === 'omniva')
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="5" y="4.5" width="14" height="15" rx="2" />
                                                            <path d="M9 8.5h6" />
                                                            <path d="M9 12h6" />
                                                            <path d="M9 15.5h3" />
                                                        </svg>
                                                    @else
                                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M4.5 9.5 12 4l7.5 5.5" />
                                                            <path d="M6.5 8.5V20h11V8.5" />
                                                            <path d="M9.5 20v-5h5v5" />
                                                        </svg>
                                                    @endif
                                                </span>
                                                <span class="checkout-delivery-option__content">
                                                    <span class="checkout-delivery-option__title">{{ $method['label'] }}</span>
                                                    <span class="checkout-delivery-option__text">{{ $method['description'] }}</span>
                                                </span>
                                                <span class="checkout-delivery-option__meta">{{ $method['price_label'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>

                                    @error('delivery_method')
                                        <p class="checkout-field__error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="checkout-delivery-panels">
                                    @if ($addressMethodKeys !== '')
                                        <div class="checkout-delivery-panel" data-delivery-section-values="{{ $addressMethodKeys }}">
                                            <div class="checkout-delivery-panel__inner">
                                                <div class="checkout-grid checkout-grid--double">
                                                    <div class="checkout-field">
                                                        <label for="city" class="checkout-field__label">Pilsēta</label>
                                                        <input
                                                            id="city"
                                                            name="city"
                                                            type="text"
                                                            value="{{ old('city') }}"
                                                            @class(['checkout-field__input', $inputErrorClasses => $errors->has('city')])
                                                            data-delivery-required="address"
                                                            aria-invalid="{{ $errors->has('city') ? 'true' : 'false' }}"
                                                        >
                                                        @error('city')
                                                            <p class="checkout-field__error">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="checkout-field">
                                                        <label for="postal_code" class="checkout-field__label">Pasta indekss</label>
                                                        <input
                                                            id="postal_code"
                                                            name="postal_code"
                                                            type="text"
                                                            value="{{ old('postal_code') }}"
                                                            @class(['checkout-field__input', $inputErrorClasses => $errors->has('postal_code')])
                                                            data-delivery-required="address"
                                                            aria-invalid="{{ $errors->has('postal_code') ? 'true' : 'false' }}"
                                                        >
                                                        @error('postal_code')
                                                            <p class="checkout-field__error">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="checkout-grid checkout-grid--triple">
                                                    <div
                                                        class="checkout-field checkout-field--autocomplete checkout-grid__span-2"
                                                        data-address-autocomplete
                                                        data-suggest-url="{{ route('checkout.address-suggestions') }}"
                                                        data-address-fill-street="#street"
                                                        data-address-fill-house="#house"
                                                        data-address-fill-city="#city"
                                                        data-address-fill-postal="#postal_code"
                                                    >
                                                        <label for="street" class="checkout-field__label">Iela</label>
                                                        <div class="checkout-autocomplete">
                                                            <input
                                                                id="street"
                                                                name="street"
                                                                type="text"
                                                                value="{{ old('street') }}"
                                                                autocomplete="address-line1"
                                                                placeholder="Sāciet rakstīt ielu vai pilnu adresi Latvijā"
                                                                @class(['checkout-field__input', $inputErrorClasses => $errors->has('street')])
                                                                data-address-input
                                                                data-delivery-required="address"
                                                                aria-autocomplete="list"
                                                                aria-expanded="false"
                                                                aria-controls="street-address-suggestions"
                                                                aria-invalid="{{ $errors->has('street') ? 'true' : 'false' }}"
                                                            >
                                                            <div
                                                                id="street-address-suggestions"
                                                                class="checkout-autocomplete__panel"
                                                                data-address-suggestions
                                                                hidden
                                                            ></div>
                                                        </div>
                                                        <p class="checkout-autocomplete__hint">
                                                            Autocomplete palīdz aizpildīt adresi, bet varat visu ievadīt arī manuāli.
                                                        </p>
                                                        @error('street')
                                                            <p class="checkout-field__error">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <div class="checkout-field">
                                                        <label for="house" class="checkout-field__label">Mājas nr.</label>
                                                        <input
                                                            id="house"
                                                            name="house"
                                                            type="text"
                                                            value="{{ old('house') }}"
                                                            @class(['checkout-field__input', $inputErrorClasses => $errors->has('house')])
                                                            data-delivery-required="address"
                                                            aria-invalid="{{ $errors->has('house') ? 'true' : 'false' }}"
                                                        >
                                                        @error('house')
                                                            <p class="checkout-field__error">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="checkout-field">
                                                    <label for="apartment" class="checkout-field__label">Dzīvoklis / birojs</label>
                                                    <input
                                                        id="apartment"
                                                        name="apartment"
                                                        type="text"
                                                        value="{{ old('apartment') }}"
                                                        @class(['checkout-field__input', $inputErrorClasses => $errors->has('apartment')])
                                                        aria-invalid="{{ $errors->has('apartment') ? 'true' : 'false' }}"
                                                    >
                                                    @error('apartment')
                                                        <p class="checkout-field__error">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <div class="checkout-field">
                                                    <label for="delivery_comment" class="checkout-field__label">Komentārs piegādei</label>
                                                    <textarea
                                                        id="delivery_comment"
                                                        name="delivery_comment"
                                                        rows="3"
                                                        @class(['checkout-field__input', 'checkout-field__textarea', 'checkout-field__textarea--compact', $inputErrorClasses => $errors->has('delivery_comment')])
                                                        placeholder="Piemēram, durvju kods, stāvs vai piekļuves informācija."
                                                        aria-invalid="{{ $errors->has('delivery_comment') ? 'true' : 'false' }}"
                                                    >{{ old('delivery_comment') }}</textarea>
                                                    @error('delivery_comment')
                                                        <p class="checkout-field__error">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @foreach (['omniva', 'dpd'] as $pointMethod)
                                        @if (isset($deliveryMethods[$pointMethod]))
                                            @php
                                                $pointPayload = $selectedDeliveryPointPayload && $selectedDeliveryPointPayload['provider'] === $pointMethod
                                                    ? $selectedDeliveryPointPayload
                                                    : null;
                                            @endphp
                                            <div class="checkout-delivery-panel" data-delivery-section-values="{{ $pointMethod }}">
                                                <div class="checkout-delivery-panel__inner">
                                                    <div
                                                        @class([
                                                            'checkout-field checkout-field--delivery-point',
                                                            'rounded-[1.4rem] border border-[#b45454]/30 bg-[#fff7f7] px-4 py-4 sm:px-5' => $errors->has('selected_delivery_point_id'),
                                                        ])
                                                        data-delivery-point-search
                                                        data-provider="{{ $pointMethod }}"
                                                        data-search-url="{{ route('checkout.delivery-points') }}"
                                                        data-selected-point='@json($pointPayload)'
                                                    >
                                                        <label class="checkout-field__label">
                                                            {{ $pointMethod === 'omniva' ? 'Izvēlieties Omniva pakomātu' : 'Izvēlieties DPD Pickup punktu' }}
                                                        </label>
                                                        <div class="checkout-autocomplete">
                                                            <input
                                                                type="text"
                                                                value="{{ $pointPayload['name'] ?? '' }}"
                                                                @class(['checkout-field__input', $inputErrorClasses => $errors->has('selected_delivery_point_id')])
                                                                data-delivery-point-input
                                                                placeholder="{{ $pointMethod === 'omniva' ? 'Meklēt Omniva pakomātu' : 'Meklēt DPD Pickup punktu' }}"
                                                                aria-autocomplete="list"
                                                                aria-expanded="false"
                                                                aria-controls="delivery-points-{{ $pointMethod }}"
                                                                aria-invalid="{{ $errors->has('selected_delivery_point_id') ? 'true' : 'false' }}"
                                                            >
                                                            <div
                                                                id="delivery-points-{{ $pointMethod }}"
                                                                class="checkout-autocomplete__panel"
                                                                data-delivery-point-results
                                                                hidden
                                                            ></div>
                                                        </div>
                                                        <p class="checkout-autocomplete__hint">
                                                            Meklējiet pēc pilsētas, punkta nosaukuma vai adreses.
                                                        </p>
                                                        <div class="checkout-delivery-point__selected" data-delivery-point-selected hidden></div>
                                                        @error('selected_delivery_point_id')
                                                            <p class="checkout-field__error">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach

                                    @if (isset($deliveryMethods['pickup']))
                                        <div class="checkout-delivery-panel" data-delivery-section-values="pickup">
                                            <div class="checkout-delivery-info">
                                                <span class="checkout-delivery-info__icon" aria-hidden="true">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M12 8.5v4" />
                                                        <path d="M12 16h.01" />
                                                        <path d="M12 3.5a8.5 8.5 0 1 1 0 17 8.5 8.5 0 0 1 0-17Z" />
                                                    </svg>
                                                </span>
                                                <div>
                                                    <p class="checkout-delivery-info__title">Saņemšana pēc apstiprināšanas</p>
                                                    <p class="checkout-delivery-info__text">
                                                        Par saņemšanas laiku un vietu vienosimies pēc pasūtījuma apstiprināšanas.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="checkout-field">
                                <label for="comment" class="checkout-field__label">Komentārs pasūtījumam</label>
                                <textarea
                                    id="comment"
                                    name="comment"
                                    rows="4"
                                    @class(['checkout-field__input', 'checkout-field__textarea', $inputErrorClasses => $errors->has('comment')])
                                    placeholder="Papildu informācija par pasūtījumu."
                                    aria-invalid="{{ $errors->has('comment') ? 'true' : 'false' }}"
                                >{{ old('comment') }}</textarea>
                                @error('comment')
                                    <p class="checkout-field__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                                <a href="{{ route('cart.index') }}" class="shop-button shop-button--secondary">
                                    Atgriezties uz grozu
                                </a>

                                <button type="submit" class="shop-button shop-button--primary">
                                    Apstiprināt pasūtījumu
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

                <aside data-reveal class="reveal checkout-summary xl:sticky xl:top-28">
                    <div class="cart-summary__card" data-checkout-summary data-subtotal="{{ $cartTotal }}">
                        <p class="section-kicker text-[#60716a]">Kopsavilkums</p>

                        <div class="mt-6 space-y-4">
                            @foreach ($items as $item)
                                <div class="checkout-summary__item">
                                    <div>
                                        <p class="font-semibold text-[#12261f]">{{ $item['name'] }}</p>
                                        <p class="mt-1 text-sm text-[#60716a]">{{ $item['quantity'] }} × €{{ number_format((float) $item['price'], 2, '.', ' ') }}</p>
                                    </div>
                                    <div class="text-right text-sm font-semibold text-[#12261f]">
                                        €{{ number_format(((float) $item['price']) * ((int) $item['quantity']), 2, '.', ' ') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 space-y-4 border-t border-[#06402B]/8 pt-5">
                            <div class="cart-summary__row">
                                <span>Preču skaits</span>
                                <span>{{ $cartCount }}</span>
                            </div>
                            <div class="cart-summary__row">
                                <span>Starp summa</span>
                                <span data-summary-subtotal>€{{ number_format($cartTotal, 2, '.', ' ') }}</span>
                            </div>
                            <div class="cart-summary__row">
                                <span>Piegāde</span>
                                <span data-summary-delivery>Tiks precizēta</span>
                            </div>
                            <div class="cart-summary__row cart-summary__row--total">
                                <span>Kopā</span>
                                <span data-summary-total>€{{ number_format($cartTotal, 2, '.', ' ') }}</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
