@extends('admin.layouts.app', ['title' => $page->title . ' | Top Care Group Admin', 'activeNav' => 'pages'])

@php
    $imageUrl = static function (?string $value): ?string {
        if (! $value) {
            return null;
        }

        if (str_starts_with($value, '/')) {
            return asset(ltrim($value, '/'));
        }

        return route('media.public', ['path' => $value]);
    };
@endphp

@section('content')
    <header class="rounded-[2rem] border border-white/70 bg-[linear-gradient(135deg,#ffffff_0%,#f7faf7_100%)] p-6 shadow-[0_24px_70px_rgba(6,64,43,0.06)] sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Pages</p>
                <h1 class="mt-4 text-3xl font-semibold leading-tight text-[#12261f] sm:text-4xl">{{ $page->title }}</h1>
                <p class="mt-4 text-sm leading-7 text-[#5c6d66] sm:text-base">
                    Slug: <code>{{ $page->slug }}</code>
                </p>
            </div>

            <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-5 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
                Back to pages
            </a>
        </div>
    </header>

    <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data" class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-6 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-8">
        @csrf
        @method('PUT')

        <div class="grid gap-6">
            @foreach ($fields as $field)
                @php
                    $key = $field['key'];
                    $type = $field['type'];
                    $value = old($key, $content[$key] ?? '');
                @endphp

                <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
                    <label for="{{ $key }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
                        {{ $field['label'] }}
                    </label>

                    @if ($type === 'textarea')
                        <textarea id="{{ $key }}" name="{{ $key }}" rows="6" class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">{{ $value }}</textarea>
                    @elseif ($type === 'image')
                        @if ($imageUrl($value))
                            <div class="mt-4 overflow-hidden rounded-[1.2rem] border border-[#06402B]/8 bg-white">
                                <img src="{{ $imageUrl($value) }}" alt="{{ $field['label'] }}" class="h-[220px] w-full object-cover sm:h-[280px]">
                            </div>
                        @endif

                        <input id="{{ $key }}" name="{{ $key }}" type="file" accept="image/*" class="mt-4 block w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#244338] file:mr-4 file:rounded-full file:border-0 file:bg-[#06402B] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                    @else
                        <input id="{{ $key }}" name="{{ $key }}" type="{{ $type === 'date' ? 'date' : 'text' }}" value="{{ $value }}" class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                    @endif

                    @error($key)
                        <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                Save changes
            </button>
            <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-6 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
                Cancel
            </a>
        </div>
    </form>

    @if ($page->slug === 'galerija')
        <section id="gallery-images" class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-6 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Galerija</p>
                    <h2 class="mt-3 text-2xl font-semibold text-[#12261f] sm:text-3xl">Galerijas attēli</h2>
                    <p class="mt-3 max-w-[760px] text-sm leading-7 text-[#5c6d66] sm:text-base">
                        Pievienojiet, rediģējiet un sakārtojiet galerijas attēlus. Publiskajā lapā tiek rādīti tikai aktīvie attēli.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.gallery-images.store') }}" enctype="multipart/form-data" class="mt-8 rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
                @csrf
                <label for="images" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
                    Augšupielādēt fotogrāfijas
                </label>
                <input id="images" name="images[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple class="mt-4 block w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#244338] file:mr-4 file:rounded-full file:border-0 file:bg-[#06402B] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                @error('images')
                    <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
                @enderror

                <div class="mt-5">
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                        Upload images
                    </button>
                </div>
            </form>

            <div class="mt-8 grid gap-5 lg:grid-cols-2">
                @forelse ($galleryImages as $galleryImage)
                    <article class="overflow-hidden rounded-[1.6rem] border border-[#06402B]/8 bg-[#f8fbf8]">
                        <div class="overflow-hidden border-b border-[#06402B]/8 bg-white">
                            <img src="{{ $imageUrl($galleryImage->image) }}" alt="{{ $galleryImage->title ?: 'Galerijas attēls' }}" class="h-[240px] w-full object-cover">
                        </div>

                        <div class="p-5 sm:p-6">
                            <form method="POST" action="{{ route('admin.gallery-images.update', $galleryImage) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="grid gap-4">
                                    <div>
                                        <label for="title-{{ $galleryImage->id }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">title</label>
                                        <input id="title-{{ $galleryImage->id }}" name="title" type="text" value="{{ old('title', $galleryImage->title) }}" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                                    </div>

                                    <div>
                                        <label for="category-{{ $galleryImage->id }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">category</label>
                                        <input id="category-{{ $galleryImage->id }}" name="category" type="text" value="{{ old('category', $galleryImage->category) }}" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="sort-order-{{ $galleryImage->id }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">sort_order</label>
                                            <input id="sort-order-{{ $galleryImage->id }}" name="sort_order" type="number" min="0" value="{{ old('sort_order', $galleryImage->sort_order) }}" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                                        </div>

                                        <div>
                                            <label for="image-{{ $galleryImage->id }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">replace image</label>
                                            <input id="image-{{ $galleryImage->id }}" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="mt-2 block w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#244338] file:mr-3 file:rounded-full file:border-0 file:bg-[#06402B] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white">
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 rounded-[1.2rem] border border-[#06402B]/8 bg-white px-4 py-3">
                                        <div>
                                            <p class="text-sm font-semibold text-[#12261f]">Status</p>
                                            <p class="text-sm text-[#5c6d66]">{{ $galleryImage->is_active ? 'Active' : 'Inactive' }}</p>
                                        </div>
                                        <input type="hidden" name="is_active" value="{{ $galleryImage->is_active ? 1 : 0 }}">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $galleryImage->is_active ? 'bg-[#edf7d3] text-[#5b6f11]' : 'bg-[#f1f3f2] text-[#60716a]' }}">
                                            {{ $galleryImage->is_active ? 'Visible' : 'Hidden' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap gap-3">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-5 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                                        Edit
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <form method="POST" action="{{ route('admin.gallery-images.update', $galleryImage) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ $galleryImage->is_active ? 0 : 1 }}">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-5 py-2.5 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
                                        {{ $galleryImage->is_active ? 'Inactive' : 'Active' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.gallery-images.destroy', $galleryImage) }}" onsubmit="return confirm('Delete this gallery image?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#a12626]/12 bg-white px-5 py-2.5 text-sm font-semibold text-[#a12626] transition hover:-translate-y-0.5 hover:bg-[#fff6f6]">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[1.5rem] border border-dashed border-[#06402B]/18 bg-[#f8fbf8] px-6 py-10 text-center text-sm leading-7 text-[#5c6d66] lg:col-span-2">
                        Gallery images have not been uploaded yet. The public page will continue using the current fallback images until you add new ones here.
                    </div>
                @endforelse
            </div>
        </section>
    @endif

    @if ($page->slug === 'pirms-pec')
        <section id="before-after-items" class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-6 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Pirms / Pēc</p>
                    <h2 class="mt-3 text-2xl font-semibold text-[#12261f] sm:text-3xl">Pirms / Pēc darbi</h2>
                    <p class="mt-3 max-w-[760px] text-sm leading-7 text-[#5c6d66] sm:text-base">
                        Pārvaldiet esošos case studies, kas tiek rādīti publiskajā lapā. Aktīvie ieraksti tiek kārtoti pēc sort_order un created_at.
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.before-after-items.store') }}" enctype="multipart/form-data" class="mt-8 rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
                @csrf

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <label for="new-before-title" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">title</label>
                        <input id="new-before-title" name="title" type="text" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                    </div>
                    <div>
                        <label for="new-before-sort-order" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">sort_order</label>
                        <input id="new-before-sort-order" name="sort_order" type="number" min="0" value="0" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                    </div>
                </div>

                <div class="mt-4">
                    <label for="new-before-description" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">description</label>
                    <textarea id="new-before-description" name="description" rows="4" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20"></textarea>
                </div>

                <div class="mt-4">
                    <div>
                        <label for="new-before-image" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">image</label>
                        <input id="new-before-image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="mt-2 block w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#244338] file:mr-3 file:rounded-full file:border-0 file:bg-[#06402B] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white">
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                        Add case
                    </button>
                </div>
            </form>

            <div class="mt-8 grid gap-5 lg:grid-cols-2">
                @forelse ($beforeAfterItems as $item)
                    @php
                        $previewImage = $item->image ?: ($item->before_image ?: $item->after_image);
                    @endphp

                    <article class="overflow-hidden rounded-[1.6rem] border border-[#06402B]/8 bg-[#f8fbf8]">
                        @if ($previewImage)
                            <div class="overflow-hidden border-b border-[#06402B]/8 bg-white">
                                <img src="{{ $imageUrl($previewImage) }}" alt="{{ $item->title ?: 'Pirms / Pēc darbs' }}" class="h-[240px] w-full object-cover">
                            </div>
                        @endif

                        <div class="p-5 sm:p-6">
                            <form method="POST" action="{{ route('admin.before-after-items.update', $item) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="grid gap-4">
                                    <div>
                                        <label for="before-title-{{ $item->id }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">title</label>
                                        <input id="before-title-{{ $item->id }}" name="title" type="text" value="{{ $item->title }}" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                                    </div>

                                    <div>
                                        <label for="before-description-{{ $item->id }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">description</label>
                                        <textarea id="before-description-{{ $item->id }}" name="description" rows="4" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">{{ $item->description }}</textarea>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="before-sort-order-{{ $item->id }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">sort_order</label>
                                            <input id="before-sort-order-{{ $item->id }}" name="sort_order" type="number" min="0" value="{{ $item->sort_order ?? 0 }}" class="mt-2 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                                        </div>

                                        <div class="flex items-end">
                                            <div class="w-full rounded-[1.2rem] border border-[#06402B]/8 bg-white px-4 py-3">
                                                <p class="text-sm font-semibold text-[#12261f]">Status</p>
                                                <p class="mt-1 text-sm text-[#5c6d66]">{{ $item->is_active ? 'Active' : 'Inactive' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="before-image-{{ $item->id }}" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">image</label>
                                        <input id="before-image-{{ $item->id }}" name="image" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="mt-2 block w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#244338] file:mr-3 file:rounded-full file:border-0 file:bg-[#06402B] file:px-3 file:py-2 file:text-xs file:font-semibold file:text-white">
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-wrap gap-3">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-5 py-2.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                                        Edit
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <form method="POST" action="{{ route('admin.before-after-items.update', $item) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ $item->is_active ? 0 : 1 }}">
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-5 py-2.5 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
                                        {{ $item->is_active ? 'Inactive' : 'Active' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.before-after-items.destroy', $item) }}" onsubmit="return confirm('Delete this case?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center rounded-full border border-[#a12626]/12 bg-white px-5 py-2.5 text-sm font-semibold text-[#a12626] transition hover:-translate-y-0.5 hover:bg-[#fff6f6]">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[1.5rem] border border-dashed border-[#06402B]/18 bg-[#f8fbf8] px-6 py-10 text-center text-sm leading-7 text-[#5c6d66] lg:col-span-2">
                        Before/after cases have not been seeded yet. The public page will continue using fallback content until you add or seed the current cases.
                    </div>
                @endforelse
            </div>
        </section>
    @endif
@endsection
