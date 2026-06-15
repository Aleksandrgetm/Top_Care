@extends('admin.layouts.app', ['title' => 'Kategorijas | Top Care Group Admin', 'activeNav' => 'categories'])

@section('content')
    <header class="rounded-[2rem] border border-white/70 bg-[linear-gradient(135deg,#ffffff_0%,#f7faf7_100%)] p-6 shadow-[0_24px_70px_rgba(6,64,43,0.06)] sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Kategorijas</p>
                <h1 class="mt-4 text-3xl font-semibold leading-tight text-[#12261f] sm:text-4xl">Product categories</h1>
                <p class="mt-4 max-w-[760px] text-sm leading-7 text-[#5c6d66] sm:text-base">
                    Manage the category structure used by the product admin module.
                </p>
            </div>

            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                Add category
            </a>
        </div>
    </header>

    <section class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-4 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#06402B]/8 text-left">
                <thead>
                    <tr class="text-xs font-semibold uppercase tracking-[0.18em] text-[#60716a]">
                        <th class="px-4 py-4">Name</th>
                        <th class="px-4 py-4">Slug</th>
                        <th class="px-4 py-4">Products</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#06402B]/8 text-sm text-[#244338]">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-[#12261f]">{{ $category->name }}</td>
                            <td class="px-4 py-4"><code>{{ $category->slug }}</code></td>
                            <td class="px-4 py-4">{{ $category->products_count }}</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $category->is_active ? 'bg-[#edf7d3] text-[#5b6f11]' : 'bg-[#f1f3f2] text-[#60716a]' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-4 py-2 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?');">
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
                            <td colspan="5" class="px-4 py-12 text-center text-sm leading-7 text-[#5c6d66]">
                                Categories have not been created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
