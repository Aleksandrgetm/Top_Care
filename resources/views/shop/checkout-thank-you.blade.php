@extends('shop.layouts.app')

@section('content')
    <section class="checkout-page bg-[#f8fbf8] py-8 sm:py-10 lg:py-14">
        <div class="mx-auto max-w-[1080px] px-5 sm:px-8 lg:px-10">
            <div data-reveal class="reveal cart-page__hero">
                <div>
                    <p class="section-kicker text-[#60716a]">Paldies</p>
                    <h1 class="mt-3 text-[2.8rem] sm:text-[3.4rem] lg:text-[4.25rem]">Pasūtījums ir saņemts</h1>
                </div>

                <ol class="cart-steps" aria-label="Pasūtījuma soļi">
                    <li class="cart-step cart-step--completed">
                        <span class="cart-step__number">1</span>
                        <span class="cart-step__label">Grozs</span>
                    </li>
                    <li class="cart-step cart-step--connector" aria-hidden="true">
                        <span class="cart-step__chevron">›</span>
                    </li>
                    <li class="cart-step cart-step--completed">
                        <span class="cart-step__number">2</span>
                        <span class="cart-step__label">Piegāde</span>
                    </li>
                    <li class="cart-step cart-step--connector" aria-hidden="true">
                        <span class="cart-step__chevron">›</span>
                    </li>
                    <li class="cart-step cart-step--active">
                        <span class="cart-step__number">3</span>
                        <span class="cart-step__label">Apmaksa</span>
                    </li>
                </ol>
            </div>

            <section data-reveal class="reveal checkout-card">
                <div class="checkout-card__inner text-center">
                    <div class="mx-auto inline-flex h-18 w-18 items-center justify-center rounded-[1.8rem] bg-[#edf7d3] text-[#5b6f11] shadow-[0_16px_36px_rgba(95,150,42,0.16)]">
                        <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true">
                            <path d="m5 12 4.2 4.2L19 6.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>

                    <h2 class="mt-6 text-3xl font-semibold text-[#12261f] sm:text-4xl">Pasūtījums ir saņemts.</h2>
                    <p class="mx-auto mt-4 max-w-[620px] text-base leading-8 text-[#5c6d66]">
                        Paldies, {{ $order->customer_name }}. Mēs sazināsimies ar jums, lai precizētu piegādes un apmaksas detaļas.
                    </p>

                    <div class="mt-8 grid gap-4 text-left sm:grid-cols-3">
                        <div class="checkout-stat">
                            <span class="checkout-stat__label">Pasūtījuma numurs</span>
                            <span class="checkout-stat__value">#{{ $order->id }}</span>
                        </div>
                        <div class="checkout-stat">
                            <span class="checkout-stat__label">Klients</span>
                            <span class="checkout-stat__value">{{ $order->customer_name }}</span>
                        </div>
                        <div class="checkout-stat">
                            <span class="checkout-stat__label">Kopā</span>
                            <span class="checkout-stat__value">€{{ number_format((float) $order->total_price, 2, '.', ' ') }}</span>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-center">
                        <a href="{{ route('shop.index') }}" class="shop-button shop-button--primary">
                            Turpināt iepirkties
                        </a>
                        <a href="{{ route('cart.index') }}" class="shop-button shop-button--secondary">
                            Atvērt grozu
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </section>
@endsection
