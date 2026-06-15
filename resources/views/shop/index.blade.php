@extends('shop.layouts.app')

@section('content')
    <section class="bg-white py-10 sm:py-12">
        <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
            <div data-reveal class="reveal flex flex-wrap items-center gap-3">
                <a href="{{ route('shop.index') }}" class="inline-flex rounded-full px-5 py-3 text-sm font-semibold transition {{ $currentCategory ? 'border border-[#06402B]/12 bg-[#f7faf7] text-[#06402B] hover:bg-[#eef4ef]' : 'bg-[#06402B] text-white shadow-[0_12px_28px_rgba(6,64,43,0.14)]' }}">
                    Visas kategorijas
                </a>

                @foreach ($categories as $category)
                    <a href="{{ route('shop.category', $category) }}" class="inline-flex rounded-full border border-[#06402B]/12 px-5 py-3 text-sm font-semibold transition {{ optional($currentCategory)->is($category) ? 'bg-[#06402B] text-white shadow-[0_12px_28px_rgba(6,64,43,0.14)]' : 'bg-[#f7faf7] text-[#06402B] hover:bg-[#eef4ef]' }}">
                        {{ $category->name }}
                        <span class="ml-2 text-xs {{ optional($currentCategory)->is($category) ? 'text-white/72' : 'text-[#60716a]' }}">
                            {{ $category->active_products_count }}
                        </span>
                    </a>
                @endforeach
            </div>

            @if ($currentCategory)
                <div data-reveal class="reveal mt-6">
                    <p class="text-sm leading-7 text-[#5c6d66]">
                        Kategorija:
                        <span class="font-semibold text-[#12261f]">{{ $currentCategory->name }}</span>
                    </p>
                </div>
            @endif
        </div>
    </section>

    <section class="bg-white pb-12 sm:pb-16 lg:pb-18">
        <div class="mx-auto max-w-[1320px] px-5 sm:px-8 lg:px-10">
            @if ($products->isEmpty())
                <div data-reveal class="reveal rounded-[2rem] border border-dashed border-[#06402B]/16 bg-[#f7faf7] px-8 py-16 text-center shadow-[0_18px_50px_rgba(6,64,43,0.05)]">
                    <h2 class="text-3xl text-[#12261f]">Šobrīd preces nav pieejamas</h2>
                    <p class="mt-4 text-base leading-8 text-[#5c6d66]">
                        Šajā sadaļā aktīvas preces vēl nav publicētas. Lūdzu, ieskatieties vēlāk.
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
    </section>
@endsection
