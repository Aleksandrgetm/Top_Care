@extends('admin.layouts.app', ['title' => $product->name . ' | Top Care Group Admin', 'activeNav' => 'products'])

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
                <h1 class="mt-4 text-3xl font-semibold leading-tight text-[#12261f] sm:text-4xl">{{ $product->name }}</h1>
                <p class="mt-4 text-sm leading-7 text-[#5c6d66] sm:text-base">
                    Slug: <code>{{ $product->slug }}</code>
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-5 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
                Back to products
            </a>
        </div>
    </header>

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-6 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-8">
        @method('PUT')
        @include('admin.products._form', ['submitLabel' => 'Save changes', 'showImageUpload' => false])
    </form>

    <section class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-6 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Photos</p>
                <h2 class="mt-3 text-2xl font-semibold text-[#12261f] sm:text-3xl">Product gallery</h2>
                <p class="mt-3 max-w-[760px] text-sm leading-7 text-[#5c6d66] sm:text-base">
                    Add new product photos or remove outdated images individually.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.products.images.store', $product) }}" enctype="multipart/form-data" class="mt-8 rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
            @csrf
            <label for="extra-images" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
                Add new photos
            </label>
            <p class="mt-3 text-sm leading-7 text-[#5c6d66]">
                Choose one or more images to upload them all at once. Maximum file size: 4 MB per image.
            </p>
            <input id="extra-images" name="images[]" type="file" accept="image/*" multiple class="mt-4 block w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#244338] file:mr-4 file:rounded-full file:border-0 file:bg-[#06402B] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
            @error('images')
                <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
            @enderror

            <div class="mt-5">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                    Upload photos
                </button>
            </div>
        </form>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($product->productImages as $image)
                <article class="overflow-hidden rounded-[1.6rem] border border-[#06402B]/8 bg-[#f8fbf8]">
                    <div class="overflow-hidden border-b border-[#06402B]/8 bg-white">
                        <img src="{{ $imageUrl($image->image_path) }}" alt="{{ $product->name }}" class="h-[240px] w-full object-cover">
                    </div>

                    <div class="flex items-center justify-between gap-4 p-5">
                        <div>
                            <p class="text-sm font-semibold text-[#12261f]">Photo #{{ $loop->iteration }}</p>
                            <p class="mt-1 text-xs text-[#60716a]">{{ $image->image_path }}</p>
                        </div>

                        <form method="POST" action="{{ route('admin.product-images.destroy', $image) }}" onsubmit="return confirm('Delete this product image?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#a12626]/12 bg-white px-4 py-2 text-sm font-semibold text-[#a12626] transition hover:-translate-y-0.5 hover:bg-[#fff6f6]">
                                Delete
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="rounded-[1.5rem] border border-dashed border-[#06402B]/18 bg-[#f8fbf8] px-6 py-10 text-center text-sm leading-7 text-[#5c6d66] sm:col-span-2 xl:col-span-3">
                    Product images have not been uploaded yet.
                </div>
            @endforelse
        </div>
    </section>
@endsection
