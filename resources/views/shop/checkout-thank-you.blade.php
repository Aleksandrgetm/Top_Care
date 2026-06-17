@extends('shop.layouts.app')

@section('content')
    <section class="checkout-page bg-[#f8fbf8] py-8 sm:py-10 lg:py-14">
        <div class="mx-auto max-w-[1520px] px-5 sm:px-8 lg:px-10">
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

                    <div class="mt-8 grid gap-4 text-left sm:grid-cols-[1fr_1.3fr_1fr]">
                        <div class="checkout-stat">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex min-w-0 items-start gap-3">
                                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-[#06402B] shadow-[0_10px_24px_rgba(6,64,43,0.06)]">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <path d="M9 3.75h6l3 3V19.5a.75.75 0 0 1-.75.75h-10.5A.75.75 0 0 1 6 19.5v-15a.75.75 0 0 1 .75-.75H9Z" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9 3.75V7.5h3.75" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9 11.25h6M9 14.25h6M9 17.25h3.75" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="checkout-stat__label">Pasūtījuma numurs</span>
                                        <span class="checkout-stat__value break-all">{{ $order->display_order_number }}</span>
                                    </div>
                                </div>

                                <div class="relative shrink-0">
                                    <button
                                        type="button"
                                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-[#06402B]/10 bg-white text-[#06402B] transition hover:-translate-y-0.5 hover:border-[#06402B]/20 hover:bg-[#eef4ef]"
                                        data-copy-order-number
                                        data-order-number="{{ $order->display_order_number }}"
                                        aria-label="Kopēt pasūtījuma numuru"
                                    >
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <rect x="9" y="9" width="10.5" height="10.5" rx="2" />
                                            <path d="M6 15V6.75A2.25 2.25 0 0 1 8.25 4.5h8.25" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                    <span
                                        class="pointer-events-none absolute -top-9 right-0 rounded-full bg-[#06402B] px-3 py-1 text-xs font-semibold text-white opacity-0 shadow-[0_10px_24px_rgba(6,64,43,0.18)] transition"
                                        data-copy-tooltip
                                        role="status"
                                        aria-live="polite"
                                    >
                                        Nokopēts
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-stat">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-[#06402B] shadow-[0_10px_24px_rgba(6,64,43,0.06)]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4.5 19.25a7.5 7.5 0 0 1 15 0" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <span class="checkout-stat__label">Klients</span>
                                    <span class="checkout-stat__value whitespace-nowrap">{{ $order->customer_name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-stat">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-white text-[#06402B] shadow-[0_10px_24px_rgba(6,64,43,0.06)]">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M6.75 7.5h10.5l-.9 9a1.5 1.5 0 0 1-1.49 1.35H9.14a1.5 1.5 0 0 1-1.49-1.35l-.9-9Z" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M9.75 9.75V7.5a2.25 2.25 0 1 1 4.5 0v2.25" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                                <div class="min-w-0">
                                    <span class="checkout-stat__label">Kopā</span>
                                    <span class="checkout-stat__value">€{{ number_format((float) $order->total_price, 2, '.', ' ') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-center">
                        <a href="{{ route('shop.index') }}" class="shop-button shop-button--primary">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M6.75 7.5h10.5l-.9 9a1.5 1.5 0 0 1-1.49 1.35H9.14a1.5 1.5 0 0 1-1.49-1.35l-.9-9Z" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9.75 9.75V7.5a2.25 2.25 0 1 1 4.5 0v2.25" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Turpināt iepirkties</span>
                        </a>
                        <a href="{{ route('cart.index') }}" class="shop-button shop-button--secondary">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M3 4h2.2c.5 0 .94.34 1.06.82L6.8 7H20a1 1 0 0 1 .97 1.24l-1.4 5.6a1 1 0 0 1-.97.76H9.2a1 1 0 0 1-.97-.76L5.1 3.64A1 1 0 0 0 4.14 3H3" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="10" cy="19" r="1.5"/>
                                <circle cx="18" cy="19" r="1.5"/>
                            </svg>
                            <span>Atvērt grozu</span>
                        </a>
                    </div>
                </div>
            </section>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const copyButton = document.querySelector('[data-copy-order-number]');

            if (!copyButton) {
                return;
            }

            const tooltip = copyButton.parentElement?.querySelector('[data-copy-tooltip]');
            let tooltipTimer = null;

            copyButton.addEventListener('click', async () => {
                const orderNumber = copyButton.dataset.orderNumber || '';

                if (!orderNumber) {
                    return;
                }

                try {
                    await navigator.clipboard.writeText(orderNumber);

                    if (tooltip) {
                        tooltip.classList.remove('opacity-0');
                        tooltip.classList.add('opacity-100');

                        window.clearTimeout(tooltipTimer);
                        tooltipTimer = window.setTimeout(() => {
                            tooltip.classList.remove('opacity-100');
                            tooltip.classList.add('opacity-0');
                        }, 1800);
                    }
                } catch (error) {
                    console.error('Unable to copy order number', error);
                }
            });
        });
    </script>
@endsection
