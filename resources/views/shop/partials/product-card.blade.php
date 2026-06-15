@php
    $primaryImage = $product->productImages->first();
    $imagePath = $primaryImage?->image_path;
@endphp

<article data-reveal class="reveal group flex h-full flex-col overflow-hidden rounded-[2rem] border border-[#06402B]/8 bg-white shadow-[0_18px_48px_rgba(6,64,43,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_62px_rgba(6,64,43,0.12)]">
    <a href="{{ route('shop.show', $product) }}" class="block overflow-hidden bg-[#edf3ef]">
        @if ($imagePath)
            <img src="{{ route('media.public', ['path' => $imagePath]) }}" alt="{{ $product->name }}" class="h-[260px] w-full object-cover transition duration-500 group-hover:scale-[1.03]">
        @else
            <div class="flex h-[260px] items-center justify-center bg-[radial-gradient(circle_at_top_left,rgba(191,215,48,0.24),transparent_38%),linear-gradient(180deg,#eaf1ec_0%,#dce7df_100%)] px-6 text-center text-sm font-medium text-[#60716a]">
                Produkta attēls tiks pievienots drīzumā
            </div>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="max-w-[75%]">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[#60716a]">
                    {{ $product->category?->name }}
                </p>
                <h3 class="mt-3 text-2xl text-[#12261f]">{{ $product->name }}</h3>
            </div>
            <div class="rounded-full bg-[#edf7d3] px-4 py-2 text-sm font-semibold text-[#4b6110]">
                €{{ number_format((float) $product->price, 2, '.', ' ') }}
            </div>
        </div>

        <p class="mt-4 text-sm leading-7 text-[#5c6d66]">
            {{ \Illuminate\Support\Str::limit($product->description ?: 'Apskatiet detalizētu informāciju par šo preci.', 140) }}
        </p>

        <div class="mt-6 flex items-center justify-between gap-4">
            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $product->stock_quantity > 0 ? 'bg-[#edf7d3] text-[#5b6f11]' : 'bg-[#f4ecec] text-[#9a4040]' }}">
                {{ $product->stock_quantity > 0 ? 'Ir noliktavā' : 'Pašlaik nav noliktavā' }}
            </span>

            <a href="{{ route('shop.show', $product) }}" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                Apskatīt
            </a>
        </div>
    </div>
</article>
