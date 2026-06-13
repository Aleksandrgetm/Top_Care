@extends('admin.layouts.app', ['title' => 'Pages | Top Care Group Admin', 'activeNav' => 'pages'])

@section('content')
    <header class="rounded-[2rem] border border-white/70 bg-[linear-gradient(135deg,#ffffff_0%,#f7faf7_100%)] p-6 shadow-[0_24px_70px_rgba(6,64,43,0.06)] sm:p-8">
        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Pages</p>
        <h1 class="mt-4 text-3xl font-semibold leading-tight text-[#12261f] sm:text-4xl">Website page content</h1>
        <p class="mt-4 max-w-[760px] text-sm leading-7 text-[#5c6d66] sm:text-base">
            Edit the text and images used on existing public pages. Page creation and deletion are intentionally disabled.
        </p>
    </header>

    <section class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-4 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#06402B]/8 text-left">
                <thead>
                    <tr class="text-xs font-semibold uppercase tracking-[0.18em] text-[#60716a]">
                        <th class="px-4 py-4">Page</th>
                        <th class="px-4 py-4">Slug</th>
                        <th class="px-4 py-4">Last updated</th>
                        <th class="px-4 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#06402B]/8 text-sm text-[#244338]">
                    @foreach ($pages as $page)
                        <tr>
                            <td class="px-4 py-4">
                                <div class="font-semibold text-[#12261f]">{{ $page->title }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <code>{{ $page->slug }}</code>
                            </td>
                            <td class="px-4 py-4">
                                {{ optional($page->updated_at)->format('d.m.Y H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
