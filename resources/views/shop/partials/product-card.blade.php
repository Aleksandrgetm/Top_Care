@php
    $primaryImage = $product->productImages->first();
    $imagePath = $primaryImage?->image_path;
    $isInStock = $product->stock_quantity > 0;
@endphp

<article data-reveal class="reveal shop-product-card group">
    <div class="relative">
        <a href="{{ route('shop.show', $product) }}" class="shop-product-card__media">
            @if ($imagePath)
                <img
                    src="{{ route('media.public', ['path' => $imagePath]) }}"
                    alt="{{ $product->name }}"
                    class="shop-product-card__image"
                >
            @else
                <div class="shop-product-card__placeholder">
                    Produkta attēls tiks pievienots drīzumā
                </div>
            @endif
        </a>

        <span class="shop-stock-badge {{ $isInStock ? 'shop-stock-badge--in-stock' : 'shop-stock-badge--out-of-stock' }}">
            {{ $isInStock ? 'Ir noliktavā' : 'Nav noliktavā' }}
        </span>
    </div>

    <div class="flex flex-1 flex-col p-5 sm:p-6">
        <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-[#7b8a83]">
            {{ $product->category?->name }}
        </p>

        <h3 class="mt-3 text-xl font-semibold leading-tight text-[#12261f]">
            <a href="{{ route('shop.show', $product) }}" class="transition hover:text-[#06402B]">
                {{ $product->name }}
            </a>
        </h3>

        @if ($product->description)
            <p class="mt-3 text-sm leading-6 text-[#60716a] line-clamp-2">
                {{ $product->description }}
            </p>
        @endif

        <div class="mt-auto flex items-end justify-between gap-4 pt-5">
            <div class="text-2xl font-bold tracking-[-0.04em] text-[#06402B]">
                €{{ number_format((float) $product->price, 2, '.', ' ') }}
            </div>

            <a href="{{ route('shop.show', $product) }}" class="shop-button shop-button--ghost">
                Apskatīt
            </a>
        </div>
    </div>
</article>
