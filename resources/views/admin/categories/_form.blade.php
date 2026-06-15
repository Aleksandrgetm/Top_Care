@csrf

<div class="grid gap-6">
    <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
        <label for="name" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
            Name
        </label>
        <input id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
        @error('name')
            <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
        @enderror
    </div>

    <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
        <label class="flex items-center justify-between gap-4 rounded-[1.2rem] border border-[#06402B]/8 bg-white px-4 py-4">
            <div>
                <span class="block text-sm font-semibold text-[#12261f]">Status</span>
                <span class="mt-1 block text-sm text-[#5c6d66]">Use this switch to show or hide the category in admin selections.</span>
            </div>
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true)) class="h-5 w-5 rounded border-[#06402B]/20 text-[#06402B] focus:ring-[#BFD730]">
        </label>
    </div>
</div>

<div class="mt-8 flex flex-col gap-3 sm:flex-row">
    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-6 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
        Cancel
    </a>
</div>
