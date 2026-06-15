@extends('admin.layouts.app', ['title' => 'Preces | Top Care Group Admin', 'activeNav' => 'products'])

@php
    $imageUrl = static function (?string $value): ?string {
        if (! $value) {
            return null;
        }

        return route('media.public', ['path' => $value]);
    };
@endphp

@section('content')
    <header class="rounded-[2rem] border border-white/70 bg-[linear-gradient(135deg,#ffffff_0%,#f7faf7_100%)] p-6 shadow-[0_24px_70px_rgba(6,64,43,0.06)] sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Preces</p>
                <h1 class="mt-4 text-3xl font-semibold leading-tight text-[#12261f] sm:text-4xl">Product management</h1>
                <p class="mt-4 max-w-[760px] text-sm leading-7 text-[#5c6d66] sm:text-base">
                    Add, edit and remove products available for the upcoming shop module.
                </p>
            </div>

            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                Add product
            </a>
        </div>
    </header>

    <section class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-4 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#06402B]/8 text-left">
                <thead>
                    <tr class="text-xs font-semibold uppercase tracking-[0.18em] text-[#60716a]">
                        <th class="px-4 py-4">Photo</th>
                        <th class="px-4 py-4">Name</th>
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Price</th>
                        <th class="px-4 py-4">Stock</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#06402B]/8 text-sm text-[#244338]">
                    @forelse ($products as $product)
                        @php
                            $preview = $product->productImages->first();
                        @endphp
                        <tr>
                            <td class="px-4 py-4">
                                @if ($preview)
                                    <a href="{{ route('admin.products.show', $product) }}" class="inline-flex rounded-2xl focus:outline-none focus:ring-4 focus:ring-[#BFD730]/20">
                                        <img src="{{ $imageUrl($preview->image_path) }}" alt="{{ $product->name }}" class="h-14 w-14 rounded-2xl border border-[#06402B]/8 object-cover">
                                    </a>
                                @else
                                    <a href="{{ route('admin.products.show', $product) }}" class="flex h-14 w-14 items-center justify-center rounded-2xl border border-dashed border-[#06402B]/12 bg-[#f8fbf8] text-xs text-[#60716a] transition hover:border-[#06402B]/25 hover:bg-white focus:outline-none focus:ring-4 focus:ring-[#BFD730]/20">
                                        N/A
                                    </a>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <a href="{{ route('admin.products.show', $product) }}" class="font-semibold text-[#12261f] transition hover:text-[#06402B]">
                                    {{ $product->name }}
                                </a>
                                <div class="mt-1 text-xs text-[#60716a]"><code>{{ $product->slug }}</code></div>
                            </td>
                            <td class="px-4 py-4">{{ $product->category?->name ?? '—' }}</td>
                            <td class="px-4 py-4">€{{ number_format((float) $product->price, 2, '.', ' ') }}</td>
                            <td class="px-4 py-4">{{ $product->stock_quantity }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-[#edf7d3] text-[#5b6f11]' : 'bg-[#f1f3f2] text-[#60716a]' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.products.show', $product) }}" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-4 py-2 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
                                        View/Edit
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#a12626]/12 bg-white px-4 py-2 text-sm font-semibold text-[#a12626] transition hover:-translate-y-0.5 hover:bg-[#fff6f6]">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-sm leading-7 text-[#5c6d66]">
                                Products have not been added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
