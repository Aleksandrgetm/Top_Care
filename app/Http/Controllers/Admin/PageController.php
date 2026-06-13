<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeforeAfterItem;
use App\Models\GalleryImage;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $this->syncConfiguredPages();

        $definitions = config('site_pages.pages', []);
        $order = array_flip(array_keys($definitions));

        $pages = Page::query()
            ->get()
            ->sortBy(fn (Page $page) => $order[$page->slug] ?? PHP_INT_MAX)
            ->values();

        return view('admin.pages.index', [
            'pages' => $pages,
        ]);
    }

    public function edit(Page $page): View
    {
        $this->syncConfiguredPages();

        $definition = config("site_pages.pages.{$page->slug}");

        abort_unless($definition, 404);

        return view('admin.pages.edit', [
            'page' => $page,
            'fields' => $definition['fields'] ?? [],
            'content' => array_replace($definition['defaults'] ?? [], $page->content ?? []),
            'galleryImages' => $page->slug === 'galerija' && Schema::hasTable('gallery_images')
                ? GalleryImage::query()->orderBy('sort_order')->orderBy('created_at')->get()
                : collect(),
            'beforeAfterItems' => $page->slug === 'pirms-pec' && Schema::hasTable('before_after_items')
                ? BeforeAfterItem::query()->orderBy('sort_order')->orderByDesc('created_at')->get()
                : collect(),
        ]);
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $this->syncConfiguredPages();

        $definition = config("site_pages.pages.{$page->slug}");

        abort_unless($definition, 404);

        $fields = $definition['fields'] ?? [];
        $rules = [];

        foreach ($fields as $field) {
            $rules[$field['key']] = match ($field['type']) {
                'image' => ['nullable', 'image', 'max:5120'],
                'date' => ['nullable', 'date'],
                default => ['nullable', 'string'],
            };
        }

        $validated = $request->validate($rules);
        $content = $page->content ?? [];

        foreach ($fields as $field) {
            $key = $field['key'];

            if ($field['type'] === 'image') {
                if ($request->hasFile($key)) {
                    $existingPath = $content[$key] ?? null;

                    if (is_string($existingPath) && str_starts_with($existingPath, 'pages/')) {
                        Storage::disk('public')->delete($existingPath);
                    }

                    $content[$key] = $request->file($key)->store('pages', 'public');
                }

                continue;
            }

            $content[$key] = $validated[$key] ?? null;
        }

        $page->update([
            'content' => $content,
        ]);

        return redirect()
            ->route('admin.pages.edit', $page)
            ->with('status', 'Page content updated successfully.');
    }

    private function syncConfiguredPages(): void
    {
        foreach (config('site_pages.pages', []) as $slug => $definition) {
            $page = Page::firstOrNew(['slug' => $slug]);
            $page->title = $definition['title'];
            $page->is_active = true;

            if (empty($page->content)) {
                $page->content = $definition['defaults'] ?? [];
            }

            $page->save();
        }
    }
}
