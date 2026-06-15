@csrf

<div class="grid gap-6">
    <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
        <label for="name" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
            Name
        </label>
        <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}" required class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
        @error('name')
            <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
            <label for="category_id" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
                Category
            </label>
            <select id="category_id" name="category_id" required class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
                <option value="">Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) old('category_id', $product->category_id) === (string) $category->id)>
                        {{ $category->name }}{{ $category->is_active ? '' : ' (inactive)' }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
            @enderror
            @if ($categories->isEmpty())
                <p class="mt-3 text-sm text-[#5c6d66]">
                    No categories yet. <a href="{{ route('admin.categories.create') }}" class="font-semibold text-[#06402B] underline decoration-[#BFD730] underline-offset-4">Create a category first</a>.
                </p>
            @endif
        </div>
    </div>

    <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
        <label for="description" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
            Description
        </label>
        <textarea id="description" name="description" rows="6" class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">{{ old('description', $product->description) }}</textarea>
        @error('description')
            <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
            <label for="price" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
                Price
            </label>
            <input id="price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $product->price) }}" required class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
            @error('price')
                <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
            <label for="stock_quantity" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
                Stock quantity
            </label>
            <input id="stock_quantity" name="stock_quantity" type="number" min="0" step="1" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required class="mt-3 w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#12261f] outline-none transition focus:border-[#06402B] focus:ring-4 focus:ring-[#BFD730]/20">
            @error('stock_quantity')
                <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
        <label class="flex items-center justify-between gap-4 rounded-[1.2rem] border border-[#06402B]/8 bg-white px-4 py-4">
            <div>
                <span class="block text-sm font-semibold text-[#12261f]">Status</span>
                <span class="mt-1 block text-sm text-[#5c6d66]">Inactive products stay hidden from future storefront modules.</span>
            </div>
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true)) class="h-5 w-5 rounded border-[#06402B]/20 text-[#06402B] focus:ring-[#BFD730]">
        </label>
    </div>

    @if (($showImageUpload ?? true))
        <div class="rounded-[1.5rem] border border-[#06402B]/8 bg-[#f8fbf8] p-5 sm:p-6">
            <label for="images" class="block text-sm font-semibold uppercase tracking-[0.14em] text-[#60716a]">
                Product photos
            </label>
            <input id="images" name="images[]" type="file" accept="image/*" multiple class="mt-4 block w-full rounded-[1.2rem] border border-[#06402B]/12 bg-white px-4 py-3 text-sm text-[#244338] file:mr-4 file:rounded-full file:border-0 file:bg-[#06402B] file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
            <p class="mt-3 text-sm text-[#5c6d66]">You can upload multiple product images at once.</p>
            @error('images')
                <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="mt-3 text-sm font-medium text-[#a12626]">{{ $message }}</p>
            @enderror
        </div>
    @endif
</div>

<div class="mt-8 flex flex-col gap-3 sm:flex-row">
    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#06402B] px-6 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-[#0b5c3f]">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#06402B]/12 bg-white px-6 py-3 text-sm font-semibold text-[#06402B] transition hover:-translate-y-0.5 hover:bg-[#f7faf7]">
        Cancel
    </a>
</div>
