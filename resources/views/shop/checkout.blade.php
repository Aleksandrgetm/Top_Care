@extends('shop.layouts.app')

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

                        <form method="POST" action="{{ route('checkout.store') }}" class="mt-8 grid gap-5">
                            @csrf

                            <div class="checkout-field">
                                <label for="customer_name" class="checkout-field__label">Vārds un uzvārds</label>
                                <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" required class="checkout-field__input">
                                @error('customer_name')
                                    <p class="checkout-field__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="checkout-grid checkout-grid--double">
                                <div class="checkout-field">
                                    <label for="customer_phone" class="checkout-field__label">Tālrunis</label>
                                    <input id="customer_phone" name="customer_phone" type="text" value="{{ old('customer_phone') }}" required class="checkout-field__input">
                                    @error('customer_phone')
                                        <p class="checkout-field__error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="checkout-field">
                                    <label for="customer_email" class="checkout-field__label">E-pasts</label>
                                    <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" required class="checkout-field__input">
                                    @error('customer_email')
                                        <p class="checkout-field__error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div
                                class="checkout-field checkout-field--autocomplete"
                                data-address-autocomplete
                                data-suggest-url="{{ route('checkout.address-suggestions') }}"
                            >
                                <label for="delivery_address" class="checkout-field__label">Piegādes adrese</label>
                                <div class="checkout-autocomplete">
                                    <input
                                        id="delivery_address"
                                        name="delivery_address"
                                        type="text"
                                        value="{{ old('delivery_address') }}"
                                        required
                                        autocomplete="street-address"
                                        placeholder="Sāciet rakstīt adresi Latvijā"
                                        class="checkout-field__input"
                                        data-address-input
                                        aria-autocomplete="list"
                                        aria-expanded="false"
                                        aria-controls="delivery-address-suggestions"
                                    >
                                    <div
                                        id="delivery-address-suggestions"
                                        class="checkout-autocomplete__panel"
                                        data-address-suggestions
                                        hidden
                                    ></div>
                                </div>
                                <p class="checkout-autocomplete__hint">
                                    Ievadiet vismaz 3 rakstzīmes. Ja adresi neizdodas atrast, varat turpināt ievadi manuāli.
                                </p>
                                @error('delivery_address')
                                    <p class="checkout-field__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="checkout-field">
                                <label for="delivery_method" class="checkout-field__label">Piegādes veids</label>
                                <select id="delivery_method" name="delivery_method" required class="checkout-field__input checkout-field__select">
                                    <option value="">Izvēlieties piegādes veidu</option>
                                    @foreach ($deliveryMethods as $value => $label)
                                        <option value="{{ $value }}" @selected(old('delivery_method') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('delivery_method')
                                    <p class="checkout-field__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="checkout-field">
                                <label for="comment" class="checkout-field__label">Komentārs</label>
                                <textarea id="comment" name="comment" rows="4" class="checkout-field__input checkout-field__textarea" placeholder="Papildu informācija par piegādi, piekļuvi vai pasūtījumu.">{{ old('comment') }}</textarea>
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
                    <div class="cart-summary__card">
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
                                <span>€{{ number_format($cartTotal, 2, '.', ' ') }}</span>
                            </div>
                            <div class="cart-summary__row">
                                <span>Piegāde</span>
                                <span>Tiks precizēta</span>
                            </div>
                            <div class="cart-summary__row cart-summary__row--total">
                                <span>Kopā</span>
                                <span>€{{ number_format($cartTotal, 2, '.', ' ') }}</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
