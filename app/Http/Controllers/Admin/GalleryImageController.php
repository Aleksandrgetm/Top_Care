<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Page;
use App\Support\GalleryImageSyncService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryImageController extends Controller
{
    public function __construct(
        private readonly GalleryImageSyncService $galleryImageSyncService,
    ) {
    }

    public function index(): RedirectResponse
    {
        $page = Page::query()->where('slug', 'galerija')->first();

        return $page
            ? redirect()->to(route('admin.pages.edit', $page) . '#gallery-images')
            : redirect()->route('admin.pages.index');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $nextSortOrder = (int) GalleryImage::query()->max('sort_order');

        foreach ($validated['images'] as $image) {
            $nextSortOrder++;
            $originalName = $image->getClientOriginalName();

            GalleryImage::create([
                'image' => $image->store('gallery', 'public'),
                'title' => $this->galleryImageSyncService->makeTitle($originalName),
                'category' => $this->galleryImageSyncService->detectCategory($originalName),
                'sort_order' => $nextSortOrder,
                'is_active' => true,
            ]);
        }

        return $this->redirectToGalleryEditor('Gallery images uploaded successfully.');
    }

    public function update(Request $request, GalleryImage $galleryImage): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $payload = [];

        foreach (['title', 'category', 'sort_order'] as $field) {
            if ($request->exists($field)) {
                $payload[$field] = $validated[$field] ?? null;
            }
        }

        if ($request->exists('is_active')) {
            $payload['is_active'] = (bool) ($validated['is_active'] ?? false);
        }

        if ($request->hasFile('image')) {
            $uploadedImage = $request->file('image');

            if ($galleryImage->image && str_starts_with($galleryImage->image, 'gallery/')) {
                Storage::disk('public')->delete($galleryImage->image);
            }

            $payload['image'] = $uploadedImage->store('gallery', 'public');
            $payload['title'] ??= $this->galleryImageSyncService->makeTitle($uploadedImage->getClientOriginalName());
            $payload['category'] ??= $this->galleryImageSyncService->detectCategory($uploadedImage->getClientOriginalName());
        }

        $galleryImage->update($payload);

        return $this->redirectToGalleryEditor('Gallery image updated successfully.');
    }

    public function destroy(GalleryImage $galleryImage): RedirectResponse
    {
        if ($galleryImage->image && str_starts_with($galleryImage->image, 'gallery/')) {
            Storage::disk('public')->delete($galleryImage->image);
        }

        $galleryImage->delete();

        return $this->redirectToGalleryEditor('Gallery image deleted successfully.');
    }

    private function redirectToGalleryEditor(string $status): RedirectResponse
    {
        $page = Page::query()->where('slug', 'galerija')->first();

        if (! $page) {
            return redirect()->route('admin.pages.index')->with('status', $status);
        }

        return redirect()->to(route('admin.pages.edit', $page) . '#gallery-images')->with('status', $status);
    }
}
