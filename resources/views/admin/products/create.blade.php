@extends('admin.layouts.app', ['title' => 'Jauna prece | Top Care Group Admin', 'activeNav' => 'products'])

@section('content')
    <header class="rounded-[2rem] border border-white/70 bg-[linear-gradient(135deg,#ffffff_0%,#f7faf7_100%)] p-6 shadow-[0_24px_70px_rgba(6,64,43,0.06)] sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-[#60716a]">Preces</p>
                <h1 class="mt-4 text-3xl font-semibold leading-tight text-[#12261f] sm:text-4xl">Create product</h1>
            </div>

            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-5 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
                Back to products
            </a>
        </div>
    </header>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="mt-8 rounded-[1.8rem] border border-white/70 bg-white p-6 shadow-[0_20px_55px_rgba(6,64,43,0.05)] sm:p-8">
        @include('admin.products._form', ['submitLabel' => 'Create product', 'showImageUpload' => true])
    </form>
@endsection
