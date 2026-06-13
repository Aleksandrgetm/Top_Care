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
@endsection
